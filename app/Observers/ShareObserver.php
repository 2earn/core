<?php

namespace App\Observers;

use App\Models\ShareBalances;

class ShareObserver
{
    /**
     * Handle the ShareBalances "created" event.
     *
     * @param  \App\Models\ShareBalances  $shareBalances
     * @return void
     */
    public function created(ShareBalances $shareBalances)
    {
        //
    }

    /**
     * Handle the ShareBalances "updated" event.
     *
     * @param  \App\Models\ShareBalances  $shareBalances
     * @return void
     */
    public function updated(ShareBalances $shareBalances)
    {
        //
    }

    /**
     * Handle the ShareBalances "deleted" event.
     *
     * @param  \App\Models\ShareBalances  $shareBalances
     * @return void
     */
    public function deleted(ShareBalances $shareBalances)
    {
        //
    }

    /**
     * Handle the ShareBalances "restored" event.
     *
     * @param  \App\Models\ShareBalances  $shareBalances
     * @return void
     */
    public function restored(ShareBalances $shareBalances)
    {
        //
    }

    /**
     * Handle the ShareBalances "force deleted" event.
     *
     * @param  \App\Models\ShareBalances  $shareBalances
     * @return void
     */
    public function forceDeleted(ShareBalances $shareBalances)
    {
        //
    }
}
