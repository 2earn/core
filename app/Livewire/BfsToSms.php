<?php

namespace App\Livewire;

use App\Enums\ExchangeTypeEnum;
use App\Enums\TypeEventNotificationEnum;
use App\Enums\TypeNotificationEnum;
use App\Models\BFSsBalances;
use App\Models\User;
use App\Services\Balances\Balances;
use App\Services\UserService;
use App\Services\settingsManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class BfsToSms extends Component
{
    protected UserService $userService;

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

    public function boot(UserService $userService)
    {
        $this->userService = $userService;
    }

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

        $userContactActif = $settingsManager->getidCountryForSms($userAuth->id);
        $fullNumber = $userContactActif->fullNumber;

        // Prepare exchange verification using service
        $result = $this->userService->prepareExchangeVerification($userAuth->id, $fullNumber);

        if (!$result['success']) {
            return redirect()->route("financial_transaction", ['locale' => app()->getLocale(), 'filter' => 3])
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
        $this->dispatch('confirmSms', $result['verificationParams']);
    }

    public function exchangeSms($code, $numberSms, settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        $user = $settingsManager->getUserById($userAuth->id);

        // Verify OTP using service
        $result = $this->userService->verifyExchangeOtp(
            $userAuth->id,
            $code,
            $user->activationCodeValue
        );

        if (!$result['success']) {
            return redirect()->route("financial_transaction", ['locale' => app()->getLocale(), 'filter' => 3])
                ->with('danger', Lang::get($result['message']));
        }

        // Execute exchange operation
        $settingsManager->exchange(ExchangeTypeEnum::BFSToSMS, $userAuth->idUser, intval($numberSms));

        return redirect()->route('financial_transaction', ['locale' => app()->getLocale(), 'filter' => 3])
            ->with('success', Lang::get('BFS to sms exchange operation seceded'));
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
