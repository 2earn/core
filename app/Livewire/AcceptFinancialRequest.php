<?php

namespace App\Livewire;

use App\Models\BFSsBalances;
use App\Services\Balances\Balances;
use App\Services\FinancialRequest\FinancialRequestService;
use App\Services\BalancesManager;
use App\Services\settingsManager;
use App\Services\UserBalances\UserBalancesHelper;
use Illuminate\Http\Request;
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

        // Delegate all logic to the service
        $result = $financialRequestService->confirmFinancialRequest(
            $val,
            $num,
            $secCode,
            $userAuth->idUser,
            $userBalancesHelper
        );

        // Handle the result
        if (!$result['success']) {
            return redirect()->route(
                $result['redirect'],
                array_merge(['locale' => app()->getLocale()], $result['redirectParams'])
            )->with($result['type'], $result['message']);
        }

        return redirect()->route(
            $result['redirect'],
            array_merge(['locale' => app()->getLocale()], $result['redirectParams'])
        )->with($result['type'], $result['message']);
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
