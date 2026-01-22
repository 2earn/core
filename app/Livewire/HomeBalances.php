<?php

namespace App\Livewire;

use App\Models\vip;
use App\Services\BalancesManager;
use App\Services\settingsManager;
use Livewire\Component;

class HomeBalances extends Component
{
    public $cashBalance;
    public $balanceForSopping;
    public $discountBalance;
    public $treeBalance;
    public $chanceBalance;
    public $SMSBalance;
    public $decimalSeperator = '.';
    public $actionsValues = 0;
    public $userSelledAction = 0;
    public $userActualActionsProfit = 0;
    public $actualActionValue;
    public $arraySoldeD;
    public $flash = false;

    public function render(settingsManager $settingsManager, BalancesManager $balancesManager)
    {
        $user = $settingsManager->getAuthUser();

        if (!$user) {
            return view('livewire.home-balances');
        }

        $solde = $balancesManager->getBalances($user->idUser, -1);
        $this->cashBalance = $solde->soldeCB;
        $this->balanceForSopping = $solde->soldeBFS;
        $this->discountBalance = $solde->soldeDB;
        $this->treeBalance = $solde->soldeTree;
        $this->chanceBalance = $solde->soldeChance;
        $this->SMSBalance = intval($solde->soldeSMS);

        $currentSolde = $balancesManager->getCurrentBalance($user->idUser);
        $this->arraySoldeD = [$currentSolde->soldeCB, $currentSolde->soldeBFS, $currentSolde->soldeDB];

        $this->actionsValues = formatSolde(getUserSelledActions(auth()->user()->idUser) * actualActionValue(getSelledActions(true)), 2);
        $this->userActualActionsProfit = number_format(getUserActualActionsProfit(auth()->user()->idUser), 2);
        $this->userSelledAction = getUserSelledActions(auth()->user()->idUser);

        $actualActionValueRaw = actualActionValue(getSelledActions(true), false);
        $this->actualActionValue = [
            'int' => intval($actualActionValueRaw),
            '2Fraction' => intval(($actualActionValueRaw - floor($actualActionValueRaw)) * 100),
            '3_2Fraction' => str_pad(intval(($actualActionValueRaw - floor($actualActionValueRaw)) * 100000) - intval(($actualActionValueRaw - floor($actualActionValueRaw)) * 100) * 1000, 3, "0", STR_PAD_LEFT)
        ];

        $this->flash = vip::where('idUser', '=', $user->idUser)
            ->where('closed', '=', false)
            ->whereRaw('DATE_ADD(dateFNS, INTERVAL flashDeadline HOUR) > NOW()')
            ->exists();
        return view('livewire.home-balances');
    }
}

