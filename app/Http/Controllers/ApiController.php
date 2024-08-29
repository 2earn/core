<?php

namespace App\Http\Controllers;

use App\DAL\UserRepository;
use App\Models\User;
use App\Models\vip;
use App\Services\Sponsorship\SponsorshipFacade;
use carbon;
use Core\Enum\AmoutEnum;
use Core\Enum\StatusRequest;
use Core\Enum\TypeEventNotificationEnum;
use Core\Enum\TypeNotificationEnum;
use Core\Models\countrie;
use Core\Models\detail_financial_request;
use Core\Models\FinancialRequest;
use Core\Models\Setting;
use Core\Models\user_balance;
use Core\Services\BalancesManager;
use Core\Services\settingsManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request as Req;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator as Val;
use Illuminate\Support\Facades\Vite;
use Illuminate\Validation\Rule;
use Paytabscom\Laravel_paytabs\Facades\paypage;
use phpDocumentor\Reflection\Types\Collection;
use Propaganistas\LaravelPhone\PhoneNumber;
use Yajra\DataTables\Facades\DataTables;


class ApiController extends BaseController
{
    private string $reqRequest = "select recharge_requests.Date , user.name user  ,
recharge_requests.userPhone userphone, recharge_requests.amount  from recharge_requests
left join users user on user.idUser = recharge_requests.idUser";

    public function __construct(private settingsManager $settingsManager, private BalancesManager $balancesManager, private UserRepository $userRepository)
    {
    }

    public function SendSMS(Req $request, settingsManager $settingsManager)
    {

        $settingsManager->NotifyUser($request->user, TypeEventNotificationEnum::none, [
            'msg' => $request->msg,
            'type' => TypeNotificationEnum::SMS
        ]);

    }

    public function buyAction(Req $request, BalancesManager $balancesManager)
    {
        $actualActionValue = actualActionValue(getSelledActions());
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
        $number_of_action = intval($request->ammount / $actualActionValue);
        $gift = getGiftedActions($number_of_action);
        $actual_price = actualActionValue(getSelledActions());
        $PU = $number_of_action * ($actual_price) / ($number_of_action + $gift);
        $Count = DB::table('user_balances')->count();
        $ref = "44" . date('ymd') . substr((10000 + $Count + 1), 1, 4);
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
        $userSponsored = SponsorshipFacade::checkProactifSponsorship($this->userRepository->getUserByIdUser($reciver));
        if ($userSponsored) {
            SponsorshipFacade::executeProactifSponsorship($userSponsored->idUser, $ref, $number_of_action, $gift, $PU, $fullphone_number);
        }


        $vip = vip::Where('idUser', '=', $reciver)
            ->where('closed', '=', false)->first();
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
        $gift = $gift + $flashGift;

        $PU = $number_of_action * ($actual_price) / ($number_of_action + $gift);

        $this->userRepository->increasePurchasesNumber($reciver);

        // share sold
        $user_balance = new user_balance();
        // Action
        $user_balance->ref = $ref;
        $user_balance->idBalancesOperation = 44;
        $user_balance->Date = now();
        $user_balance->idSource = '11111111';
        $user_balance->idUser = $reciver;
        $user_balance->idamount = AmoutEnum::Action;
        $user_balance->value = $number_of_action;
        $user_balance->gifted_shares = $gift;
        $user_balance->PU = $PU;
        $user_balance->WinPurchaseAmount = "1";
        $user_balance->Balance = ($number_of_action + $gift) * number_format($PU, 2, '.', '');
        $user_balance->save();
        // cach
        $user_balance = new user_balance();
        $user_balance->ref = $ref;
        $user_balance->idBalancesOperation = 48;
        $user_balance->Date = now();
        $user_balance->idSource = auth()->user()->idUser;
        $user_balance->idUser = auth()->user()->idUser;
        $user_balance->idamount = AmoutEnum::CASH_BALANCE;
        $user_balance->value = ($number_of_action + $gift) * $PU;
        $user_balance->WinPurchaseAmount = "0.000";
        $user_balance->Description = "purchase of " . ($number_of_action + $gift) . " shares for " . $a;
        $user_balance->Balance = $balancesManager->getBalances(auth()->user()->idUser, -1)->soldeCB - ($number_of_action + $gift) * $PU;
        $user_balance->save();

        //bfs
        $user_balance = new user_balance();
        $user_balance->ref = $ref;
        $user_balance->idBalancesOperation = 46;
        $user_balance->Date = now();
        $user_balance->idSource = "11111111";
        $user_balance->idUser = $reciver_bfs;
        $user_balance->idamount = AmoutEnum::BFS;
        $user_balance->value = intval($number_of_action / $palier) * $actual_price * $palier;
        $user_balance->WinPurchaseAmount = "0.000";
        $user_balance->Balance = $balancesManager->getBalances(auth()->user()->idUser, -1)->soldeBFS + intval($number_of_action / $palier) * $actual_price * $palier;
        $user_balance->save();
        return response()->json(['type' => ['success'], 'message' => [trans('Actions purchase transaction completed successfully')]],);
    }

    public function giftActionByAmmount(Req $request)
    {
        return getGiftedActions($request->ammount);
    }

    public function actionByAmmount(Req $request)
    {
        $action = intval($request->ammount / actualActionValue(getSelledActions()));
        $gifted_action = getGiftedActions($action);
        $profit = actualActionValue(getSelledActions()) * $gifted_action;
        $array = array('action' => $action, "gift" => $gifted_action, 'profit' => $profit);
        return response()->json($array);
    }

    public function vip(Req $request)
    {
        vip::where('idUser', $request->reciver)
            ->where('closed', '=', 0)
            ->update([
                'closed' => 1,
                'closedDate' => now(),
            ]);

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

        $old_value = DB::table('usercurrentbalances')
            ->where('idUser', Auth()->user()->idUser)
            ->where('idamounts', AmoutEnum::CASH_BALANCE)
            ->value('value');


        if ($old_value >= $request->amount) {

            $Count = DB::table('user_balances')->count();

            $user_balance = new user_balance();
            $user_balance->ref = "42" . date('ymd') . substr((10000 + $Count + 1), 1, 4);
            $user_balance->idBalancesOperation = 42;
            $user_balance->Date = now();
            $user_balance->idSource = auth()->user()->idUser;
            $user_balance->idUser = auth()->user()->idUser;
            $user_balance->idamount = AmoutEnum::CASH_BALANCE;
            $user_balance->value = $request->input('amount');
            $user_balance->WinPurchaseAmount = "0.000";
            $user_balance->Description = "Transfered to " . getPhoneByUser($request->input('reciver'));
            $user_balance->Balance = $balancesManager->getBalances(auth()->user()->idUser, -1)->soldeCB - $request->input('amount');

            $user_balance->save();


            $user_balance = new user_balance();
            $user_balance->ref = "43" . date('ymd') . substr((10000 + $Count + 1), 1, 4);
            $user_balance->idBalancesOperation = 43;
            $user_balance->Date = now();
            $user_balance->idSource = Auth()->user()->idUser;
            $user_balance->idUser = $request->input('reciver');
            $user_balance->idamount = AmoutEnum::CASH_BALANCE;
            $user_balance->value = $request->input('amount');
            $user_balance->WinPurchaseAmount = "0.000";
            $user_balance->Description = "Transfered from " . getPhoneByUser(Auth()->user()->idUser);
            $user_balance->Balance = $balancesManager->getBalances($request->input('reciver'), -1)->soldeCB + $request->input('amount');

            $user_balance->save();


            $new_value = intval($old_value) - intval($request->amount);
            DB::table('usercurrentbalances')
                ->where('idUser', Auth()->user()->idUser)
                ->where('idamounts', AmoutEnum::CASH_BALANCE)
                ->update(['value' => $new_value, 'dernier_value' => $old_value]);

            $old_value = DB::table('usercurrentbalances')
                ->where('idUser', $request->reciver)
                ->where('idamounts', AmoutEnum::CASH_BALANCE)
                ->value('value');
            $new_value = intval($old_value) + intval($request->amount);
            DB::table('usercurrentbalances')
                ->where('idUser', $request->all()['reciver'])
                ->where('idamounts', AmoutEnum::CASH_BALANCE)
                ->update(['value' => $new_value, 'dernier_value' => $old_value]);
            return "success";
        } else {
            return "error";
        }

    }

    public function getCountriStat()
    {
        $data = DB::select("select name,apha2,continant,
           CASH_BALANCE,
    BFS,
    DISCOUNT_BALANCE,
    SMS_BALANCE,
    SOLD_SHARES,
    GIFTED_SHARES,
    TOTAL_SHARES,
    SHARES_REVENUE,
    TRANSFERT_MADE,
    COUNT_USERS,
    COUNT_TRAIDERS,
    COUNT_REAL_TRAIDERS from tableau_croise");

        return response()->json($data);
    }

    public function getSankey()
    {
        $data = DB::select("select s.`from`,s.`to`,cast(s.weight as decimal (10,2)) as weight from sankey s");

        return response()->json($data);
    }

    public function getSharesSolde()
    {
        $query = DB::table('user_balances')->select('value', 'gifted_shares', 'PU', 'Date')->where('idBalancesOperation', 44)
            ->where('idUser', Auth()->user()->idUser);
        return datatables($query)
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
                return number_format(($user_balance->value + $user_balance->gifted_shares) * actualActionValue(getSelledActions()), 2);
            })
            ->addColumn('current_earnings', function ($user_balance) {
                return number_format(($user_balance->value + $user_balance->gifted_shares) * actualActionValue(getSelledActions()) - $user_balance->PU * ($user_balance->value + $user_balance->gifted_shares), 2);
            })
            ->addColumn('value_format', function ($user_balance) {
                return number_format($user_balance->value, 0);
            })
            ->rawColumns(['total_price', 'share_price'])
            ->make(true);
    }

    public function getSharesSoldeList($idUser)
    {
        $actualActionValue = actualActionValue(getSelledActions());

        // Effectuer la requête SQL avec le résultat obtenu
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

    public function getUpdatedCardContent()
    {
        $updatedContent = number_format(getRevenuSharesReal(), 2); // Adjust this based on your logic
        return response()->json(['value' => $updatedContent]);
    }

    public function getSharesSoldes()
    {
        $query = DB::table('user_balances')
            ->select('Balance', 'WinPurchaseAmount', 'countries.apha2', 'user_balances.id', DB::raw('CONCAT(nvl( meta.arFirstName,meta.enFirstName), \' \' ,nvl( meta.arLastName,meta.enLastName)) AS Name'), 'user.mobile', DB::raw('CAST(value AS DECIMAL(10,0)) AS value'), 'gifted_shares', DB::raw('CAST(PU AS DECIMAL(10,2)) AS PU'), 'Date', 'user_balances.idUser')
            ->join('users as user', 'user.idUser', '=', 'user_balances.idUser')
            ->join('metta_users as meta', 'meta.idUser', '=', 'user.idUser')
            ->join('countries', 'countries.id', '=', 'user.idCountry')
            ->where('idBalancesOperation', 44);


        return datatables($query)
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
                return number_format(actualActionValue(getSelledActions()) * ($user_balance->value + $user_balance->gifted_shares), 2);
            })
            ->addColumn('gain', function ($user_balance) {
                return number_format(actualActionValue(getSelledActions()) * ($user_balance->value + $user_balance->gifted_shares) - $user_balance->PU * ($user_balance->value + $user_balance->gifted_shares), 2);
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
                ->where('idamounts', AmoutEnum::CASH_BALANCE)
                ->value('value');
            $value = DB::table('user_balances as u')
                ->select(DB::raw('SUM(CASE WHEN b.IO = "I" THEN u.value ELSE -u.value END) as value'))
                ->join('balanceoperations as b', 'u.idBalancesOperation', '=', 'b.idBalanceOperations')
                ->join('users as s', 'u.idUser', '=', 's.idUser')
                ->where('u.idamount', 1)
                ->where('u.idUser', $user)
                ->first();

            $Count = DB::table('user_balances')->count();
            $value = $value->value * 1;

            $user_balance = new user_balance();
            $user_balance->ref = "51" . date('ymd') . substr((10000 + $Count + 1), 1, 4);
            $user_balance->idBalancesOperation = 51;
            $user_balance->Date = now();
            $user_balance->idSource = $user;
            $user_balance->idUser = $user;
            $user_balance->idamount = AmoutEnum::CASH_BALANCE;
            $user_balance->value = $data->tran_total / $k;
            $user_balance->WinPurchaseAmount = "0.000";
            $user_balance->Description = $data->tran_ref;
            $user_balance->Balance = $value + $data->tran_total / $k;
            $user_balance->save();
            $mnt = $data->tran_total / $k;
            $new_value = intval($old_value) + $data->tran_total / $k;
            DB::table('usercurrentbalances')
                ->where('idUser', $user)
                ->where('idamounts', AmoutEnum::CASH_BALANCE)
                ->update(['value' => $new_value, 'dernier_value' => $old_value]);
        }

        DB::table('user_transactions')->updateOrInsert(
            ['idUser' => $user],
            [
                'autorised' => $data->success,
                'cause' => $data->payment_result->response_message,
                'mnt' => $mnt
            ]
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
                DB::table('user_contacts')
                    ->where('id', $id)
                    ->update(['availablity' => $st, 'reserved_at' => $dt]);

                return response()->json(['success' => true]);
            } else {
                $st = 0;
                DB::table('user_contacts')
                    ->where('id', $id)
                    ->update(['availablity' => $st]);

                return response()->json(['success' => true]);

            }

            // Assuming 'id' is the primary key for the 'user_balances' table
            DB::table('user_contacts')
                ->where('id', $id)
                ->update(['availablity' => $st, 'reserved_at' => $dt]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // Log the exception
            \Log::error('Error updating balance status: ' . $e->getMessage());

            // Return an error response
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function updateBalanceStatus(Req $request, BalancesManager $balancesManager)
    {
        try {
            $id = $request->input('id');
            $st = 0;

            // Assuming 'id' is the primary key for the 'user_balances' table
            DB::table('user_balances')
                ->where('id', $id)
                ->update(['WinPurchaseAmount' => $st]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // Log the exception
            \Log::error('Error updating balance status: ' . $e->getMessage());

            // Return an error response
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
            DB::table('user_balances')
                ->where('id', $id)
                ->update(['Balance' => floatval($st), 'WinPurchaseAmount' => $p]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // Log the exception
            \Log::error('Error updating balance status: ' . $e->getMessage());

            // Return an error response
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function getTransfert()
    {
        $query = DB::table('user_balances')->select('value', 'Description', 'Date')->where('idBalancesOperation', 42)
            ->where('idUser', Auth()->user()->idUser)
            ->whereNotNull('Description');
        return datatables($query)
            ->addColumn('formatted_created_at', function ($user_balance) {
                return Carbon\Carbon::parse($user_balance->Date)->format('Y-m-d H:i:s');
            })
            ->make(true);


    }

    public function getUserCashBalance()
    {
        $query = DB::table('user_balances')
            ->select(DB::raw('DATE_FORMAT(Date, "%Y-%m-%d") AS x'), DB::raw('CAST(Balance AS DECIMAL(10,2)) AS y'))
            ->where('idamount', 1)
            ->where('idUser', auth()->user()->idUser)
            ->orderBy('Date', 'asc')
            ->get();
        foreach ($query as $record) {
            $record->Balance = (float)$record->y;
        }
        return response()->json($query);
    }


    public function getSharePriceEvolution()
    {
        $query = DB::table('user_balances')
            ->select(
                DB::raw('CAST(SUM(value) OVER (ORDER BY id) AS DECIMAL(10,0))AS x'),
                DB::raw('CAST((value + gifted_shares) * PU / value AS DECIMAL(10,2)) AS y')
            )
            ->where('idBalancesOperation', 44)
            ->where('value', '>', 0)
            ->orderBy('Date')
            ->get();
        foreach ($query as $record) {
            $record->y = (float)$record->y;
            $record->x = (float)$record->x;
        }
        return response()->json($query);
    }

    public function getSharePriceEvolutionDate()
    {
        $query = DB::table('user_balances')
            ->select(DB::raw('DATE(date) as x'), DB::raw('SUM(value) as y'))
            ->where('idBalancesOperation', 44)
            ->where('value', '>', 0)
            ->groupBy('x')
            ->get();

        foreach ($query as $record) {

            $record->y = (float)$record->y;
        }
        return response()->json($query);
    }

    public function getSharePriceEvolutionWeek()
    {
        $query = DB::table('user_balances')
            ->select(DB::raw(' concat(year(date),\'-\',WEEK(date, 1)) as x'), DB::raw('SUM(value) as y'), DB::raw(' WEEK(date, 1) as z'))
            ->where('idBalancesOperation', 44)
            ->where('value', '>', 0)
            ->groupBy('x', 'z')
            ->orderBy('z')
            ->get();

        foreach ($query as $record) {

            $record->y = (float)$record->y;
        }
        return response()->json($query);
    }

    public function getSharePriceEvolutionMonth()
    {
        $query = DB::table('user_balances')
            ->select(DB::raw('DATE_FORMAT(date, \'%Y-%m\') as x'), DB::raw('SUM(value) as y'))
            ->where('idBalancesOperation', 44)
            ->where('value', '>', 0)
            ->groupBy('x')
            ->get();

        foreach ($query as $record) {

            $record->y = (float)$record->y;
        }
        return response()->json($query);
    }

    public function getSharePriceEvolutionDay()
    {
        $query = DB::table('user_balances')
            ->select(DB::raw('DAYNAME(date) as x'), DB::raw('SUM(value) as y'), DB::raw('DAYOFWEEK(date) as z'))
            ->where('idBalancesOperation', 44)
            ->where('value', '>', 0)
            ->groupBy('x', 'z')
            ->orderBy('z')
            ->get();

        foreach ($query as $record) {

            $record->y = (float)$record->y;
        }
        return response()->json($query);
    }

    public function getSharePriceEvolutionUser()
    {


        $idUser = auth()->user()->idUser;


        $query = DB::select("select CAST(a.x AS DECIMAL(10,0))as x,case when a.me=1 then a.y else null end as y from (
SELECT
    id,
    CAST(SUM(value) OVER (ORDER BY id) AS DECIMAL(10,0)) AS x,
    CAST((value + gifted_shares) * PU / value AS DECIMAL(10,2)) AS y,
    case when id in(select id from user_balances where idBalancesOperation = 44
                                                   AND idUser = ? ) then 1 else 0 end as me
FROM
    user_balances
WHERE
    idBalancesOperation = 44
    and value>0

ORDER BY
    Date)as a
union all
select CAST(b.x- b.value AS DECIMAL(10,0))as x,case when b.me=1 then b.y else null end as y from (
                    SELECT
                        id,value,
                        SUM(value) OVER (ORDER BY id)  AS x,
                        CAST((value + gifted_shares) * PU / value AS DECIMAL(10,2)) AS y,
                        case when id in(select id from user_balances where idBalancesOperation = 44
                                                                       AND idUser = ? ) then 1 else 0 end as me
                    FROM
                        user_balances
                    WHERE
                        idBalancesOperation = 44

                    ORDER BY
                        Date)as b ORDER BY x", [$idUser, $idUser]);
        foreach ($query as $record) {
            if ($record->y) $record->y = (float)$record->y;
            $record->x = (float)$record->x;
        }

        return response()->json($query);
    }

    public function getActionValues()
    {
        $limit = getSelledActions() * 1.05;
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
        return User::select('countries.apha2', 'users.idUser', 'idUplineRegister',
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
                $join->on('vip.idUser', '=', 'users.idUser')
                    ->where('vip.closed', '=', 0);
            })->orderBy('created_at', 'ASC');
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
            ->addColumn('VIP', function ($settings) {
                $vip = "";
                $hasVip = vip::Where('idUser', '=', $settings->idUser)
                    ->where('closed', '=', false)->get();
                if ($hasVip->isNotEmpty()) {
                    $dateStart = new \DateTime($hasVip->first()->dateFNS);
                    $dateEnd = $dateStart->modify($hasVip->first()->flashDeadline . ' hour');;
                    if ($dateEnd > now()) {
                        $vip = '<a class="btn btn-success m-1" disabled="disabled">' . Lang::get('Acctually is vip') . '</a>';
                    } else {
                        $vip = '<a class="btn btn-info m-1" disabled="disabled">' . Lang::get('It was a vip') . '</a>';
                    }
                }
                return $vip . '<a data-bs-toggle="modal" data-bs-target="#vip"   data-phone="' . $settings->mobile . '" data-country="' . $this->getFormatedFlagResourceName($settings->apha2) . '"  data-reciver="' . $settings->idUser . '"
class="btn btn-xs btn-flash btn2earnTable vip m-1"  >
<i class="glyphicon glyphicon-add"></i>' . Lang::get('VIP') . '</a> ';

            })
            ->addColumn('flag', function ($settings) {
                return '<img src="' . $this->getFormatedFlagResourceName($settings->apha2) . '" alt="' . strtolower($settings->apha2) . '" title="' . strtolower($settings->apha2) . '" class="avatar-xxs me-2">';
            })
            ->addColumn('SoldeCB', function ($user_balance) {
                return '<a data-bs-toggle="modal" data-bs-target="#detail"   data-amount="1" data-reciver="' . $user_balance->idUser . '"
class="btn btn-ghost-secondary waves-effect waves-light cb"  >
<i class="glyphicon glyphicon-add"></i>$' . number_format(getUserBalanceSoldes($user_balance->idUser, 1), 2) . '</a> ';
            })
            ->addColumn('SoldeBFS', function ($user_balance) {
                return '<a data-bs-toggle="modal" data-bs-target="#detail"   data-amount="2" data-reciver="' . $user_balance->idUser . '"
class="btn btn-ghost-danger waves-effect waves-light bfs"  >
<i class="glyphicon glyphicon-add"></i>$' . number_format(getUserBalanceSoldes($user_balance->idUser, 2), 2) . '</a> ';
            })
            ->addColumn('SoldeDB', function ($user_balance) {
                return '<a data-bs-toggle="modal" data-bs-target="#detail"   data-amount="3" data-reciver="' . $user_balance->idUser . '"
class="btn btn-ghost-info waves-effect waves-light db"  >
<i class="glyphicon glyphicon-add"></i>$' . number_format(getUserBalanceSoldes($user_balance->idUser, 3), 2) . '</a> ';
            })
            ->addColumn('SoldeSMS', function ($user_balance) {
                return '<a data-bs-toggle="modal" data-bs-target="#detail"   data-amount="5" data-reciver="' . $user_balance->idUser . '"
class="btn btn-ghost-warning waves-effect waves-light smsb"  >
<i class="glyphicon glyphicon-add"></i>' . number_format(getUserBalanceSoldes($user_balance->idUser, 5), 0) . '</a> ';
            })
            ->addColumn('SoldeSH', function ($user_balance) {
                return '<a data-bs-toggle="modal" data-bs-target="#detailsh"   data-amount="6" data-reciver="' . $user_balance->idUser . '"
class="btn btn-ghost-success waves-effect waves-light sh"  >
<i class="glyphicon glyphicon-add"></i>' . number_format(getUserSelledActions($user_balance->idUser), 0) . '</a> ';
            })
            ->addColumn('action', function ($settings) {
                return '<a data-bs-toggle="modal" data-bs-target="#AddCash"   data-phone="' . $settings->mobile . '" data-country="' . $this->getFormatedFlagResourceName($settings->apha2) . '" data-reciver="' . $settings->idUser . '"
class="btn btn-xs btn-primary btn2earnTable addCash m-1" >' . Lang::get('Add cash') . '</a> ';
            })
            ->rawColumns(['action', 'flag', 'SoldeCB', 'SoldeBFS', 'SoldeDB', 'SoldeSMS', 'SoldeSH', 'VIP'])
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
            ->addColumn('action', function ($settings) {
                return '<a data-bs-toggle="modal" data-bs-target="#editCountriesModal"  onclick="getEditCountrie(' . $settings->id . ')"
class="btn btn-xs btn-primary btn2earnTable"  >
<i class="glyphicon glyphicon-edit""></i>' . Lang::get('Edit') . '</a> ';
            })
            ->make(true);
    }

    public function getSettings()
    {
        $settings = DB::table('settings')
            ->select('idSETTINGS', 'ParameterName', 'IntegerValue', 'StringValue', 'DecimalValue', 'Unit', 'Automatically_calculated');
        return datatables($settings)
            ->addColumn('action', function ($settings) {
                return '<div class="d-flex gap-2">
                             <div class="edit">
                                    <button  data-id="' . $settings->idSETTINGS . '"   data-bs-toggle="modal" data-bs-target="#settingModal"
 class="btn btn-primary edit-item-btn edit-setting-btn"  ><i class="glyphicon glyphicon-edit""></i>' . Lang::get('Edit') . '</button> </div> </div>';
            })
            ->setRowId('idSETTINGS')
            ->editColumn('Automatically_calculated', function ($settings) {
                if ($settings->Automatically_calculated == 1)
                    return '<span class="badge badge-success">' . trans('Yes') . '</span>';
                else
                    return '<span class="badge badge-info">' . trans('No') . '</span>';
            })
            ->editColumn('StringValue', function ($settings) {
                return '***';
            })
            ->setRowClass(function ($settings) {
                return 'testaddclass';
            })
            ->escapeColumns([])
            ->toJson();
    }

    public function getBalanceOperations()
    {
        $balanceOperations = DB::table('balanceoperations')
            ->join('amounts', 'balanceoperations.idamounts', '=', 'amounts.idamounts')
            ->select('balanceoperations.idBalanceOperations', 'balanceoperations.Designation', 'balanceoperations.IO', 'balanceoperations.idSource', 'balanceoperations.Mode',
                'balanceoperations.idamounts', 'balanceoperations.Note', 'balanceoperations.MODIFY_AMOUNT', 'amounts.amountsshortname');
        return datatables($balanceOperations)
            ->addColumn('action', function ($settings) {
                return '<a  data-id="' . $settings->idBalanceOperations . '"   data-bs-toggle="modal" data-bs-target="#BoModal"
class="btn btn-xs btn-primary btn2earnTable edit-bo-btn"  ><i class="glyphicon glyphicon-edit""></i>' . Lang::get('Edit') . '</a> ';
            })
            ->editColumn('MODIFY_AMOUNT', function ($balanceOperations) {
                if ($balanceOperations->MODIFY_AMOUNT == 1)
                    return '<span class="badge badge-success">Yes</span>';
                else
                    return '<span class="badge badge-info">No</span>';
            })
            ->editColumn('amountsshortname', function ($balanceOperations) {

                if ($balanceOperations->amountsshortname == "BFS") {
                    return ' <img width="20" height="20" id="imm" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAABmJLR0QA/wD/AP+gvaeTAAAEX0lEQVR4nO2az48URRTHP5jZsGA2svw4ADsJFwPyB3jxBwkcMNwMHoCLurp69AQhcOGf8ICEP0CCLAfABNhVXIRsiFd2RUABEw+gsIvKCcfDe0X39lR1Vc9U9ww7/U0qma73qt73va569aMHagw2VnjkrS7b932fr3RgrEaNZYQ6B3RgrEaNAULLUzrBjzn9zVTNc+CTYC/geivdjKqOMfCrgC8AZczXMtAxz17mgGxbV32RPl1w9tkINOAi228ozLPOARXYmKLY3iGte7lEXkA1AVhtqbMlpquBbStFjDV7BDiv+s+AfTm67wP/qO4lYE2FPEvtuAF8qW3+A45ZdL4AnqvOSWCoBzzb5quv46Lz9RCJk18hTg7p75bKDvWS5zVLgx8sejMWvWsBxAH2Av9qm4taWlr3QWAf18viOQKco9z5CvAm8EeK1EPg7QLtS80r2SF50KJzkGQon6DYfDXYAsxq2dJB+yG1XRpPW1IKSWZVo1SeMeZrFSiVZ7fztSqUyrPb+VoVXhaeNfoSebcqy628OIylLxAK7ZmXAdpuibL76QOE7/H7FdOID+mdovOAlBZsAH7T509LJFg2PkN8+BVYr3XeAOwncX6G8HvDEKwFxoGzwDzwt5Z5YBL4GBiNaK+BXLSYIOwnIADpRLExEpFVwFFgwWInW54AR7RNDGzCfipsgxFMIcM+1psfA34i2ZdfQkbBdmAYOZ1tBSZYes6/AWyOxKGB+JTuvw1OQRcYA37Xfm8C7wa0eQe4o20eEC8IBpUFYBXJm/8e+zncZXMUueAwI2E4Iq/KAnBU+7sFvFaUDJIwb6n8cERelQRgLUnCe8si34MMb19i2qn1jyl245SHQgFoAqeBRS2TSMLyYVz7uuiQ25x3Bd8krg8D7IbwDQ5AE/jTQvIvleXhrOp+4pAXGW1mM/ONRy+Ub3AATuvzeSQTN4ELWnfKQ+Zn1XvdIS8SgG2qO+fRC+UbHIBFfU5Hr6l1Cx4ypu2IQ56dAg+A9xy6I6qzGGjTx3eJnyHfBm3RCn17zx31E4jTBmPAcYdu0f8LdMw3OwIm9fkCEskm8K3Wfe3py0yBbSGGLbbTeENl854+QvkGT4GtSALJJpVHyBsLITPu0fOSAj4nLAmG8i28DJ5C5tACEkmf8yCnurxl0LYE3nPomjN96DLo41vZRuiJ9rcjx1ba+d0WvV30eCPUDY6QzF3XGT/P5jrgtspDvhiHorIADCMHmRZwBXsQXDbXkZzhZ3lJD0MgGxKz5v+CfTpksYvkzd9HLjRiwhuAaWT7GetCZDPJSGghl6wTyBL5KrLR2Y5k++mU3izxnG9Y+m9DNjFdjUhgGDnSmsSYVx4jc35lJNubsH/zaIMR7EMuEFvEvxQdBT5C9glzwFMtc8AZZKmLle1BuBvn7yK+BeWA9SRBmIhIqGqYTdRdJLFCgSRorpCnSiRYNr4jGdUGS/wc+E9j6dOg7Z+ayxX99Ff/Gj3F/1ZQl/ObCiogAAAAAElFTkSuQmCC">';

                } else {
                    return '<span><i class="fa fa-cogs" aria-hidden="true"></i></span>';
                }
            })
            ->escapeColumns([])
            ->toJson();
    }

    public function getAmounts()
    {
        $amounts = DB::table('amounts')
            ->select('idamounts', 'amountsname', 'amountswithholding_tax', 'amountspaymentrequest', 'amountstransfer', 'amountscash', 'amountsactive', 'amountsshortname');

        return datatables($amounts)
            ->addColumn('action', function ($settings) {
                return '<a data-id="' . $settings->idamounts . '"   data-bs-toggle="modal" data-bs-target="#AmountsModal"
class="btn btn-xs btn-primary edit-amounts-btn btn2earnTable"  >
<i class="glyphicon glyphicon-edit""></i>' . Lang::get('Edit') . '</a>';
            })
            ->editColumn('amountswithholding_tax', function ($amounts) {
                if ($amounts->amountswithholding_tax == 1)
                    return '<span class="badge badge-success">Yes</span>';
                else
                    return '<span class="badge badge-info">No</span>';
            })
            ->editColumn('amountstransfer', function ($amounts) {
                if ($amounts->amountstransfer == 1)
                    return '<span class="badge badge-success">Yes</span>';
                else
                    return '<span class="badge badge-info">No</span>';
            })
            ->editColumn('amountspaymentrequest', function ($amounts) {
                if ($amounts->amountspaymentrequest == 1)
                    return '<span class="badge badge-success">Yes</span>';
                else
                    return '<span class="badge badge-info">No</span>';
            })
            ->editColumn('amountscash', function ($amounts) {
                if ($amounts->amountscash == 1)
                    return '<span class="badge badge-success">Yes</span>';
                else
                    return '<span class="badge badge-info">No</span>';
            })
            ->editColumn('amountsactive', function ($amounts) {
                if ($amounts->amountsactive == 1)
                    return '<span class="badge badge-success">Yes</span>';
                else
                    return '<span class="badge badge-info">No</span>';
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function getActionHistorys()
    {
        $actionHistorys = DB::table('action_history')
            ->select('id', 'title', 'reponce');
        return datatables($actionHistorys)
            ->addColumn('action', function ($settings) {
                return '<a data-id="' . $settings->id . '"   data-bs-toggle="modal" data-bs-target="#HistoryActionModal"  class="btn btn-xs btn-primary edit-ha-btn btn2earnTable"  ><i class="glyphicon glyphicon-edit""></i>' . Lang::get('Edit') . '</a>';
            })
            ->editColumn('reponce', function ($actionHistorys) {
                if ($actionHistorys->reponce == 1)
                    return '<span class="badge bg-success-subtle text-success ">' . trans('create reponce') . '</span>';
                else
                    return '<span class="badge bg-info-subtle text-info ">' . trans('sans reponce') . '</span>';
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function getUrlList($idUser, $idamount)
    {
        return route('api_user_balances_list', ['locale' => app()->getLocale(), 'idUser' => $idUser, 'idAmounts' => $idamount]);
    }

    public function getUserBalancesList($idUser, $idamount)
    {

        $userData = DB::select("select ref,u.idUser,u.idamount,Date,u.idBalancesOperation,b.Designation,u.Description,
 case when b.IO ='I' then value else -value end value ,u.balance from user_balances u,balanceoperations b,users s
where u.idBalancesOperation=b.idBalanceOperations
  and u.idUser=s.idUser
and u.idamount not in(4,6)  and u.idUser=? and u.idamount=? order by Date   ", [$idUser, $idamount]
        );
        return response()->json($userData);
    }

    public function getUserBalances($typeAmounts)
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

        $userData = DB::select("SELECT RANK() OVER (
        ORDER BY ub.Date desc
    ) as ranks  , ub.idUser, ub.id ,ub.idSource ,ub.Ref , ub.Date, bo.Designation,ub.Description,ub.Description,ub.idamount,
case when ub.idSource = '11111111' then 'system' else
(select concat( IFNULL(enfirstname,''),' ',  IFNULL( enlastname,''))  from metta_users mu  where mu.idUser = ub.idSource)
end as source,
case when bo.IO = 'I' then  concat('+ ', format(ub.value/PrixUnitaire,3),' $' )
when bo.IO ='O' then concat('- ', format(ub.value/PrixUnitaire,3),' $' )
when bo.IO = 'IO' then 'IO'
end as value , case when idAmount = 5  then  concat( format(  SUM(case when bo.IO = 'I' then   format(format(ub.value,3)/format(PrixUnitaire,3) ,3)
when bo.IO ='O' then  format(format(ub.value,3)/format(PrixUnitaire *-1,3) ,3)
when bo.IO = 'IO' then 'IO'
end)   OVER(ORDER BY date) ,0) ,' ') when idAmount = 3 then concat( format(  SUM(case when bo.IO = 'I' then   format(format(ub.value,3)/format(PrixUnitaire,3) ,3)
when bo.IO ='O' then  format(format(ub.value,3)/format(PrixUnitaire *-1,3) ,3)
when bo.IO = 'IO' then 'IO'
end)   OVER(ORDER BY date) ,3) , ' $') else concat( format( ub.balance ,3,'en_EN') ,' $') end  as balance,ub.PrixUnitaire,'d' as sensP
  FROM user_balances ub inner join balanceoperations bo on
ub.idBalancesOperation = bo.idBalanceOperations
where  (bo.idamounts = ? and ub.idUser =  ?)  order by ref desc", [$idAmounts, auth()->user()->idUser]
        );

        return Datatables::of($userData)
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

    public function getPurchaseBFSUser()
    {
        $user = $this->settingsManager->getAuthUser();
        if (!$user) $user->idUser = '';

        $userData = DB::select("SELECT RANK() OVER (
        ORDER BY ub.Date desc
    ) as ranks  , ub.idUser, ub.id ,ub.idSource ,ub.Ref , ub.Date, bo.Designation,ub.Description,
case when ub.idSource = '11111111' then 'system' else
(select concat( IFNULL(enfirstname,''),' ',  IFNULL( enlastname,''))  from metta_users mu  where mu.idUser = ub.idSource)
end as source,
case when bo.IO = 'I' then  concat('+ ','$ ', format(ub.value/PrixUnitaire,3) )
when bo.IO ='O' then concat('- ', format(ub.value/PrixUnitaire,3),' $' )
when bo.IO = 'IO' then 'IO'
end as value , case when idAmount = 5  then  concat( format(  SUM(case when bo.IO = 'I' then   format(format(ub.value,3)/format(PrixUnitaire,3) ,3)
when bo.IO ='O' then  format(format(ub.value,3)/format(PrixUnitaire *-1,3) ,3)
when bo.IO = 'IO' then 'IO'
end)   OVER(ORDER BY date) ,0) ,' ') when idAmount = 3 then concat('$ ', format(  SUM(case when bo.IO = 'I' then   format(format(ub.value,3)/format(PrixUnitaire,3) ,3)
when bo.IO ='O' then  format(format(ub.value,3)/format(PrixUnitaire *-1,3) ,3)
when bo.IO = 'IO' then 'IO'
end)   OVER(ORDER BY date) ,2) ) else concat( '$ ', format( ub.balance ,3,'en_EN') ) end  as balance,ub.PrixUnitaire, bo.IO as sensP
  FROM user_balances ub inner join balanceoperations bo on
ub.idBalancesOperation = bo.idBalanceOperations
where  (bo.idamounts = ? and ub.idUser =  ?)  order by Date   ", [2, $user->idUser]
        );

        return datatables($userData)
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
            ->addColumn('action', function ($query) {
                return "<a data-bs-toggle='modal' data-bs-target='#modalcontact'  onclick='myFunction(" . $query->id . ")'
class='btn btn-xs btn-primary btn2earnTable'><i class='glyphicon glyphicon-edit'></i>" . __('Edit') . "</a>
<a  class='btn btn-xs btn-danger btn2earnTable'  ><i></i>" . Lang::get('Delete') . "</a>";
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getHistoryNotificationModerateur()
    {
        $history = $this->settingsManager->getHistoryForModerateur();
        return datatables($history)
            ->make(true);
    }

    public function getHistoryNotification()
    {
        $history = $this->settingsManager->getHistory();
        return datatables($history)
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

        $request = DB::select($this->reqRequest . $condition . "  ? ", [$idUser]);
        return datatables($request)
            ->make(true);
    }

    public function getRepresentatives()
    {
        $representatives = DB::table('representatives')->get();
        return datatables($representatives)
            ->make(true);
    }

    public function getIdentificationRequest()
    {
        $query = DB::select('SELECT  u1.id id, u1.name User ,u1.fullphone_number, ir.created_at DateCreation, u2.name Validator, ir.response, ir.responseDate DateReponce , ir.note from identificationuserrequest ir
inner join users u1 on ir.IdUser = u1.idUser
left join users u2 on ir.idUserResponse = u2.idUser
where ir.status = ?
', [StatusRequest::EnCours->value]);

        return datatables($query)
            ->addColumn('action', function ($query) {
                return '<a data-bs-toggle="" data-bs-target="#modal"
                href="' . route('validate_account', ['locale' => app()->getLocale(), 'paramIdUser' => $query->id]) . '"
class="btn btn-primary btn2earnTable">' . __("Edit") . '</a> ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getUserBalancesCB()
    {
        $user = $this->settingsManager->getAuthUser();
        if (!$user) $user->idUser = '';
        $userData = DB::select("SELECT ub.idUser, ub.id ,ub.idSource ,ub.Ref , ub.Date, bo.Designation,ub.Description,
case when ub.idSource = '11111111' then 'system' else
(select concat( IFNULL(enfirstname,''),' ',  IFNULL( enlastname,''))  from metta_users mu  where mu.idUser = ub.idSource)
end as source,
case when bo.IO = 'I' then  concat('+ ', ub.value )
when bo.IO ='O' then concat('- ', ub.value )
when bo.IO = 'IO' then 'IO'
end as value , ub.Balance as balance
  FROM user_balances ub inner join balanceoperations bo on
ub.idBalancesOperation = bo.idBalanceOperations
where  (bo.idamounts = ? and ub.idUser =  ?)  order by Date   ", [1, $user->idUser]
        );
        return datatables($userData)->make(true);
    }

    public function getPurchaseUser()
    {
        $user = $this->settingsManager->getAuthUser();
        $userData = DB::select("

    SELECT
        list1.DateAchat AS DateAchat,
        IFNULL(list1.ReferenceAchat, '') AS ReferenceAchat,
        list1.idUser AS idUser,
        list1.item_title AS item_title,
        list1.nbr_achat AS nbrAchat,
        list1.Amout AS Amout,
        list1.invitationPurshase AS invitationPurshase,
        list1.visit AS visit,
        list1.PRC_BFS AS PRC_BFS,
        list1.PRC_CB AS PRC_CB,
        list1.CashBack_BFS AS CashBack_BFS,
        list1.CashBack_CB AS CashBack_CB,
        list1.PRC_BFS + list1.PRC_CB + list1.CashBack_BFS + list1.CashBack_CB AS Economy
    FROM
        (SELECT
            ub.idUser AS idUser,
                ub.item_title AS item_title,
                (SELECT
                        ub2.ref
                    FROM
                        user_balances ub2
                    WHERE
                        ub2.idBalancesOperation = 17
                            AND ub2.idUser = ub.idUser
                            AND ub.id_item = ub2.id_item) AS ReferenceAchat,
                (SELECT
                        ub2.Date
                    FROM
                        user_balances ub2
                    WHERE
                        ub2.idBalancesOperation = 17
                            AND ub2.idUser = ub.idUser
                            AND ub.id_item = ub2.id_item) AS DateAchat,
                la.nbr_achat AS nbr_achat,
                (SELECT
                        SUM(IFNULL(ub2.value, 0))
                    FROM
                        user_balances ub2
                    WHERE
                        ub2.idBalancesOperation = 17
                            AND ub2.idUser = ub.idUser
                            AND ub.id_item = ub2.id_item) AS Amout,
                IFNULL((SELECT
                        SUM(IFNULL(ub2.value, 0))
                    FROM
                        user_balances ub2
                    WHERE
                        ub2.idBalancesOperation = 10
                            AND ub2.idUser = ub.idUser
                            AND ub.id_item = ub2.id_item), 0) AS invitationPurshase,
                IFNULL((SELECT
                        SUM(IFNULL(ub2.value, 0))
                    FROM
                        user_balances ub2
                    WHERE
                        ub2.idBalancesOperation = 8
                            AND ub2.idUser = ub.idUser
                            AND ub.id_item = ub2.id_item), 0) AS visit,
                IFNULL((SELECT
                        SUM(IFNULL(ub2.value, 0))
                    FROM
                        user_balances ub2
                    WHERE
                        ub2.idBalancesOperation = 24
                            AND ub2.idUser = ub.idUser
                            AND ub.id_item = ub2.id_item), 0) AS PRC_BFS,
                IFNULL((SELECT
                        SUM(IFNULL(ub2.value, 0))
                    FROM
                        user_balances ub2
                    WHERE
                        ub2.idBalancesOperation = 26
                            AND ub2.idUser = ub.idUser
                            AND ub.id_item = ub2.id_item), 0) AS PRC_CB,
                IFNULL((SELECT
                        SUM(IFNULL(ub2.value, 0))
                    FROM
                        user_balances ub2
                    WHERE
                        ub2.idBalancesOperation = 23
                            AND ub2.idUser = ub.idUser
                            AND ub.id_item = ub2.id_item), 0) AS CashBack_BFS,
                IFNULL((SELECT
                        SUM(IFNULL(ub2.value, 0))
                    FROM
                        user_balances ub2
                    WHERE
                        ub2.idBalancesOperation = 25
                            AND ub2.idUser = ub.idUser
                            AND ub.id_item = ub2.id_item), 0) AS CashBack_CB
        FROM
            ((user_balances ub
        JOIN balanceoperations bo ON (ub.idBalancesOperation = bo.idBalanceOperations))
        JOIN (
    SELECT
        `user_balances`.`idUser` AS `idUser`,
        `user_balances`.`id_item` AS `id_item`,
        `user_balances`.`item_title` AS `item_title`,
        `user_balances`.`id_plateform` AS `id_plateform`,
        COUNT(0) AS `nbr_achat`
    FROM
        `user_balances`
    WHERE
        `user_balances`.`idBalancesOperation` = 17
    GROUP BY `user_balances`.`idUser` , `user_balances`.`id_item` , `user_balances`.`item_title` , `user_balances`.`id_plateform`) la ON (la.id_item = ub.id_item
            AND ub.idUser = la.idUser))
        GROUP BY ub.idUser , ub.item_title , ub.id_item , (SELECT
                ub2.Date
            FROM
                user_balances ub2
            WHERE
                ub2.idBalancesOperation = 17
                    AND ub2.idUser = ub.idUser
                    AND ub.id_item = ub2.id_item) , (SELECT
                ub2.ref
            FROM
                user_balances ub2
            WHERE
                ub2.idBalancesOperation = 17
                    AND ub2.idUser = ub.idUser
                    AND ub.id_item = ub2.id_item) , la.nbr_achat) list1 where idUser= ? ", [$user->idUser]);
        return datatables($userData)
            ->make(true);
    }


    public function getAllUsers()
    {
        $userData = DB::select("SELECT RANK() OVER (
        ORDER BY u.id
    ) as N , u.idUser ,u.status ,ue.registred_from,u.fullphone_number ,  concat( ifnull(mu.enFirstName,''),' ', ifnull(mu.enLastName,'')) as LatinName ,
    concat( ifnull(mu.arFirstName,''),' ',ifnull(mu.arLastName,'')) as ArabicName ,
    (select max(ub.date) from  user_balances ub where ub.idUser = u.idUser ) as lastOperation ,
    (select  c.name from countries c where c.phonecode = ue.idCountry limit 1 ) as country
    from users u
    left join user_earns ue on ue.idUser = u.IdUser
    left join metta_users mu on mu.idUser = u.idUser");

        return datatables($userData)
            ->addColumn('action', function ($settings) {
                return '<a  href="' . route('adminUserEdit', ['userId' => $settings->idUser, 'locale' => app()->getLocale()]) . '"   onclick="EditUserByAdmin()" class="btn btn-xs btn-primary btn2earnTable" ><i class="glyphicon glyphicon-edit""></i>' . Lang::get('Edit') . '</a>
<a onclick="deleteUser(' . $settings->idUser . ')" class="btn btn-xs btn-danger btn2earnTable"   ><i></i>' . Lang::get('Delete') . '</a>';
            })
            ->editColumn('status', function ($userData) {
                switch ($userData->status) {
                    case StatusRequest::OptValidated->value :
                        return '<span class="badge badge-info">' . trans('Authentied') . '</span>';
                        break;
                    case StatusRequest::InProgressNational->value :
                        return ' <span class="badge badge-success">' . trans('In progress national') . '</span>';
                        break;
                    case StatusRequest::InProgressInternational->value :
                        return ' <span class="badge badge-success">' . trans('In progress international') . '</span>';
                        break;
                    case StatusRequest::ValidNational->value :
                        return '<span class="badge badge-warning">' . trans('National valid') . '</span>';
                        break;
                    case StatusRequest::ValidInternational->value:
                        return '<span class="badge badge-danger">' . trans('International Valid') . '</span>';
                        break;
                    default:
                        return '<span class=" ">' . trans('Erreur') . '</span>';
                }
            })
            ->editColumn('registred_from', function ($userData) {
                switch ($userData->registred_from) {
                    case 1 :
                        return '<span class="">Learn2earn</span>';
                        break;
                    case 2 :
                        return '<span class="">Shop2earn</span>';
                        break;
                    case 3 :
                        return '<span class="">2earn</span>';
                        break;
                    default :
                        return '<span class=""> </span>';
                        break;
                }
            })
            ->escapeColumns([])
            ->make(true);
    }


    public function getInvitationsUser()
    {
        $user = $this->settingsManager->getAuthUser();

        $userData = DB::select(" select id, name,lastName,fullphone_number,
       case when
           fullphone_number in (select users.fullphone_number from users where idUpline != ? and idUpline <> 0)
           then 'UNAVAILABLE Registred'
           when
           fullphone_number in (select users.fullphone_number from users where idUpline = ?)
           then 'DONE registred'
           when
           fullphone_number in (select users.fullphone_number from users where  idUpline = 0)
           then 'Available registred'
           when
           fullphone_number in (select users_invitations.fullNumber from users_invitations where NOW() <= users_invitations.dateFIn and users_invitations.idUser <> ?)
           then concat('Invited by ','user : ', (select users.name from users_invitations inner join users on users.idUser = users_invitations.idUser  where users_invitations.fullNumber=user_contacts.fullphone_number  ),'  dispo after  ',(select concat( Datediff( users_invitations.datefin, now()),' jours') from users_invitations where users_invitations.fullNumber=user_contacts.fullphone_number ))
           when
           fullphone_number in (select users_invitations.fullNumber from users_invitations where NOW() <= users_invitations.dateFIn and users_invitations.idUser = ?)
           then concat('Invited by ','you  : ', (select users.name from users_invitations inner join users on users.idUser = users_invitations.idUser  where users_invitations.fullNumber=user_contacts.fullphone_number  ),'  dispo after  ',(select concat( Datediff( users_invitations.datefin, now()),' jours') from users_invitations where users_invitations.fullNumber=user_contacts.fullphone_number ))
           else
           'Available'
           end  as status
    from user_contacts
    where idUser = ? ", [$user->idUser, $user->idUser, $user->idUser, $user->idUser, $user->idUser]);
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
