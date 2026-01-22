<?php

namespace App\Livewire;


use App\Services\Settings\SettingService;
use App\Services\BalancesManager;
use App\Services\settingsManager as SettingsManager;
use App\Services\VipService;
use App\Services\MettaUsersService;
use Illuminate\View\View;
use Livewire\Component;

class Home extends Component
{
    const MAX_AMOUNT = 99999999;
    const MAX_ACTIONS = 9999999;

    private SettingsManager $settingsManager;
    private BalancesManager $balancesManager;
    private SettingService $settingService;
    private VipService $vipService;
    private MettaUsersService $mettaUsersService;
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


    public function mount(
        SettingsManager $settingsManager,
        BalancesManager $balancesManager,
        SettingService $settingService,
        VipService $vipService,
        MettaUsersService $mettaUsersService
    )
    {
        $this->settingsManager = $settingsManager;
        $this->balancesManager = $balancesManager;
        $this->settingService = $settingService;
        $this->vipService = $vipService;
        $this->mettaUsersService = $mettaUsersService;
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

        $userMetaInfo = $this->mettaUsersService->getMettaUserInfo($user->idUser);

        $actualActionValue = actualActionValue(getSelledActions(true), false);

        $params = [
            'usermetta_info' => $userMetaInfo,
        ];

        $this->vip = $this->vipService->getActiveVipByUserId($user->idUser);

        if ($this->vip) {
            $vipIntegerValues = $this->settingService->getIntegerValues(['20', '18']);
            $maxBonus = $vipIntegerValues['20'] ?? 0;
            $totalActions = $vipIntegerValues['18'] ?? 0;
            $flashCoefficient = $this->settingService->getDecimalValue('21') ?? 0.0;

            $vipCalculations = $this->vipService->getVipCalculations(
                $this->vip,
                $totalActions,
                $maxBonus,
                $flashCoefficient,
                $actualActionValue
            );

            $this->actions = $vipCalculations['actions'];
            $this->benefices = $vipCalculations['benefits'];
            $this->cout = $vipCalculations['cost'];

            $flashStatus = $vipCalculations['flashStatus'];
            $this->flashTimes = $flashStatus['flashTimes'];
            $this->flashPeriod = $flashStatus['flashPeriod'];
            $this->flashDate = $flashStatus['flashEndDate'];
            $this->flashMinShares = $flashStatus['flashMinShares'];
            $this->flash = $flashStatus['isFlashActive'];
        }

        return view('livewire.home', $params)->extends('layouts.master')->section('content');
    }
}
