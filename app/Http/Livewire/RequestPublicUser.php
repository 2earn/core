<?php

namespace App\Http\Livewire;

use App\Models\User;
use Core\Enum\RequestStatus;
use Core\Enum\StatusRequest;
use Core\Models\detail_financial_request;
use Core\Services\settingsManager;
use Illuminate\Http\Request;
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
        if (!count($this->selectedUsers) > 0) return;
        $userAuth = $settingsManager->getAuthUser();
        $lastnumero = 0;
        $lastRequest = DB::table('financial_request')
            ->latest('numeroReq')
            ->first();
        if ($lastRequest) {
            $lastnumero = $lastRequest->numeroReq;
        }
        $date = date('Y-m-d H:i:s');
        $year = date('y', strtotime($date));
        $numer = (int)substr($lastnumero, -6);
        $ref = date('y') . substr((1000000 + $numer + 1), 1, 6);
        $data = [];
        foreach ($this->selectedUsers as $val) {
            if ($val != $userAuth->idUser) {
                $new = ['numeroRequest' => $ref, 'idUser' => $val];
                array_push($data, $new);
            }
        }
        detail_financial_request::insert($data);
        $securityCode = $settingsManager->randomNewCodeOpt();
        DB::table('financial_request')
            ->insert([
                'numeroReq' => $ref,
                'idSender' => $userAuth->idUser,
                'Date' => $date,
                'amount' => $this->amount,
                'status' => '0',
                'securityCode' => $securityCode
            ]);
        return redirect()->route('financial_transaction', app()->getLocale())->with('success', $securityCode);
    }

    public function send($idUser, settingsManager $settingsManager)
    {
        if ($idUser == null || $idUser == "") return;
        $user = $settingsManager->getUsers()->where('idUser', $idUser)->first();
        if (!$user) return;
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) return;
        $date = date('Y-m-d H:i:s');
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
        return redirect()->route('financial_transaction', app()->getLocale())->with('success', Lang::get('SuccesSendReqPublicUser'));
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
        $users = User::where('is_public', 1)
            ->where('idUser', '<>', $userAuth->idUser)
            ->where('idCountry', $userAuth->idCountry)
            ->where('status', '>=',StatusRequest::ValidNational)
            ->get();

        return view('livewire.request-public-user', ['pub_users' => $users])->extends('layouts.master')->section('content');
    }
}
