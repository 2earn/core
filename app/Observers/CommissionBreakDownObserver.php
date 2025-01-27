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
        Log::info('Commission BreakDown CommissionBreakDownObserver : ' . $commissionBreakDown->id);
        if ($commissionBreakDown->wasRecentlyCreated) {
            if ($commissionBreakDown->type->value == CommissionTypeEnum::IN->value) {
            $oldCommissionBreakDowns = CommissionBreakDown::where('deal_id', $commissionBreakDown->deal_id)->where('type', CommissionTypeEnum::IN)->get();
            Log::info('Commission BreakDown: ' . count($oldCommissionBreakDowns));
            if (!empty($oldCommissionBreakDowns)) {
                $first = $oldCommissionBreakDowns->first();
                $percentage = $commissionBreakDown->percentage - $first->percentage;
                $deal = Deal::find($commissionBreakDown->deal_id);
                foreach ($oldCommissionBreakDowns as $oldCommissionBreakDown) {
                    $cumulative = CommissionBreakDown::sum('cumulative_commission');
                    $cumulativeCashback = CommissionBreakDown::where('deal_id', $commissionBreakDown->deal_id)->sum('cumulative_cashback');
                    $cbData = [
                        'trigger' => $oldCommissionBreakDown->id,
                        'deal_id' => $commissionBreakDown->deal_id,
                        'type' => CommissionTypeEnum::RECOVERED->value,
                        'order_id' => $oldCommissionBreakDown->order_id,
                        'amount' => $oldCommissionBreakDown->amount,
                        'percentage' => $percentage,
                        'value' => $oldCommissionBreakDown->amount / 100 * $percentage,
                        'additional' =>0,
                        'cumulative' =>0,
                        'cumulative_percentage' =>0,
                        'earn' =>0,
                        'pool' =>0,
                        'cashback_proactif' =>0,
                        'tree' =>0,
                    ];

                    $cbData['new_turnover'] = $commissionBreakDown->new_turnover;
                    $cbData['old_turnover'] = $oldCommissionBreakDown->new_turnover;
                    $cbData['purchase_value'] = $commissionBreakDown->purchase_value;
                    $cbData['commission_percentage'] = $oldCommissionBreakDown->commission_percentage - $commissionBreakDown->commission_percentage;
                    $cbData['commission_value'] = $cbData['purchase_value'] * $cbData['commission_percentage'] / 100;

                    $cbData['cumulative_commission'] = $cumulative + $cbData['commission_value'];
                    $cbData['cumulative_commission_percentage'] = $cbData['cumulative_commission'] / $cbData['new_turnover'] * 100;
                    $cbData['cash_company_profit'] = $cbData['commission_value'] * $deal->earn_profit / 100;
                    $cbData['cash_jackpot'] = $cbData['commission_value'] * $deal->jackpot / 100;
                    $cbData['cash_tree'] = $cbData['commission_value'] * $deal->tree_remuneration / 100;
                    $cbData['cash_cashback'] = $cbData['commission_value'] * $deal->proactive_cashback / 100;
                    $cbData['cumulative_cashback'] = $cumulativeCashback + $cbData['cash_cashback'];
                    CommissionBreakDown::create($cbData);
                }
            }
            }
        }
    }
}
