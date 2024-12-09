<?php

namespace App\Observers;

use App\Models\SmsBalances;

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
        //
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
