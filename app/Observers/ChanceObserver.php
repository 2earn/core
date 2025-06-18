<?php

namespace App\Observers;

use App\Models\ChanceBalances;
use App\Models\UserCurrentBalanceVertical;
use App\Services\Balances\Balances;
use Core\Enum\BalanceEnum;
use Core\Models\BalanceOperation;
use Illuminate\Support\Facades\Log;

class ChanceObserver
{
    public function created(ChanceBalances $chanceBalances)
    {
        $userCurrentBalancehorisontal = Balances::getStoredUserBalances($chanceBalances->beneficiary_id);

        $newChanceBalanceVertical = $userCurrentBalancehorisontal->getChancesBalance($chanceBalances->pool_id) + BalanceOperation::getMultiplicator($chanceBalances->balance_operation_id) * $chanceBalances->value;
        $userCurrentBalancehorisontal->setChancesBalance($chanceBalances->pool_id, $newChanceBalanceVertical);

        $userCurrentBalanceVertical = UserCurrentBalanceVertical::where('user_id', $chanceBalances->beneficiary_id)
            ->where('balance_id', BalanceEnum::CHANCE)
            ->first();

        $userCurrentBalanceVertical->update(
            [
                'current_balance' => $userCurrentBalanceVertical->current_balance + BalanceOperation::getMultiplicator($chanceBalances->balance_operation_id) * $chanceBalances->value,
                'previous_balance' => $userCurrentBalanceVertical->current_balance,
                'last_operation_id' => $chanceBalances->id,
                'last_operation_value' => $chanceBalances->value,
                'last_operation_date' => $chanceBalances->created_at,
            ]
        );

        Log::info('ChanceObserver current_balance ' . $newChanceBalanceVertical);

    }


}
