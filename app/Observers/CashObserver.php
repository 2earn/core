<?php

namespace App\Observers;

use App\Models\CashBalances;

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
        //
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
