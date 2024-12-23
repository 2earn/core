<?php

namespace App\Observers;

use App\Models\CashBalances;
use App\Models\UserCurrentBalanceVertical;
use App\Services\Balances\Balances;
use Core\Enum\BalanceEnum;
use Core\Models\BalanceOperation;

class CashObserver
{
    public function created(CashBalances $cashBalances)
    {
            $userCurrentBalancehorisontal = Balances::getStoredUserBalances($cashBalances->beneficiary_id);
            $newCashBalanceHorisental = $newCashBalanceVertical= $userCurrentBalancehorisontal->cash_balance + BalanceOperation::getMultiplicator($cashBalances->balance_operation_id)* $cashBalances->value;
            $userCurrentBalancehorisontal->update(['cash_balance' => $newCashBalanceHorisental]);

            $userCurrentBalanceVertical = UserCurrentBalanceVertical::where('user_id', $cashBalances->beneficiary_id)
            ->where('balance_id', BalanceEnum::CASH)
            ->first();

            $userCurrentBalanceVertical->update(
            [
                'current_balance' => $userCurrentBalanceVertical->cash_balance+$newCashBalanceVertical,
                'previous_balance' => $userCurrentBalanceVertical->cash_balance,
                'last_operation_id' => $cashBalances->id,
                'last_operation_value' => $cashBalances->value,
                'last_operation_date' => $cashBalances->created_at,
            ]
        );

    }

}
