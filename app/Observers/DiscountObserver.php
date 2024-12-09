<?php

namespace App\Observers;

use App\Models\DiscountBalances;
use App\Models\UserCurrentBalanceHorisontal;
use App\Models\UserCurrentBalanceVertical;
use Core\Enum\BalanceEnum;
use Illuminate\Support\Facades\DB;

class DiscountObserver
{
    public function created(DiscountBalances $discountBalances)
    {
        DB::beginTransaction();
        try {
            $userCurrentBalancehorisontal = UserCurrentBalancehorisontal::where('user_id', $discountBalances->beneficiary_id)->first();
        // TO DO
        $newDiscountBalanceHorisental = [];

        $userCurrentBalancehorisontal->update(
            [
                'discount_balance' => $newDiscountBalanceHorisental
            ]);

        $userCurrentBalanceVertical = UserCurrentBalanceVertical::where('user_id', $discountBalances->beneficiary_id)
            ->where('balance_id', BalanceEnum::CHANCE)
            ->first();
        // TO DO
        $newDiscountBalanceVertical = [];

        $userCurrentBalanceVertical->update(
            [
                'current_balance' => $newDiscountBalanceVertical,
                'previous_balance' => $userCurrentBalanceVertical->cash_balance,
                'last_operation_id' => $discountBalances->id,
                'last_operation_value' => $discountBalances->value,
                'last_operation_date' => $discountBalances->created_at,
            ]
        );
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

}
