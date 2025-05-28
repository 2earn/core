<?php

namespace App\Observers;

use App\Models\ChanceBalances;
use App\Models\UserCurrentBalanceVertical;
use App\Services\Balances\Balances;
use Core\Enum\BalanceEnum;
use Core\Models\BalanceOperation;

class ChanceObserver
{
    public function created(ChanceBalances $chanceBalances)
    {
        $userCurrentBalancehorisontal = Balances::getStoredUserBalances($chanceBalances->beneficiary_id);
        $newChanceBalanceHorisental = $newChanceBalanceVertical = $userCurrentBalancehorisontal->chances_balance + BalanceOperation::getMultiplicator($chanceBalances->balance_operation_id) * $chanceBalances->value;

        $userCurrentBalancehorisontal->update(['chances_balance' => $newChanceBalanceHorisental]);

        $userCurrentBalanceVertical = UserCurrentBalanceVertical::where('user_id', $chanceBalances->beneficiary_id)
            ->where('balance_id', BalanceEnum::CHANCE)
            ->first();

        $userCurrentBalanceVertical->update(
            [
                'current_balance' => $userCurrentBalanceVertical->current_balance + $newChanceBalanceVertical,
                'previous_balance' => $userCurrentBalanceVertical->current_balance,
                'last_operation_id' => $chanceBalances->id,
                'last_operation_value' => $chanceBalances->value,
                'last_operation_date' => $chanceBalances->created_at,
            ]
        );
    }


}
