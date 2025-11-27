<?php

namespace App\Http\Controllers;

use App\DAL\UserRepository;
use App\Models\BalanceInjectorCoupon;
use App\Models\BFSsBalances;
use App\Models\CashBalances;
use App\Models\Coupon;
use App\Models\SharesBalances;
use App\Models\User;
use App\Models\vip;
use App\Notifications\SharePurchase;
use App\Services\Balances\Balances;
use App\Services\Balances\BalancesFacade;
use App\Services\Settings\SettingService;
use App\Services\Sponsorship\SponsorshipFacade;
use carbon;
use Core\Enum\BalanceOperationsEnum;
use Core\Enum\CouponStatusEnum;
use Core\Enum\StatusRequest;
use Core\Enum\TypeEventNotificationEnum;
use Core\Enum\TypeNotificationEnum;
use Core\Models\BalanceOperation;
use Core\Models\detail_financial_request;
use Core\Models\FinancialRequest;
use Core\Services\BalancesManager;
use Core\Services\settingsManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request as Req;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator as Val;
use Illuminate\Support\Facades\Vite;
use Illuminate\Validation\Rule;
use Paytabscom\Laravel_paytabs\Facades\paypage;
use phpDocumentor\Reflection\Types\Collection;
use Propaganistas\LaravelPhone\PhoneNumber;


class ApiController extends BaseController
{
    const DATE_FORMAT = 'd/m/Y H:i:s';

    public function __construct(
        private readonly settingsManager $settingsManager,
        private UserRepository $userRepository,
        private SettingService $settingService
    )
    {
    }

    public function buyAction(Req $request, BalancesManager $balancesManager)
    {
        $actualActionValue = round(actualActionValue(getSelledActions(true), false), 3);
        $validator = Val::make($request->all(), [
            'ammount' => ['required', 'numeric', 'gte:' . $actualActionValue, 'lte:' . $balancesManager->getBalances(Auth()->user()->idUser, -1)->soldeCB],
            'phone' => [Rule::requiredIf($request->me_or_other == "other")],
            'bfs_for' => [Rule::requiredIf($request->me_or_other == "other")],
        ], [
            'ammount.required' => Lang::get('ammonut is required !'),
            'ammount.numeric' => Lang::get('Ammount must be numeric !!'),
            'ammount.gte' => Lang::get('The ammount must be greater than action value') . ' ( ' . $actualActionValue . ' )',
            'ammount.lte' => Lang::get('Ammount > Cash Balance !!'),
            'teinte.exists' => Lang::get('Le champ Teinte est obligatoire !'),
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }

        $number_of_action = intval($request->numberOfActions);
        $gift = getGiftedActions($number_of_action);
        $actual_price = actualActionValue(getSelledActions(true), false);


        $ref = BalancesFacade::getReference(BalanceOperationsEnum::OLD_ID_44->value);


        $palier = $this->settingService->getIntegerValue('19');
        $reciver = Auth()->user()->idUser;
        $reciver_bfs = Auth()->user()->idUser;
        $a = "me";
        if ($request->me_or_other == "other") {
            $reciver = getUserByPhone($request->phone, $request->country_code);
            $a = getPhoneByUser($reciver);
            if ($request->bfs_for == "other") {
                $reciver_bfs = $reciver;
            }
        }

        $fullphone_number = getPhoneByUser($reciver);

        DB::beginTransaction();
        try {
            $userSponsored = SponsorshipFacade::checkProactifSponsorship($this->userRepository->getUserByIdUser($reciver));
            if ($userSponsored) {
                SponsorshipFacade::executeProactifSponsorship($userSponsored->idUser, $ref, $number_of_action, $actual_price, $reciver);
            }
            $vip = vip::Where('idUser', '=', $reciver)->where('closed', '=', false)->first();
            if ($request->flash) {
                if ($vip->declenched) {
                    if ($number_of_action >= $request->actions) {
                        $flashGift = getFlashGiftedActions($request->actions, $request->vip);
                        vip::where('idUser', $request->reciver)
                            ->where('closed', '=', 0)
                            ->update(['closed' => 1, 'closedDate' => now()]);
                    } else {
                        $flashGift = getFlashGiftedActions($number_of_action, $request->vip);
                    }
                } else {
                    if ($number_of_action >= $request->flashMinShares) {
                        if ($number_of_action >= $request->actions) {
                            $flashGift = getFlashGiftedActions($request->actions, $request->vip);
                            vip::where('idUser', $request->reciver)
                                ->where('closed', '=', 0)
                                ->update(['closed' => 1, 'closedDate' => now(), 'declenched' => 1, 'declenchedDate' => now()]);
                        } else {
                            $flashGift = getFlashGiftedActions($number_of_action, $request->vip);
                            vip::where('idUser', $request->reciver)
                                ->where('closed', '=', 0)
                                ->update(['declenched' => 1, 'declenchedDate' => now()]);
                        }
                    } else {
                        $flashGift = 0;
                    }
                }
            } else {
                $flashGift = 0;
            }
            $balances = Balances::getStoredUserBalances($reciver);
            $this->userRepository->increasePurchasesNumber($reciver);
            $oldTotalAmount = SharesBalances::where('beneficiary_id', $reciver)->orderBy(DB::raw('created_at'), "DESC")->pluck('total_amount')->first();

            SharesBalances::addLine([
                'balance_operation_id' => BalanceOperationsEnum::OLD_ID_44->value,
                'operator_id' => Balances::SYSTEM_SOURCE_ID,
                'beneficiary_id' => $reciver,
                'reference' => $ref,
                'unit_price' => $actual_price,
                'payed' => 1,
                'value' => $number_of_action,
                'amount' => $number_of_action * $actual_price,
                'total_amount' => $oldTotalAmount + ($number_of_action * $actual_price),
                'real_amount' => $number_of_action * $actual_price,
                'description' => $number_of_action . ' share(s) purchased',
                'current_balance' => $balances->share_balance + $number_of_action
            ]);

            if ($gift > 0) {
                $balances = Balances::getStoredUserBalances($reciver);
                SharesBalances::addLine([
                    'balance_operation_id' => BalanceOperationsEnum::OLD_ID_54->value,
                    'operator_id' => Balances::SYSTEM_SOURCE_ID,
                    'beneficiary_id' => $reciver,
                    'reference' => $ref,
                    'unit_price' => 0,
                    'description' => $gift . ' share(s) purchased',
                    'value' => $gift,
                    'current_balance' => $balances->share_balance + $number_of_action
                ]);
            }
            if ($flashGift > 0) {
                $balances = Balances::getStoredUserBalances($reciver);
                SharesBalances::addLine([
                    'balance_operation_id' => BalanceOperationsEnum::OLD_ID_55->value,
                    'operator_id' => Balances::SYSTEM_SOURCE_ID,
                    'beneficiary_id' => $reciver,
                    'reference' => $ref,
                    'unit_price' => 0,
                    'value' => $flashGift,
                    'description' => $flashGift . ' share(s) purchased',
                    'current_balance' => $balances->share_balance + $number_of_action
                ]);
            }
            $balances = Balances::getStoredUserBalances($reciver);

            CashBalances::addLine([
                'balance_operation_id' => BalanceOperationsEnum::OLD_ID_48->value,
                'operator_id' => auth()->user()->idUser,
                'beneficiary_id' => auth()->user()->idUser,
                'reference' => $ref,
                'description' => $number_of_action . ' share(s) purchased',
                'value' => $number_of_action * $actual_price,
                'current_balance' => $balances->cash_balance - ($number_of_action) * $actual_price
            ]);
            $balances = Balances::getStoredUserBalances($reciver);

            $value = intval($number_of_action / $palier) * $actual_price * $palier;

            $SettingBFSsTypeForAction = getSettingStringParam('BFSS_TYPE_FOR_ACTION', '50');
            if (floatval($SettingBFSsTypeForAction) > 100 or floatval($SettingBFSsTypeForAction) < 0.01) {
                $SettingBFSsTypeForAction = '50';
            }
            $SettingBFSsLimitForAction = getSettingIntegerParam('BFSS_LIMIT_FOR_ACTION', '500');
            $SettingBFSsGiftForAction = getSettingIntegerParam('BFSS_GIFT_FOR_ACTION', '100');

            if (($number_of_action * $actual_price) > $SettingBFSsLimitForAction) {
                BFSsBalances::addLine([
                    'balance_operation_id' => BalanceOperationsEnum::OLD_ID_46->value,
                    'operator_id' => Balances::SYSTEM_SOURCE_ID,
                    'beneficiary_id' => $reciver_bfs,
                    'reference' => $ref,
                    'percentage' => $SettingBFSsTypeForAction,
                    'description' => $number_of_action . ' share(s) purchased',
                    'value' => $SettingBFSsGiftForAction,
                    'current_balance' => $balances->getBfssBalance($SettingBFSsTypeForAction) + BalanceOperation::getMultiplicator(BalanceOperationsEnum::OLD_ID_46->value) * $value
                ]);
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            Log::error($exception->getMessage());
            return response()->json(['type' => ['error'], 'message' => [trans('Actions purchase transaction failed')]]);
        }
        Auth()->user()->notify(new SharePurchase());
        return response()->json(['type' => ['success'], 'title' => [trans('Actions purchase transaction completed successfully')], 'text' => [trans('This page will reload for update')]]);
    }


    public function giftActionByAmmount(Req $request)
    {
        return getGiftedActions($request->ammount);
    }

    public function actionByAmmount(Req $request)
    {
        $action = intval($request->ammount / actualActionValue(getSelledActions(true)));
        $gifted_action = getGiftedActions($action);
        $profit = actualActionValue(getSelledActions(true)) * $gifted_action;
        $array = array('action' => $action, "gift" => $gifted_action, 'profit' => $profit);
        return response()->json($array);
    }

    public function getCountriStat()
    {
        return response()->json(DB::table('tableau_croise')->select('*')->get());
    }

    public function getSankey()
    {
        $data = DB::select(getSqlFromPath('get_sankey'));
        return response()->json($data);
    }

    public function getFormatedFlagResourceName($flagName)
    {
        return Vite::asset("resources/images/flags/" . strtolower($flagName) . ".svg");
    }

    public function handlePaymentNotification(Req $request, settingsManager $settingsManager)
    {
        $mnt = 0;
        $responseData = $request->request->all();
        $tranRef = $responseData['tranRef'];
        $data = Paypage::queryTransaction($tranRef);
        if (isset($data->payment_info->payment_method) && $data->payment_info->payment_method !== "ApplePay") {
            DB::table('transactions')->insert([
                'tran_ref' => $data->tran_ref,
                'tran_type' => $data->tran_type,
                'cart_id' => $data->cart_id,
                'cart_currency' => $data->cart_currency,
                'cart_amount' => $data->cart_amount,
                'tran_currency' => $data->tran_currency,
                'tran_total' => $data->tran_total,
                'customer_phone' => $data->customer_details->phone,
                'response_status' => $data->payment_result->response_status,
                'response_code' => $data->payment_result->response_code,
                'response_message' => $data->payment_result->response_message,
                'payment_method' => $data->payment_info->payment_method,
                'card_type' => $data->payment_info->card_type,
                'card_scheme' => $data->payment_info->card_scheme,
                'payment_description' => $data->payment_info->payment_description,
                'expiry_month' => $data->payment_info->expiryMonth,
                'expiry_year' => $data->payment_info->expiryYear,
                'issuer_country' => $data->payment_info->issuerCountry,
                'issuer_name' => $data->payment_info->issuerName,
                'success' => $data->success,
                'failed' => $data->failed,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } else {
            DB::table('transactions')->insert([
                'tran_ref' => $data->tran_ref,
                'tran_type' => $data->tran_type,
                'cart_id' => $data->cart_id,
                'cart_currency' => $data->cart_currency,
                'cart_amount' => $data->cart_amount,
                'tran_currency' => $data->tran_currency,
                'tran_total' => $data->tran_total,
                'customer_phone' => $data->customer_details->phone,
                'response_status' => $data->payment_result->response_status,
                'response_code' => $data->payment_result->response_code,
                'response_message' => $data->payment_result->response_message,
                'payment_method' => isset($data->payment_info->payment_method) ?? $data->payment_info->payment_method,
                'card_type' => isset($data->payment_info->card_type) ?? $data->payment_info->card_type,
                'card_scheme' => isset($data->payment_info->card_scheme) ?? $data->payment_info->card_scheme,
                'payment_description' => isset($data->payment_info->payment_description) ?? $data->payment_info->payment_description,
                'expiry_month' => isset($data->payment_info->expiryMonth) ?? $data->payment_info->expiryMonth,
                'expiry_year' => isset($data->payment_info->expiryYear) ?? $data->payment_info->expiryYear,
                'success' => $data->success,
                'failed' => $data->failed,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        $chaine = $data->cart_id;
        $user = explode('-', $chaine)[0];
        $k = $this->settingService->getDecimalValue('30');
        $msg = $settingsManager->getUserByIdUser($user)->mobile . " " . $data->payment_result->response_message;
        if ($data->success) {
            $msg = $msg . " transfert de " . $data->tran_total . $data->cart_currency . "(" . number_format($data->tran_total / $k, 2) . "$)";
        }
        $idUser = $settingsManager->getUserByIdUser($user)->id;
        $notifParams = ['msg' => $msg, 'type' => TypeNotificationEnum::SMS];
        foreach ([2, 126] as $userId) {
            $settingsManager->NotifyUser($userId, TypeEventNotificationEnum::none, $notifParams);
        }

        if ($data->success) {
            $chaine = $data->cart_id;
            $user = explode('-', $chaine)[0];
            $old_value = Balances::getStoredUserBalances($user, Balances::CASH_BALANCE);
            $value = BalancesFacade::getCash($user);
            CashBalances::addLine([
                'balance_operation_id' => BalanceOperationsEnum::OLD_ID_51->value,
                'operator_id' => $user,
                'beneficiary_id' => $user,
                'reference' => BalancesFacade::getReference(BalanceOperationsEnum::OLD_ID_51),
                'description' => $data->tran_ref,
                'value' => $data->tran_total / $k,
                'current_balance' => $value + $data->tran_total / $k
            ]);
        }
        DB::table('user_transactions')->updateOrInsert(
            ['idUser' => $user],
            ['autorised' => $data->success, 'cause' => $data->payment_result->response_message, 'mnt' => $mnt]
        );
        return redirect()->route('user_balance_cb', app()->getLocale());
    }

    public function getUsersListQuery()
    {
        return User::select('countries.apha2', 'countries.name as country', 'users.id', 'users.status', 'users.idUser', 'idUplineRegister',
            DB::raw('CONCAT(nvl( meta.arFirstName,meta.enFirstName), \' \' ,nvl( meta.arLastName,meta.enLastName)) AS name'),
            'users.mobile', 'users.created_at', 'OptActivation', 'activationCodeValue', 'pass',
            DB::raw('IFNULL(`vip`.`flashCoefficient`,"##") as coeff'),
            DB::raw('IFNULL(`vip`.`flashDeadline`,"##") as periode'),
            DB::raw('IFNULL(`vip`.`flashNote`,"##") as note'),
            DB::raw('IFNULL(`vip`.`flashMinAmount`,"##") as minshares'),
            DB::raw('`vip`.`dateFNS` as date'))
            ->join('metta_users as meta', 'meta.idUser', '=', 'users.idUser')
            ->join('countries', 'countries.id', '=', 'users.idCountry')
            ->leftJoin('vip', function ($join) {
                $join->on('vip.idUser', '=', 'users.idUser')->where('vip.closed', '=', 0);
            })->orderBy('created_at', 'DESC');
    }

    public function getUsersList()
    {
        return datatables($this->getUsersListQuery())
            ->addColumn('register_upline', function ($user) {
                if ($user->idUplineRegister == 11111111) return trans("system"); else
                    return getRegisterUpline($user->idUplineRegister);
            })
            ->addColumn('formatted_created_at', function ($user) {
                return view('parts.datatable.user-date', ['dateTime' => Carbon\Carbon::parse($user->created_at)]);
            })
            ->addColumn('more_details', function ($user) {
                return view('parts.datatable.user-details', ['user' => $user]);
            })
            ->addColumn('status', function ($user) {
                return view('parts.datatable.user-status', ['status' => $user->status]);
            })
            ->addColumn('name', function ($user) {
                return view('parts.datatable.user-name', ['name' => $user->name]);
            })
            ->addColumn('soldes', function ($user) {
                return view('parts.datatable.user-soldes', ['idUser' => $user->idUser]);
            })
            ->addColumn('uplines', function ($user) {
                $params = [
                    'uplineRegister' => User::where('idUser', $user->idUplineRegister)->first(),
                    'upline' => User::where('idUser', $user->idUplineRegister)->first(),
                ];
                return view('parts.datatable.user-upline-register', $params);
            })
            ->addColumn('vip_history', function ($user) {
                return view('parts.datatable.user-vip-history', ['user' => $user]);
            })
            ->addColumn('flag', function ($user) {
                return view('parts.datatable.user-flag', ['src' => $this->getFormatedFlagResourceName($user->apha2), 'title' => strtolower($user->apha2), 'name' => Lang::get($user->country)]);
            })
            ->addColumn('action', function ($settings) {
                $hasVip = vip::Where('idUser', '=', $settings->idUser)->where('closed', '=', false)->get();
                $params = [
                    'phone' => $settings->mobile,
                    'user' => $settings,
                    'country' => $this->getFormatedFlagResourceName($settings->apha2),
                    'reciver' => $settings->idUser,
                    'userId' => $settings->id,
                    'isVip' => null
                ];
                if ($hasVip->isNotEmpty()) {
                    $dateStart = new \DateTime($hasVip->first()->dateFNS);
                    $dateEnd = $dateStart->modify($hasVip->first()->flashDeadline . ' hour');;
                    $params['isVip'] = $dateEnd > now();
                }
                return view('parts.datatable.user-action', $params);
            })
            ->removeColumn('OptActivation')
            ->removeColumn('note')
            ->removeColumn('periode')
            ->removeColumn('register_upline')
            ->removeColumn('minshares')
            ->removeColumn('coeff')
            ->removeColumn('date')
            ->rawColumns(['action', 'flag', 'VIP'])
            ->make(true);
    }

    public function validatePhone(req $request)
    {
        $phoneNumber = $request->input('phoneNumber');
        $inputName = $request->input('inputName');
        if (is_null($inputName) or is_null($phoneNumber)) {
            return new JsonResponse(['message' => Lang::get('Invalid phone number format')], 200);
        }
        try {
            $country = DB::table('countries')->where('phonecode', $inputName)->first();
            $phone = new PhoneNumber($phoneNumber, $country->apha2);
            $phone->formatForCountry($country->apha2);
            return new JsonResponse(['message' => ''], 200);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return new JsonResponse(['message' => Lang::get($exception->getMessage())], 200);
        }
    }


    public function getUserCouponsInjector()
    {
        $coupons = BalanceInjectorCoupon::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();
        return datatables($coupons)
            ->addColumn('action', function ($coupon) {
                return view('parts.datatable.coupon-action', ['coupon' => $coupon]);
            })
            ->addColumn('category', function ($coupon) {
                return view('parts.datatable.coupon-category', ['coupon' => $coupon]);
            })
            ->addColumn('value', function ($coupon) {
                return view('parts.datatable.coupon-value', ['coupon' => $coupon]);
            })
            ->addColumn('consumed', function ($coupon) {
                return view('parts.datatable.coupon-consumed', ['coupon' => $coupon]);
            })
            ->addColumn('dates', function ($coupon) {
                return view('parts.datatable.coupon-injector-dates', ['coupon' => $coupon]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getUserCoupons()
    {
        return datatables(Coupon::where('user_id', auth()->user()->id)->where('status', CouponStatusEnum::purchased->value)->orderBy('id', 'desc')->get())
            ->addColumn('action', function ($coupon) {
                return view('parts.datatable.coupon-consume', ['coupon' => $coupon]);
            })
            ->addColumn('platform_id', function ($coupon) {
                if ($coupon->platform()->first()) {
                    return $coupon->platform()->first()->id . ' - ' . $coupon->platform()->first()->name;
                }
                return '**';
            })
            ->addColumn('value', function ($coupon) {
                return view('parts.datatable.coupon-value', ['coupon' => $coupon]);
            })
            ->addColumn('pin', function ($coupon) {
                return view('parts.datatable.coupon-pin', ['coupon' => $coupon]);
            })
            ->addColumn('consumed', function ($coupon) {
                return view('parts.datatable.coupon-consumed', ['coupon' => $coupon]);
            })
            ->addColumn('dates', function ($coupon) {
                return view('parts.datatable.coupon-dates', ['coupon' => $coupon]);
            })
            ->rawColumns(['action', 'platform_id'])
            ->make(true);
    }

    public function getRequest()
    {
        $condition = "";
        if ($this->settingsManager->getAuthUser() == null) {
            $idUser = "";
        } else {
            $idUser = $this->settingsManager->getAuthUser()->idUser;
        }
        $type = request()->type;
        switch ($type) {
            case('out'):
                $condition = " where recharge_requests.idPayee = ";
                break;
            case('in'):
                $condition = " where recharge_requests.idUser = ";
                break;
        }
        if ($condition == "") {
            $condition = " where recharge_requests.idUser = ";
        }

        $request = DB::select(getSqlFromPath('get_request') . $condition . "  ? ", [$idUser]);
        return datatables($request)
            ->make(true);
    }

    public function getIdentificationRequestQuery()
    {
        return IdentificationUserRequest::select(
            'users1.id as id',
            'users1.name as USER',
            'users1.fullphone_number',
            'identificationuserrequest.created_at as DateCreation',
            'users2.name as Validator',
            'identificationuserrequest.response',
            'identificationuserrequest.responseDate as DateReponce',
            'identificationuserrequest.note')
            ->join('users as users1', 'identificationuserrequest.IdUser', '=', 'users1.idUser')
            ->leftJoin('users as users2', 'identificationuserrequest.idUserResponse', '=', 'users2.idUser')
            ->where('identificationuserrequest.status', StatusRequest::InProgressNational->value)
            ->orWhere('identificationuserrequest.status', StatusRequest::InProgressInternational->value)
            ->get();
    }

    public function getIdentificationRequest()
    {
        return datatables($this->getIdentificationRequestQuery())
            ->addColumn('action', function ($identifications) {
                return view('parts.datatable.identification-action', ['identifications' => $identifications]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getRequestAjax()
    {
        $requestArray = ['requestInOpen' => null, 'requestOutAccepted' => null, 'requestOutRefused' => null];
        if (auth()->user()) {
            $requestInOpen = detail_financial_request::join('financial_request', 'financial_request.numeroReq', '=', 'detail_financial_request.numeroRequest')
                ->where('detail_financial_request.idUser', auth()->user()->idUser)
                ->where('financial_request.Status', 0)
                ->where('detail_financial_request.vu', 0)
                ->count();
            $requestOutAccepted = FinancialRequest::where('financial_request.idSender', auth()->user()->idUser)
                ->where('financial_request.Status', 1)
                ->where('financial_request.vu', 0)
                ->count();
            $requestOutRefused = FinancialRequest::where('financial_request.idSender', auth()->user()->idUser)
                ->where('financial_request.Status', 5)
                ->where('financial_request.vu', 0)
                ->count();
            $requestArray = ['requestInOpen' => $requestInOpen, 'requestOutAccepted' => $requestOutAccepted, 'requestOutRefused' => $requestOutRefused];
        }
        return json_encode(array('data' => $requestArray));
    }
}
