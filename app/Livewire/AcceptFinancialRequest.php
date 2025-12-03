<?php

namespace App\Livewire;

use App\Models\BFSsBalances;
use App\Services\Balances\Balances;
use App\Services\FinancialRequest\FinancialRequestService;
use Core\Enum\EventBalanceOperationEnum;
use Core\Services\BalancesManager;
use Core\Services\settingsManager;
use Core\Services\UserBalancesHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class AcceptFinancialRequest extends Component
{
    public $numeroReq;

    public $listeners = [
        'ConfirmRequest' => 'ConfirmRequest'
    ];

    public function mount(Request $request)
    {
        $this->numeroReq = $request->input('numeroReq');
    }

    public function ConfirmRequest($val, $num, $secCode, UserBalancesHelper $userBalancesHelper, settingsManager $settingsManager, BalancesManager $balancesManager, FinancialRequestService $financialRequestService)
    {

        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) return;

        $financialRequest = $financialRequestService->getByNumeroReq($num);
        if (!$financialRequest || $financialRequest->status != 0) {
            return redirect()->route('accept_financial_request', ['locale' => app()->getLocale(), 'numeroReq' => $num])->with('danger', Lang::get('Invalid financial request'));
        }
        if ($financialRequest->securityCode == "" || $financialRequest->securityCode != $secCode) {
            return redirect()->route('accept_financial_request', ['locale' => app()->getLocale(), 'numeroReq' => $num])->with('danger', Lang::get('Failed security code'));
        }
        $param = ['montant' => $financialRequest->amount, 'recipient' => $financialRequest->idSender];
        $userCurrentBalancehorisontal = Balances::getStoredUserBalances($userAuth->idUser);
        $bfs100 = $userCurrentBalancehorisontal->getBfssBalance(BFSsBalances::BFS_100);
        $cash = $userCurrentBalancehorisontal->cash_balance;
        $financialRequestAmount = floatval($financialRequest->amount);

        if ($val == 2) {
            if ($bfs100 < $financialRequestAmount) {
                $montant = $financialRequestAmount - $bfs100;
                return redirect()->route('financial_transaction', ['locale' => app()->getLocale(), 'filter' => 5, 'FinRequestN' => $financialRequest->numeroReq])->with('warning', trans('Insufficient BFS 100 balance') . ' : ' . $bfs100 . ' > ' . $montant);
            }
            $userBalancesHelper->AddBalanceByEvent(EventBalanceOperationEnum::SendToPublicFromBFS, $userAuth->idUser, $param);
        }

        $detailRequst = $financialRequestService->getDetailRequest($num, $userAuth->idUser);
        if (!$detailRequst) return;

        $financialRequestService->acceptFinancialRequest($num, $userAuth->idUser);

        return redirect()->route('financial_transaction', ['locale' => app()->getLocale(), 'filter' => 5])->with('success', Lang::get('accepted Request'));
    }


    public function render(settingsManager $settingsManager, BalancesManager $balancesManager, FinancialRequestService $financialRequestService)
    {
        $soldeUser= floatval(Balances::getStoredBfss(auth()->user()->idUser, BFSsBalances::BFS_100));

        $financialRequest = $financialRequestService->getRequestWithUserDetails($this->numeroReq);

        if (!$financialRequest) abort(404);
        if ($financialRequest->status != 0) abort(404);
        $params = [
            'financialRequest' => $financialRequest,
            'soldeUser' => $soldeUser
        ];
        return view('livewire.accept-financial-request', $params)->extends('layouts.master')->section('content');
    }
}
