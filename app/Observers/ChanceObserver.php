<?php

namespace App\Observers;

use App\Models\ChanceBalances;
use App\Models\UserCurrentBalanceHorisontal;
use App\Models\UserCurrentBalanceVertical;
use Core\Enum\BalanceEnum;

class ChanceObserver
{
    /**
     * Handle the ChanceBalances "created" event.
     *
     * @param  \App\Models\ChanceBalances  $chanceBalances
     * @return void
     */
    public function created(ChanceBalances $chanceBalances)
    {
        $userCurrentBalancehorisontal = UserCurrentBalancehorisontal::where('user_id', $chanceBalances->beneficiary_id)->first();
        // TO DO
        $newChanceBalanceHorisental = [];

        $userCurrentBalancehorisontal->update(
            [
                'chance_balance' => $newChanceBalanceHorisental
            ]);

        $userCurrentBalanceVertical = UserCurrentBalanceVertical::where('user_id', $chanceBalances->beneficiary_id)
            ->where('balance_id', BalanceEnum::CHANCE)
            ->first();
        // TO DO
        $newChanceBalanceVertical = [];

        $userCurrentBalanceVertical->update(
            [
                'current_balance' => $newChanceBalanceVertical,
                'previous_balance' => $userCurrentBalanceVertical->cash_balance,
                'last_operation_id' => $chanceBalances->id,
                'last_operation_value' => $chanceBalances->value,
                'last_operation_date' => $chanceBalances->created_at,
            ]
        );
    }

    /**
     * Handle the ChanceBalances "updated" event.
     *
     * @param  \App\Models\ChanceBalances  $chanceBalances
     * @return void
     */
    public function updated(ChanceBalances $chanceBalances)
    {
        //
    }

    /**
     * Handle the ChanceBalances "deleted" event.
     *
     * @param  \App\Models\ChanceBalances  $chanceBalances
     * @return void
     */
    public function deleted(ChanceBalances $chanceBalances)
    {
        //
    }

    /**
     * Handle the ChanceBalances "restored" event.
     *
     * @param  \App\Models\ChanceBalances  $chanceBalances
     * @return void
     */
    public function restored(ChanceBalances $chanceBalances)
    {
        //
    }

    /**
     * Handle the ChanceBalances "force deleted" event.
     *
     * @param  \App\Models\ChanceBalances  $chanceBalances
     * @return void
     */
    public function forceDeleted(ChanceBalances $chanceBalances)
    {
        //
    }
}
