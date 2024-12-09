<?php

namespace App\Observers;

use App\Models\TreeBalances;

class TreeObserver
{
    /**
     * Handle the TreeBalances "created" event.
     *
     * @param  \App\Models\TreeBalances  $treeBalances
     * @return void
     */
    public function created(TreeBalances $treeBalances)
    {
        //
    }

    /**
     * Handle the TreeBalances "updated" event.
     *
     * @param  \App\Models\TreeBalances  $treeBalances
     * @return void
     */
    public function updated(TreeBalances $treeBalances)
    {
        //
    }

    /**
     * Handle the TreeBalances "deleted" event.
     *
     * @param  \App\Models\TreeBalances  $treeBalances
     * @return void
     */
    public function deleted(TreeBalances $treeBalances)
    {
        //
    }

    /**
     * Handle the TreeBalances "restored" event.
     *
     * @param  \App\Models\TreeBalances  $treeBalances
     * @return void
     */
    public function restored(TreeBalances $treeBalances)
    {
        //
    }

    /**
     * Handle the TreeBalances "force deleted" event.
     *
     * @param  \App\Models\TreeBalances  $treeBalances
     * @return void
     */
    public function forceDeleted(TreeBalances $treeBalances)
    {
        //
    }
}
