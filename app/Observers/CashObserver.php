<?php

namespace App\Observers;

use App\Enums\BalanceEnum;
use App\Models\CashBalances;
use App\Models\UserCurrentBalanceVertical;
use App\Services\Balances\Balances;
use Core\Models\BalanceOperation;
use Illuminate\Support\Facades\Log;

class CashObserver
{
    public function created(CashBalances $cashBalances)
    {
        $userCurrentBalancehorisontal = Balances::getStoredUserBalances($cashBalances->beneficiary_id);
        $newCashBalanceHorisental = $newCashBalanceVertical = $userCurrentBalancehorisontal->cash_balance + BalanceOperation::getMultiplicator($cashBalances->balance_operation_id) * $cashBalances->value;
        $userCurrentBalancehorisontal->update([Balances::CASH_BALANCE => $newCashBalanceHorisental]);

        $userCurrentBalanceVertical = UserCurrentBalanceVertical::where('user_id', $cashBalances->beneficiary_id)
            ->where('balance_id', BalanceEnum::CASH)
            ->first();

        $userCurrentBalanceVertical->update(
            [
                'current_balance' => $userCurrentBalanceVertical->current_balance + BalanceOperation::getMultiplicator($cashBalances->balance_operation_id) * $cashBalances->value,
                'previous_balance' => $userCurrentBalanceVertical->current_balance,
                'last_operation_id' => $cashBalances->id,
                'last_operation_value' => $cashBalances->value,
                'last_operation_date' => $cashBalances->created_at,
            ]
        );
        Log::info('CashObserver current_balance ' . $newCashBalanceVertical);

    }
}
