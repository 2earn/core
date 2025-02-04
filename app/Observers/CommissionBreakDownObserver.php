<?php

namespace App\Observers;

use App\Models\CommissionBreakDown;
use App\Models\Deal;
use Core\Enum\CommissionTypeEnum;
use Illuminate\Support\Facades\Log;

class CommissionBreakDownObserver
{
    public function initCommission(CommissionBreakDown $oldCommissionBreakDown, CommissionBreakDown $commissionBreakDown)
    {
        return [
            'trigger' => $oldCommissionBreakDown->id,
            'deal_id' => $commissionBreakDown->deal_id,
            'type' => CommissionTypeEnum::RECOVERED->value,
            'order_id' => $commissionBreakDown->order_id,
            'new_turnover' => $oldCommissionBreakDown->new_turnover,
            'old_turnover' => $oldCommissionBreakDown->old_turnover,
            'purchase_value' => $oldCommissionBreakDown->purchase_value,
        ];
    }

    public function updateOldsCommissions(CommissionBreakDown $commissionBreakDown)
    {
        $oldCommissionBreakDowns = CommissionBreakDown::where('deal_id', $commissionBreakDown->deal_id)->where('type', CommissionTypeEnum::IN)->get();
        $cashback = CommissionBreakDown::getSum($commissionBreakDown->deal_id, 'cash_cashback');
        foreach ($oldCommissionBreakDowns as $oldCommissionBreakDown) {
            $oldCommissionBreakDown->cashback_allocation = $oldCommissionBreakDown->cumulative_cashback / $cashback * 100;
            $oldCommissionBreakDown->earned_cashback = $oldCommissionBreakDown->earned_cashback + ($oldCommissionBreakDown->cumulative_cashback * $oldCommissionBreakDown->cashback_allocation / 100);
            $oldCommissionBreakDown->final_cashback = min($oldCommissionBreakDown->purchase_value, $oldCommissionBreakDown->earned_cashback);
            $oldCommissionBreakDown->final_cashback_percentage = $oldCommissionBreakDown->final_cashback / $oldCommissionBreakDown->purchase_value * 100;
            $oldCommissionBreakDown->save();
        }
    }

    public function addRecovredCommissions(CommissionBreakDown $commissionBreakDown)
    {
        if ($commissionBreakDown->wasRecentlyCreated) {

            if ($commissionBreakDown->type->value == CommissionTypeEnum::IN->value) {
                $oldCommissionBreakDowns = CommissionBreakDown::where('deal_id', $commissionBreakDown->deal_id)->where('type', CommissionTypeEnum::IN)->get();
                if (count($oldCommissionBreakDowns) >= 2) {
                    $percentage = $commissionBreakDown->getRecoveredPercentage();
                    $deal = Deal::find($commissionBreakDown->deal_id);

                    foreach ($oldCommissionBreakDowns as $key => $oldCommissionBreakDown) {
                        if ($key > 0) {
                            $cumulative = CommissionBreakDown::getSum($commissionBreakDown->deal_id, 'cumulative_commission');
                            $cumulativeCashback = CommissionBreakDown::getSum($commissionBreakDown->deal_id, 'cumulative_cashback');

                            $cbData = $this->initCommission($oldCommissionBreakDown, $commissionBreakDown);

                            $cbData['commission_percentage'] = $percentage;
                            $cbData['commission_value'] = $cbData['purchase_value'] * $cbData['commission_percentage'] / 100;
                            $cbData['cumulative_commission'] = $cumulative + $cbData['commission_value'];
                            $cbData['cumulative_commission_percentage'] = $cbData['cumulative_commission'] / $cbData['new_turnover'] * 100;
                            $cbData['cash_company_profit'] = $cbData['commission_value'] * $deal->earn_profit / 100;
                            $cbData['cash_jackpot'] = $cbData['commission_value'] * $deal->jackpot / 100;
                            $cbData['cash_tree'] = $cbData['commission_value'] * $deal->tree_remuneration / 100;
                            $cbData['cash_cashback'] = $cbData['commission_value'] * $deal->proactive_cashback / 100;
                            $cbData['cumulative_cashback'] = $cumulative_cashback = $cumulativeCashback + $cbData['cash_cashback'];
                            CommissionBreakDown::create($cbData);
                        }
                    }
                }
            }
        }

    }

    public function created(CommissionBreakDown $commissionBreakDown)
    {
        Log::info('Commission BreakDown Commission BreakDown Observer ');
        $this->addRecovredCommissions($commissionBreakDown);
        $this->updateOldsCommissions($commissionBreakDown);
    }
}
