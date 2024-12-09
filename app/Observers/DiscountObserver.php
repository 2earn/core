<?php

namespace App\Observers;

use App\Models\DiscountBalances;

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
        //
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
