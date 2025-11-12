<?php

namespace App\Livewire;


use App\Models\vip;
use Core\Models\Setting;
use Core\Services\BalancesManager;
use Core\Services\settingsManager;
use DateInterval;
use DateTime;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Home extends Component
{
    const MAX_AMOUNT = 99999999;
    const MAX_ACTIONS = 9999999;

    private settingsManager $settingsManager;
    private BalancesManager $balancesManager;
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


    public function mount(settingsManager $settingsManager, BalancesManager $balancesManager)
    {
        $this->settingsManager = $settingsManager;
        $this->balancesManager = $balancesManager;
    }

    public function getIp()
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
    }

    public function render(settingsManager $settingsManager, BalancesManager $balancesManager)
    {
        $user = $settingsManager->getAuthUser();
        delUsertransaction($user->idUser);
        if (!$user) {
            dd('not found page');
        }

        // Get user metadata
        $usermetta_info = collect(DB::table('metta_users')->where('idUser', $user->idUser)->first());

        // Get actual action value for flash sale calculation
        $actualActionValue = actualActionValue(getSelledActions(true), false);

        $params = [
            'usermetta_info' => $usermetta_info,
        ];

        // Check for VIP/Flash sale
        $this->vip = vip::Where('idUser', '=', $user->idUser)
            ->where('closed', '=', false)->first();


        if ($this->vip) {
            $setting = Setting::WhereIn('idSETTINGS', ['20', '18'])->orderBy('idSETTINGS')->pluck('IntegerValue');
            $max_bonus = $setting[0];
            $total_actions = $setting[1];
            $k = Setting::Where('idSETTINGS', '21')->orderBy('idSETTINGS')->pluck('DecimalValue')->first();
            $this->actions = find_actions($this->vip->solde, $total_actions, $max_bonus, $k, $this->vip->flashCoefficient);
            $this->benefices = ($this->vip->solde - find_actions($this->vip->solde, $total_actions, $max_bonus, $k, $this->vip->flashCoefficient)) * $actualActionValue;
            $this->cout = formatSolde($this->actions * $actualActionValue / (($this->actions * $this->vip->flashCoefficient) + getGiftedActions($this->actions)), 2);
            $this->flashTimes = $this->vip->flashCoefficient;
            $this->flashPeriod = $this->vip->flashDeadline;
            $this->flashDate = $this->vip->dateFNS;
            $this->flashMinShares = $this->vip->flashMinAmount;
            $currentDateTime = new DateTime();
            $dateFlash = new DateTime($this->flashDate);
            $interval = new DateInterval('PT' . $this->flashPeriod . 'H');
            $dateFlash = $dateFlash->add($interval);
            $this->flashDate = $dateFlash->format('F j, Y G:i:s');
            $this->flash = $currentDateTime < $dateFlash;
        }
        return view('livewire.home', $params)->extends('layouts.master')->section('content');
    }
}
