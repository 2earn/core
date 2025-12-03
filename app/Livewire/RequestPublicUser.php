<?php

namespace App\Livewire;

use App\Notifications\FinancialRequestSent;
use App\Services\FinancialRequest\FinancialRequestService;
use App\Services\UserService;
use Core\Enum\StatusRequest;
use Core\Models\detail_financial_request;
use Core\Services\settingsManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class RequestPublicUser extends Component
{
    public $amount;
    public $selectedUsers = [];

    protected $listeners = [
        'send' => 'send',
        'sendFinancialRequest' => 'sendFinancialRequest'
    ];

    public function sendFinancialRequest(settingsManager $settingsManager)
    {
        if (!count($this->selectedUsers) > 0) {
            return redirect()->route('financial_transaction', ['locale' => app()->getLocale(), 'filter' => 2])->with('danger', Lang::get('No selected users'));
        };

        $userAuth = $settingsManager->getAuthUser();
        $securityCode = $settingsManager->randomNewCodeOpt();

        $financialRequestService = app(FinancialRequestService::class);
        $requestNumber = $financialRequestService->createFinancialRequest(
            $userAuth->idUser,
            $this->amount,
            $this->selectedUsers,
            $securityCode
        );

        Auth::user()->notify(new FinancialRequestSent());
        return redirect()->route('financial_transaction', ['locale' => app()->getLocale(), 'filter' => 2])->with('success', Lang::get('Financial request sent successfully ,This is your security code') . ' : ' . $securityCode);
    }

    public function send($idUser, settingsManager $settingsManager)
    {
        if ($idUser == null || $idUser == "") return;
        $user = $settingsManager->getUsers()->where('idUser', $idUser)->first();
        if (!$user) return;
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) return;
        $date = date(config('app.date_format'));
        DB::table('recharge_requests')
            ->insert([
                'Date' => $date,
                'idUser' => $user->idUser,
                'idPayee' => $userAuth->idUser,
                'userPhone' => $user->fullphone_number,
                'payeePhone' => $userAuth->fullNumber,
                'amount' => $this->amount,
                'validated' => 0,
                'type_user' => 2
            ]);
        return redirect()->route('financial_transaction', ['locale' => app()->getLocale(), 'filter' => 2])->with('success', Lang::get('Success send req to public user'));
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
