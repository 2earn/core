<?php

namespace App\Observers;

use App\Models\CommissionBreakDown;
use Core\Enum\CommissionTypeEnum;
use Illuminate\Support\Facades\Log;

class CommissionBreakDownObserver
{
    public function created(CommissionBreakDown $commissionBreakDown)
    {
        Log::info('Commission BreakDown CommissionBreakDownObserver : ' . $commissionBreakDown->id);
        if ($commissionBreakDown->type->value == CommissionTypeEnum::IN->value) {
            $oldCommissionBreakDowns = CommissionBreakDown::where('deal_id', $commissionBreakDown->deal_id)->get();
            Log::info('Commission BreakDown A : ' . $commissionBreakDown->id);
            if (!empty($oldCommissionBreakDowns)) {
                Log::info('Commission BreakDown B : ' . $commissionBreakDown->id);
                $first = $oldCommissionBreakDowns->first();
                $percentage = $commissionBreakDown->percentage - $first->percentage;
                foreach ($oldCommissionBreakDowns as $oldCommissionBreakDown) {
                    CommissionBreakDown::create([
                        'trigger' => $oldCommissionBreakDown->id,
                        'type' => CommissionTypeEnum::RECOVERED->value,
                        'order_id' => $oldCommissionBreakDown->order_id,
                        'amount' => $oldCommissionBreakDown->amount,
                        'percentage' => $percentage,
                        'value' => $oldCommissionBreakDown->amount / 100 * $percentage,
                    ]);
                }
            }
        }
    }
}
