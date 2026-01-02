<?php


namespace App\Livewire;

use App\Models\BFSsBalances;
use App\Services\Balances\Balances;
use App\Services\FinancialRequest\FinancialRequestService;
use App\Services\BalancesManager;
use App\Services\settingsManager;
use Illuminate\Http\Request;
use Livewire\Component;

class FinancialTransaction extends Component
{


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
        'redirectToTransferCash' => 'redirectToTransferCash',
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

    public function redirectToTransferCash($mnt, $req)
    {
        return redirect()->route('financial_transaction', ['locale' => app()->getLocale(), 'montant' => $mnt, 'FinRequestN' => $req]);
    }


    public function render(settingsManager $settingsManager, BalancesManager $balancesManager, FinancialRequestService $financialRequestService)
    {
        if ($this->showCanceled == null || $this->showCanceled == "") {
            $this->showCanceled = 0;
        }
        $userAuth = $settingsManager->getAuthUser();

        $this->mobile = $userAuth->fullNumber;
        $this->soldecashB = floatval(Balances::getStoredUserBalances(auth()->user()->idUser, Balances::CASH_BALANCE)) - floatval($this->soldeExchange);
        $this->soldeBFS = floatval(Balances::getStoredBfss(auth()->user()->idUser, BFSsBalances::BFS_100)) - floatval($this->numberSmsExchange);


        number_format($this->soldecashB, 2, '.', ',');
        number_format($this->soldeBFS, 2, '.', ',');

        $requestFromMee = $financialRequestService->getRequestsFromUser($userAuth->idUser, $this->showCanceled == '1');

        $params = [
            'requestToMee' => $financialRequestService->getRequestsToUser($userAuth->idUser),
            'requestFromMee' => $requestFromMee,
            'requestInOpen' => $financialRequestService->countRequestsInOpen($userAuth->idUser),
            'requestOutAccepted' => $financialRequestService->countRequestsOutAccepted($userAuth->idUser),
            'requestOutRefused' => $financialRequestService->countRequestsOutRefused($userAuth->idUser)
        ];

        return view('livewire.financial-transaction', $params)->extends('layouts.master')->section('content');
    }
}


