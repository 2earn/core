<?php

namespace App\Http\Livewire;

use App\Models\vip;
use Core\Models\Setting;
use Core\Models\user_balance;
use Core\Services\BalancesManager;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Trading extends Component
{
    const MAX_AMOUNT = 99999999;
    const MAX_ACTIONS = 9999999;
    public $flash = false;
    public $cashBalance;
    public $action;
    public $ammount;
    public $currency = '$';
    public $maxActions;
    public $flashGift = 0;
    public $flashTimes = 1;
    public $flashPeriod;
    public $flashDate;
    public $flashMinShares = -1;
    public $flashGain = 0;
    public $selledActions = 0;
    public $totalActions = 0;
    public $precentageOfActions = 0;
    public $precentageOfSharesSale = 0;
    public $numberSharesSale = 0;
    public $giftedShares = 0;
    public $estimatedGain = 0;
    public $selledActionCursor = 0;
    public $totalPaied = 0;
    public $userSelledActionNumber = 0;
    public $actionValue = 0;


    public function mount()
    {
        $param = DB::table('settings')->where("ParameterName", "=", "GIFTED_SHARES")->first();
        if (!is_null($param)) {
            $this->giftedShares = $param->IntegerValue;
        }

        $param = DB::table('settings')->where("ParameterName", "=", "Actions Number")->first();
        if (!is_null($param)) {
            $this->totalActions = $param->IntegerValue - $this->giftedShares;
        }

        $this->selledActions = intval(getSelledActions(true));
        $this->precentageOfActions = round($this->selledActions / $this->totalActions, 3) * 100;

        $this->numberSharesSale = $this->totalActions - $this->giftedShares;
        $this->precentageOfSharesSale = round($this->selledActions / $this->numberSharesSale, 3) * 100;
        $this->userSelledActionNumber = round(user_balance::where('idBalancesOperation', 44)->where('idUser', Auth()->user()->idUser)->selectRaw('SUM(value) as total_sum')->first()->total_sum);

        $this->selledActionCursor = $this->selledActions;
        $this->totalPaied = round(user_balance::where('idBalancesOperation', 44)->where('idUser', Auth()->user()->idUser)->selectRaw('SUM((value + gifted_shares) * PU) as total_sum')->first()->total_sum, 3);

        $this->simulateGain();

    }

    public function simulateGain()
    {
        $this->actionValue = round(actualActionValue($this->selledActionCursor, false), 3);
        $this->estimatedGain = round(($this->userSelledActionNumber * actualActionValue($this->selledActionCursor, false)) - $this->totalPaied, 3);
        if ($this->estimatedGain < 0) {
            $this->estimatedGain = 0;
        }
    }

    public function simulateAction()
    {
        if ($this->action < 0 && $this->action == "") {
           $this->ammount = "";
            $this->action = "";
            return;
        }

        if ($this->action > self::MAX_ACTIONS) {
            $this->action = self::MAX_ACTIONS;
        }

        $this->ammount =  round($this->action * actualActionValue(getSelledActions(true), false),3);
        $this->getCommounSimulation();
    }

    public function simulateAmmount()
    {
       if ($this->ammount < 0 && $this->ammount == "") {
            $this->ammount = "";
            $this->action = "";
            return;
        }

        if ($this->ammount > self::MAX_AMOUNT) {
          $this->ammount = self::MAX_AMOUNT;
        }

        $this->action = intval(intval($this->ammount) / actualActionValue(getSelledActions(true), false));

        $this->getCommounSimulation();
    }

    public function getCommounSimulation()
    {
        $this->gift = getGiftedActions($this->action);
        $actualActionValue=actualActionValue(getSelledActions(true), false);
        $profitRaw = $actualActionValue * $this->gift;
        $this->profit = formatSolde($profitRaw, 2);
        if ($this->flash) {
            if ($this->vip->declenched) {
                if ($this->action >= $this->actions) {
                    $this->flashGift = '+' . getFlashGiftedActions($this->actions, $this->flashTimes);
                    $this->flashGain = '+' . formatSolde($this->flashGift * $actualActionValue, 2);
                } else {
                    $this->flashGift = '+' . getFlashGiftedActions($this->action, $this->flashTimes);
                    $this->flashGain = '+' . formatSolde($this->flashGift * $actualActionValue, 2);
                }
            } else {
                if ($this->action >= $this->flashMinShares) {
                    if ($this->action >= $this->actions) {
                        $this->flashGift = '+' . getFlashGiftedActions($this->actions, $this->flashTimes);
                        $this->flashGain = '+' . formatSolde($this->flashGift * $actualActionValue, 2);
                    } else {
                        $this->flashGift = '+' . getFlashGiftedActions($this->action, $this->flashTimes);
                        $this->flashGain = '+' . formatSolde($this->flashGift * $actualActionValue, 2);
                    }
                } else {
                    $this->flashGift = $this->flashGain = 0;
                }
            }

        }
    }

    public function initSoldes(BalancesManager $balancesManager)
    {
        $solde = $balancesManager->getBalances(auth()->user()->idUser, -1);
        $this->cashBalance = $solde->soldeCB;
        $this->balanceForSopping = $solde->soldeBFS;
        $this->discountBalance = $solde->soldeDB;
        $this->SMSBalance = intval($solde->soldeSMS);
        $this->maxActions = intval($solde->soldeCB / actualActionValue(getSelledActions(true), false));
    }

    public function render(BalancesManager $balancesManager)
    {
        $this->initSoldes($balancesManager);

        $actualActionValue = actualActionValue(getSelledActions(true), false);
        $this->vip = vip::Where('idUser', '=', auth()->user()->idUser)->where('closed', '=', false)->first();
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
            $currentDateTime = new \DateTime();
            $dateFlash = new \DateTime($this->flashDate);
            $interval = new \DateInterval('PT' . $this->flashPeriod . 'H');
            $dateFlash = $dateFlash->add($interval);
            $this->flashDate = $dateFlash->format('F j, Y G:i:s');
            $this->flash = $currentDateTime < $dateFlash;
        }
        $params = [
            "soldeBuyShares" => $balancesManager->getBalances(auth()->user()->idUser, 2)
        ];

        return view('livewire.trading', $params)->extends('layouts.master')->section('content');
    }
}
