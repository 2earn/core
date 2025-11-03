<?php


namespace App\Livewire;

use App\Models\BFSsBalances;
use App\Services\Balances\Balances;
use Core\Models\detail_financial_request;
use Core\Models\FinancialRequest;
use Core\Services\BalancesManager;
use Core\Services\settingsManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FinancialTransaction extends Component
{

    const DATE_FORMAT = 'Y-m-d H:i:s';

    public $soldecashB;
    public $soldeBFS;
    public $soldeExchange = 0;
    public $numberSmsExchange = 0;
    public $mobile;
    public $FinRequestN;
    public $fromTab;
    public $showCanceled;
    public $filter;

    protected $listeners = [
        'exchangeSms' => 'exchangeSms',
        'redirectPay', 'redirectPay',
        'redirectToTransfertCash' => 'redirectToTransfertCash',
        'ShowCanceled' => 'ShowCanceled',
        'RejectRequest' => 'RejectRequest', 'refreshChildren' => '$refresh'
    ];

    public function mount(Request $request)
    {
        $this->filter = $request->route('filter');
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


    public function ShowCanceled($val)
    {
        $this->showCanceled = $val;
        $this->fromTab = 'fromRequestOut';
        return redirect()->route('financial_transaction', ['locale' => app()->getLocale(), 'ShowCancel' => $val])->with('info', trans('Show cancelled requests'));
    }

    public function redirectToTransfertCash($mnt, $req)
    {
        return redirect()->route('financial_transaction', ['locale' => app()->getLocale(), 'montant' => $mnt, 'FinRequestN' => $req]);
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

    public function render(settingsManager $settingsManager, BalancesManager $balancesManager)
    {
        if ($this->showCanceled == null || $this->showCanceled == "") {
            $this->showCanceled = 0;
        }
        $userAuth = $settingsManager->getAuthUser();

        $this->getRequestIn($settingsManager);
        $this->mobile = $userAuth->fullNumber;
        $this->soldecashB = floatval(Balances::getStoredUserBalances(auth()->user()->idUser, Balances::CASH_BALANCE)) - floatval($this->soldeExchange);
        $this->soldeBFS = floatval(Balances::getStoredBfss(auth()->user()->idUser, BFSsBalances::BFS_100)) - floatval($this->numberSmsExchange);


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


