<?php

namespace App\Livewire;

use App\Enums\ExchangeTypeEnum;
use App\Enums\TypeEventNotificationEnum;
use App\Enums\TypeNotificationEnum;
use App\Models\BFSsBalances;
use App\Models\User;
use App\Services\Balances\Balances;
use App\Services\CashService;
use App\Services\Settings\SettingService;
use App\Services\settingsManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class CashToBfs extends Component
{
    protected CashService $cashService;

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

    public function boot(CashService $cashService)
    {
        $this->cashService = $cashService;
    }

    public function mount($filter, Request $request, SettingService $settingService)
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

        $this->prix_sms = $settingService->getDecimalValue(13) ?? 1.5;
    }

    public function ExchangeCashToBFS($code, settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        $user = $settingsManager->getUserById($userAuth->id);

        // Verify OTP using service
        $result = $this->cashService->verifyCashToBfsExchange(
            $userAuth->id,
            $code,
            $user->activationCodeValue
        );

        if (!$result['success']) {
            return redirect()->route("financial_transaction", ['locale' => app()->getLocale(), 'filter' => 1])
                ->with('danger', Lang::get($result['message']));
        }

        // Execute exchange operation
        $settingsManager->exchange(ExchangeTypeEnum::CashToBFS, $userAuth->idUser, floatval($this->soldeExchange));

        // Handle financial request if present
        if ($this->FinRequestN != null && $this->FinRequestN != '') {
            return redirect()->route('accept_financial_request', ['locale' => app()->getLocale(), 'numeroReq' => $this->FinRequestN]);
        }

        // Send notification
        $user->notify(new \App\Notifications\CashToBfs());

        return redirect()->route('financial_transaction', ['locale' => app()->getLocale(), 'filter' => 1])
            ->with('success', Lang::get('Success CASH to BFS exchange'));
    }

    public function PreExchange(settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) return;

        $userContactActif = $settingsManager->getidCountryForSms($userAuth->id);
        $fullNumber = $userContactActif->fullNumber;

        // Prepare exchange verification using service
        $result = $this->cashService->prepareCashToBfsExchange($userAuth->id, $fullNumber);

        if (!$result['success']) {
            return redirect()->route("financial_transaction", ['locale' => app()->getLocale(), 'filter' => 1])
                ->with('danger', Lang::get($result['message']));
        }

        // Send SMS notification with OTP
        if ($result['shouldNotifyBySms']) {
            $settingsManager->NotifyUser(
                $userAuth->id,
                TypeEventNotificationEnum::OPTVerification,
                ['msg' => $result['otpCode'], 'type' => TypeNotificationEnum::SMS]
            );
        }

        // Dispatch Livewire event with verification params
        $this->dispatch('OptExBFSCash', $result['verificationParams']);
    }


    public function updatetheSoldeExchange()
    {
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
