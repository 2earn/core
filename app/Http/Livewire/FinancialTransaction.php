<?php


namespace App\Http\Livewire;

use App\Models\User;
use Core\Enum\AmoutEnum;
use Core\Enum\EventBalanceOperationEnum;
use Core\Enum\ExchangeTypeEnum;
use Core\Enum\TypeEventNotificationEnum;
use Core\Enum\TypeNotificationEnum;
use Core\Models\detail_financial_request;
use Core\Models\FinancialRequest;
use Core\Models\user_balance;
use Core\Models\user_earn;
use Core\Services\BalancesManager;
use Core\Services\settingsManager;
use Core\Services\UserBalancesHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

use Livewire\Component;

class FinancialTransaction extends Component
{
    public $soldecashB;
    public $soldeBFS;
    public $soldeExchange = 0;
    public $numberSmsExchange = 0;
    public $prix_sms = 0;
    public $montantSms = 0;
    public $testprop = 0;
    public $mobile;
    public $requestIn;
    public $FinRequestN;
    public $showCanceled;
    public $fromTab;
    protected $listeners = [
        'PreExchange' => 'PreExchange',
        'ExchangeCashToBFS' => 'ExchangeCashToBFS',
        'PreExchangeSMS' => 'PExchangeSms',
        'exchangeSms' => 'exchangeSms',
        'redirectPay', 'redirectPay',
        'AcceptRequest' => 'AcceptRequest',
        'redirectToTransfertCash' => 'redirectToTransfertCash',
        'DeleteRequest' => 'DeleteRequest',
        'ShowCanceled' => 'ShowCanceled',
        'RejectRequest' => 'RejectRequest'
    ];

    public function RejectRequest($numeroRequste, settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) abort(404);
        $financialRequest = FinancialRequest::where('numeroReq', '=', $numeroRequste)->first();
        if (!$financialRequest) abort(404);
        if ($financialRequest->status != 0) abort(404);
        $detailReques = detail_financial_request::where('numeroRequest', '=', $numeroRequste)
            ->where('idUser', '=', $userAuth->idUser)
            ->first();
        if (!$detailReques) abort(404);
        detail_financial_request::where('numeroRequest', '=', $numeroRequste)
            ->where('idUser', '=', $userAuth->idUser)->update(['response' => 2, 'dateResponse' => date('Y-m-d H:i:s')]);

        $detailRest = detail_financial_request::where('numeroRequest', '=', $numeroRequste)
            ->where('response', '=', null)
            ->get();
        if (count($detailRest) == 0) {
            FinancialRequest::where('numeroReq', '=', $numeroRequste)
                ->update(['status' => 5, 'idUserAccepted' => $userAuth->idUser, 'dateAccepted' => date('Y-m-d H:i:s')]);
        }
    }

    public function ShowCanceled($val)
    {
        $this->showCanceled = $val;
        $this->fromTab = 'fromRequestOut';
        return redirect()->route('financial_transaction', ['locale' => app()->getLocale(), 'ShowCancel' => $val])->with('tabRequest', 'sdf');;
    }

    public function redirectToTransfertCash($mnt, $req)
    {
        return redirect()->route('financial_transaction', ['locale' => app()->getLocale(), 'montant' => $mnt, 'FinRequestN' => $req]);

    }

    public function mount(Request $request)
    {
        $val = $request->input('montant');
        $show = $request->input('ShowCancel');
        if ($val != null) {
            $this->soldeExchange = $val;
        }
        $numReq = $request->input('FinRequestN');
        if ($numReq != null) {
            $this->FinRequestN = $numReq;
        }
        if ($show != null) {
            $this->showCanceled = $show;
        }
    }

    public function AcceptRequest($numeroRequste)
    {
        $financialRequest = FinancialRequest::where('numeroReq', '=', $numeroRequste)->first();
        if (!$financialRequest) return;
        if ($financialRequest->status != 0) return;
        return redirect()->route('AcceptFinancialRequest', ['locale' => app()->getLocale(), 'numeroReq' => $numeroRequste]);

    }

    public function redirectPay($url, $amount)
    {
        switch ($url) {
            case 'paymentpaypal':
                return redirect()->route('paymentpaypal', ["locale" => app()->getLocale(), "amount" => $amount]);
                break;
            case 'paymentcreditcard' :
                return redirect()->route('paymentstrip', ["locale" => app()->getLocale(), "amount" => $amount]);
                break;
            case 'req_public_user' :
                return redirect()->route('RequesPublicUser', ["locale" => app()->getLocale(), "amount" => $amount]);
                break;

//            case 1:
//                echo "i equals 1";
//                break;
//            case 2:
//                echo "i equals 2";
//                break;
        }
//        dd("url: ".$url ."amount : ".$amount);
    }
    public function PExchangeSms(settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) return;
        $check_exchange = rand(1000, 9999);
        User::where('id', $userAuth->id)->update(['activationCodeValue' => $check_exchange]);
        $userContactActif = $settingsManager->getidCountryForSms($userAuth->id);
        $fullNumber = $userContactActif->fullNumber;
        $settingsManager->NotifyUser($userAuth->id, TypeEventNotificationEnum::OPTVerification, [
            'msg' => $check_exchange,
            'type' => TypeNotificationEnum::SMS
        ]);
        $this->dispatchBrowserEvent('confirmSms', [
            'tyepe' => 'warning',
            'title' => "Opt",
            'text' => '',
            'FullNumber' => $fullNumber,
        ]);
    }
    public function render(
        settingsManager $settingsManager, BalancesManager $balancesManager)
    {


        if ($this->showCanceled == null || $this->showCanceled == "")
            $this->showCanceled = 0;
        $this->getRequestIn($settingsManager);

        $userAuth = $settingsManager->getAuthUser();

        $this->mobile = $userAuth->fullNumber;
        $solde = $balancesManager->getBalances($userAuth->idUser);
        $this->soldecashB = floatval($solde->soldeCB)
            - floatval($this->soldeExchange);
        $this->soldeBFS = floatval($solde->soldeBFS)
            - floatval($this->numberSmsExchange);

        $seting = DB::table('settings')->where("idSETTINGS", "=", "13")->first();

        $this->prix_sms = $seting->IntegerValue;
        $this->montantSms = $this->prix_sms * $this->numberSmsExchange;

        number_format($this->soldecashB, 2, '.', ',');
        number_format($this->soldeBFS, 2, '.', ',');
//        dd($this->soldeBFS) ;

        $requestToMee = detail_financial_request::join('financial_request', 'financial_request.numeroReq', '=', 'detail_financial_request.numeroRequest')
            ->join('users', 'financial_request.idSender', '=', 'users.idUser')
            ->where('detail_financial_request.idUser', $userAuth->idUser)
            ->orderBy('financial_request.date', 'desc')
            ->get(['financial_request.numeroReq', 'financial_request.date', 'users.name', 'users.mobile', 'financial_request.amount', 'financial_request.status']);
//dd($requestToMee);

//        $requestFromMee = detail_financial_request::join('financial_request', 'financial_request.numeroReq', '=', 'detail_financial_request.numeroRequest')
//            ->join('users', 'financial_request.idSender', '=', 'users.idUser')
//            ->where('financial_request.idSender', $userAuth->idUser)
//            ->get(['financial_request.numeroReq', 'financial_request.date', 'users.name', 'users.mobile', 'financial_request.amount', 'financial_request.status']);
        if ($this->showCanceled == '1') {
            $requestFromMee = FinancialRequest::where('financial_request.idSender', $userAuth->idUser)
                ->join('users as u1', 'financial_request.idSender', '=', 'u1.idUser')
                ->with('details', 'details.User')
                ->orderBy('financial_request.date', 'desc')
                ->get(['financial_request.numeroReq', 'financial_request.date', 'u1.name', 'u1.mobile', 'financial_request.amount', 'financial_request.status as FStatus', 'financial_request.securityCode']);
        } else {
            $requestFromMee = FinancialRequest::where('financial_request.idSender', $userAuth->idUser)
                ->where('financial_request.Status', '!=', '3')
                ->join('users as u1', 'financial_request.idSender', '=', 'u1.idUser')
                ->with('details', 'details.User')
                ->orderBy('financial_request.date', 'desc')
                ->get(['financial_request.numeroReq', 'financial_request.date', 'u1.name', 'u1.mobile', 'financial_request.amount', 'financial_request.status as FStatus', 'financial_request.securityCode']);
        }


        $requestInOpen = detail_financial_request::join('financial_request', 'financial_request.numeroReq', '=', 'detail_financial_request.numeroRequest')
            ->where('detail_financial_request.idUser', $userAuth->idUser)
            ->where('financial_request.Status', 0)
            ->where('detail_financial_request.vu',0)
            ->count();
//        dd($requestInOpen);

        $requestOutAccepted = FinancialRequest::where('financial_request.idSender', $userAuth->idUser)
            ->where('financial_request.Status', 1)
            ->where('financial_request.vu',0)
            ->count();
        $requestOutRefused = FinancialRequest::where('financial_request.idSender', $userAuth->idUser)
            ->where('financial_request.Status', 5)
            ->where('financial_request.vu',0)
            ->count();

//            ->get();
//dd($requestFromMee);
//        dd($requestFromMee);
//        if ($this->fromTab == "fromRequestOut") {
//
//            return view('livewire.financial-transaction', [
//                'requestToMee' => $requestToMee,
//                'requestFromMee' => $requestFromMee
//            ])->with('fromRequestOut', 'ss');
//        }
        return view('livewire.financial-transaction', [
            'requestToMee' => $requestToMee,
            'requestFromMee' => $requestFromMee,
            'requestInOpen' => $requestInOpen,
            'requestOutAccepted' => $requestOutAccepted,
            'requestOutRefused' => $requestOutRefused
        ])->extends('layouts.master')->section('content');
    }
    public function PreExchange(settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) return;
        $check_exchange = rand(1000, 9999);
        User::where('id', $userAuth->id)->update(['activationCodeValue' => $check_exchange]);
        $userContactActif = $settingsManager->getidCountryForSms($userAuth->id);
        $fullNumber = $userContactActif->fullNumber;
        $settingsManager->NotifyUser($userAuth->id, TypeEventNotificationEnum::OPTVerification, [
            'msg' => $check_exchange,
            'type' => TypeNotificationEnum::SMS
        ]);
        $this->dispatchBrowserEvent('OptExBFSCash', [
            'tyepe' => 'warning',
            'title' => "Opt",
            'text' => '',
            'FullNumber' => $fullNumber,
        ]);
    }
    public function ExchangeCashToBFS($code, settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        $user = $settingsManager->getUserById($userAuth->id);
        if ($code != $user->activationCodeValue)
            return redirect()->route("financial_transaction", app()->getLocale())->with('ErrorOptCodeForget', 'Invalid OPT code');
        $settingsManager->exchange(
            ExchangeTypeEnum::CashToBFS,
            $settingsManager->getAuthUser()->idUser,
            $this->soldeExchange);
//        $this->emitTo('livewire-toast', 'show', 'Project Added Successfully'); //Will show Success Message

        if ($this->FinRequestN != null && $this->FinRequestN != '') {
            return redirect()->route('AcceptFinancialRequest', ['locale' => app()->getLocale(), 'numeroReq' => $this->FinRequestN]);
        }
        return redirect()->route('financial_transaction', app()->getLocale())->with('SuccesExchange',Lang::get('SuccesExchange'));
    }
    public function exchangeSms($code, $numberSms, settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        $user = $settingsManager->getUserById($userAuth->id);
        if ($code != $user->activationCodeValue)
            return redirect()->route("financial_transaction", app()->getLocale())->with('ErrorOptCodeForget', 'Invalid OPT code');
        $settingsManager->exchange(
            ExchangeTypeEnum::BFSToSMS,
            $settingsManager->getAuthUser()->idUser,
            $numberSms);
        return redirect()->route('financial_transaction', app()->getLocale())->with('succesOpttSms', ' OPT code');
    }
    public function getRequestIn($settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        $reqRequest = "select recharge_requests.Date , user.name user  ,
recharge_requests.payeePhone userphone, recharge_requests.amount  from recharge_requests
left join users user on user.idUser = recharge_requests.idPayee where recharge_requests.idUser = ? ";
        $request = DB::select($reqRequest, [$userAuth->idUser]);

    }

    public function getRequestOut($settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        $reqRequest = "select recharge_requests.Date , user.name user  ,
recharge_requests.payeePhone userphone, recharge_requests.amount  from recharge_requests
left join users user on user.idUser = recharge_requests.idPayee where recharge_requests.idSender = ? ";
        $request = DB::select($reqRequest, [$userAuth->idUser]);

    }


    public function DeleteRequest($num, settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) return;
        $financialRequest = FinancialRequest::where('numeroReq', '=', $num)->first();
        if (!$financialRequest) abort(404);
        if ($financialRequest->status != 0) abort(404);
        FinancialRequest::where('numeroReq', '=', $num)
            ->update(['status' => 3, 'idUserAccepted' => $userAuth->idUser, 'dateAccepted' => date('Y-m-d H:i:s')]);
        return redirect()->route('financial_transaction', app()->getLocale())->with('SuccesDelteAccepted', '');
    }


}


