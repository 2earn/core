<?php

namespace App\Http\Controllers;

use App\Models\User;

//use App\Models\UserBalance;
use Core\Enum\AmoutEnum;
use Core\Enum\StatusRequst;
use Core\Models\countrie;
use Core\Models\detail_financial_request;
use Core\Models\FinancialRequest;
use Core\Models\identificationuserrequest;
use Core\Models\user_balance;
use Core\Models\UserContact;
use Core\Models\metta_user;

use Core\Services\BalancesManager;
use Core\Services\settingsManager;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Livewire\Request;
use Paytabscom\Laravel_paytabs\Facades\paypage;
use phpDocumentor\Reflection\Types\Collection;
use Illuminate\Support\Facades\Lang;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request as Req;
use Illuminate\Validation\Validator;
use Illuminate\Validation\Rule;
use  Illuminate\Support\Facades\Validator as Val;
use function PHPUnit\Framework\isNull;
use carbon;
use Propaganistas\LaravelPhone\PhoneNumber;

class ApiController extends BaseController
{
    private settingsManager $settingsManager;
    private BalancesManager $balancesManager;
    private string $reqRequest = "select recharge_requests.Date , user.name user  ,
recharge_requests.userPhone userphone, recharge_requests.amount  from recharge_requests
left join users user on user.idUser = recharge_requests.idUser";

    public function __construct(settingsManager $settingsManager, BalancesManager $balancesManager)
    {
        $this->settingsManager = $settingsManager;
        $this->balancesManager = $balancesManager;
        $this->name = "fsdf";
    }

    public function buyAction(Req $request, BalancesManager $balancesManager)
    {
        $validator = Val::make($request->all(), [
            'ammount' => ['required', 'numeric', 'lte:' . $balancesManager->getBalances(Auth()->user()->idUser)->soldeCB],
            'phone' => [
                Rule::requiredIf($request->me_or_other == "other"),
            ],
            'bfs_for' => [
                Rule::requiredIf($request->me_or_other == "other"),
            ],


        ], [
            'ammount.required' => 'ammonut is required !',
            'ammount.numeric' => 'Ammount must be numeric !!',
            'ammount.lte' => 'Ammount > Cash Balance !!',
            'teinte.exists' => 'Le champ Teinte est obligatoire !',

        ]);

        if ($validator->fails()) {


            return response()->json([
                'error' => $validator->errors()->all()
            ], 400);
        }
        $number_of_action = intval($request->ammount / actualActionValue(getSelledActions()));
        $gift = getGiftedActions($number_of_action);
        $actual_price = actualActionValue(getSelledActions());
        $PU = $number_of_action * ($actual_price) / ($number_of_action + $gift);
        $Count = DB::table('user_balances')->count();
        $palier = \Core\Models\Setting::Where('idSETTINGS', '19')->orderBy('idSETTINGS')->pluck('IntegerValue')->first();
        $reciver = Auth()->user()->idUser;
        $reciver_bfs = Auth()->user()->idUser;
        $a = "me";
        if ($request->me_or_other == "other") {
            $reciver = getUserByPhone($request->phone, $request->country_code);
            $a = getPhoneByUser($reciver);
            if ($request->bfs_for == "other") {
                $reciver_bfs = $reciver;

                /*if(isNull($reciver_bfs))
                {$response= [
                        'type' => "error",
                        'message' => "user not Found",
                ];
                 return response()->json($response) ;}*/
            }

        }
        $b = getPhoneByUser($reciver);
        // dd($reciver_bfs);
        $reserve=getUserByContact($b);
        //dd($b);
        if ($reserve){
            if ($reserve!=$reciver){



                $setting=\Core\Models\Setting::WhereIn('idSETTINGS',['24','26','27','28'])->orderBy('idSETTINGS')->pluck('IntegerValue')->first();
                $prcShares=$setting[0];
                $prcAmount=$setting[1];
                $pcrCash=$setting[2];
                $pcrBFS=$setting[3];

                $user_balance = new user_balance();
                $user_balance->ref = "44" . date('ymd') . substr((10000 + $Count + 1), 1, 4);
                $user_balance->idBalancesOperation = 44;
                $user_balance->Date = now();
                $user_balance->idSource = '11111111';
                $user_balance->idUser = $reserve;
                $user_balance->idamount = AmoutEnum::Action;
                $user_balance->value = 0;
                $user_balance->gifted_shares = $number_of_action*$prcShares/100;//settings value
                $user_balance->PU = 0;
                $user_balance->WinPurchaseAmount = "0";
                $user_balance->Description = 'sponsorship commission from '.$b;
                $user_balance->Balance = 0;
                $user_balance->save();
                $amount=($number_of_action + $gift) * $PU*$prcAmount/100;
                $user_balance = new user_balance();
                $user_balance->ref = "44" . date('ymd') . substr((10000 + $Count + 1), 1, 4);
                $user_balance->idBalancesOperation = 49;
                $user_balance->Date = now();
                $user_balance->idSource = '11111111';
                $user_balance->idUser = $reserve;
                $user_balance->idamount = AmoutEnum::CASH_BALANCE;
                $user_balance->value = $amount*$pcrCash/100;
                $user_balance->PU = 0;
                $user_balance->WinPurchaseAmount = "0.000";
                $user_balance->Description = 'sponsorship commission from '.$b;
                $user_balance->Balance = $balancesManager->getBalances(auth()->user()->idUser)->soldeCB + $amount*$pcrCash/100;
                $user_balance->save();
                $user_balance = new user_balance();
                $user_balance->ref = "44" . date('ymd') . substr((10000 + $Count + 1), 1, 4);
                $user_balance->idBalancesOperation = 50;
                $user_balance->Date = now();
                $user_balance->idSource = '11111111';
                $user_balance->idUser = $reserve;
                $user_balance->idamount = AmoutEnum::BFS;
                $user_balance->value = $amount*$pcrBFS/100;
                $user_balance->PU = 0;
                $user_balance->WinPurchaseAmount = "0.000";
                $user_balance->Description = 'sponsorship commission from '.$b;
                $user_balance->Balance = $balancesManager->getBalances(auth()->user()->idUser)->soldeBFS + $amount*$pcrBFS/100;


                $user_balance->save();


            }
        }


        $user_balance = new user_balance();

        $user_balance->ref = "44" . date('ymd') . substr((10000 + $Count + 1), 1, 4);
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
        //$reserve=getUserByContact('0021653615614');
//        $id_balance = DB::table('user_balances')
//            ->insertGetId([
//                'ref' => "44" . date('ymd') . substr((10000 + $Count + 1), 1, 4),
//                'idBalancesOperation' => 44,
//                'Date' => date("Y-m-d H:i:s"),
//                'idSource' => '11111111',
//                'idUser' => $reciver,
//                'idamount' => AmoutEnum::Action,
//                'value' => $number_of_action,
//                'gifted_shares' => $gift,
//                'PU' => $PU,
//                'WinPurchaseAmount' => "0.000",
//
//            ]);
        //$Count = DB::table('user_balances')->count();
        $user_balance = new user_balance();
        $user_balance->ref = "44" . date('ymd') . substr((10000 + $Count + 1), 1, 4);
        $user_balance->idBalancesOperation = 48;
        $user_balance->Date = now();
        $user_balance->idSource = auth()->user()->idUser;
        $user_balance->idUser = auth()->user()->idUser;
        $user_balance->idamount = AmoutEnum::CASH_BALANCE;
        $user_balance->value = ($number_of_action + $gift) * $PU;
        $user_balance->WinPurchaseAmount = "0.000";
        $user_balance->Description = "purchase of " . ($number_of_action + $gift) . " shares for " . $a;
        $user_balance->Balance = $balancesManager->getBalances(auth()->user()->idUser)->soldeCB - ($number_of_action + $gift) * $PU;

        $user_balance->save();
//        $id_balance = DB::table('user_balances')
//            ->insertGetId([
//                'ref' => "42" . date('ymd') . substr((10000 + $Count + 1), 1, 4),
//                'idBalancesOperation' => 42,
//                'Date' => date("Y-m-d H:i:s"),
//                'idSource' => Auth()->user()->idUser,
//                'idUser' =>  Auth()->user()->idUser,
//                'idamount' => AmoutEnum::CASH_BALANCE,
//                'value' => ($number_of_action+$gift)*$PU,
//                'WinPurchaseAmount' => "0.000" ,
//                  'Balance'=> $balancesManager->getBalances(Auth()->user()->idUser)->soldeCB - ($number_of_action+$gift)*$PU
//            ]);
        //$Count = DB::table('user_balances')->count();


        $user_balance = new user_balance();
        $user_balance->ref = "44" . date('ymd') . substr((10000 + $Count + 1), 1, 4);
        $user_balance->idBalancesOperation = 46;
        $user_balance->Date = now();
        $user_balance->idSource = "11111111";
        $user_balance->idUser = $reciver_bfs;
        $user_balance->idamount = AmoutEnum::BFS;
        $user_balance->value = intval($number_of_action / $palier) * $actual_price * $palier;
        $user_balance->WinPurchaseAmount = "0.000";
        $user_balance->Balance = $balancesManager->getBalances(auth()->user()->idUser)->soldeBFS + intval($number_of_action / $palier) * $actual_price * $palier;

        $user_balance->save();
//        $id_balance = DB::table('user_balances')
//            ->insertGetId([
//                'ref' => "46" . date('ymd') . substr((10000 + $Count + 1), 1, 4),
//                'idBalancesOperation' => 46,
//                'Date' => date("Y-m-d H:i:s"),
//                'idSource' => "11111111",
//                'idUser' => $reciver_bfs,
//                'idamount' => AmoutEnum::BFS,
//                'value' => intval($number_of_action/$palier) *$actual_price*$palier,
//                'WinPurchaseAmount' => "0.000" ,
//                'Balance'=> $balancesManager->getBalances(Auth()->user()->idUser)->soldeBFS
//            ]);

        return response()->json(['type' => ['success'], 'message' => ['success']],);

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
    public function pay_request(Req $request)
    {
        $pay= paypage::sendPaymentCode('all')
            ->sendTransaction('sale','ecom')
            ->sendCart(10,1000,'test')
            ->sendCustomerDetails('Walaa Elsaeed', 'w.elsaeed@paytabs.com', '0101111111', 'test', 'Nasr City', 'Cairo', 'EG', '1234','100.279.20.10')
            ->sendShippingDetails('Walaa Elsaeed', 'w.elsaeed@paytabs.com', '0101111111', 'test', 'Nasr City', 'Cairo', 'EG', '1234','100.279.20.10')
            ->sendURLs('return_url', 'callback_url')
            ->sendLanguage('en')
            ->sendHideShipping(true)
            ->create_pay_page();

        return $pay;
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
            $user_balance->Balance = $balancesManager->getBalances(auth()->user()->idUser)->soldeCB - $request->input('amount');

            $user_balance->save();
//            $id_balance = DB::table('user_balances')
//                ->insertGetId([
//                    'ref' => "42" . date('ymd') . substr((10000 + $Count + 1), 1, 4),
//                    'idBalancesOperation' => 42,
//                    'Date' => date("Y-m-d H:i:s"),
//                    'idSource' => Auth()->user()->idUser,
//                    'idUser' => $request->all()['reciver'],
//                    'idamount' => AmoutEnum::CASH_BALANCE,
//                    'value' => $request->all()['amount'],
//                    'WinPurchaseAmount' => "0.000" ,
//                    'Balance' =>$balancesManager->getBalances(Auth()->user()->idUser)->soldeCB -$request->all()['amount']
//                ]);


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
            $user_balance->Balance = $balancesManager->getBalances($request->input('reciver'))->soldeCB + $request->input('amount');

            $user_balance->save();
//            $id_balance = DB::table('user_balances')
//                ->insertGetId([
//                    'ref' => "43" . date('ymd') . substr((10000 + $Count + 1), 1, 4),
//                    'idBalancesOperation' => 43,
//                    'Date' => date("Y-m-d H:i:s"),
//                    'idSource' => "11111111",
//                    'idUser' => $request->all()['reciver'],
//                    'idamount' => AmoutEnum::CASH_BALANCE,
//                    'value' => $request->all()['amount'],
//                    'WinPurchaseAmount' => "0.000",
//                    'Balance' =>$balancesManager->getBalances($request->input('reciver'))->soldeCB + $request->all()['amount']
//                ]);


            // adjust new value for admin

            $new_value = intval($old_value) - intval($request->amount);
            DB::table('usercurrentbalances')
                ->where('idUser', Auth()->user()->idUser)
                ->where('idamounts', AmoutEnum::CASH_BALANCE)
                ->update(['value' => $new_value, 'dernier_value' => $old_value]);

            // adjust new value for reciver
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
        $data=DB::select("select name,apha2,continant,
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
        //dd($data);
        //dd(response()->json($data));
        //return datatables($data) ->make(true);
        return response()->json($data);
    }
    public function getSankey()
    {
        $data=DB::select("select s.`from`,s.`to`,cast(s.weight as decimal (10,2)) as weight from sankey s");
        //dd($data);
        //dd(response()->json($data));
        //return datatables($data) ->make(true);
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
                return $user_balance->PU * ($user_balance->value + $user_balance->gifted_shares) / $user_balance->value;
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
            DB::raw('CAST((gifted_shares + value) * ' . $actualActionValue .'- (gifted_shares + value) * PU AS DECIMAL(10,2)) AS current_earnings')
        )
            ->where('idBalancesOperation', 44)
            ->where('idUser', $idUser)
            ->get();
        //dd($userBalances);
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
                return number_format($user_balance->PU * ($user_balance->value + $user_balance->gifted_shares) / $user_balance->value, 2);
            })
            ->addColumn('formatted_created_at', function ($user_balance) {
                return Carbon\Carbon::parse($user_balance->Date)->format('Y-m-d H:i:s');
            })
            ->addColumn('formatted_created_at_date', function ($user_balance) {
                return Carbon\Carbon::parse($user_balance->Date)->format('Y-m-d');
            })
            ->addColumn('flag', function ($settings) {

                return '<img src="' . Asset("assets/images/flags/" . strtolower($settings->apha2)) . '.svg" alt="' . strtolower($settings->apha2) . '" class="avatar-xxs me-2">';
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
                return Asset("assets/images/flags/" . strtolower($settings->apha2)) . '.svg';
            })
            ->rawColumns(['flag', 'share_price', 'status'])
            ->make(true);


    }

    public function handlePaymentNotification(Req $request)
    {

        //$d= route('paytabs_notification1');
        //dd($d);
        $a=$request->request;

        $responseData = $a->all();
        $tranRef = $responseData['tranRef'];
        $data = Paypage::queryTransaction($tranRef);
        //dd($data->tran_type);
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
            'created_at'=>now(),
            'updated_at'=>now()


        ]);
        if($data->success)
        {

            $chaine = $data->cart_id;
            $user = explode('-', $chaine)[0];
            $k=\Core\Models\Setting::Where('idSETTINGS','30')->orderBy('idSETTINGS')->pluck('DecimalValue')->first();

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
            $value=$value->value*1;

            $user_balance = new user_balance();
            $user_balance->ref = "51" . date('ymd') . substr((10000 + $Count + 1), 1, 4);
            $user_balance->idBalancesOperation = 51;
            $user_balance->Date = now();
            $user_balance->idSource = $user;
            $user_balance->idUser = $user;
            $user_balance->idamount = AmoutEnum::CASH_BALANCE;
            $user_balance->value = $data->tran_total/$k;
            $user_balance->WinPurchaseAmount = "0.000";
            $user_balance->Description = $data->tran_ref;
            $user_balance->Balance = $value      + $data->tran_total/$k;
            $user_balance->save();





            $new_value = intval($old_value) + $data->tran_total/$k;
            DB::table('usercurrentbalances')
                ->where('idUser', $user)
                ->where('idamounts', AmoutEnum::CASH_BALANCE)
                ->update(['value' => $new_value, 'dernier_value' => $old_value]);

            // adjust new value for reciver



        }


        return redirect()->route('user_balance_cb',  app()->getLocale());
    }
    public function handlePaymentNotification1(Req $request)
    {
        dd($request);

        return $request;
    }
    public function updateReserveDate(Req $request)
    {
        try {
            $id = $request->input('id');
            $status = $request->input('status');

            if ($status == "true") {
                $st = 1;
                $dt=now();
                DB::table('user_contacts')
                    ->where('id', $id)
                    ->update(['availablity' => $st,'reserved_at' => $dt]);

                return response()->json(['success' => true]);} else {
                $st = 0;
                DB::table('user_contacts')
                    ->where('id', $id)
                    ->update(['availablity' => $st]);

                return response()->json(['success' => true]);

            }

            // Assuming 'id' is the primary key for the 'user_balances' table
            DB::table('user_contacts')
                ->where('id', $id)
                ->update(['availablity' => $st,'reserved_at' => $dt]);

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
            //$status = $request->input('status');

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
            $total=$request->input('total');


            if($st==0)
            {$p=0;
            }
            else{
                if($st<$total)$p=2;
                if($st==$total)$p=1;}
            DB::table('user_balances')
                ->where('id', $id)
                ->update(['Balance' => floatval($st),'WinPurchaseAmount'=>$p]);

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
        $query =  DB::table('user_balances')
            ->select(DB::raw('DATE(date) as x'),DB::raw('SUM(value) as y'))
            ->where('idBalancesOperation', 44)
            ->groupBy('x')
            ->get();

        foreach ($query as $record) {

            $record->y = (float)$record->y;
        }
        return response()->json($query);
    }

    public function getSharePriceEvolutionWeek()
    {
        $query =  DB::table('user_balances')
            ->select(DB::raw(' concat(year(date),\'-\',WEEK(date, 1)) as x'),DB::raw('SUM(value) as y'),DB::raw(' WEEK(date, 1) as z'))
            ->where('idBalancesOperation', 44)
            ->groupBy('x','z')
            ->orderBy('z')
            ->get();

        foreach ($query as $record) {

            $record->y = (float)$record->y;
        }
        return response()->json($query);
    }
    public function getSharePriceEvolutionMonth()
    {
        $query =  DB::table('user_balances')
            ->select(DB::raw('DATE_FORMAT(date, \'%Y-%m\') as x'),DB::raw('SUM(value) as y'))
            ->where('idBalancesOperation', 44)
            ->groupBy('x')
            ->get();

        foreach ($query as $record) {

            $record->y = (float)$record->y;
        }
        return response()->json($query);
    }
    public function getSharePriceEvolutionDay()
    {
        $query =  DB::table('user_balances')
            ->select(DB::raw('DAYNAME(date) as x'),DB::raw('SUM(value) as y'),DB::raw('DAYOFWEEK(date) as z'))
            ->where('idBalancesOperation', 44)
            ->groupBy('x','z')
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
        // Call getSelledActions to determine the limit
        $limit = getSelledActions() * 1.05;

        $data = [];

        for ($x = 0; $x <= $limit; $x += intval($limit / 20)) {
            $data[] = [
                'x' => $x,
                'y' => actualActionValue($x), // Call your helper function
            ];
        }
        return response()->json($data);
    }


    public function getUsersList()
    {

        $query = User::select('countries.apha2', 'users.idUser', DB::raw('CONCAT(nvl( meta.arFirstName,meta.enFirstName), \' \' ,nvl( meta.arLastName,meta.enLastName)) AS name'), 'users.mobile', 'users.created_at', 'OptActivation', 'pass')
            ->join('metta_users as meta', 'meta.idUser', '=', 'users.idUser')
            ->join('countries', 'countries.id', '=', 'users.idCountry');
        //->where('users.idUser','<' ,197604180);

        //  dd($query) ;
        return datatables($query)
            ->addColumn('formatted_mobile', function ($user) {
                $phone = new PhoneNumber($user->mobile, $user->apha2);
                return $phone->formatForCountry($user->apha2);
            })
            ->addColumn('formatted_created_at', function ($user) {
                return Carbon\Carbon::parse($user->created_at)->format('Y-m-d H:i:s');
            })
            ->addColumn('action', function ($settings) {

                return '<a data-bs-toggle="modal" data-bs-target="#AddCash"   data-phone="' . $settings->mobile . '" data-country="' . Asset("assets/images/flags/" . strtolower($settings->apha2)) . '.svg" data-reciver="' . $settings->idUser . '"
class="btn btn-xs btn-primary btn2earnTable addCash"  >
<i class="glyphicon glyphicon-add"></i>' . Lang::get('AddCash') . '</a> ';
            })
            ->addColumn('flag', function ($settings) {

                return '<img src="' . Asset("assets/images/flags/" . strtolower($settings->apha2)) . '.svg" alt="' . strtolower($settings->apha2) . '" class="avatar-xxs me-2">';
            })
            ->addColumn('SoldeCB', function ($user_balance) {
                //dd($user_balance->idUser);
                return '<a data-bs-toggle="modal" data-bs-target="#detail"   data-amount="1" data-reciver="' . $user_balance->idUser . '"
class="btn btn-ghost-secondary waves-effect waves-light cb"  >
<i class="glyphicon glyphicon-add"></i>$' . number_format(getUserBalanceSoldes($user_balance->idUser, 1), 2) . '</a> '; // number_format(getUserBalanceSoldes($user_balance->idUser,1),2);
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
<i class="glyphicon glyphicon-add"></i>' . number_format(getUserSelledActions($user_balance->idUser), 0) . '</a> '; //number_format(getUserSelledActions($user_balance->idUser),0);
            })
            ->rawColumns(['action', 'flag', 'SoldeCB', 'SoldeBFS', 'SoldeDB', 'SoldeSMS', 'SoldeSH'])
            ->make(true);
    }

    public function getCountries()
    {
        $query = countrie::all('id', 'name', 'phonecode', 'langage');
        // $query=DB::table('countries')->select('id','name','phonecode','langage');
        return datatables($query)
            ->addColumn('action', function ($settings) {
                return '<a data-bs-toggle="modal" data-bs-target="#editCountriesModal"  onclick="getEditCountrie(' . $settings->id . ')"
class="btn btn-xs btn-primary btn2earnTable"  >
<i class="glyphicon glyphicon-edit""></i>' . Lang::get('Edit') . '</a> ';
            })
//            ->addColumn('action', function ($query) {
//                return '<a href="#edit-' . $query->id . '" "><i class="fa fa-edit" aria-hidden="true" style="cursor: pointer;color: green; padding-left: 10px;"></i></a>';
//            })
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
                                    <button  onclick="editSettingFunction(' . $settings->idSETTINGS . ')"   data-bs-toggle="modal" data-bs-target="#settingModal"
 class="btn btn-sm btn-primary edit-item-btn"  ><i class="glyphicon glyphicon-edit""></i>' . Lang::get('Edit') . '</button> </div> </div>';
            })
            ->setRowId('idSETTINGS')
            ->editColumn('Automatically_calculated', function ($settings) {
                if ($settings->Automatically_calculated == 1)
                    return '<span class="badge badge-success">Yes</span>';
                else
                    return '<span class="badge badge-info">No</span>';
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
                return '<a  onclick="editBOFunction(' . $settings->idBalanceOperations . ')"   data-bs-toggle="modal" data-bs-target="#BoModal"
class="btn btn-xs btn-primary btn2earnTable"  ><i class="glyphicon glyphicon-edit""></i>' . Lang::get('Edit') . '</a> ';
            })
//            ->addColumn('action', function ($balanceOperations) {
//                return '<a href="#edit-' . $balanceOperations->idBalanceOperations . '" "><i class="fa fa-edit" aria-hidden="true" style="cursor: pointer;color: green; padding-left: 10px;"></i></a>';
//            })
//            <span><i class="fa fa-cogs" aria-hidden="true"></i></span>
            ->editColumn('MODIFY_AMOUNT', function ($balanceOperations) {
                if ($balanceOperations->MODIFY_AMOUNT == 1)
                    return '<span class="badge badge-success">Yes</span>';
                else
                    return '<span class="badge badge-info">No</span>';
            })
            ->editColumn('amountsshortname', function ($balanceOperations) {

                if ($balanceOperations->amountsshortname == "BFS") {
                    return ' <img width="20" height="20" id="imm" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAABmJLR0QA/wD/AP+gvaeTAAAEX0lEQVR4nO2az48URRTHP5jZsGA2svw4ADsJFwPyB3jxBwkcMNwMHoCLurp69AQhcOGf8ICEP0CCLAfABNhVXIRsiFd2RUABEw+gsIvKCcfDe0X39lR1Vc9U9ww7/U0qma73qt73va569aMHagw2VnjkrS7b932fr3RgrEaNZYQ6B3RgrEaNAULLUzrBjzn9zVTNc+CTYC/geivdjKqOMfCrgC8AZczXMtAxz17mgGxbV32RPl1w9tkINOAi228ozLPOARXYmKLY3iGte7lEXkA1AVhtqbMlpquBbStFjDV7BDiv+s+AfTm67wP/qO4lYE2FPEvtuAF8qW3+A45ZdL4AnqvOSWCoBzzb5quv46Lz9RCJk18hTg7p75bKDvWS5zVLgx8sejMWvWsBxAH2Av9qm4taWlr3QWAf18viOQKco9z5CvAm8EeK1EPg7QLtS80r2SF50KJzkGQon6DYfDXYAsxq2dJB+yG1XRpPW1IKSWZVo1SeMeZrFSiVZ7fztSqUyrPb+VoVXhaeNfoSebcqy628OIylLxAK7ZmXAdpuibL76QOE7/H7FdOID+mdovOAlBZsAH7T509LJFg2PkN8+BVYr3XeAOwncX6G8HvDEKwFxoGzwDzwt5Z5YBL4GBiNaK+BXLSYIOwnIADpRLExEpFVwFFgwWInW54AR7RNDGzCfipsgxFMIcM+1psfA34i2ZdfQkbBdmAYOZ1tBSZYes6/AWyOxKGB+JTuvw1OQRcYA37Xfm8C7wa0eQe4o20eEC8IBpUFYBXJm/8e+zncZXMUueAwI2E4Iq/KAnBU+7sFvFaUDJIwb6n8cERelQRgLUnCe8si34MMb19i2qn1jyl245SHQgFoAqeBRS2TSMLyYVz7uuiQ25x3Bd8krg8D7IbwDQ5AE/jTQvIvleXhrOp+4pAXGW1mM/ONRy+Ub3AATuvzeSQTN4ELWnfKQ+Zn1XvdIS8SgG2qO+fRC+UbHIBFfU5Hr6l1Cx4ypu2IQ56dAg+A9xy6I6qzGGjTx3eJnyHfBm3RCn17zx31E4jTBmPAcYdu0f8LdMw3OwIm9fkCEskm8K3Wfe3py0yBbSGGLbbTeENl854+QvkGT4GtSALJJpVHyBsLITPu0fOSAj4nLAmG8i28DJ5C5tACEkmf8yCnurxl0LYE3nPomjN96DLo41vZRuiJ9rcjx1ba+d0WvV30eCPUDY6QzF3XGT/P5jrgtspDvhiHorIADCMHmRZwBXsQXDbXkZzhZ3lJD0MgGxKz5v+CfTpksYvkzd9HLjRiwhuAaWT7GetCZDPJSGghl6wTyBL5KrLR2Y5k++mU3izxnG9Y+m9DNjFdjUhgGDnSmsSYVx4jc35lJNubsH/zaIMR7EMuEFvEvxQdBT5C9glzwFMtc8AZZKmLle1BuBvn7yK+BeWA9SRBmIhIqGqYTdRdJLFCgSRorpCnSiRYNr4jGdUGS/wc+E9j6dOg7Z+ayxX99Ff/Gj3F/1ZQl/ObCiogAAAAAElFTkSuQmCC">';
//                    return '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAABmJLR0QA/wD/AP+gvaeTAAAFvElEQVR4nO3ca6xdRRXA8R+tLWgMUssjisojRUICNFCCaAQFP4BA5NqYmiZ+4FESBRLSolTj45uCNSbGRyBWRTAQKJgWBEFirLwCBKomRnk/DFUSDVAfWNraXj+sfUMtZ597zrmzzz3bs/7Jzpx79uyZdWftmVmz1swhSZIkSZIkSZIkSZIkSZIkSZIkSZIkSZIkSZKWs1eP+Y7ABViMA5oTp/W8iqewDnc3Vcml2I7JvPq6foo399vY0/WQ5bih+nw9NuC5fisZIxbgZKzCW0WbfapU4XOxWWj7slKFjgnHYKtouxNKFfq+qsDnMadUoWPEd0T7XdnPQ90a+t1V+lvsGlCocWZTla7GP3Ezjp3uoW4KmVelW2cm19jyWpVOivnkE3hEzMu1vKlQ5V/CRKGyhsU1uA9rcR2+1yHP4ur+ni/u33CW3kaOm/BlfF4sHa7B7/BYp8ylFDKBb9VVMqK8gC24qPrcicfxmQ7f34WD8GKPdT2NFcJQOhfn43OdMpZQyDtwuLC72zi8bepyb1vN/QdwOn7cZ113C4UcXJehhPW0Bj/STmUMyrfxFbHuKMpMFHIgrsV7hXDjxK/E3LARS0oWPIhCTsDv8SRewUfw75JCtYQviLXGerFWW1Wi0EHmkEX4k1DMthJC9MF8YUBM4Hi8q/p+M34jXDsbhO9tGPxQDNcX4wMlChx0yPqH4StjqbDiVogh4xxh6RwkFLQRF+KP+PgQ5ZrES6UKK2X2Nskc4X44B+fh3g55Hq+utfhQlZ4khpVWeRna4KO6UvjVTtJZGXtyT5X3/biiQbkaYdQVslT0jAlhQPTKy9UzS7XMgzDKCpkv1jif1p8ypnhZzDffrMpqBcOeQ+bioyIk/KRwQeysyTuBZ8VkPSj3iIDax3BLAZkaZ5g9ZIFwOVwuXPurcT/2q8k/IWLTM2Wd+mGrX5kaZ5gK+ToewiliEXWKcEfXBXCWCG/sTLlXfdSuX5kaZ5gKOdsb/9GvieGkE+/EnwvUu1m9M69fmRpn2JP6npsq5mp+nTBnmjpmQ6ZahqmQn4kgzRR7VX/fXpP/L7q4qfvgYPVxi35lapxhWlmrcaeYFx7BieKFOLMm/6NiTH9ihvVOzQslZGqcYSpkCz4oAjtH4pf4hXoT81bhm1o7w3qX4apCMjXOsNchO/Hz6pqO9fgqThPOxEH4MA7FbYVkapxRXqnvEOuDq/H2AZ5fiB9gpeG542fMKCuE6CXrRYyjH6UsrJ65WffeMXKMukIIF/qDeBin9pD/tCrvA/hig3I1QhviIbuENfQgvi+ileuEZTS1fec9YpPzMuECuUwYBa2jDQqZYgPu8Lo7fqXXQ7gviO06VwlF7JgNAUswiEL+I2z7NcIkfaqoRN3ZIby2dZ7bYbOvcPEvU6gdBplDbq+E2C7G6QtLCNJCFovdN8fjG/53xT8wg/SQ10TM4C6xT3WjGNcbO8I1giwUL+ZKhXvrTK2sZ0REb00BWdrEKmFOFx86S5i9d2J/HFagrLYwIRadxSlhZU3iD0Ix/ypQXhtYJLYdFaeU2XuuCCiNC69qaHN5Lwrp5Sz7i3o/K5F0odscsqVKDxmGIP+HHFqlW7rm2oNuCnlUuKaX4KgBhRpX5uGT1eeHShZ8nZi0HxMLoWR6FuBG0W6b8Zbd7i2vvr+hw3OYfg65FMfhaHFQ8TmxIzDpzN4i8jhPTPzLNXB25m34rjBpZ/v3Q9pw7RRh4KM7tOWKKk/t2cRerKy/4xJ8Vtjfe/fwzLgyKdxIdedFFlVpUYt0X7GRrE2u+1Fgvjj6NinOuBfjiqrQW8ziHtiWsQ9+ItrtGV1e5l5/wGx3jsWvhTWxVbgQWnVKaRY4XLTXNpwh2q8oRwrf1U6zP4m25dqkh4Ohg/SQ3Vko4tlt2Cwxm/xV/c93JEmSJEmSJEmSJEmSJEmSJEmSJEmSJEmSJEmSJMn48F9tHf
//8Ho/1NSwAAAABJRU5ErkJggg==">';
//                    return  ' <img  src="https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885__480.jpg" alt="" width="20" height="20" />'
                    ;
//class="fa-solid fa-user-large" aria-hidden="true">'

                } else {
                    return '<span><i class="fa fa-cogs" aria-hidden="true"></i></span>';
                }

//                if ($balanceOperations->MODIFY_AMOUNT == 1)
//                    return '<span class="badge badge-success">Yes</span>';
//                else
//                    return '<span class="badge badge-info">No</span>';
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
                return '<a onclick="editAmountsFunction(' . $settings->idamounts . ')"   data-bs-toggle="modal" data-bs-target="#AmountsModal"
class="btn btn-xs btn-primary btn2earnTable"  >
<i class="glyphicon glyphicon-edit""></i>' . Lang::get('Edit') . '</a>';
            })
//            ->addColumn('action', function ($amounts) {
//                return '<a href="#edit-' . $amounts->idamounts . '" "><i class="fa fa-edit" aria-hidden="true" style="cursor: pointer;color: green; padding-left: 10px;"></i></a>';
//            })
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
//            ->addColumn('action', function ($actionHistorys) {
//                return '<a href="#edit-' . $actionHistorys->id . '" "><i class="fa fa-edit" aria-hidden="true" style="cursor: pointer;color: green; padding-left: 10px;"></i></a>';
//            })
            ->addColumn('action', function ($settings) {
                return '<a onclick="editHAFunction(' . $settings->id . ')"   data-bs-toggle="modal" data-bs-target="#HistoryActionModal"  class="btn btn-xs btn-primary btn2earnTable"  ><i class="glyphicon glyphicon-edit""></i>' . Lang::get('Edit') . '</a>
<a  class="btn btn-xs btn-danger btn2earnTable"  ><i></i>' . Lang::get('Delete') . '</a>';
            })
            ->editColumn('reponce', function ($actionHistorys) {
                if ($actionHistorys->reponce == 1)
                    return '<span class="badge badge-success">create reponce</span>';
                else
                    return '<span class="badge badge-info">sans reponce</span>';
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function getUserContacts()
    {

//        $this->settingsManager->getAuthUser()->idUser
//        Lang::get('aside.Contact')

        $query = UserContact::select('id', 'name', 'lastName', 'mobile','availablity')
            ->where('idUser', '=', $this->settingsManager->getAuthUser()->idUser);
        // $query=DB::table('countries')->select('id','name','phonecode','langage');

        return datatables($query)->make(true);

        /*  ->addColumn('action', function ($query) {
              $ms = "Are you sure?";
              return '<a

class="btn btn-primary btn2earnTable">' . __('Edit') . '</a>
<a  class="btn btn-danger btn2earnTable"
class="btn btn-danger" data-bs-toggle="modal"  data-bs-target="#deleteModal" onclick="deleteId(' . $query->id . ')"
  > ' . Lang::get("Delete") . '</a>';
          })
          ->rawColumns(['action'])
          ->make(true);*/
    }


    public function getUrlList($idUser, $idamount)
    {
        // Construire l'URL de la route avec les paramètres idUser et idamount
        $url = route('API_UserBalances_list', ['locale' => app()->getLocale(), 'idUser' => $idUser, 'idAmounts' => $idamount]);

        // Retourner l'URL de la route
        return $url;
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
        /*return Datatables::of($userData)
            ->addColumn('formatted_date', function ($user) {
                return Carbon\Carbon::parse($user->Date)->format('Y-m-d');
            })
            ->editColumn('Description', function ($row) {

                if ($row->idamount == 3)
                    // return '<span style="text-align:right;">'.htmlspecialchars($row->Description).'</span>';
                    return '<div style="text-align:right;">' . htmlspecialchars($row->Description) . '</div>';
                else return $row->Description;
            })
            ->rawColumns(['Description', 'formatted_date'])
//           ->orderColumn('name', 'email $1')
            ->make(true);*/

//        return datatables($userData)
//
//            ->make(true);

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
        $user = $this->settingsManager->getAuthUser();
        if (!$user) $user->idUser = '';

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
end)   OVER(ORDER BY date) ,3) , ' $') else concat( format( ub.balance ,3,'pt_BR') ,' $') end  as balance,ub.PrixUnitaire,'d' as sensP
  FROM user_balances ub inner join balanceoperations bo on
ub.idBalancesOperation = bo.idBalanceOperations
where  (bo.idamounts = ? and ub.idUser =  ?)  order by Date   ", [$idAmounts, $user->idUser]
        );

        return Datatables::of($userData)
            ->addColumn('formatted_date', function ($user) {
                return Carbon\Carbon::parse($user->Date)->format('Y-m-d');
            })
            ->editColumn('Description', function ($row) use ($idAmounts) {

                if ($idAmounts == 3)
                    // return '<span style="text-align:right;">'.htmlspecialchars($row->Description).'</span>';
                    return '<div style="text-align:right;">' . htmlspecialchars($row->Description) . '</div>';
                else return $row->Description;
            })
            ->rawColumns(['Description', 'formatted_date'])
//           ->orderColumn('name', 'email $1')
            ->make(true);

//        return datatables($userData)
//
//            ->make(true);

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
case when bo.IO = 'I' then  concat('+ ','$ ', format(ub.value/PrixUnitaire,2) )
when bo.IO ='O' then concat('- ', format(ub.value/PrixUnitaire,2),' $' )
when bo.IO = 'IO' then 'IO'
end as value , case when idAmount = 5  then  concat( format(  SUM(case when bo.IO = 'I' then   format(format(ub.value,2)/format(PrixUnitaire,2) ,2)
when bo.IO ='O' then  format(format(ub.value,2)/format(PrixUnitaire *-1,2) ,2)
when bo.IO = 'IO' then 'IO'
end)   OVER(ORDER BY date) ,0) ,' ') when idAmount = 3 then concat('$ ', format(  SUM(case when bo.IO = 'I' then   format(format(ub.value,2)/format(PrixUnitaire,2) ,2)
when bo.IO ='O' then  format(format(ub.value,2)/format(PrixUnitaire *-1,2) ,2)
when bo.IO = 'IO' then 'IO'
end)   OVER(ORDER BY date) ,2) ) else concat( '$ ', format( ub.balance ,2,'pt_BR') ) end  as balance,ub.PrixUnitaire, bo.IO as sensP
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

//        $id= auth()->user()->id;
//        $idUser= auth()->user()->idUser;
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
//        $idUser = request()->idUser ;
        if ($this->settingsManager->getAuthUser() == null) {
            $idUser = "";
        } else {
            $idUser = $this->settingsManager->getAuthUser()->idUser;
        }
        $type = request()->type;
//        if ($type == null || $idUser == null)
//            $condition =   " where recharge_requests.idPayee = ";
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
//if($idUser == null)
//{
//    $idUser = "";
//}
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
//        $query = User::select('id', 'name', 'mobile', 'idCountry', 'asked_at')
//            ->where('status', '=', -1);

        $query = DB::select('SELECT  u1.id id, u1.name User ,u1.fullphone_number, ir.created_at DateCreation, u2.name Validator, ir.response, ir.responseDate DateReponce , ir.note from identificationuserrequest ir
inner join users u1 on ir.IdUser = u1.idUser
left join users u2 on ir.idUserResponse = u2.idUser
where ir.status = ?
', [StatusRequst::EnCours->value]);

        return datatables($query)
            ->addColumn('action', function ($query) {
                return '<a data-bs-toggle="" data-bs-target="#modal"
                href="' . route('validateaccount', ['locale' => app()->getLocale(), 'paramIdUser' => $query->id]) . '"
class="btn btn-primary btn2earnTable">' . __("Edit") . '</a> ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

//onclick='f(" . $query->id . ")'
    public function getUserBalancesCB()
    {
//        $idAmounts = 0;
//        switch ($typeAmounts) {
//            case 'cash-Balance':
//                $idAmounts = 1;
//                break;
//            case 'Balance-For-Shopping':
//                $idAmounts = 2;
//                break;
//            case 'Discounts-Balance':
//                $idAmounts = 3;
//            case 'SMS-Balance':
//                $idAmounts = 5;
//                break;
//            default :
//                $idAmounts = 0;
//                break ;
//        }
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
//        return Datatables::of($userData)
////           ->orderColumn('name', 'email $1')
//            ->make(true);


        return datatables($userData)
            ->make(true);

    }

    public function getPurchaseUser()
    {
        $user = $this->settingsManager->getAuthUser();
//        if (!$user) $user->idUser ='' ;
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
//            ->addColumn('action', function ($amounts) {
//                return '<a href="#edit-' . $amounts->idamounts . '" "><i class="fa fa-edit" aria-hidden="true" style="cursor: pointer;color: green; padding-left: 10px;"></i></a>';
//            })
            ->editColumn('status', function ($userData) {
                switch ($userData->status) {
                    case 0 :
                        return '<span class="badge badge-info">Authentied</span>';
                        break;
                    case 1 :
                        return ' <span class="badge badge-success">Identfied</span>';
                        break;
                    case -1 :
                        return '<span class="badge badge-warning">Identification in Progress</span>';
                        break;
                    case 2 :
                        return '<span class="badge badge-danger">Suspended</span>';
                        break;
                    default:
                        return '<span class=" ">Erreur</span>';
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

//        if (!$user) $user->idUser ='' ;
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
        $userAuth = $this->settingsManager->getAuthUser();
        if (!$userAuth) abort(404);
        $array = [];
        $requestInOpen = detail_financial_request::join('financial_request', 'financial_request.numeroReq', '=', 'detail_financial_request.numeroRequest')
            ->where('detail_financial_request.idUser', $userAuth->idUser)
            ->where('financial_request.Status', 0)
            ->where('detail_financial_request.vu', 0)
            ->count();
        $requestOutAccepted = FinancialRequest::where('financial_request.idSender', $userAuth->idUser)
            ->where('financial_request.Status', 1)
            ->where('financial_request.vu', 0)
            ->count();
        $requestOutRefused = FinancialRequest::where('financial_request.idSender', $userAuth->idUser)
            ->where('financial_request.Status', 5)
            ->where('financial_request.vu', 0)
            ->count();
        $array['requestInOpen'] = $requestInOpen;
        $array['requestOutAccepted'] = $requestOutAccepted;
        $array['requestOutRefused'] = $requestOutRefused;

//        $array['out'] = 60;
//        return $array ;
        return json_encode(array('data' => $array));

//        return response()->json($array);
    }

}
