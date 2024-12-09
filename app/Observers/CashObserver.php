<?php

namespace App\Observers;

use App\Models\CashBalances;
use App\Models\UserCurrentBalanceHorisontal;
use App\Models\UserCurrentBalanceVertical;
use Core\Enum\BalanceEnum;

class CashObserver
{
    /**
     * Handle the CashBalances "created" event.
     *
     * @param  \App\Models\CashBalances  $cashBalances
     * @return void
     */
    public function created(CashBalances $cashBalances)
    {
        $userCurrentBalancehorisontal = UserCurrentBalancehorisontal::where('user_id', $cashBalances->beneficiary_id)->first();

        $newCashBalanceHorisental = $userCurrentBalancehorisontal->cash_balance + $cashBalances->value;
        $userCurrentBalancehorisontal->update(
            [
                'cash_balance' => $newCashBalanceHorisental
            ]);

        $userCurrentBalanceVertical = UserCurrentBalanceVertical::where('user_id', $cashBalances->beneficiary_id)
            ->where('balance_id', BalanceEnum::CASH)
            ->first();
        $newCashBalanceVertical = $userCurrentBalanceVertical->cash_balance + $cashBalances->value;

        $userCurrentBalanceVertical->update(
            [
                'current_balance' => $newCashBalanceVertical,
                'previous_balance' => $userCurrentBalanceVertical->cash_balance,
                'last_operation_id' => $cashBalances->id,
                'last_operation_value' => $cashBalances->value,
                'last_operation_date' => $cashBalances->created_at,
            ]
        );

    }

    /**
     * Handle the CashBalances "updated" event.
     *
     * @param  \App\Models\CashBalances  $cashBalances
     * @return void
     */
    public function updated(CashBalances $cashBalances)
    {
        //
    }

    /**
     * Handle the CashBalances "deleted" event.
     *
     * @param  \App\Models\CashBalances  $cashBalances
     * @return void
     */
    public function deleted(CashBalances $cashBalances)
    {
        //
    }

    /**
     * Handle the CashBalances "restored" event.
     *
     * @param  \App\Models\CashBalances  $cashBalances
     * @return void
     */
    public function restored(CashBalances $cashBalances)
    {
        //
    }

    /**
     * Handle the CashBalances "force deleted" event.
     *
     * @param  \App\Models\CashBalances  $cashBalances
     * @return void
     */
    public function forceDeleted(CashBalances $cashBalances)
    {
        //
    }
}
