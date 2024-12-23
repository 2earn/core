<?php

namespace App\Observers;

use App\Models\BFSsBalances;
use App\Models\DiscountBalances;
use App\Services\Balances\Balances;
use Core\Enum\BalanceEnum;
use Core\Enum\BalanceOperationsEnum;
use Core\Models\BalanceOperation;
use Core\Models\Setting;
use App\Models\UserCurrentBalanceVertical;
use Core\Services\BalancesManager;


class BfssObserver
{
    public function __construct(private BalancesManager $balancesManager)
    {
    }

    public function created(BFSsBalances $bFSsBalances)
    {
            $setting = Setting::WhereIn('idSETTINGS', ['22', '23'])->orderBy('idSETTINGS')->pluck('IntegerValue');
            $md = $setting[0];
            $rc = $setting[1];
            $balances = Balances::getStoredUserBalances($bFSsBalances->beneficiary_id);
            DiscountBalances::addLine(
                [
                    'balance_operation_id' => BalanceOperationsEnum::FROM_BFS->value,
                    'operator_id' => Balances::SYSTEM_SOURCE_ID,
                    'beneficiary_id' => $bFSsBalances->beneficiary_id,
                    'reference' => $bFSsBalances->reference,
                    'value' => min($md, $bFSsBalances->value * (pow(abs($bFSsBalances->value - 10), 1.5) / $rc)),
                    'description' => number_format(100 * min($md, $bFSsBalances->value * (pow(abs($bFSsBalances->value - 10), 1.5) / $rc)) / $md, 2, '.', '') . '%',
                    'current_balance' => $balances->discount_balance + min($md, $bFSsBalances->value * (pow(abs($bFSsBalances->value - 10), 1.5) / $rc))
                ]
            );

            $userCurrentBalancehorisontal = Balances::getStoredUserBalances($bFSsBalances->beneficiary_id);

            $newBfssBalanceVertical = floatval($userCurrentBalancehorisontal->getBfssBalance($bFSsBalances->percentage)) + ( BalanceOperation::getMultiplicator($bFSsBalances->balance_operation_id) * $bFSsBalances->value);

            $userCurrentBalancehorisontal->setBfssBalance($bFSsBalances->percentage, $newBfssBalanceVertical);

            $userCurrentBalanceVertical = UserCurrentBalanceVertical::where('user_id', $bFSsBalances->beneficiary_id)
            ->where('balance_id', BalanceEnum::BFS)
            ->first();

            $userCurrentBalanceVertical->update(
            [
                'current_balance' => $userCurrentBalanceVertical->current_balance + $newBfssBalanceVertical,
                'previous_balance' => $userCurrentBalanceVertical->current_balance,
                'last_operation_id' => $bFSsBalances->id,
                'last_operation_value' => $bFSsBalances->value,
                'last_operation_date' => $bFSsBalances->created_at,
            ]
        );
    }
}
