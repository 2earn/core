<?php

namespace App\Http\Livewire;


use App\Http\Traits\contactNumberCheker;
use Core\Services\BalancesManager;
use Core\Services\settingsManager;
use DateInterval;
use DateTime;
use Illuminate\Http\Request as Req;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

class Home extends Component
{
    public $cashBalance;
    public $balanceForSopping;
    public $discountBalance;
    public $SMSBalance;
    public $currency = '$';
    public $decimalSeperator = '.';
    public $actionsValues = 0;
    public $userSelledAction = 0;
    public $userActualActionsProfit = 0;
    private settingsManager $settingsManager;
    private BalancesManager $balancesManager;
    public $ammount;
    public $action;
    public $gift;
    public $profit;
    public $flashGift = 0;
    public $flashTimes=1;
    public $flashPeriod;
    public $flashDate;
    public $flashMinShares=-1;
    public $maxActions;

    public $flashGain = 0;

    public $flash = false;
    public $hasFlashAmount = 0;


    protected $listeners = [
        'checkContactNumbre' => 'checkContactNumbre',
        'simulate' => 'simulate'
    ];


    public function mount(settingsManager $settingsManager, BalancesManager $balancesManager)
    {
        $this->settingsManager = $settingsManager;
        $this->balancesManager = $balancesManager;
    }

    public function simulateAction()
    {
        if ($this->action < 0 && $this->action == "") {
            $this->action = 0;
        }
        if ($this->action > $this->maxActions) {
            $this->action = $this->maxActions;
        }

        $this->ammount = round($this->action * actualActionValue(getSelledActions()), 3);
        $this->getCommounSimulation();
    }

    public function simulateAmmount()
    {
        if ($this->ammount < 0 && $this->ammount == "") {
            $this->ammount = 0;
        }
        $this->action = intval(intval($this->ammount) / actualActionValue(getSelledActions()));
        $this->getCommounSimulation();
    }

    public function getCommounSimulation()
    {
        $this->gift = getGiftedActions($this->action);
        $profitRaw = actualActionValue(getSelledActions(), false) * $this->gift;
        $this->profit = formatSolde($profitRaw, 2);
        if ($this->flash) {
            if ($this->action >= $this->flashMinShares) {
                $this->hasFlashAmount = 1;
                $this->flashGift = '+' . getFlashGiftedActions($this->action, $this->flashTimes);
                $this->flashGain = '+' . formatSolde($this->flashGift * actualActionValue(getSelledActions(), false), 2);
            } else {
                $this->flashGift = $this->flashGain = 0;
            }
        }
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
        $solde = $balancesManager->getBalances($user->idUser, -1);
        $this->cashBalance = $solde->soldeCB;
        $this->balanceForSopping = $solde->soldeBFS;
        $this->discountBalance = $solde->soldeDB;
        $this->SMSBalance = intval($solde->soldeSMS);

        $this->maxActions = intval($solde->soldeCB / actualActionValue(getSelledActions(), false));
        $solde = $balancesManager->getCurrentBalance($user->idUser);
        $usermetta_info = collect(DB::table('metta_users')->where('idUser', $user->idUser)->first());
        $this->actionsValues = formatSolde(getUserSelledActions(Auth()->user()->idUser) * actualActionValue(getSelledActions()), 2);
        $this->userActualActionsProfit = number_format(getUserActualActionsProfit(Auth()->user()->idUser), 2);
        $this->userSelledAction = getUserSelledActions(Auth()->user()->idUser);
        $actualActionValue = actualActionValue(getSelledActions(), false);
        $flashUser =


        $params = [
            "soldeBuyShares" => $balancesManager->getBalances($user->idUser, 2),
            'arraySoldeD' => [$solde->soldeCB, $solde->soldeBFS, $solde->soldeDB],
            'usermetta_info' => $usermetta_info,
            "actualActionValue" => [
                'int' => intval($actualActionValue),
                '2Fraction' => intval(($actualActionValue - floor($actualActionValue)) * 100),
                '3_2Fraction' => intval(($actualActionValue - floor($actualActionValue)) * 100000) - intval(($actualActionValue - floor($actualActionValue)) * 100) * 1000]
        ];
        if ($user->flashCoefficient) {
            $this->flashTimes = $user->flashCoefficient;
            $this->flashPeriod = $user->flashDeadline;
            $this->flashDate = $user->dateFNS;
            $this->flashMinShares = $user->flashMinAmount;
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
