<?php


namespace App\Livewire;

use App\Models\BFSsBalances;
use App\Models\User;
use App\Services\Balances\Balances;
use Core\Enum\ExchangeTypeEnum;
use Core\Enum\TypeEventNotificationEnum;
use Core\Enum\TypeNotificationEnum;
use Core\Models\detail_financial_request;
use Core\Models\FinancialRequest;
use Core\Services\BalancesManager;
use Core\Services\settingsManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class FinancialTransaction extends Component
{

    const DATE_FORMAT = 'Y-m-d H:i:s';

    public $testprop = 0;
    public $soldecashB;
    public $soldeBFS;
    public $soldeExchange = 0;
    public $newBfsSolde = 0;
    public $numberSmsExchange = 0;
    public $prix_sms = 0;
    public $montantSms = 0;
    public $mobile;
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


    public function updatedSoldeExchange($value)
    {
        $this->newBfsSolde = $value;
    }

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
            ->where('idUser', '=', $userAuth->idUser)
            ->update(['response' => 2, 'dateResponse' => date(self::DATE_FORMAT)]);

        $detailRest = detail_financial_request::where('numeroRequest', '=', $numeroRequste)
            ->where('response', '=', null)
            ->get();
        if (count($detailRest) == 0) {
            FinancialRequest::where('numeroReq', '=', $numeroRequste)
                ->update(['status' => 5, 'idUserAccepted' => $userAuth->idUser, 'dateAccepted' => date(self::DATE_FORMAT)]);
        }
    }

    public function ShowCanceled($val)
    {
        $this->showCanceled = $val;
        $this->fromTab = 'fromRequestOut';
        return redirect()->route('financial_transaction', ['locale' => app()->getLocale(), 'ShowCancel' => $val])->with('info', 'sdf');
    }

    public function redirectToTransfertCash($mnt, $req)
    {
        return redirect()->route('financial_transaction', ['locale' => app()->getLocale(), 'montant' => $mnt, 'FinRequestN' => $req]);
    }


    public function AcceptRequest($numeroRequste)
    {
        $financialRequest = FinancialRequest::where('numeroReq', '=', $numeroRequste)->first();
        if (!$financialRequest) return;
        if ($financialRequest->status != 0) return;
        return redirect()->route('accept_financial_request', ['locale' => app()->getLocale(), 'numeroReq' => $numeroRequste]);
    }

    public function redirectPay($url, $amount)
    {
        switch ($url) {
            case 'paymentpaypal':
                return redirect()->route('payment_paypal', ["locale" => app()->getLocale(), "amount" => $amount]);
                break;
            case 'paymentcreditcard' :
                return redirect()->route('payment_strip', ["locale" => app()->getLocale(), "amount" => $amount]);
                break;
            case 'req_public_user' :
                return redirect()->route('user_request_public', ["locale" => app()->getLocale(), "amount" => $amount]);
                break;
        }
    }

    public function PExchangeSms(settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) return;
        $check_exchange = rand(1000, 9999);
        User::where('id', $userAuth->id)->update(['activationCodeValue' => $check_exchange]);
        $userContactActif = $settingsManager->getidCountryForSms($userAuth->id);
        $fullNumber = $userContactActif->fullNumber;
        $settingsManager->NotifyUser($userAuth->id, TypeEventNotificationEnum::OPTVerification, ['msg' => $check_exchange, 'type' => TypeNotificationEnum::SMS]);
        $this->dispatch('confirmSms', ['type' => 'warning', 'title' => "Opt", 'text' => '', 'FullNumber' => $fullNumber]);
    }


    public function PreExchange(settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) return;
        $check_exchange = rand(1000, 9999);
        User::where('id', $userAuth->id)->update(['activationCodeValue' => $check_exchange]);
        $userContactActif = $settingsManager->getidCountryForSms($userAuth->id);
        $fullNumber = $userContactActif->fullNumber;
        $settingsManager->NotifyUser($userAuth->id, TypeEventNotificationEnum::OPTVerification, ['msg' => $check_exchange, 'type' => TypeNotificationEnum::SMS]);
        $this->dispatch('OptExBFSCash', ['type' => 'warning', 'title' => "Opt", 'text' => '', 'FullNumber' => $fullNumber]);
    }

    public function ExchangeCashToBFS($code, settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        $user = $settingsManager->getUserById($userAuth->id);
        if ($code != $user->activationCodeValue)
            return redirect()->route("financial_transaction", app()->getLocale())->with('danger', Lang::get('Invalid OPT code'));
        $settingsManager->exchange(ExchangeTypeEnum::CashToBFS, $settingsManager->getAuthUser()->idUser, floatval($this->soldeExchange));
        if ($this->FinRequestN != null && $this->FinRequestN != '') {
            return redirect()->route('accept_financial_request', ['locale' => app()->getLocale(), 'numeroReq' => $this->FinRequestN]);
        }
        return redirect()->route('financial_transaction', app()->getLocale())->with('success', Lang::get('Success CASH to BFS exchange'));
    }

    public function exchangeSms($code, $numberSms, settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        $user = $settingsManager->getUserById($userAuth->id);
        if ($code != $user->activationCodeValue)
            return redirect()->route("financial_transaction", app()->getLocale())->with('danger', Lang::get('Invalid OPT code'));
        $settingsManager->exchange(ExchangeTypeEnum::BFSToSMS, $settingsManager->getAuthUser()->idUser, intval($numberSms));
        return redirect()->route('financial_transaction', app()->getLocale())->with('success', Lang::get('BFS to sms exchange operation seceded'));
    }

    public function getRequestIn()
    {
        $rechargeRequests = DB::table('recharge_requests')->select('recharge_requests.Date', 'users.name as USER', 'recharge_requests.payeePhone as userphone', 'recharge_requests.amount')
            ->leftJoin('users', 'users.idUser', '=', 'recharge_requests.idPayee')
            ->where('recharge_requests.idUser', auth()->user()->idUser)->get();
    }

    public function getRequestOut()
    {
        $rechargeRequests = DB::table('recharge_requests')
            ->select('recharge_requests.Date', 'users.name as USER', 'recharge_requests.payeePhone as userphone', 'recharge_requests.amount')
            ->leftJoin('users', 'users.idUser', '=', 'recharge_requests.idPayee')
            ->where('recharge_requests.idSender', auth()->user()->idUser)->get();
    }


    public function DeleteRequest($num, settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) return;
        $financialRequest = FinancialRequest::where('numeroReq', '=', $num)->first();
        if (!$financialRequest) abort(404);
        if ($financialRequest->status != 0) abort(404);
        FinancialRequest::where('numeroReq', '=', $num)
            ->update(['status' => 3, 'idUserAccepted' => $userAuth->idUser, 'dateAccepted' => date(self::DATE_FORMAT)]);
        return redirect()->route('financial_transaction', app()->getLocale())->with('success', Lang::get('Delete request accepted'));
    }

    public function render(settingsManager $settingsManager, BalancesManager $balancesManager)
    {
        if ($this->showCanceled == null || $this->showCanceled == "") {
            $this->showCanceled = 0;
        }
        $this->getRequestIn($settingsManager);
        $userAuth = $settingsManager->getAuthUser();
        $this->mobile = $userAuth->fullNumber;
        $this->soldecashB = floatval(Balances::getStoredUserBalances(auth()->user()->idUser, Balances::CASH_BALANCE)) - floatval($this->soldeExchange);
        $this->soldeBFS = floatval(Balances::getStoredBfss(auth()->user()->idUser, BFSsBalances::BFS_100)) - floatval($this->numberSmsExchange);

        $seting = DB::table('settings')->where("idSETTINGS", "=", "13")->first();

        $this->prix_sms = $seting->DecimalValue ?? 1.5;

        $this->montantSms = $this->prix_sms * $this->numberSmsExchange;

        number_format($this->soldecashB, 2, '.', ',');
        number_format($this->soldeBFS, 2, '.', ',');


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

        $params = [
            'requestToMee' => detail_financial_request::join('financial_request', 'financial_request.numeroReq', '=', 'detail_financial_request.numeroRequest')
                ->join('users', 'financial_request.idSender', '=', 'users.idUser')
                ->where('detail_financial_request.idUser', $userAuth->idUser)
                ->orderBy('financial_request.date', 'desc')
                ->get(['financial_request.numeroReq', 'financial_request.date', 'users.name', 'users.mobile', 'financial_request.amount', 'financial_request.status'])
            ,
            'requestFromMee' => $requestFromMee,
            'requestInOpen' => detail_financial_request::join('financial_request', 'financial_request.numeroReq', '=', 'detail_financial_request.numeroRequest')
                ->where('detail_financial_request.idUser', $userAuth->idUser)
                ->where('financial_request.Status', 0)
                ->where('detail_financial_request.vu', 0)
                ->count(),
            'requestOutAccepted' => FinancialRequest::where('financial_request.idSender', $userAuth->idUser)
                ->where('financial_request.Status', 1)
                ->where('financial_request.vu', 0)
                ->count(),
            'requestOutRefused' => FinancialRequest::where('financial_request.idSender', $userAuth->idUser)
                ->where('financial_request.Status', 5)
                ->where('financial_request.vu', 0)
                ->count()
        ];
        return view('livewire.financial-transaction', $params)->extends('layouts.master')->section('content');
    }

}


