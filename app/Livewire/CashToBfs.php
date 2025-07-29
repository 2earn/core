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

class CashToBfs extends Component
{
    public $soldecashB;
    public $soldeBFS;
    public $soldeExchange;
    public $numberSmsExchange = 0;
    public $FinRequestN;
    public $prix_sms;
    public $filter;
    public $newBfsSolde;
    public $ernedDiscount;

    protected $listeners = [
        'PreExchange' => 'PreExchange',
        'ExchangeCashToBFS' => 'ExchangeCashToBFS',
    ];

    public function mount($filter, Request $request)
    {
        $this->filter = is_null($filter) ? 1 : $filter;
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
        $seting = DB::table('settings')->where("idSETTINGS", "=", "13")->first();

        $this->prix_sms = $seting->DecimalValue ?? 1.5;
    }

    public function ExchangeCashToBFS($code, settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        $user = $settingsManager->getUserById($userAuth->id);
        if ($code != $user->activationCodeValue)
            return redirect()->route("financial_transaction", ['locale' => app()->getLocale(), 'filter' => 1])->with('danger', Lang::get('Invalid OPT code'));
        $settingsManager->exchange(ExchangeTypeEnum::CashToBFS, $settingsManager->getAuthUser()->idUser, floatval($this->soldeExchange));
        if ($this->FinRequestN != null && $this->FinRequestN != '') {
            return redirect()->route('accept_financial_request', ['locale' => app()->getLocale(), 'numeroReq' => $this->FinRequestN]);
        }
        return redirect()->route('financial_transaction', ['locale' => app()->getLocale(), 'filter' => 1])->with('success', Lang::get('Success CASH to BFS exchange'));
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


    public function updatetheSoldeExchange()
    {
        $balances = Balances::getStoredUserBalances(auth()->user()->idUser);
        $this->soldeBFS = floatval(Balances::getStoredBfss(auth()->user()->idUser, BFSsBalances::BFS_100)) - floatval($this->numberSmsExchange);
        $this->newBfsSolde = $this->soldeBFS + floatval( $this->soldeExchange);
        $this->ernedDiscount = Balances::getDiscountEarnedFromBFS100I(floatval($this->soldeExchange));

    }

    public function render()
    {

        $this->soldecashB = floatval(Balances::getStoredUserBalances(auth()->user()->idUser, Balances::CASH_BALANCE)) - floatval($this->soldeExchange);
        $this->soldeBFS = floatval(Balances::getStoredBfss(auth()->user()->idUser, BFSsBalances::BFS_100)) - floatval($this->numberSmsExchange);
        $this->updatetheSoldeExchange();
        return view('livewire.cash-to-bfs');
    }
}
