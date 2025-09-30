<?php

namespace App\Http\Controllers;

use App\DAL\UserRepository;
use App\Models\BalanceInjectorCoupon;
use App\Models\BFSsBalances;
use App\Models\BusinessSector;
use App\Models\CashBalances;
use App\Models\Coupon;
use App\Models\OperationCategory;
use App\Models\SharesBalances;
use App\Models\User;
use App\Models\vip;
use App\Notifications\SharePurchase;
use App\Services\Balances\Balances;
use App\Services\Balances\BalancesFacade;
use App\Services\Sponsorship\SponsorshipFacade;
use carbon;
use Core\Enum\BalanceEnum;
use Core\Enum\BalanceOperationsEnum;
use Core\Enum\CouponStatusEnum;
use Core\Enum\PlatformType;
use Core\Enum\StatusRequest;
use Core\Enum\TypeEventNotificationEnum;
use Core\Enum\TypeNotificationEnum;
use Core\Models\BalanceOperation;
use Core\Models\countrie;
use Core\Models\detail_financial_request;
use Core\Models\FinancialRequest;
use Core\Models\Platform;
use Core\Models\Setting;
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
use Spatie\Permission\Models\Role;


class ApiController extends BaseController
{
    const DATE_FORMAT = 'd/m/Y H:i:s';
    const CURRENCY = '$';
    const SEPACE = ' ';
    const SEPARATOR = ' : ';
    const PERCENTAGE = ' % ';


    public function __construct(private readonly settingsManager $settingsManager, private BalancesManager $balancesManager, private UserRepository $userRepository)
    {
    }

    public function SendSMS(Req $request, settingsManager $settingsManager)
    {
        $settingsManager->NotifyUser($request->user, TypeEventNotificationEnum::none, ['msg' => $request->msg, 'type' => TypeNotificationEnum::SMS]);
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


        $palier = Setting::Where('idSETTINGS', '19')->orderBy('idSETTINGS')->pluck('IntegerValue')->first();
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

    public function vip(Req $request)
    {
        vip::where('idUser', $request->reciver)
            ->where('closed', '=', 0)
            ->update(['closed' => 1, 'closedDate' => now(),]);

        $maxShares = Setting::find(34);
        vip::create(
            [
                'idUser' => $request->reciver,
                'flashCoefficient' => $request->coefficient,
                'flashDeadline' => $request->periode,
                'flashNote' => $request->note,
                'flashMinAmount' => $request->minshares,
                'dateFNS' => now(),
                'maxShares' => $maxShares->IntegerValue,
                'solde' => $maxShares->IntegerValue,
                'declenched' => 0,
                'closed' => 0,
            ]
        );
        return "success";

    }

    public function addCash(Req $request, BalancesManager $balancesManager)
    {
        DB::beginTransaction();
        try {
            $old_value = Balances::getStoredUserBalances(Auth()->user()->idUser, Balances::CASH_BALANCE);
            if (intval($old_value) < intval($request->amount)) {
                throw new \Exception(Lang::get('Insuffisant cash solde'));
            }
            $ref = BalancesFacade::getReference(BalanceOperationsEnum::OLD_ID_42->value);
            CashBalances::addLine([
                'balance_operation_id' => BalanceOperationsEnum::OLD_ID_48->value,
                'operator_id' => auth()->user()->idUser,
                'beneficiary_id' => auth()->user()->idUser,
                'reference' => $ref,
                'description' => "Transfered to " . getPhoneByUser($request->input('reciver')),
                'value' => $request->input('amount'),
                'current_balance' => $balancesManager->getBalances(auth()->user()->idUser, -1)->soldeCB - $request->input('amount')
            ]);

            CashBalances::addLine([
                'balance_operation_id' => BalanceOperationsEnum::OLD_ID_43->value,
                'operator_id' => auth()->user()->idUser,
                'beneficiary_id' => $request->input('reciver'),
                'reference' => $ref,
                'description' => "Transfered from " . getPhoneByUser(Auth()->user()->idUser),
                'value' => $request->input('amount'),
                'current_balance' => $balancesManager->getBalances($request->input('reciver'), -1)->soldeCB + $request->input('amount')
            ]);
            $message = $request->amount . ' $ ' . Lang::get('for') . ' ' . getUserDisplayedName($request->input('reciver'));
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return response()->json($exception->getMessage(), 500);
        }
        return response()->json(Lang::get('Successfully runned operation') . ' ' . $message, 200);

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


    public function getSharesSoldeQuery()
    {
        return DB::table('shares_balances')
            ->where('beneficiary_id', Auth()->user()->idUser)
            ->orderBy('id', 'desc');
    }

    public function getSharesSolde()
    {
        return datatables($this->getSharesSoldeQuery())
            ->addColumn('total_price', function ($sharesBalances) {
                return number_format($sharesBalances->unit_price * ($sharesBalances->value), 2);
            })
            ->addColumn('share_price', function ($sharesBalances) {
                if ($sharesBalances->value != 0)
                    return $sharesBalances->unit_price * ($sharesBalances->value) / $sharesBalances->value;
                else return 0;
            })
            ->addColumn('formatted_created_at', function ($sharesBalances) {
                return Carbon\Carbon::parse($sharesBalances->created_at)->format('Y-m-d H:i:s');
            })
            ->addColumn('total_shares', function ($sharesBalances) {
                return $sharesBalances->value;
            })
            ->addColumn('present_value', function ($sharesBalances) {
                return number_format(($sharesBalances->value) * actualActionValue(getSelledActions(true)), 2);
            })
            ->addColumn('current_earnings', function ($sharesBalances) {
                return number_format(($sharesBalances->value) * actualActionValue(getSelledActions(true)) - $sharesBalances->unit_price * ($sharesBalances->value), 2);
            })
            ->addColumn('value_format', function ($sharesBalances) {
                return number_format($sharesBalances->value, 0);
            })
            ->addColumn('complementary_information', function ($balance) {
                return view('parts.datatable.ci.ci-' . $balance->balance_operation_id, ['balance' => $balance]);
            })
            ->rawColumns(['total_price', 'share_price', 'formatted_created_at', 'total_shares', 'present_value', 'current_earnings', 'value_format'])
            ->make(true);
    }


    public function getSharesSoldeList($locale, $idUser)
    {
        $results = DB::table('shares_balances as u')
            ->select(
                'u.reference',
                'u.created_at',
                DB::raw("CASE WHEN b.IO = 'I' THEN u.value ELSE -u.value END AS value"),
                'u.beneficiary_id',
                'u.balance_operation_id',
                'u.real_amount',
                'u.current_balance',
                'u.unit_price',
                'u.total_amount'
            )
            ->join('balance_operations as b', 'u.balance_operation_id', '=', 'b.id')
            ->join('users as s', 'u.beneficiary_id', '=', 's.idUser')
            ->where('u.beneficiary_id', $idUser)
            ->orderBy('u.created_at')->get();
        return response()->json($results);
    }

    public function getUpdatedCardContent()
    {
        $updatedContent = number_format(getRevenuSharesReal(), 2); // Adjust this based on your logic
        return response()->json(['value' => $updatedContent]);
    }

    public function getSharesSoldesQuery()
    {
        return DB::table('shares_balances')
            ->select(
                'current_balance',
                'payed',
                'countries.apha2',
                'shares_balances.id',
                DB::raw('CONCAT(nvl( meta.arFirstName,meta.enFirstName), \' \' ,nvl( meta.arLastName,meta.enLastName)) AS Name'),
                'user.mobile',
                DB::raw('CAST(value AS DECIMAL(10,0)) AS value'),
                'value',
                DB::raw('CAST(shares_balances.unit_price AS DECIMAL(10,2)) AS unit_price'),
                'shares_balances.created_at',
                'shares_balances.payed as payed',
                'shares_balances.beneficiary_id'
            )
            ->join('users as user', 'user.idUser', '=', 'shares_balances.beneficiary_id')
            ->join('metta_users as meta', 'meta.idUser', '=', 'user.idUser')
            ->join('countries', 'countries.id', '=', 'user.idCountry')
            ->orderBy('created_at')
            ->get();
    }

    public function getSharesSoldes()
    {
        return datatables($this->getSharesSoldesQuery())
            ->addColumn('total_price', function ($sharesBalances) {
                return number_format($sharesBalances->unit_price * ($sharesBalances->value), 2);
            })
            ->addColumn('share_price', function ($sharesBalances) {
                if ($sharesBalances->value != 0)
                    return $sharesBalances->unit_price * ($sharesBalances->value) / $sharesBalances->value;
                else return 0;
            })
            ->addColumn('flag', function ($sharesBalances) {
                return '<img src="' . $this->getFormatedFlagResourceName($sharesBalances->apha2) . '" alt="' . strtolower($sharesBalances->apha2) . '" class="avatar-xxs me-2">';
            })
            ->addColumn('sell_price_now', function ($sharesBalances) {
                return number_format(actualActionValue(getSelledActions(true)) * ($sharesBalances->value), 2);
            })
            ->addColumn('gain', function ($sharesBalances) {
                return number_format(actualActionValue(getSelledActions(true)) * ($sharesBalances->value) - $sharesBalances->unit_price * ($sharesBalances->value), 2);
            })
            ->addColumn('total_shares', function ($sharesBalances) {
                return number_format($sharesBalances->value, 0);
            })
            ->addColumn('asset', function ($sharesBalances) {
                return $this->getFormatedFlagResourceName($sharesBalances->apha2);
            })
            ->rawColumns(['flag', 'share_price', 'status'])
            ->make(true);
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
        $k = Setting::Where('idSETTINGS', '30')->orderBy('idSETTINGS')->pluck('DecimalValue')->first();
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

    public function updateReserveDate(Req $request)
    {
        try {
            $id = $request->input('id');
            $status = $request->input('status');
            $successArray = ['success' => true];
            if ($status == "true") {
                $st = 1;
                $dt = now();
                DB::table('user_contacts')->where('id', $id)->update(['availablity' => $st, 'reserved_at' => $dt]);
                return response()->json($successArray);
            } else {
                $st = 0;
                DB::table('user_contacts')->where('id', $id)->update(['availablity' => $st]);
                return response()->json($successArray);
            }
            DB::table('user_contacts')
                ->where('id', $id)
                ->update(['availablity' => $st, 'reserved_at' => $dt]);
            return response()->json($successArray);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function updateBalanceStatus(Req $request, BalancesManager $balancesManager)
    {
        try {
            $id = $request->input('id');
            $st = 0;
            DB::table('shares_balances')->where('id', $id)->update(['payed' => $st]);
            return response()->json(['success' => true]);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function updateBalanceReal(Req $request, BalancesManager $balancesManager)
    {
        try {
            $id = $request->input('id');
            $st = $request->input('amount');
            $total = $request->input('total');

            if ($st == 0) {
                $p = 0;
            } else {
                if ($st < $total) $p = 2;
                if ($st == $total) $p = 1;
            }
            DB::table('shares_balances')
                ->where('id', $id)
                ->update(['real_amount' => floatval($st), 'payed' => $p]);

            return response()->json(['success' => true]);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function getTransfertQuery()
    {
        return DB::table('cash_balances')
            ->select('value', 'description', 'created_at')
            ->where('balance_operation_id', BalanceOperationsEnum::OLD_ID_42->value)
            ->where('beneficiary_id', Auth()->user()->idUser)
            ->whereNotNull('description')
            ->orderBy('created_at', 'DESC');
    }

    public function getTransfert()
    {
        return datatables($this->getTransfertQuery())->make(true);
    }


    public function getUserCashBalanceQuery()
    {
        return DB::table('cash_balances')
            ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") AS x'), DB::raw('CAST(current_balance AS DECIMAL(10,2)) AS y'))
            ->where('beneficiary_id', auth()->user()->idUser)
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    public function getUserCashBalance()
    {
        $query = $this->getUserCashBalanceQuery();
        foreach ($query as $record) {
            $record->Balance = (float)$record->y;
        }
        return response()->json($query);
    }

    public function getSharePriceEvolutionQuery()
    {
        return DB::table('shares_balances')
            ->select(
                DB::raw('CAST(SUM(value) OVER (ORDER BY id) AS DECIMAL(10,0))AS x'),
                DB::raw('CAST(unit_price AS DECIMAL(10,2)) AS y')
            )
            ->where('balance_operation_id', 44)
            ->orderBy('created_at')
            ->get();
    }

    public function getSharePriceEvolution()
    {
        $query = $this->getSharePriceEvolutionQuery();
        foreach ($query as $record) {
            $record->y = (float)$record->y;
            $record->x = (float)$record->x;
        }
        return response()->json($query);
    }

    public function getSharePriceEvolutionDateQuery()
    {
        return DB::table('shares_balances')
            ->select(DB::raw('DATE(created_at) as x'), DB::raw('SUM(value) as y'))
            ->where('balance_operation_id', 44)
            ->groupBy('x')
            ->get();
    }

    public function getSharePriceEvolutionDate()
    {
        $query = $this->getSharePriceEvolutionDateQuery();
        foreach ($query as $record) {
            $record->y = (float)$record->y;
        }
        return response()->json($query);
    }

    public function getSharePriceEvolutionWeekQuery()
    {
        return DB::table('shares_balances')
            ->select(DB::raw(' concat(year(created_at),\'-\',WEEK(created_at, 1)) as x'), DB::raw('SUM(value) as y'), DB::raw(' WEEK(created_at, 1) as z'))
            ->where('balance_operation_id', 44)
            ->groupBy('x', 'z')
            ->orderBy('z')
            ->get();
    }

    public function getSharePriceEvolutionWeek()
    {
        $query = $this->getSharePriceEvolutionWeekQuery();
        foreach ($query as $record) {
            $record->y = (float)$record->y;
        }
        return response()->json($query);
    }


    public function getSharePriceEvolutionMonthQuery()
    {
        return DB::table('shares_balances')
            ->select(DB::raw('DATE_FORMAT(created_at, \'%Y-%m\') as x'), DB::raw('SUM(value) as y'))
            ->where('balance_operation_id', 44)
            ->groupBy('x')
            ->get();
    }

    public function getSharePriceEvolutionMonth()
    {
        $query = $this->getSharePriceEvolutionMonthQuery();
        foreach ($query as $record) {
            $record->y = (float)$record->y;
        }
        return response()->json($query);
    }

    public function getSharePriceEvolutionDayQuery()
    {
        return DB::table('shares_balances')
            ->select(DB::raw('DAYNAME(created_at) as x'), DB::raw('SUM(value) as y'), DB::raw('DAYOFWEEK(created_at) as z'))
            ->where('balance_operation_id', 44)
            ->groupBy('x', 'z')
            ->orderBy('z')
            ->get();
    }

    public function getSharePriceEvolutionDay()
    {
        $query = $this->getSharePriceEvolutionDayQuery();
        foreach ($query as $record) {
            $record->y = (float)$record->y;
        }
        return response()->json($query);
    }

    public function getSharePriceEvolutionUser()
    {
        $query = DB::select(getSqlFromPath('get_share_price_evolution_user'), [auth()->user()->idUser, auth()->user()->idUser]);
        foreach ($query as $record) {
            if ($record->y) $record->y = (float)$record->y;
            $record->x = (float)$record->x;
        }
        return response()->json($query);
    }

    public function getActionValues()
    {
        $limit = getSelledActions(true) * 1.05;
        $data = [];
        $setting = Setting::WhereIn('idSETTINGS', ['16', '17', '18'])->orderBy('idSETTINGS')->pluck('IntegerValue');
        $initial_value = $setting[0];
        $final_value = $initial_value * 5;
        $total_actions = $setting[2];

        for ($x = 0; $x <= $limit; $x += intval($limit / 20)) {
            $val = ($final_value - $initial_value) / ($total_actions - 1) * ($x + 1) + ($initial_value - ($final_value - $initial_value) / ($total_actions - 1));
            $data[] = [
                'x' => $x,
                'y' => number_format($val, 2, '.', '') * 1
            ];
        }
        return response()->json($data);

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

    public function getCountries()
    {
        $query = countrie::all('id', 'name', 'phonecode', 'langage');
        return datatables($query)
            ->addColumn('action', function ($country) {
                return view('parts.datatable.countries-action', ['country' => $country]);
            })
            ->make(true);
    }




    public function getBalanceOperationsCategories()
    {
        return datatables(OperationCategory::all())
            ->addColumn('action', function ($operationCategory) {
                return view('parts.datatable.balances-categories-actions', ['operationCategory' => $operationCategory]);
            })
            ->escapeColumns([])
            ->toJson();
    }

    public function getAmountsQuery()
    {
        return DB::table('amounts')
            ->select('idamounts', 'amountsname', 'amountswithholding_tax', 'amountspaymentrequest', 'amountstransfer', 'amountscash', 'amountsactive', 'amountsshortname');
    }

    public function getAmounts()
    {
        return datatables($this->getAmountsQuery())
            ->addColumn('action', function ($amounts) {
                return view('parts.datatable.amounts-action', ['amounts' => $amounts]);
            })
            ->editColumn('amountswithholding_tax', function ($amounts) {
                return view('parts.datatable.amounts-tax', ['amounts' => $amounts]);
            })
            ->editColumn('amountstransfer', function ($amounts) {
                return view('parts.datatable.amounts-transfer', ['amounts' => $amounts]);
            })
            ->editColumn('amountspaymentrequest', function ($amounts) {
                return view('parts.datatable.amounts-payment', ['amounts' => $amounts]);
            })
            ->editColumn('amountscash', function ($amounts) {
                return view('parts.datatable.amounts-cash', ['amounts' => $amounts]);
            })
            ->editColumn('amountsactive', function ($amounts) {
                return view('parts.datatable.amounts-active', ['amounts' => $amounts]);
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function getActionHistorysQuery()
    {
        return DB::table('action_history')
            ->select('id', 'title', 'reponce');
    }

    public function getActionHistorys()
    {
        return datatables($this->getActionHistorysQuery())
            ->addColumn('action', function ($share) {
                return view('parts.datatable.share-history-action', ['share' => $share]);
            })
            ->editColumn('reponce', function ($share) {
                return view('parts.datatable.share-history-reponce', ['share' => $share]);
            })
            ->escapeColumns([])
            ->make(true);
    }


    public function getUserBalancesList($locale, $idUser, $idamount, $json = true)
    {
        match (intval($idamount)) {
            BalanceEnum::CASH->value => $balances = "cash_balances",
            BalanceEnum::BFS->value => $balances = "bfss_balances",
            BalanceEnum::DB->value => $balances = "discount_balances",
            BalanceEnum::SMS->value => $balances = "sms_balances",
            BalanceEnum::TREE->value => $balances = "tree_balances",
            BalanceEnum::SHARE->value => $balances = "shares_balances",
            default => $balances = "cash_balances",
        };
        $results = DB::table($balances . ' as u')
            ->select(
                DB::raw("RANK() OVER (ORDER BY u.created_at ASC, u.reference ASC) as ranks"),
                'u.id',
                'u.reference',
                'u.beneficiary_id',
                'u.created_at',
                'u.balance_operation_id',
                'b.operation',
                DB::raw("CASE WHEN b.IO = 'I' THEN u.value ELSE -u.value END AS value"),
                'u.current_balance'
            )
            ->join('balance_operations as b', 'u.balance_operation_id', '=', 'b.id')
            ->join('users as s', 'u.beneficiary_id', '=', 's.idUser')
            ->where('u.beneficiary_id', $idUser)
            ->orderBy('u.created_at', 'DESC')->get();
        if (!$json) {
            return $results;
        }
        return response()->json($results);
    }

    public function getUserBalancesQuery($balance)
    {
        return DB::table($balance . ' as ub')
            ->join('balance_operations as bo', 'ub.balance_operation_id', '=', 'bo.id')
            ->selectRaw('
        RANK() OVER (ORDER BY ub.created_at ASC, ub.reference ASC) as ranks,
        ub.beneficiary_id,
        ub.id,
        ub.operator_id,
        ub.reference,
        ub.created_at,
        bo.operation,
        ub.description,
        ub.current_balance,
        ub.balance_operation_id,
        CASE
            WHEN ub.operator_id = "11111111" THEN "system"
            ELSE (SELECT CONCAT(IFNULL(enfirstname, ""), " ", IFNULL(enlastname, ""))
                  FROM metta_users mu
                  WHERE mu.idUser = ub.operator_id)
        END as source,
        CASE
            WHEN bo.IO = "I" THEN CONCAT("+", "$", FORMAT(ub.value, 3))
            WHEN bo.IO = "O" THEN CONCAT("-", "$", FORMAT(ub.value, 3))
            WHEN bo.IO = "IO" THEN "IO"
        END as value
    ')
            ->where('ub.beneficiary_id', auth()->user()->idUser)
            ->orderBy('ub.created_at', 'desc')
            ->orderBy('ub.reference', 'desc');
    }

    public function getUserBalances($typeAmounts)
    {
        $balance = null;
        switch ($typeAmounts) {
            case 'Balance-For-Shopping':
                $balance = "bfss_balances";
                break;
            case 'Discounts-Balance':
                $balance = "discount_balances";
                break;
            case 'SMS-Balance':
                $balance = "sms_balances";
                break;
            default :
                $balance = "cash_balances";
                break;
        }
        return datatables($this->getUserBalancesQuery($balance))
            ->addColumn('reference', function ($balance) {
                return view('parts.datatable.balances-references', ['balance' => $balance]);
            })
            ->addColumn('formatted_date', function ($user) {
                return Carbon\Carbon::parse($user->created_at)->format('Y-m-d');
            })
            ->editColumn('current_balance', function ($balance) {
                return self::CURRENCY . self::SEPACE . formatSolde($balance->current_balance, 2);
            })
            ->addColumn('complementary_information', function ($balance) {
                return view('parts.datatable.ci.ci-' . $balance->balance_operation_id, ['balance' => $balance]);
            })
            ->rawColumns(['formatted_date'])
            ->make(true);
    }

    public function getTreeUser($locale)
    {
        return datatables($this->getUserBalancesList($locale, auth()->user()->idUser, BalanceEnum::TREE->value, false))
            ->editColumn('value', function ($balcene) {
                return formatSolde($balcene->value, 2) . ' ' . self::PERCENTAGE;
            })
            ->editColumn('current_balance', function ($balcene) {
                return formatSolde($balcene->current_balance, 2) . ' ' . self::PERCENTAGE;
            })
            ->addColumn('complementary_information', function ($balance) {
                return view('parts.datatable.ci.ci-' . $balance->balance_operation_id, ['balance' => $balance]);
            })
            ->make(true);
    }

    public function getSmsUser($locale)
    {
        return datatables($this->getUserBalancesList($locale, auth()->user()->idUser, BalanceEnum::SMS->value, false))
            ->addColumn('complementary_information', function ($balance) {
                return view('parts.datatable.ci.ci-' . $balance->balance_operation_id, ['balance' => $balance]);
            })
            ->make(true);
    }

    public function getChanceUser()
    {
        $user = $this->settingsManager->getAuthUser();
        if (!$user) $user->idUser = '';
        $userData = DB::table('chance_balances as ub')
            ->select(
                DB::raw('RANK() OVER (ORDER BY ub.created_at DESC) as ranks'),
                'ub.beneficiary_id',
                'ub.id',
                'ub.operator_id',
                'ub.reference',
                'ub.created_at',
                'bo.operation',
                'ub.description',
                'ub.value',
                'ub.current_balance',
                'ub.balance_operation_id',
                DB::raw(" CASE WHEN ub.beneficiary_id = '11111111' THEN 'system' ELSE (SELECT CONCAT(IFNULL(enfirstname, ''), ' ', IFNULL(enlastname, '')) FROM metta_users mu WHERE mu.idUser = ub.beneficiary_id) END AS source "),
                'bo.IO as sensP'
            )
            ->join('balance_operations as bo', 'ub.balance_operation_id', '=', 'bo.id')
            ->where('ub.beneficiary_id', $user->idUser)
            ->orderBy('created_at')->get();

        return datatables($userData)
            ->editColumn('value', function ($balance) {
                return formatSolde($balance->value, 2);
            })
            ->editColumn('description', function ($row) {
                return Balances::generateDescriptionById($row->id, BalanceEnum::CHANCE->value);
            })
            ->editColumn('current_balance', function ($balance) {
                return formatSolde($balance->current_balance, 2);
            })
            ->addColumn('complementary_information', function ($balance) {
                return view('parts.datatable.ci.ci-' . $balance->balance_operation_id, ['balance' => $balance]);
            })
            ->rawColumns(['description'])
            ->make(true);
    }

    public function getPurchaseBFSUser($locale, $type = null)
    {
        $user = $this->settingsManager->getAuthUser();
        if (!$user) $user->idUser = '';
        $query = DB::table('bfss_balances as ub')
            ->select(
                DB::raw('RANK() OVER (ORDER BY ub.created_at ASC) as ranks'),
                'ub.beneficiary_id', 'ub.id', 'ub.operator_id', 'ub.reference', 'ub.created_at', 'bo.operation', 'ub.description',
                DB::raw(" CASE WHEN ub.operator_id = '11111111' THEN 'system' ELSE (SELECT CONCAT(IFNULL(enfirstname, ''), ' ', IFNULL(enlastname, '')) FROM metta_users mu WHERE mu.idUser = ub.beneficiary_id) END AS source "),
                DB::raw(" CASE WHEN bo.IO = 'I' THEN CONCAT('+', '$', FORMAT(ub.value, 2)) WHEN bo.IO = 'O' THEN CONCAT('-', '$', FORMAT(ub.value , 2)) WHEN bo.IO = 'IO' THEN 'IO' END AS value "),
                'bo.IO as sensP',
                'ub.percentage as percentage',
                'ub.current_balance',
                'ub.balance_operation_id'
            )
            ->join('balance_operations as bo', 'ub.balance_operation_id', '=', 'bo.id');
        $query->where('ub.beneficiary_id', $user->idUser);

        if ($type != null && $type != 'ALL') {
            $query->where('percentage', $type);
        };
        return datatables($query->orderBy('created_at')->orderBy('percentage')->get())
            ->editColumn('description', function ($row) {
                return Balances::generateDescriptionById($row->id, BalanceEnum::BFS->value);
            })
            ->editColumn('current_balance', function ($balance) {
                return self::CURRENCY . self::SEPACE . formatSolde($balance->current_balance, 2);
            })
            ->addColumn('complementary_information', function ($balance) {
                return view('parts.datatable.ci.ci-' . $balance->balance_operation_id, ['balance' => $balance]);
            })
            ->rawColumns(['description'])
            ->make(true);
    }

    /**
     * @throws \Yajra\DataTables\Exceptions\Exception
     */
    public function getUserAdmin()
    {
        $authorizedRoles = ['admin', 'Moderateur'];
        $admins = User::whereHas('roles', static function ($query) use ($authorizedRoles) {
            return
                $query->whereIn('roles.name', $authorizedRoles)
                    ->join('countries', 'users.idCountry', '=', 'countries.phonecode')
                    ->select('roles.name');
        })
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->join('countries', 'idCountry', '=', 'countries.phonecode')
            ->select('users.name as userName', 'users.id', 'users.mobile', 'countries.name as countrieName', 'roles.name as roleName')
            ->get();
        foreach ($admins as $admin) {
            $plateformes = DB::table('user_plateforme')
                ->join('plateformes', 'plateformes.id', '=', 'user_plateforme.plateforme_id')
                ->where('user_id', '=', $admin->id)
                ->get();
            $admin->plateformes = "";
            foreach ($plateformes as $plateforme) {
                $admin->plateformes = $admin->plateformes . $plateforme->name . ",";
            }
            $admin->plateformes = rtrim($admin->plateformes, ", ");
        }
        return datatables($admins)
            ->addColumn('action', function ($admin) {
                return view('parts.datatable.share-history-action.blade', ['admin' => $admin]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getHistoryNotificationModerateur()
    {
        return datatables($this->settingsManager->getHistoryForModerateur())->make(true);
    }

    public function getHistoryNotification()
    {
        return datatables($this->settingsManager->getHistory())->make(true);
    }


    public function deleteCoupon(Req $request)
    {
        $ids = $request->input('ids');

        if (empty($ids)) {
            return response()->json(['message' => 'No IDs provided'], 400);
        }

        try {
            Coupon::whereIn('id', $ids)->where('consumed', 0)->delete();
            return response()->json(['message' => 'Coupons deleted successfully (Only not consumed)']);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json(['message' => 'An error occurred while deleting the coupons'], 500);
        }
    }

    public function deleteInjectorCoupon(Req $request)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return response()->json(['message' => 'No IDs provided'], 400);
        }
        try {
            BalanceInjectorCoupon::whereIn('id', $ids)->where('consumed', 0)->delete();
            return response()->json(['message' => 'Injector coupons deleted successfully (Only not consumed)']);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json(['message' => 'An error occurred while deleting the coupons'], 500);
        }
    }

    public function getCoupons()
    {
        return datatables(Coupon::orderBy('id', 'desc')->get())
            ->addColumn('action', function ($coupon) {
                return view('parts.datatable.coupon-action', ['coupon' => $coupon]);
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
            ->addColumn('consumed', function ($coupon) {
                return view('parts.datatable.coupon-consumed', ['coupon' => $coupon]);
            })
            ->addColumn('dates', function ($coupon) {
                return view('parts.datatable.coupon-dates', ['coupon' => $coupon]);
            })
            ->rawColumns(['action', 'platform_id'])
            ->make(true);
    }

    public function getCouponsInjector()
    {
        return datatables(BalanceInjectorCoupon::orderBy('created_at', 'desc')->get())
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

    public function getPlatforms()
    {
        return datatables(Platform::all())
            ->addColumn('type', function ($platform) {
                return Lang::get(PlatformType::from($platform->type)->name);
            })
            ->addColumn('image', function ($platform) {
                return view('parts.datatable.platform-image', ['platform' => $platform]);
            })
            ->addColumn('action', function ($platform) {
                return view('parts.datatable.platform-action', ['platform' => $platform]);
            })
            ->addColumn('created_at', function ($platform) {
                return $platform->created_at?->format(self::DATE_FORMAT);
            })
            ->editColumn('name', function ($platform) {
                return view('parts.datatable.platform-name', ['platform' => $platform]);
            })
            ->addColumn('business_sector_id', function ($platform) {
                $businessSector = BusinessSector::find($platform->business_sector_id);
                return view('parts.datatable.platform-bussines-sector', ['businessSector' => $businessSector]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getRoles()
    {
        return datatables(Role::all())
            ->addColumn('action', function ($role) {
                return view('parts.datatable.role-action', ['roleId' => $role->id, 'roleName' => $role->name]);
            })
            ->addColumn('created_at', function ($platform) {
                return $platform->created_at?->format(self::DATE_FORMAT);
            })
            ->addColumn('updated_at', function ($platform) {
                return $platform->updated_at?->format(self::DATE_FORMAT);
            })
            ->rawColumns(['action'])
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

    public function getRepresentatives()
    {
        $representatives = DB::table('representatives')->get();
        return datatables($representatives)
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


    public function getInvitationsUser()
    {
        $user = $this->settingsManager->getAuthUser();
        $userData = DB::select(getSqlFromPath('get_invitations_user'), [$user->idUser, $user->idUser, $user->idUser, $user->idUser, $user->idUser]);
        return datatables($userData)
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
