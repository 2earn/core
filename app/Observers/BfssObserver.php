<?php

namespace App\Observers;

use App\Models\BFSsBalances;
use App\Models\DiscountBalances;
use App\Services\Balances\Balances;
use Core\Enum\BalanceEnum;
use Core\Enum\BalanceOperationsEnum;
use Core\Models\Setting;
use App\Models\UserCurrentBalanceHorisontal;
use App\Models\UserCurrentBalanceVertical;

class BfssObserver
{
    /**
     * Handle the BFSsBalances "created" event.
     *
     * @param  \App\Models\BFSsBalances  $bFSsBalances
     * @return void
     */
    public function created(BFSsBalances $bFSsBalances)
    {

            $setting = Setting::WhereIn('idSETTINGS', ['22', '23'])->orderBy('idSETTINGS')->pluck('IntegerValue');
            $md = $setting[0];
            $rc = $setting[1];
            DiscountBalances::addLine(
                [
                    'balance_operation_id' => BalanceOperationsEnum::FROM_BFS->value,
                    'operator_id' => Balances::SYSTEM_SOURCE_ID,
                    'beneficiary_id' => $bFSsBalances->beneficiary_id,
                    'reference' => $bFSsBalances->reference,
                    'value' => min($md, $bFSsBalances->value * (pow(abs($bFSsBalances->value - 10), 1.5) / $rc)),
                    'description' =>  number_format(100 * min($md, $bFSsBalances->value * (pow(abs($bFSsBalances->value - 10), 1.5) / $rc)) / $md, 2, '.', '') . '%',
                    'current_balance' => $this->balancesManager->getBalances($bFSsBalances->beneficiary_id, -1)->soldeDB + min($md, $bFSsBalances->value * (pow(abs($bFSsBalances->value - 10), 1.5) / $rc))
                ]
            );



        $userCurrentBalancehorisontal = UserCurrentBalancehorisontal::where('user_id', $bFSsBalances->beneficiary_id)->first();
        // TO DO
        $newBfsBalanceHorisental = [];

        $userCurrentBalancehorisontal->update(
            [
                'cash_balance' => $newBfsBalanceHorisental
            ]);

        $userCurrentBalanceVertical = UserCurrentBalanceVertical::where('user_id', $bFSsBalances->beneficiary_id)
            ->where('balance_id', BalanceEnum::BFS)
            ->first();
        // TO DO
        $newBfssBalanceVertical = [];

        $userCurrentBalanceVertical->update(
            [
                'current_balance' => $newBfssBalanceVertical,
                'previous_balance' => $userCurrentBalanceVertical->cash_balance,
                'last_operation_id' => $bFSsBalances->id,
                'last_operation_value' => $bFSsBalances->value,
                'last_operation_date' => $bFSsBalances->created_at,
            ]
        );
    }

    /**
     * Handle the BFSsBalances "updated" event.
     *
     * @param  \App\Models\BFSsBalances  $bFSsBalances
     * @return void
     */
    public function updated(BFSsBalances $bFSsBalances)
    {
        //
    }

    /**
     * Handle the BFSsBalances "deleted" event.
     *
     * @param  \App\Models\BFSsBalances  $bFSsBalances
     * @return void
     */
    public function deleted(BFSsBalances $bFSsBalances)
    {
        //
    }

    /**
     * Handle the BFSsBalances "restored" event.
     *
     * @param  \App\Models\BFSsBalances  $bFSsBalances
     * @return void
     */
    public function restored(BFSsBalances $bFSsBalances)
    {
        //
    }

    /**
     * Handle the BFSsBalances "force deleted" event.
     *
     * @param  \App\Models\BFSsBalances  $bFSsBalances
     * @return void
     */
    public function forceDeleted(BFSsBalances $bFSsBalances)
    {
        //
    }
}
