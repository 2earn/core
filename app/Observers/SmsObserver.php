<?php

namespace App\Observers;

use App\Models\SmsBalances;
use App\Models\UserCurrentBalanceHorisontal;
use App\Models\UserCurrentBalanceVertical;
use Core\Enum\BalanceEnum;

class SmsObserver
{
    /**
     * Handle the SmsBalances "created" event.
     *
     * @param  \App\Models\SmsBalances  $smsBalances
     * @return void
     */
    public function created(SmsBalances $smsBalances)
    {
        $userCurrentBalancehorisontal = UserCurrentBalancehorisontal::where('user_id', $smsBalances->beneficiary_id)->first();
        // TO DO
        $newSmsBalanceHorisental = [];

        $userCurrentBalancehorisontal->update(
            [
                'sms_balance' => $newSmsBalanceHorisental
            ]);

        $userCurrentBalanceVertical = UserCurrentBalanceVertical::where('user_id', $smsBalances->beneficiary_id)
            ->where('balance_id', BalanceEnum::CHANCE)
            ->first();
        // TO DO
        $newSmsBalanceVertical = [];

        $userCurrentBalanceVertical->update(
            [
                'current_balance' => $newSmsBalanceVertical,
                'previous_balance' => $userCurrentBalanceVertical->cash_balance,
                'last_operation_id' => $smsBalances->id,
                'last_operation_value' => $smsBalances->value,
                'last_operation_date' => $smsBalances->created_at,
            ]
        );
    }

    /**
     * Handle the SmsBalances "updated" event.
     *
     * @param  \App\Models\SmsBalances  $smsBalances
     * @return void
     */
    public function updated(SmsBalances $smsBalances)
    {
        //
    }

    /**
     * Handle the SmsBalances "deleted" event.
     *
     * @param  \App\Models\SmsBalances  $smsBalances
     * @return void
     */
    public function deleted(SmsBalances $smsBalances)
    {
        //
    }

    /**
     * Handle the SmsBalances "restored" event.
     *
     * @param  \App\Models\SmsBalances  $smsBalances
     * @return void
     */
    public function restored(SmsBalances $smsBalances)
    {
        //
    }

    /**
     * Handle the SmsBalances "force deleted" event.
     *
     * @param  \App\Models\SmsBalances  $smsBalances
     * @return void
     */
    public function forceDeleted(SmsBalances $smsBalances)
    {
        //
    }
}
