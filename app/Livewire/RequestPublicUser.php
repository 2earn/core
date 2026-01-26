<?php

namespace App\Livewire;

use App\Enums\StatusRequest;
use App\Notifications\FinancialRequestSent;
use App\Services\FinancialRequest\FinancialRequestService;
use App\Services\UserService;
use App\Services\settingsManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class RequestPublicUser extends Component
{
    public $amount;
    public $selectedUsers = [];

    protected $listeners = [
        'send' => 'send',
        'sendFinancialRequest' => 'sendFinancialRequest'
    ];

    protected FinancialRequestService $financialRequestService;

    public function boot(FinancialRequestService $financialRequestService)
    {
        $this->financialRequestService = $financialRequestService;
    }

    public function sendFinancialRequest(settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();

        // Use the service to handle the complete send logic
        $result = $this->financialRequestService->sendFinancialRequestWithNotification(
            $userAuth->idUser,
            $this->amount,
            $this->selectedUsers,
            Auth::user()
        );

        // Handle the result
        if ($result['success']) {
            $message = Lang::get($result['message']) . ' : ' . $result['securityCode'];
            return redirect()
                ->route($result['redirect'], array_merge(['locale' => app()->getLocale()], $result['redirectParams']))
                ->with($result['type'], $message);
        } else {
            return redirect()
                ->route($result['redirect'], array_merge(['locale' => app()->getLocale()], $result['redirectParams']))
                ->with($result['type'], Lang::get($result['message']));
        }
    }

    public function send($idUser, settingsManager $settingsManager)
    {
        if ($idUser == null || $idUser == "") return;
        $user = $settingsManager->getUsers()->where('idUser', $idUser)->first();
        if (!$user) return;
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) return;

        try {
            $this->financialRequestService->createRechargeRequest(
                $user->idUser,
                $userAuth->idUser,
                $user->fullphone_number,
                $userAuth->fullNumber,
                $this->amount,
                2 // type_user for public user
            );

            return redirect()
                ->route('financial_transaction', ['locale' => app()->getLocale(), 'filter' => 2])
                ->with('success', Lang::get('Success send req to public user'));
        } catch (\Exception $e) {
            Log::error('[RequestPublicUser] Error creating recharge request', [
                'userId' => $user->idUser,
                'payeeId' => $userAuth->idUser,
                'amount' => $this->amount,
                'error' => $e->getMessage()
            ]);

            return redirect()
                ->route('financial_transaction', ['locale' => app()->getLocale(), 'filter' => 2])
                ->with('danger', Lang::get('Failed to send request'));
        }
    }

    public function mount(Request $request)
    {
        $amount = $request->input('amount');
        $this->amount = $amount;
    }

    public function render(settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) abort(404);

        $userService = app(UserService::class);
        $users = $userService->getPublicUsers(
            $userAuth->idUser,
            $userAuth->idCountry,
            StatusRequest::OptValidated->value
        );

        return view('livewire.request-public-user', ['pub_users' => $users])->extends('layouts.master')->section('content');
    }
}
