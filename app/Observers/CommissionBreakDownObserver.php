<?php

namespace App\Observers;

use App\Models\CommissionBreakDown;
use App\Models\Deal;
use Core\Enum\CommissionTypeEnum;
use Illuminate\Support\Facades\Log;

class CommissionBreakDownObserver
{
    public function created(CommissionBreakDown $commissionBreakDown)
    {
        Log::info('Commission BreakDown CommissionBreakDownObserver ');
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

                    $cbData = [
                        'trigger' => $commissionBreakDown->id,
                        'deal_id' => $commissionBreakDown->deal_id,
                        'type' => CommissionTypeEnum::RECOVERED->value,
                        'order_id' => $oldCommissionBreakDown->order_id,
                    ];

                    $cbData['new_turnover'] = $oldCommissionBreakDown->new_turnover;
                    $cbData['old_turnover'] = $oldCommissionBreakDown->old_turnover;
                    $cbData['purchase_value'] = $oldCommissionBreakDown->purchase_value;
                    $cbData['commission_percentage'] = $percentage;
                    $cbData['commission_value'] = $cbData['purchase_value'] * $cbData['commission_percentage'] / 100;
                    $cbData['cumulative_commission'] = $cumulative + $cbData['commission_value'];
                    $cbData['cumulative_commission_percentage'] = $cbData['cumulative_commission'] / $cbData['new_turnover'] * 100;
                    $cbData['cash_company_profit'] = $cbData['commission_value'] * $deal->earn_profit / 100;
                    $cbData['cash_jackpot'] = $cbData['commission_value'] * $deal->jackpot / 100;
                    $cbData['cash_tree'] = $cbData['commission_value'] * $deal->tree_remuneration / 100;
                    $cbData['cash_cashback'] = $cbData['commission_value'] * $deal->proactive_cashback / 100;
                    $cbData['cumulative_cashback'] = $cumulativeCashback + $cbData['cash_cashback'];
                            Log::notice(json_encode($cbData));
                            CommissionBreakDown::create($cbData);
                }
                    }
            }
            }
        }
    }
}
