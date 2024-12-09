<?php

namespace App\Observers;

use App\Models\ShareBalances;
use App\Models\UserCurrentBalanceHorisontal;
use App\Models\UserCurrentBalanceVertical;
use Core\Enum\BalanceEnum;

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
        $userCurrentBalancehorisontal = UserCurrentBalancehorisontal::where('user_id', $shareBalances->beneficiary_id)->first();
        // TO DO
        $newShareBalanceHorisental = [];

        $userCurrentBalancehorisontal->update(
            [
                'share_balance' => $newShareBalanceHorisental
            ]);

        $userCurrentBalanceVertical = UserCurrentBalanceVertical::where('user_id', $shareBalances->beneficiary_id)
            ->where('balance_id', BalanceEnum::CHANCE)
            ->first();
        // TO DO
        $newShareBalanceVertical = [];

        $userCurrentBalanceVertical->update(
            [
                'current_balance' => $newShareBalanceVertical,
                'previous_balance' => $userCurrentBalanceVertical->cash_balance,
                'last_operation_id' => $shareBalances->id,
                'last_operation_value' => $shareBalances->value,
                'last_operation_date' => $shareBalances->created_at,
            ]
        );
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
