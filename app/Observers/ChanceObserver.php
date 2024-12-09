<?php

namespace App\Observers;

use App\Models\ChanceBalances;

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
        //
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
