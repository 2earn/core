<?php

namespace App\Observers;

use App\Models\DiscountBalances;
use App\Models\UserCurrentBalanceHorisontal;
use App\Models\UserCurrentBalanceVertical;
use Core\Enum\BalanceEnum;
use Core\Models\BalanceOperation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DiscountObserver
{
    public function created(DiscountBalances $discountBalances)
    {
            $userCurrentBalancehorisontal = UserCurrentBalanceHorisontal::where('user_id', $discountBalances->beneficiary_id)->first();

            $newDiscountBalanceHorisental = $newDiscountBalanceVertical= $userCurrentBalancehorisontal->discount_balance +BalanceOperation::getMultiplicator($discountBalances->balance_operation_id)* $discountBalances->value;

            $userCurrentBalancehorisontal->update(['discount_balance' => $newDiscountBalanceHorisental]);

            $userCurrentBalanceVertical = UserCurrentBalanceVertical::where('user_id', $discountBalances->beneficiary_id)
            ->where('balance_id', BalanceEnum::CHANCE)
            ->first();
        $userCurrentBalanceVertical->update(
            [
                'current_balance' => $newDiscountBalanceVertical,
                'previous_balance' => $userCurrentBalanceVertical->cash_balance,
                'last_operation_id' => $discountBalances->id,
                'last_operation_value' => $discountBalances->value,
                'last_operation_date' => $discountBalances->created_at,
            ]
        );
    }

}
