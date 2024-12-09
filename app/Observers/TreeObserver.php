<?php

namespace App\Observers;

use App\Models\TreeBalances;
use App\Models\UserCurrentBalanceHorisontal;
use App\Models\UserCurrentBalanceVertical;
use Core\Enum\BalanceEnum;

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
        $userCurrentBalancehorisontal = UserCurrentBalancehorisontal::where('user_id', $treeBalances->beneficiary_id)->first();
        // TO DO
        $newTreeBalanceHorisental = [];

        $userCurrentBalancehorisontal->update(
            [
                'tree_balance' => $newTreeBalanceHorisental
            ]);

        $userCurrentBalanceVertical = UserCurrentBalanceVertical::where('user_id', $treeBalances->beneficiary_id)
            ->where('balance_id', BalanceEnum::CHANCE)
            ->first();
        // TO DO
        $newTreeBalanceVertical = [];

        $userCurrentBalanceVertical->update(
            [
                'current_balance' => $newTreeBalanceVertical,
                'previous_balance' => $userCurrentBalanceVertical->cash_balance,
                'last_operation_id' => $treeBalances->id,
                'last_operation_value' => $treeBalances->value,
                'last_operation_date' => $treeBalances->created_at,
            ]
        );    }

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
