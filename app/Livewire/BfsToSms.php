<?php

namespace App\Livewire;

use App\Models\BFSsBalances;
use App\Models\User;
use App\Services\Balances\Balances;
use Core\Enum\ExchangeTypeEnum;
use Core\Enum\TypeEventNotificationEnum;
use Core\Enum\TypeNotificationEnum;
use Core\Services\settingsManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class BfsToSms extends Component
{
    public $prix_sms = 0;
    public $montantSms = 0;
    public $numberSmsExchange = 0;
    public $testprop = 0;
    public $soldeBFS = 0;
    public $soldeExchange = 0;
    public $filter;

    protected $listeners = [
        'PreExchangeSMS' => 'PreExchangeSMS',
        'exchangeSms' => 'exchangeSms'
    ];

    public function mount($filter, Request $request)
    {
        $this->filter = $filter;
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

    public function PreExchangeSMS(settingsManager $settingsManager)
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

    public function exchangeSms($code, $numberSms, settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        $user = $settingsManager->getUserById($userAuth->id);
        if ($code != $user->activationCodeValue)
            return redirect()->route("financial_transaction", ['locale' => app()->getLocale(), 'filter' => 3])->with('danger', Lang::get('Invalid OPT code'));
        $settingsManager->exchange(ExchangeTypeEnum::BFSToSMS, $settingsManager->getAuthUser()->idUser, intval($numberSms));
        return redirect()->route('financial_transaction', ['locale' => app()->getLocale(), 'filter' => 3])->with('success', Lang::get('BFS to sms exchange operation seceded'));
    }

    public function render()
    {
        $seting = DB::table('settings')->where("idSETTINGS", "=", "13")->first();
        $this->prix_sms = $seting->DecimalValue ?? 1.5;
        $this->soldeBFS = floatval(Balances::getStoredBfss(auth()->user()->idUser, BFSsBalances::BFS_100)) - floatval($this->numberSmsExchange);
        $this->montantSms = $this->prix_sms * $this->numberSmsExchange;
        return view('livewire.bfs-to-sms');
    }
}
