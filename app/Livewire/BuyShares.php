<?php

namespace App\Livewire;

use App\Models\vip;
use App\Services\Settings\SettingService;
use Core\Services\BalancesManager;
use Livewire\Component;

class BuyShares extends Component
{
    const MAX_AMOUNT = 99999999;
    const MAX_ACTIONS = 9999999;
    public $flash = false;
    public $cashBalance;
    public $action;
    public $gift;
    public $profit;
    public $ammount;
    public $currency = '$';
    public $maxActions;
    public $flashGift = 0;
    public $flashTimes = 1;
    public $flashPeriod;
    public $flashDate;
    public $flashMinShares = -1;
    public $flashGain = 0;
    public $actions = 0;
    public $totalActions = 0;

    public $giftedShares = 0;
    public $targetDate = null;
    public $vip;

    public function mount()
    {
        $this->giftedShares = getSettingIntegerParam('GIFTED_SHARES', 0);
        $this->targetDate = getSettingStringParam('TARGET_DATE', 0);
        $this->totalActions = getSettingIntegerParam('Actions Number', 0) - $this->giftedShares;
        $this->vip = vip::Where('idUser', '=', auth()->user()->idUser)->where('closed', '=', false)->first();
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

        $this->ammount = round($this->action * actualActionValue(getSelledActions(true), false), 3);
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

        $this->action = (string)intval(intval($this->ammount) / actualActionValue(getSelledActions(true), false));
        $this->getCommounSimulation();
    }

    public function getCommounSimulation()
    {
        $this->gift = getGiftedActions($this->action);
        $actualActionValue = actualActionValue(getSelledActions(true), false);
        $this->profit = $actualActionValue * $this->gift;
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

    public function render(BalancesManager $balancesManager, SettingService $settingService)
    {
        $this->initSoldes($balancesManager);

        $actualActionValue = actualActionValue(getSelledActions(true), false);
        if ($this->vip) {
            $settingValues = $settingService->getIntegerValues(['20', '18']);
            $max_bonus = $settingValues['20'];
            $total_actions = $settingValues['18'];
            $k = $settingService->getDecimalValue('21');
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
        $params = ["soldeBuyShares" => $balancesManager->getBalances(auth()->user()->idUser, 2)];
        return view('livewire.buy-shares', $params);
    }
}
