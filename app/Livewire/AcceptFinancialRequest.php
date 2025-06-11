<?php

namespace App\Livewire;

use App\Models\BFSsBalances;
use App\Services\Balances\Balances;
use Core\Enum\EventBalanceOperationEnum;
use Core\Models\detail_financial_request;
use Core\Models\FinancialRequest;
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
        'Confirmrequest' => 'Confirmrequest'
    ];

    public function Confirmrequest($val, $num, $secCode, UserBalancesHelper $userBalancesHelper, settingsManager $settingsManager, BalancesManager $balancesManager)
    {

        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) return;
        $financialRequest = FinancialRequest::where('numeroReq', '=', $num)->first();
        if (!$financialRequest) abort(404);
        if ($financialRequest->status != 0) abort(404);
        if ($financialRequest->securityCode == "") abort(404);
        if ($financialRequest->securityCode != $secCode) {
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
                return redirect()->route('financial_transaction', ['locale' => app()->getLocale(), 'FinRequestN' => $financialRequest->numeroReq])->with('warning', trans('Insufficient BFS 100 balance') . ' : ' . $bfs100 . ' > ' . $montant);
            }
            $userBalancesHelper->AddBalanceByEvent(EventBalanceOperationEnum::SendToPublicFromBFS, $userAuth->idUser, $param);
        }

        $detailRequst = detail_financial_request::where('numeroRequest', '=', $num)->where('idUser', '=', $userAuth->idUser)->first();
        if (!$detailRequst) return;
        detail_financial_request::where('numeroRequest', '=', $num)->where('response', '=', null)
            ->update(['response' => 3, 'dateResponse' => date('Y-m-d H:i:s')]);
        detail_financial_request::where('numeroRequest', '=', $num)->where('idUser', '=', $userAuth->idUser)
            ->update(['response' => 1, 'dateResponse' => date('Y-m-d H:i:s')]);
        FinancialRequest::where('numeroReq', '=', $num)
            ->update(['status' => 1, 'idUserAccepted' => $userAuth->idUser, 'dateAccepted' => date('Y-m-d H:i:s')]);
        return redirect()->route('financial_transaction', app()->getLocale())->with('success', Lang::get('accepted Request'));
    }

    public function mount(Request $request)
    {
        $this->numeroReq = $request->input('numeroReq');
    }

    public function render(settingsManager $settingsManager, BalancesManager $balancesManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        $soldeUser = $balancesManager->getBalances($userAuth->idUser, -1);

        $financialRequest = FinancialRequest::join('users', 'financial_request.idSender', '=', 'users.idUser')
            ->where('numeroReq', '=', $this->numeroReq)
            ->get(['financial_request.numeroReq', 'financial_request.date', 'users.name', 'users.mobile', 'financial_request.amount', 'financial_request.status'])
            ->first();

        if (!$financialRequest) abort(404);
        if ($financialRequest->status != 0) abort(404);
        return view('livewire.accept-financial-request', [
            'financialRequest' => $financialRequest,
            'soldeUser' => $soldeUser
        ])->extends('layouts.master')->section('content');
    }
}
