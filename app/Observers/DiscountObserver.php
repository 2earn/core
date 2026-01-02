<?php

namespace App\Observers;

use App\Enums\BalanceEnum;
use App\Models\DiscountBalances;
use App\Models\UserCurrentBalanceVertical;
use App\Services\Balances\Balances;
use Core\Models\BalanceOperation;
use Illuminate\Support\Facades\Log;

class DiscountObserver
{
    public function created(DiscountBalances $discountBalances)
    {
        $userCurrentBalancehorisontal = Balances::getStoredUserBalances($discountBalances->beneficiary_id);
        $newDiscountBalanceHorisental = $newDiscountBalanceVertical = $userCurrentBalancehorisontal->discount_balance + BalanceOperation::getMultiplicator($discountBalances->balance_operation_id) * $discountBalances->value;

        $userCurrentBalancehorisontal->update([Balances::DISCOUNT_BALANCE => $newDiscountBalanceHorisental]);

        $userCurrentBalanceVertical = UserCurrentBalanceVertical::where('user_id', $discountBalances->beneficiary_id)
            ->where('balance_id', BalanceEnum::DB)
            ->first();


        $userCurrentBalanceVertical->update(
            [
                'current_balance' => $userCurrentBalanceVertical->current_balance + BalanceOperation::getMultiplicator($discountBalances->balance_operation_id) * $discountBalances->value,
                'previous_balance' => $userCurrentBalanceVertical->current_balance,
                'last_operation_id' => $discountBalances->id,
                'last_operation_value' => $discountBalances->value,
                'last_operation_date' => $discountBalances->created_at,
            ]
        );

        Log::info('DiscountObserver current_balance '. $newDiscountBalanceVertical,);

    }

}
