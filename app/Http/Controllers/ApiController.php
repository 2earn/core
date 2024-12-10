<?php

namespace App\Http\Controllers;

use App\DAL\UserRepository;
use App\Models\BFSsBalances;
use App\Models\CashBalances;
use App\Models\Deal;
use App\Models\SharesBalances;
use App\Models\User;
use App\Models\vip;
use App\Services\Balances\Balances;
use App\Services\Balances\BalancesFacade;
use App\Services\Sponsorship\SponsorshipFacade;
use carbon;
use Core\Enum\BalanceEnum;
use Core\Enum\BalanceOperationsEnum;
use Core\Enum\DealStatus;
use Core\Enum\PlatformType;
use Core\Enum\StatusRequest;
use Core\Enum\TypeEventNotificationEnum;
use Core\Enum\TypeNotificationEnum;
use Core\Models\countrie;
use Core\Models\detail_financial_request;
use Core\Models\FinancialRequest;
use Core\Models\Platform;
use Core\Models\Setting;
use Core\Models\user_balance;
use Core\Services\BalancesManager;
use Core\Services\settingsManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request as Req;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator as Val;
use Illuminate\Support\Facades\Vite;
use Illuminate\Validation\Rule;
use Paytabscom\Laravel_paytabs\Facades\paypage;
use phpDocumentor\Reflection\Types\Collection;
use Propaganistas\LaravelPhone\PhoneNumber;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;


class ApiController extends BaseController
{
    const DATE_FORMAT = 'd/m/Y H:i:s';


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


        $ref = BalancesFacade::getRederence(BalanceOperationsEnum::SELLED_SHARES->value);


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
            SponsorshipFacade::executeProactifSponsorship($userSponsored->idUser, $ref, $number_of_action, $gift, $PU, $fullphone_number);
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


        $this->userRepository->increasePurchasesNumber($reciver);

        SharesBalances::addLine([
            'balance_operation_id' => BalanceOperationsEnum::SELLED_SHARES->value,
            'operator_id' => Balances::SYSTEM_SOURCE_ID,
            'beneficiary_id' => $reciver,
            'reference' => $ref,
            'unit_price' => $actual_price,
            'payed' => 1,
            'value' => $number_of_action,
            'amount' => $number_of_action * $actual_price,
            'total_amount' => null, // get old value of total amount  + amount ($number_of_action * $actual_price)
            'real_amount' => $number_of_action * $actual_price,
            'description' => 'TO DO DESCRIPTION',
            'current_balance' => null // get old value of current balances   +$number_of_action
        ]);

        if ($gift > 0) {
            SharesBalances::addLine([
            'balance_operation_id' => BalanceOperationsEnum::COMPLIMENTARY_BENEFITS_ON_PURCHASED_SHARES->value,
            'operator_id' => Balances::SYSTEM_SOURCE_ID,
            'beneficiary_id' => $reciver,
            'reference' => $ref,
            'unit_price' => 0,
            'payed' => 1,
            'description' => 'TO DO DESCRIPTION',
            'value' => $gift,
            'current_balance' => null // get old value of current balances   +$number_of_action
            ]);
        }
        if ($flashGift > 0) {
            SharesBalances::addLine([
                'balance_operation_id' => BalanceOperationsEnum::VIP_BENEFITS_ON_PURCHASED_SHARES->value,
                'operator_id' => Balances::SYSTEM_SOURCE_ID,
                'beneficiary_id' => $reciver,
                'reference' => $ref,
                'unit_price' => 0,
                'payed' => 1,
                'value' => $flashGift,
                'description' => 'TO DO DESCRIPTION',
                'current_balance' => null // get old value of current balances   +$number_of_action
            ]);
        }

        CashBalances::addLine([
            'balance_operation_id' => BalanceOperationsEnum::SELL_SHARES->value,
            'operator_id' => auth()->user()->idUser,
            'beneficiary_id' => auth()->user()->idUser,
            'reference' => $ref,
            'description' => "purchase of " . ($number_of_action + $gift) . " shares for " . $a,
            'value' => $number_of_action  * $actual_price,
            'current_balance' => $balancesManager->getBalances(auth()->user()->idUser, -1)->soldeCB - ($number_of_action ) * $actual_price
        ]);

        BFSsBalances::addLine([
            'balance_operation_id' => BalanceOperationsEnum::BY_ACQUIRING_SHARES->value,
            'operator_id' => Balances::SYSTEM_SOURCE_ID,
            'beneficiary_id' => $reciver_bfs,
            'reference' => $ref,
            'description' => 'TO DO DESCRIPTION',
            'value' => intval($number_of_action / $palier) * $actual_price * $palier,
            'current_balance' => $balancesManager->getBalances(auth()->user()->idUser, -1)->soldeBFS + intval($number_of_action / $palier) * $actual_price * $palier
        ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['type' => ['error'], 'message' => [trans('Actions purchase transaction failed')]]);
        }
        return response()->json(['type' => ['success'], 'message' => [trans('Actions purchase transaction completed successfully')]]);
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
            $old_value = DB::table('usercurrentbalances')
                ->where('idUser', Auth()->user()->idUser)
                ->where('idamounts', BalanceEnum::CASH)
                ->value('value');

            if (intval($old_value) < intval($request->amount)) {
                throw new \Exception(Lang::get('Insuffisant cash solde'));
            }
            $ref = BalancesFacade::getRederence(BalanceOperationsEnum::CASH_TRANSFERT_O->value->value);
            CashBalances::addLine([
                'balance_operation_id' => BalanceOperationsEnum::SELL_SHARES->value,
                'operator_id' => auth()->user()->idUser,
                'beneficiary_id' => auth()->user()->idUser,
                'reference' => $ref,
                'description' => "Transfered to " . getPhoneByUser($request->input('reciver')),
                'value' => $request->input('amount'),
                'current_balance' => $balancesManager->getBalances(auth()->user()->idUser, -1)->soldeCB - $request->input('amount')
            ]);

            CashBalances::addLine([
                'balance_operation_id' => BalanceOperationsEnum::CASH_TRANSFERT_I->value,
                'operator_id' => auth()->user()->idUser,
                'beneficiary_id' => $request->input('reciver'),
                'reference' => $ref,
                'description' => "Transfered from " . getPhoneByUser(Auth()->user()->idUser),
                'value' => $request->input('amount'),
                'current_balance' =>$balancesManager->getBalances($request->input('reciver'), -1)->soldeCB + $request->input('amount')
            ]);
            $message = $request->amount . ' $ ' . Lang::get('for ') . getUserDisplayedName($request->input('reciver'));
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
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
        return DB::table('user_balances')
            ->select('id', 'value', 'gifted_shares', 'PU', 'Date')
            ->where('idBalancesOperation', 44)
            ->where('idUser', Auth()->user()->idUser)
            ->orderBy('id', 'desc');
    }

    public function getSharesSoldeQuery_v2()
    {
        return DB::table('shares_balances')
            ->select('id', 'value',  'unit_price as PU', 'created_at as Date')
            ->where('beneficiary_id', Auth()->user()->idUser)
            ->orderBy('id', 'desc');
    }
    public function getSharesSolde()
    {
        return datatables($this->getSharesSoldeQuery())
            ->addColumn('total_price', function ($user_balance) {
                return number_format($user_balance->PU * ($user_balance->value + $user_balance->gifted_shares), 2);
            })
            ->addColumn('share_price', function ($user_balance) {
                if ($user_balance->value != 0)
                    return $user_balance->PU * ($user_balance->value + $user_balance->gifted_shares) / $user_balance->value;
                else return 0;
            })
            ->addColumn('formatted_created_at', function ($user_balance) {
                return Carbon\Carbon::parse($user_balance->Date)->format('Y-m-d H:i:s');
            })
            ->addColumn('total_shares', function ($user_balance) {
                return $user_balance->value + $user_balance->gifted_shares;
            })
            ->addColumn('present_value', function ($user_balance) {
                return number_format(($user_balance->value + $user_balance->gifted_shares) * actualActionValue(getSelledActions(true)), 2);
            })
            ->addColumn('current_earnings', function ($user_balance) {
                return number_format(($user_balance->value + $user_balance->gifted_shares) * actualActionValue(getSelledActions(true)) - $user_balance->PU * ($user_balance->value + $user_balance->gifted_shares), 2);
            })
            ->addColumn('value_format', function ($user_balance) {
                return number_format($user_balance->value, 0);
            })
            ->rawColumns(['total_price', 'share_price'])
            ->make(true);
    }

    public function getSharesSoldeList($locale, $idUser)
    {
        $actualActionValue = actualActionValue(getSelledActions(true));
        $userBalances = user_balance::select(
            'Date',
            DB::raw('CAST(value AS DECIMAL(10,0)) AS value'),
            DB::raw('CAST(gifted_shares AS DECIMAL(10,0)) AS gifted_shares'),
            DB::raw('CAST(gifted_shares + value AS DECIMAL(10,0)) AS total_shares'),
            DB::raw('CAST((gifted_shares + value) * PU AS DECIMAL(10,2)) AS total_price'),
            DB::raw('CAST((gifted_shares + value) * ' . $actualActionValue . ' AS DECIMAL(10,2)) AS present_value'),
            DB::raw('CAST((gifted_shares + value) * ' . $actualActionValue . '- (gifted_shares + value) * PU AS DECIMAL(10,2)) AS current_earnings')
        )
            ->where('idBalancesOperation', 44)
            ->where('idUser', $idUser)
            ->get();
        return $userBalances;
    }

    public function getSharesSoldeList_v2($locale, $idUser)
    {
        $actualActionValue = actualActionValue(getSelledActions(true));
        $userBalances = SharesBalances::select(
            'created_at AS Date',
            DB::raw('CAST(value AS DECIMAL(10,0)) AS value'),
            DB::raw('CAST(AS DECIMAL(10,0)) AS gifted_shares'),
            DB::raw('CAST(value AS DECIMAL(10,0)) AS total_shares'),
            DB::raw('CAST((value) * PU AS DECIMAL(10,2)) AS total_price'),
            DB::raw('CAST((value) * ' . $actualActionValue . ' AS DECIMAL(10,2)) AS present_value'),
            DB::raw('CAST(value) * ' . $actualActionValue . '- (value) * unit_price AS DECIMAL(10,2)) AS current_earnings')
        )
            ->where('beneficiary_id', $idUser)
            ->get();
        return $userBalances;
    }

    public function getUpdatedCardContent()
    {
        $updatedContent = number_format(getRevenuSharesReal(), 2); // Adjust this based on your logic
        return response()->json(['value' => $updatedContent]);
    }

    public function getSharesSoldesQuery()
    {
        return DB::table('user_balances')
            ->select('Balance', 'WinPurchaseAmount', 'countries.apha2', 'user_balances.id', DB::raw('CONCAT(nvl( meta.arFirstName,meta.enFirstName), \' \' ,nvl( meta.arLastName,meta.enLastName)) AS Name'), 'user.mobile', DB::raw('CAST(value AS DECIMAL(10,0)) AS value'), 'gifted_shares', DB::raw('CAST(PU AS DECIMAL(10,2)) AS PU'), 'Date', 'user_balances.idUser')
            ->join('users as user', 'user.idUser', '=', 'user_balances.idUser')
            ->join('metta_users as meta', 'meta.idUser', '=', 'user.idUser')
            ->join('countries', 'countries.id', '=', 'user.idCountry')
            ->where('idBalancesOperation', 44);
    }

    public function getSharesSoldesQuery_v2()
    {
        return DB::table('shares_balances')
            ->select(
                'current_balance',
                'payed',
                'countries.apha2',
                'user_balances.id',
                DB::raw('CONCAT(nvl( meta.arFirstName,meta.enFirstName), \' \' ,nvl( meta.arLastName,meta.enLastName)) AS Name'),
                'user.mobile',
                DB::raw('CAST(value AS DECIMAL(10,0)) AS value'),
                'value',
                DB::raw('CAST(PU AS DECIMAL(10,2)) AS PU'),
                'Date',
                'shares_balances.beneficiary_id'
            )
            ->join('users as user', 'user.idUser', '=', 'shares_balances.beneficiary_id')
            ->join('metta_users as meta', 'meta.idUser', '=', 'user.idUser')
            ->join('countries', 'countries.id', '=', 'user.idCountry')
            ->where('balance_operation_id', 44);
    }
    public function getSharesSoldes()
    {
        return datatables($this->getSharesSoldesQuery())
            ->addColumn('total_price', function ($user_balance) {
                return number_format($user_balance->PU * ($user_balance->value + $user_balance->gifted_shares), 2);
            })
            ->addColumn('share_price', function ($user_balance) {
                if ($user_balance->value != 0)
                    return $user_balance->PU * ($user_balance->value + $user_balance->gifted_shares) / $user_balance->value;
                else return 0;
            })
            ->addColumn('formatted_created_at', function ($user_balance) {
                return Carbon\Carbon::parse($user_balance->Date)->format('Y-m-d H:i:s');
            })
            ->addColumn('formatted_created_at_date', function ($user_balance) {
                return Carbon\Carbon::parse($user_balance->Date)->format('Y-m-d');
            })
            ->addColumn('flag', function ($settings) {

                return '<img src="' . $this->getFormatedFlagResourceName($settings->apha2) . '" alt="' . strtolower($settings->apha2) . '" class="avatar-xxs me-2">';
            })
            ->addColumn('sell_price_now', function ($user_balance) {
                return number_format(actualActionValue(getSelledActions(true)) * ($user_balance->value + $user_balance->gifted_shares), 2);
            })
            ->addColumn('gain', function ($user_balance) {
                return number_format(actualActionValue(getSelledActions(true)) * ($user_balance->value + $user_balance->gifted_shares) - $user_balance->PU * ($user_balance->value + $user_balance->gifted_shares), 2);
            })
            ->addColumn('total_shares', function ($user_balance) {
                return number_format($user_balance->value + $user_balance->gifted_shares, 0);
            })
            ->addColumn('asset', function ($settings) {
                return $this->getFormatedFlagResourceName($settings->apha2);
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
        $settingsManager->NotifyUser(2, TypeEventNotificationEnum::none, ['msg' => $msg, 'type' => TypeNotificationEnum::SMS]);
        $settingsManager->NotifyUser(126, TypeEventNotificationEnum::none, ['msg' => $msg, 'type' => TypeNotificationEnum::SMS]);
        if ($data->success) {
            $chaine = $data->cart_id;
            $user = explode('-', $chaine)[0];
            $old_value = DB::table('usercurrentbalances')
                ->where('idUser', $user)
                ->where('idamounts', BalanceEnum::CASH)
                ->value('value');

            $value =  BalancesFacade::getCash($user);

            CashBalances::addLine([
                'balance_operation_id' => BalanceOperationsEnum::CASH_TOP_UP_WITH_CARD->value,
                'operator_id' => $user,
                'beneficiary_id' => $user,
                'reference' =>  BalancesFacade::getReference(BalanceOperationsEnum::CASH_TOP_UP_WITH_CARD),
                'description' =>$data->tran_ref,
                'value' =>$data->tran_total / $k,
                'current_balance' => $value + $data->tran_total / $k
            ]);

            $mnt = $data->tran_total / $k;
            $new_value = intval($old_value) + $data->tran_total / $k;
            // *****************************************
            // OBSERVER CURRENT BALANCES
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

            if ($status == "true") {
                $st = 1;
                $dt = now();
                DB::table('user_contacts')->where('id', $id)->update(['availablity' => $st, 'reserved_at' => $dt]);

                return response()->json(['success' => true]);
            } else {
                $st = 0;
                DB::table('user_contacts')->where('id', $id)->update(['availablity' => $st]);

                return response()->json(['success' => true]);

            }

            DB::table('user_contacts')
                ->where('id', $id)
                ->update(['availablity' => $st, 'reserved_at' => $dt]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Error updating balance status: ' . $e->getMessage());
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
        } catch (\Exception $e) {
            \Log::error('Error updating balance status: ' . $e->getMessage());
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
        } catch (\Exception $e) {
            \Log::error('Error updating balance status: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function getTransfertQuery()
    {
        return DB::table('cash_balances')
            ->select('value', 'description', 'created_at')
            ->where('balance_operation_id', BalanceOperationsEnum::CASH_TRANSFERT_O->value)
            ->where('beneficiary_id', Auth()->user()->idUser)
            ->whereNotNull('description');
    }

    public function getTransfertQuery_v2()
    {
        return DB::table('cash_balances')
            ->select('value', 'description', 'created_at')
            ->where('balance_operation_id', BalanceOperationsEnum::CASH_TRANSFERT_O->value)
            ->where('beneficiary_id', Auth()->user()->idUser)
            ->whereNotNull('description');
    }
    public function getTransfert()
    {
        return datatables($this->getTransfertQuery())
            ->addColumn('formatted_created_at', function ($user_balance) {
                return Carbon\Carbon::parse($user_balance->Date)->format('Y-m-d H:i:s');
            })
            ->make(true);
    }

    public function getUserCashBalanceQuery()
    {
        return DB::table('cash_balances')
            ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") AS x'), DB::raw('CAST(current_balance AS DECIMAL(10,2)) AS y'))
            ->where('beneficiary_id', auth()->user()->idUser)
            ->orderBy('created_at', 'asc')
            ->get();

    }

    public function getUserCashBalanceQuery_v2()
    {
        return DB::table('cash_balances')
            ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") AS x'), DB::raw('CAST(current_balance AS DECIMAL(10,2)) AS y'))
            ->where('beneficiary_id', auth()->user()->idUser)
            ->orderBy('created_at', 'asc')
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
        return DB::table('user_balances')
            ->select(
                DB::raw('CAST(SUM(value ) OVER (ORDER BY id) AS DECIMAL(10,0))AS x'),
                DB::raw('CAST((value + gifted_shares) * PU / value AS DECIMAL(10,2)) AS y')
            )
            ->where('idBalancesOperation', 44) // remove
            ->where('value', '>', 0)
            ->orderBy('Date')
            ->get();
    }

    public function getSharePriceEvolutionQuery_v2()
    {
        return DB::table('shares_balances')
            ->select(
                DB::raw('CAST(SUM(value ) OVER (ORDER BY id) AS DECIMAL(10,0))AS x'),
                DB::raw('CAST((value) * PU / value AS DECIMAL(10,2)) AS y')
            )
            ->where('value', '>', 0)
            ->orderBy('Date')
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
        return DB::table('user_balances')
            ->select(DB::raw('DATE(date) as x'), DB::raw('SUM(value) as y'))
            ->where('idBalancesOperation', 44)
            ->where('value', '>', 0)
            ->groupBy('x')
            ->get();
    }

    public function getSharePriceEvolutionDateQuery_v2()
    {
        return DB::table('shares_balances')
            ->select(DB::raw('DATE(created_at) as x'), DB::raw('SUM(value) as y'))
            ->where('value', '>', 0)
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
        return DB::table('user_balances')
            ->select(DB::raw(' concat(year(date),\'-\',WEEK(date, 1)) as x'), DB::raw('SUM(value) as y'), DB::raw(' WEEK(created_at, 1) as z'))
            ->where('idBalancesOperation', 44)
            ->where('value', '>', 0)
            ->groupBy('x', 'z')
            ->orderBy('z')
            ->get();
    }

    public function getSharePriceEvolutionWeekQuery_v2()
    {
        return DB::table('shares_balances')
            ->select(DB::raw(' concat(year(date),\'-\',WEEK(created_at, 1)) as x'), DB::raw('SUM(value) as y'), DB::raw(' WEEK(created_at, 1) as z'))
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
        return DB::table('user_balances')
            ->select(DB::raw('DATE_FORMAT(date, \'%Y-%m\') as x'), DB::raw('SUM(value) as y'))
            ->where('idBalancesOperation', 44)
            ->where('value', '>', 0)
            ->groupBy('x')
            ->get();
    }

    public function getSharePriceEvolutionMonthQuery_v2()
    {
        return DB::table('shares_balances')
            ->select(DB::raw('DATE_FORMAT(created_at, \'%Y-%m\') as x'), DB::raw('SUM(value) as y'))
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
        return DB::table('user_balances')
            ->select(DB::raw('DAYNAME(date) as x'), DB::raw('SUM(value) as y'), DB::raw('DAYOFWEEK(date) as z'))
            ->where('idBalancesOperation', 44)
            ->where('value', '>', 0)
            ->groupBy('x', 'z')
            ->orderBy('z')
            ->get();
    }

    public function getSharePriceEvolutionDayQuery_v2()
    {
        return DB::table('shares_balances')
            ->select(DB::raw('DAYNAME(created_at) as x'), DB::raw('SUM(value) as y'), DB::raw('DAYOFWEEK(created_at) as z'))
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
        $idUser = auth()->user()->idUser;
        $query = DB::select(getSqlFromPath('get_share_price_evolution_user'), [$idUser, $idUser]);
        foreach ($query as $record) {
            if ($record->y) $record->y = (float)$record->y;
            $record->x = (float)$record->x;
        }

        return response()->json($query);
    }

    public function getSharePriceEvolutionUser_v2()
    {
        /***********************************************************************/
        $idUser = auth()->user()->idUser;
        $query = DB::select(getSqlFromPath('get_share_price_evolution_user'), [$idUser, $idUser]);
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
                'y' => number_format($val, 2, '.', '') * 1 // Call your helper function
            ];
        }
        return response()->json($data);

    }

    public function getUsersListQuery()
    {
        return User::select('countries.apha2', 'countries.name as country', 'users.id', 'users.status', 'users.idUser', 'idUplineRegister',
            DB::raw('CONCAT(nvl( meta.arFirstName,meta.enFirstName), \' \' ,nvl( meta.arLastName,meta.enLastName)) AS name'),
            'users.mobile', 'users.created_at', 'OptActivation', 'pass',
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
            ->addColumn('formatted_mobile', function ($user) {
                $phone = new PhoneNumber($user->mobile, $user->apha2);
                try {
                    return $phone->formatForCountry($user->apha2);
                } catch (\Exception $e) {
                    return $phone;
                }
                return $phone->formatForCountry($user->apha2);
            })
            ->addColumn('register_upline', function ($user) {
                if ($user->idUplineRegister == 11111111) return "system"; else
                    return getRegisterUpline($user->idUplineRegister);
            })
            ->addColumn('formatted_created_at', function ($user) {
                return Carbon\Carbon::parse($user->created_at)->format('Y-m-d H:i:s');
            })
            ->addColumn('more_details', function ($user) {
                return view('parts.datatable.user-details', ['user' => $user]);
            })
            ->addColumn('status', function ($user) {
                return view('parts.datatable.user-status', ['status' => $user->status]);
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
            ->addColumn('VIP', function ($settings) {
                $hasVip = vip::Where('idUser', '=', $settings->idUser)
                    ->where('closed', '=', false)->get();
                if ($hasVip->isNotEmpty()) {
                    $dateStart = new \DateTime($hasVip->first()->dateFNS);
                    $dateEnd = $dateStart->modify($hasVip->first()->flashDeadline . ' hour');;
                    return view('parts.datatable.user-vip', ['mobile' => $settings->mobile, 'isVip' => $dateEnd > now(), 'country' => $this->getFormatedFlagResourceName($settings->apha2), 'country' => $this->getFormatedFlagResourceName($settings->apha2), 'reciver' => $settings->idUser]);
                }
                return view('parts.datatable.user-vip', ['mobile' => $settings->mobile, 'isVip' => null, 'country' => $this->getFormatedFlagResourceName($settings->apha2), 'country' => $this->getFormatedFlagResourceName($settings->apha2), 'reciver' => $settings->idUser]);

            })
            ->addColumn('flag', function ($user) {
                return view('parts.datatable.user-flag', ['src' => $this->getFormatedFlagResourceName($user->apha2), 'title' => strtolower($user->apha2), 'name' => Lang::get($user->country)]);
            })
            ->addColumn('action', function ($settings) {
                return view('parts.datatable.user-action', ['phone' => $settings->mobile, 'user' => $settings, 'country' => $this->getFormatedFlagResourceName($settings->apha2), 'reciver' => $settings->idUser, 'userId' => $settings->id]);
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
        } catch (\Exception $exp) {
            return new JsonResponse(['message' => Lang::get($exp->getMessage())], 200);
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

    public function getSettings()
    {
        $settings = DB::table('settings')
            ->orderBy('idSETTINGS');
        return datatables($settings)
            ->addColumn('action', function ($settings) {
                return view('parts.datatable.settings-action', ['settings' => $settings]);
            })
            ->editColumn('Automatically_calculated', function ($settings) {
                return view('parts.datatable.settings-auto', ['settings' => $settings]);
            })
            ->escapeColumns([])
            ->toJson();
    }

    public function getBalanceOperationsQuery()
    {
        return DB::table('balance_operations')
            ->join('amounts', 'balance_operations.amounts_id', '=', 'amounts.idamounts')
            ->select(
                'balance_operations.id',
                'balance_operations.operation',
                'balance_operations.io',
                'balance_operations.source',
                'balance_operations.amounts_id',
                'balance_operations.note',
                'balance_operations.modify_amount',
                'amounts.amountsshortname');
    }
    public function getBalanceOperations()
    {
        return datatables($this->getBalanceOperationsQuery())
            ->addColumn('action', function ($balance) {
                return view('parts.datatable.balances-status', ['balance' => $balance]);
            })
            ->editColumn('modify_amount', function ($balance) {
                return view('parts.datatable.balances-modify', ['modify' => $balance->modify_amount]);
            })
            ->editColumn('amounts_id', function ($balance) {
                return view('parts.datatable.balances-amounts-id', ['ammount' => $balance->amounts_id]);
            })
            ->editColumn('amountsshortname', function ($balance) {
                return view('parts.datatable.balances-short', ['balance' => $balance]);
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


    public function getUserBalancesList($locale, $idUser, $idamount)
    {
        $results = DB::table('user_balances as u')
            ->select(
                'u.ref',
                'u.idUser',
                'u.idamount',
                'u.Date',
                'u.idBalancesOperation',
                'b.operation',
                'u.Description',
                DB::raw("CASE WHEN b.IO = 'I' THEN u.value ELSE -u.value END AS value"),
                'u.balance'
            )
            ->join('balance_operations as b', 'u.idBalancesOperation', '=', 'b.id')
            ->join('users as s', 'u.idUser', '=', 's.idUser')
            ->where('u.idamount', '!=', 4)
            ->where('u.idamount', '!=', 6)
            ->where('u.idUser', $idUser)
            ->where('u.idamount', $idamount)
            ->orderBy('u.Date')->get();
        return response()->json($results);
    }

    public function getUserBalancesQuery($idAmounts)
    {
        return DB::select(getSqlFromPath('get_user_balances'), [$idAmounts, auth()->user()->idUser]);

    }
    public function getUserBalances($locale, $typeAmounts)
    {
        $idAmounts = 0;
        switch ($typeAmounts) {
            case 'cash-Balance':
                $idAmounts = 1;
                break;
            case 'Balance-For-Shopping':
                $idAmounts = 2;
                break;
            case 'Discounts-Balance':
                $idAmounts = 3;
                break;
            case 'SMS-Balance':
                $idAmounts = 5;
                break;
            default :
                $idAmounts = 0;
                break;
        }

        return Datatables::of($this->getUserBalancesQuery($idAmounts))
            ->addColumn('formatted_date', function ($user) {
                return Carbon\Carbon::parse($user->Date)->format('Y-m-d');
            })
            ->editColumn('Description', function ($row) use ($idAmounts) {
                if ($idAmounts == 3)
                    return '<div style="text-align:right;">' . htmlspecialchars($row->Description) . '</div>';
                else return $row->Description;
            })
            ->rawColumns(['Description', 'formatted_date'])
            ->make(true);
    }

    public function getTreeUser()
    {
        $user = $this->settingsManager->getAuthUser();
        if (!$user) $user->idUser = '';
        $userData = DB::table('user_balances as ub')
            ->select(
                DB::raw('RANK() OVER (ORDER BY ub.Date DESC) as ranks'),
                'ub.idUser', 'ub.id', 'ub.idSource', 'ub.Ref', 'ub.Date', 'bo.Operation', 'ub.Description',
                DB::raw(" CASE WHEN ub.idSource = '11111111' THEN 'system' ELSE (SELECT CONCAT(IFNULL(enfirstname, ''), ' ', IFNULL(enlastname, '')) FROM metta_users mu WHERE mu.idUser = ub.idSource) END AS source "),
                DB::raw(" CASE WHEN bo.IO = 'I' THEN CONCAT('+ ', '$ ', FORMAT(ub.value / PrixUnitaire, 3)) WHEN bo.IO = 'O' THEN CONCAT('- ', FORMAT(ub.value / PrixUnitaire, 3), ' $') WHEN bo.IO = 'IO' THEN 'IO' END AS value "),
                DB::raw(" CASE WHEN idAmount = 5 THEN CONCAT(FORMAT(SUM(CASE WHEN bo.IO = 'I' THEN FORMAT(FORMAT(ub.value, 3) / FORMAT(PrixUnitaire, 3), 3) WHEN bo.IO = 'O' THEN FORMAT(FORMAT(ub.value, 3) / (FORMAT(PrixUnitaire, 3) * -1), 3) WHEN bo.IO = 'IO' THEN 'IO' END) OVER (ORDER BY date), 0), ' ') WHEN idAmount = 3 THEN CONCAT('$ ', FORMAT(SUM(CASE WHEN bo.IO = 'I' THEN FORMAT(FORMAT(ub.value, 3) / FORMAT(PrixUnitaire, 3), 3) WHEN bo.IO = 'O' THEN FORMAT(FORMAT(ub.value, 3) / (FORMAT(PrixUnitaire, 3) * -1), 3) WHEN bo.IO = 'IO' THEN 'IO' END) OVER (ORDER BY date), 2)) ELSE CONCAT('$ ', FORMAT(ub.balance, 3, 'en_EN')) END AS balance "),
                'ub.PrixUnitaire',
                'bo.IO as sensP'
            )
            ->join('balance_operations as bo', 'ub.idBalancesOperation', '=', 'bo.id')
            ->where('bo.amounts_id', 4)
            ->where('ub.idUser', $user->idUser)
            ->orderBy('Date')->get();
        return datatables($userData)->make(true);
    }

    public function getChanceUser()
    {
        $user = $this->settingsManager->getAuthUser();
        if (!$user) $user->idUser = '';
        $userData = DB::table('user_balances as ub')
            ->select(
                DB::raw('RANK() OVER (ORDER BY ub.Date DESC) as ranks'),
                'ub.idUser', 'ub.id', 'ub.idSource', 'ub.Ref', 'ub.Date', 'bo.Operation', 'ub.Description',
                DB::raw(" CASE WHEN ub.idSource = '11111111' THEN 'system' ELSE (SELECT CONCAT(IFNULL(enfirstname, ''), ' ', IFNULL(enlastname, '')) FROM metta_users mu WHERE mu.idUser = ub.idSource) END AS source "),
                DB::raw(" CASE WHEN bo.IO = 'I' THEN CONCAT('+ ', '$ ', FORMAT(ub.value / PrixUnitaire, 3)) WHEN bo.IO = 'O' THEN CONCAT('- ', FORMAT(ub.value / PrixUnitaire, 3), ' $') WHEN bo.IO = 'IO' THEN 'IO' END AS value "),
                DB::raw(" CASE WHEN idAmount = 5 THEN CONCAT(FORMAT(SUM(CASE WHEN bo.IO = 'I' THEN FORMAT(FORMAT(ub.value, 3) / FORMAT(PrixUnitaire, 3), 3) WHEN bo.IO = 'O' THEN FORMAT(FORMAT(ub.value, 3) / (FORMAT(PrixUnitaire, 3) * -1), 3) WHEN bo.IO = 'IO' THEN 'IO' END) OVER (ORDER BY date), 0), ' ') WHEN idAmount = 3 THEN CONCAT('$ ', FORMAT(SUM(CASE WHEN bo.IO = 'I' THEN FORMAT(FORMAT(ub.value, 3) / FORMAT(PrixUnitaire, 3), 3) WHEN bo.IO = 'O' THEN FORMAT(FORMAT(ub.value, 3) / (FORMAT(PrixUnitaire, 3) * -1), 3) WHEN bo.IO = 'IO' THEN 'IO' END) OVER (ORDER BY date), 2)) ELSE CONCAT('$ ', FORMAT(ub.balance, 3, 'en_EN')) END AS balance "),
                'ub.PrixUnitaire',
                'bo.IO as sensP'
            )
            ->join('balance_operations as bo', 'ub.idBalancesOperation', '=', 'bo.id')
            ->where('bo.amounts_id', 7)
            ->where('ub.idUser', $user->idUser)
            ->orderBy('Date')->get();
        return datatables($userData)->make(true);
    }
    public function getPurchaseBFSUser()
    {
        $user = $this->settingsManager->getAuthUser();
        if (!$user) $user->idUser = '';
        $userData = DB::table('user_balances as ub')
            ->select(
                DB::raw('RANK() OVER (ORDER BY ub.Date DESC) as ranks'),
                'ub.idUser', 'ub.id', 'ub.idSource', 'ub.Ref', 'ub.Date', 'bo.Operation', 'ub.Description',
                DB::raw(" CASE WHEN ub.idSource = '11111111' THEN 'system' ELSE (SELECT CONCAT(IFNULL(enfirstname, ''), ' ', IFNULL(enlastname, '')) FROM metta_users mu WHERE mu.idUser = ub.idSource) END AS source "),
                DB::raw(" CASE WHEN bo.IO = 'I' THEN CONCAT('+ ', '$ ', FORMAT(ub.value / PrixUnitaire, 3)) WHEN bo.IO = 'O' THEN CONCAT('- ', FORMAT(ub.value / PrixUnitaire, 3), ' $') WHEN bo.IO = 'IO' THEN 'IO' END AS value "),
                DB::raw(" CASE WHEN idAmount = 5 THEN CONCAT(FORMAT(SUM(CASE WHEN bo.IO = 'I' THEN FORMAT(FORMAT(ub.value, 3) / FORMAT(PrixUnitaire, 3), 3) WHEN bo.IO = 'O' THEN FORMAT(FORMAT(ub.value, 3) / (FORMAT(PrixUnitaire, 3) * -1), 3) WHEN bo.IO = 'IO' THEN 'IO' END) OVER (ORDER BY date), 0), ' ') WHEN idAmount = 3 THEN CONCAT('$ ', FORMAT(SUM(CASE WHEN bo.IO = 'I' THEN FORMAT(FORMAT(ub.value, 3) / FORMAT(PrixUnitaire, 3), 3) WHEN bo.IO = 'O' THEN FORMAT(FORMAT(ub.value, 3) / (FORMAT(PrixUnitaire, 3) * -1), 3) WHEN bo.IO = 'IO' THEN 'IO' END) OVER (ORDER BY date), 2)) ELSE CONCAT('$ ', FORMAT(ub.balance, 3, 'en_EN')) END AS balance "),
                'ub.PrixUnitaire',
                'bo.IO as sensP'
            )
            ->join('balance_operations as bo', 'ub.idBalancesOperation', '=', 'bo.id')
            ->where('bo.amounts_id', 2)
            ->where('ub.idUser', $user->idUser)
            ->orderBy('Date')->get();
        return datatables($userData)->make(true);
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

    public function getPlatforms()
    {
        return datatables(Platform::all())
            ->addColumn('type', function ($platform) {
                return Lang::get(PlatformType::from($platform->type)->name);
            })
            ->addColumn('action', function ($platform) {
                return view('parts.datatable.platform-action', ['platform' => $platform]);
            })
            ->addColumn('created_at', function ($platform) {
                return $platform->created_at?->format(self::DATE_FORMAT);
            })->addColumn('updated_at', function ($platform) {
                return $platform->updated_at?->format(self::DATE_FORMAT);
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
            })->addColumn('updated_at', function ($platform) {
                return $platform->updated_at?->format(self::DATE_FORMAT);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getDeals()
    {
        if (User::isSuperAdmin()) {
            $deals = Deal::whereNot('status', DealStatus::Archived->value)->orderBy('validated', 'ASC')->get();
        } else {
            $platforms = Platform::where(function ($query) {
                $query
                    ->where('administrative_manager_id', '=', auth()->user()->id)
                    ->orWhere('financial_manager_id', '=', auth()->user()->id);
            })->get();
            $platformsIds = [];
            foreach ($platforms as $platform) {
                $platformsIds[] = $platform->id;
            }
            $deals = Deal::whereIn('platform_id', $platformsIds)->orderBy('validated', 'ASC')->get();
        }
        return datatables($deals)
            ->addColumn('action', function ($deal) {
                return view('parts.datatable.deals-action', ['deal' => $deal, 'currentRouteName' => Route::currentRouteName()]);
            })
            ->addColumn('status', function ($deal) {
                return view('parts.datatable.deals-status', ['status' => $deal->status]);
            })
            ->addColumn('validated', function ($deal) {
                return view('parts.datatable.deals-validated', ['validated' => $deal->validated]);
            })
            ->addColumn('platform_id', function ($deal) {
                if ($deal->platform()->first()) {
                    return $deal->platform()->first()->id . ' - ' . $deal->platform()->first()->name;
                }
                return '**';
            })
            ->addColumn('created_by', function ($deal) {
                return view('parts.datatable.deals-createdBy', ['createdby' => User::find($deal->created_by_id)]);
            })
            ->addColumn('created_at', function ($platform) {
                return $platform->created_at?->format(self::DATE_FORMAT);
            })->addColumn('updated_at', function ($platform) {
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


    public function getPurchaseUser()
    {
        $user = $this->settingsManager->getAuthUser();
        $userData = DB::select(getSqlFromPath('get_purchase_user'), [$user->idUser]);
        return datatables($userData)->make(true);
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
