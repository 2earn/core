<?php

namespace App\Http\Livewire;


use App\Http\Traits\contactNumberCheker;
use Core\Services\BalancesManager;

use Core\Services\settingsManager;
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

    protected $listeners = [
        'checkContactNumbre' => 'checkContactNumbre',
        'simulate' => 'simulate'
    ];


    public function mount(settingsManager $settingsManager, BalancesManager $balancesManager)
    {
        $this->settingsManager = $settingsManager;
        $this->balancesManager = $balancesManager;
    }

    public function simulate()
    {
        if ($this->ammount < 0 && $this->ammount <> "") {
            $this->ammount = 0;
        }
        $this->action = intval(intval($this->ammount) / actualActionValue(getSelledActions()));
        $this->gift = getGiftedActions($this->action);
        $profitRaw = actualActionValue(getSelledActions(), false) * $this->gift;
        $this->profit = formatSolde($profitRaw, 2);
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
        $solde = $balancesManager->getCurrentBalance($user->idUser);
        $usermetta_info = collect(DB::table('metta_users')->where('idUser', $user->idUser)->first());
        $this->actionsValues = formatSolde(getUserSelledActions(Auth()->user()->idUser) * actualActionValue(getSelledActions()), 2);
        $this->userActualActionsProfit = number_format(getUserActualActionsProfit(Auth()->user()->idUser), 2);
        $this->userSelledAction = getUserSelledActions(Auth()->user()->idUser);
        $actualActionValue = actualActionValue(getSelledActions(), false);

        $params = [
            "soldeBuyShares" => $balancesManager->getBalances($user->idUser, 2),
            'arraySoldeD' => [$solde->soldeCB, $solde->soldeBFS, $solde->soldeDB],
            'usermetta_info' => $usermetta_info,
            "actualActionValue" => [
                'int' => intval($actualActionValue),
                '2Fraction' => intval(($actualActionValue - floor($actualActionValue)) * 100),
                '3_2Fraction' => intval(($actualActionValue - floor($actualActionValue)) * 100000) - intval(($actualActionValue - floor($actualActionValue)) * 100) * 1000]
        ];
        return view('livewire.home', $params)->extends('layouts.master')->section('content');
    }
}
