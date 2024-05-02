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
    public $cash = 25.033;
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

    function checkContactNumbre()
    {
    }

    public function mount(
        settingsManager $settingsManager,
        BalancesManager $balancesManager
    )
    {
        $this->settingsManager = $settingsManager;
        $this->balancesManager = $balancesManager;
    }

    public function simulate()
    {
        $this->action = intval(intval($this->ammount) / actualActionValue(getSelledActions()));
        $this->gift = getGiftedActions($this->action);
        $this->profit = actualActionValue(getSelledActions()) * $this->gift;
    }

    public function getIp()
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe
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
        if (!$user)
            dd('not found page');
        $solde = $balancesManager->getBalances($user->idUser);
        $this->cashBalance = $solde->soldeCB;
        $this->balanceForSopping = $solde->soldeBFS;
        $this->discountBalance = $solde->soldeDB;
        $this->SMSBalance = $solde->soldeSMS;
        $arraySoldeD = [];
        $solde = $balancesManager->getCurrentBalance($user->idUser);
        $s = $balancesManager->getBalances($user->idUser);
        $soldeCBd = $solde->soldeCB;
        $soldeBFSd = $solde->soldeBFS;
        $soldeDBd = $solde->soldeDB;
        array_push($arraySoldeD, $soldeCBd);
        array_push($arraySoldeD, $soldeBFSd);
        array_push($arraySoldeD, $soldeDBd);
        $usermetta_info = collect(DB::table('metta_users')->where('idUser', $user->idUser)->first());

        $actualActionValue = actualActionValue(getSelledActions(), false);
        $actualActionValueWhole = intval($actualActionValue);
        $actualActionValue2Fraction = intval(($actualActionValue - floor($actualActionValue)) * 100);
        $actualActionValue3_2Fraction = intval(($actualActionValue - floor($actualActionValue)) * 100000) - $actualActionValue2Fraction * 1000;
        $actualAction = ['int' => $actualActionValueWhole,'2Fraction' => $actualActionValue2Fraction,'3_2Fraction' => $actualActionValue3_2Fraction];
        $params = ["solde" => $s, 'arraySoldeD' => $arraySoldeD, 'usermetta_info' => $usermetta_info, "actualActionValue" => $actualAction];
        return view('livewire.home', $params)->extends('layouts.master')->section('content');
    }
}
