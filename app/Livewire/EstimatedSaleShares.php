<?php

namespace App\Livewire;

use App\Models\SharesBalances;
use Livewire\Component;

class EstimatedSaleShares extends Component
{
    public $userSelledActionNumber = 0;
    public $giftedShares = 0;
    public $estimatedGain = 0;
    public $selledActionCursor = 0;
    public $totalPaied = 0;
    public $actionValue = 0;
    public $targetDate = null;
    public $precentageOfActions = 0;
    public $precentageOfSharesSale = 0;
    public $numberSharesSale = 0;
    public $totalActions = 0;

    public function mount()
    {
        $this->giftedShares = getSettingIntegerParam('GIFTED_SHARES', 0);
        $this->targetDate = getSettingStringParam('TARGET_DATE', 0);
        $this->totalActions = getSettingIntegerParam('Actions Number', 0) - $this->giftedShares;

        $this->selledActions = intval(getSelledActions(true));
        $this->precentageOfActions = round($this->selledActions / $this->totalActions, 3) * 100;

        $this->numberSharesSale = $this->totalActions - $this->giftedShares;
        $this->precentageOfSharesSale = round($this->selledActions / $this->numberSharesSale, 3) * 100;
        $this->userSelledActionNumber = round(SharesBalances::where('balance_operation_id', 20)->where('beneficiary_id', Auth()->user()->idUser)->selectRaw('SUM(value) as total_sum')->first()->total_sum);

        $this->selledActionCursor = $this->selledActions;
        $this->totalPaied = round(SharesBalances::where('balance_operation_id', 20)->where('beneficiary_id', Auth()->user()->idUser)->selectRaw('SUM(total_amount) as total_sum')->first()->total_sum, 3);
    }

    public function simulateGain()
    {
        $this->actionValue = round(actualActionValue($this->selledActionCursor, false), 3);
        $this->estimatedGain = round(($this->userSelledActionNumber * actualActionValue($this->selledActionCursor, false)) - $this->totalPaied, 3);
        if ($this->estimatedGain < 0) {
            $this->estimatedGain = 0;
        }
    }

    public
    function render()
    {
        return view('livewire.estimated-sale-shares');
    }
}
