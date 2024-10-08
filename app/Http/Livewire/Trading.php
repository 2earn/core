<?php

namespace App\Http\Livewire;

use App\Models\vip;
use Core\Models\Setting;
use Core\Services\BalancesManager;
use Livewire\Component;

class Trading extends Component
{
    const MAX_AMOUNT = 99999999;
    const MAX_ACTIONS = 9999999;
    public $flash = false;
    public $cashBalance;
    public $ammount;
    public $currency = '$';

    public $maxActions;


    public $flashGift = 0;
    public $flashTimes = 1;
    public $flashPeriod;
    public $flashDate;
    public $flashMinShares = -1;
    public $flashGain = 0;

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

        $this->ammount = round($this->action * actualActionValue(getSelledActions(), false), 3);
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
        $this->action = round(intval($this->ammount) / actualActionValue(getSelledActions()));
        $this->getCommounSimulation();
    }

    public function getCommounSimulation()
    {
        $this->gift = getGiftedActions($this->action);
        $profitRaw = actualActionValue(getSelledActions(), false) * $this->gift;
        $this->profit = formatSolde($profitRaw, 2);

        if ($this->flash) {
            if ($this->vip->declenched) {
                if ($this->action >= $this->actions) {
                    $this->flashGift = '+' . getFlashGiftedActions($this->actions, $this->flashTimes);
                    $this->flashGain = '+' . formatSolde($this->flashGift * actualActionValue(getSelledActions(), false), 2);
                } else {
                    $this->flashGift = '+' . getFlashGiftedActions($this->action, $this->flashTimes);
                    $this->flashGain = '+' . formatSolde($this->flashGift * actualActionValue(getSelledActions(), false), 2);
                }
            } else {
                if ($this->action >= $this->flashMinShares) {
                    if ($this->action >= $this->actions) {
                        $this->flashGift = '+' . getFlashGiftedActions($this->actions, $this->flashTimes);
                        $this->flashGain = '+' . formatSolde($this->flashGift * actualActionValue(getSelledActions(), false), 2);
                    } else {
                        $this->flashGift = '+' . getFlashGiftedActions($this->action, $this->flashTimes);
                        $this->flashGain = '+' . formatSolde($this->flashGift * actualActionValue(getSelledActions(), false), 2);
                    }
                } else {
                    $this->flashGift = $this->flashGain = 0;
                }
            }

        }
    }

    public function render(BalancesManager $balancesManager)
    {
        $solde = $balancesManager->getBalances(auth()->user()->idUser, -1);
        $this->cashBalance = $solde->soldeCB;
        $this->balanceForSopping = $solde->soldeBFS;
        $this->discountBalance = $solde->soldeDB;
        $this->SMSBalance = intval($solde->soldeSMS);
        $this->maxActions = intval($solde->soldeCB / actualActionValue(getSelledActions(), false));

        $actualActionValue = actualActionValue(getSelledActions(), false);
        $this->vip = vip::Where('idUser', '=', auth()->user()->idUser)
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
