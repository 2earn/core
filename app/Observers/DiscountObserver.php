<?php

namespace App\Observers;

use App\Models\DiscountBalances;
use App\Models\UserCurrentBalanceHorisontal;
use App\Models\UserCurrentBalanceVertical;
use Core\Enum\BalanceEnum;

class DiscountObserver
{
    /**
     * Handle the DiscountBalances "created" event.
     *
     * @param  \App\Models\DiscountBalances  $discountBalances
     * @return void
     */
    public function created(DiscountBalances $discountBalances)
    {
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
    }

    /**
     * Handle the DiscountBalances "updated" event.
     *
     * @param  \App\Models\DiscountBalances  $discountBalances
     * @return void
     */
    public function updated(DiscountBalances $discountBalances)
    {
        //
    }

    /**
     * Handle the DiscountBalances "deleted" event.
     *
     * @param  \App\Models\DiscountBalances  $discountBalances
     * @return void
     */
    public function deleted(DiscountBalances $discountBalances)
    {
        //
    }

    /**
     * Handle the DiscountBalances "restored" event.
     *
     * @param  \App\Models\DiscountBalances  $discountBalances
     * @return void
     */
    public function restored(DiscountBalances $discountBalances)
    {
        //
    }

    /**
     * Handle the DiscountBalances "force deleted" event.
     *
     * @param  \App\Models\DiscountBalances  $discountBalances
     * @return void
     */
    public function forceDeleted(DiscountBalances $discountBalances)
    {
        //
    }
}
