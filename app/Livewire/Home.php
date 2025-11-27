<?php

namespace App\Livewire;


use App\Models\vip as Vip;
use App\Services\Settings\SettingService;
use Core\Services\BalancesManager;
use Core\Services\settingsManager as SettingsManager;
use DateInterval;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;

class Home extends Component
{
    const MAX_AMOUNT = 99999999;
    const MAX_ACTIONS = 9999999;

    private SettingsManager $settingsManager;
    private BalancesManager $balancesManager;
    private SettingService $settingService;
    public $ammount;
    public $ammountReal;
    public $action;
    public $gift;
    public $profit;
    public $flashGift = 0;
    public $flashTimes = 1;
    public $flashPeriod;
    public $flashDate;
    public $flashMinShares = -1;
    public $maxActions;

    public $flashGain = 0;

    public $flash = false;
    public $hasFlashAmount = 0;
    public $vip = 0;
    public $actions = 0;
    public $benefices = 0;
    public $cout = 0;

    protected $listeners = [
        'checkContactNumbre' => 'checkContactNumbre',
        'simulate' => 'simulate'
    ];


    public function mount(SettingsManager $settingsManager, BalancesManager $balancesManager, SettingService $settingService)
    {
        $this->settingsManager = $settingsManager;
        $this->balancesManager = $balancesManager;
        $this->settingService = $settingService;
    }

    public function getIp(): ?string
    {
        foreach (['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'] as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ipAddress) {
                    $ipAddress = trim($ipAddress);
                    if (filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ipAddress;
                    }
                }
            }
        }

        return null;
    }

    public function render(): View
    {
        $user = $this->settingsManager->getAuthUser();

        if (!$user) {
            abort(404, 'User not found');
        }

        delUsertransaction($user->idUser);

        $userMetaInfo = collect(DB::table('metta_users')->where('idUser', $user->idUser)->first());

        $actualActionValue = actualActionValue(getSelledActions(true), false);

        $params = [
            'usermetta_info' => $userMetaInfo,
        ];

        $this->vip = Vip::where('idUser', '=', $user->idUser)
            ->where('closed', '=', false)->first();

        if ($this->vip) {
            $vipIntegerValues = $this->settingService->getIntegerValues(['20', '18']);
            $maxBonus = $vipIntegerValues['20'] ?? 0;
            $totalActions = $vipIntegerValues['18'] ?? 0;
            $flashCoefficient = $this->settingService->getDecimalValue('21') ?? 0.0;

            $this->actions = find_actions(
                $this->vip->solde,
                $totalActions,
                $maxBonus,
                $flashCoefficient,
                $this->vip->flashCoefficient
            );

            $this->benefices = (
                $this->vip->solde -
                find_actions($this->vip->solde, $totalActions, $maxBonus, $flashCoefficient, $this->vip->flashCoefficient)
            ) * $actualActionValue;

            $this->cout = formatSolde(
                $this->actions * $actualActionValue /
                (($this->actions * $this->vip->flashCoefficient) + getGiftedActions($this->actions)),
                2
            );

            $this->flashTimes = $this->vip->flashCoefficient;
            $this->flashPeriod = $this->vip->flashDeadline;
            $this->flashDate = $this->vip->dateFNS;
            $this->flashMinShares = $this->vip->flashMinAmount;

            $currentDateTime = new DateTime();
            $flashWindowEnd = (new DateTime($this->flashDate))
                ->add(new DateInterval('PT' . $this->flashPeriod . 'H'));

            $this->flashDate = $flashWindowEnd->format('F j, Y G:i:s');
            $this->flash = $currentDateTime < $flashWindowEnd;
        }

        return view('livewire.home', $params)->extends('layouts.master')->section('content');
    }
}
