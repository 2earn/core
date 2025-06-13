<?php

namespace App\Observers;

use App\Models\TreeBalances;
use App\Models\UserCurrentBalanceVertical;
use App\Services\Balances\Balances;
use Core\Enum\BalanceEnum;
use Core\Models\BalanceOperation;

class TreeObserver
{
    public function created(TreeBalances $treeBalances)
    {

        $userCurrentBalancehorisontal = Balances::getStoredUserBalances($treeBalances->beneficiary_id);
        $newTreeBalanceHorisental = $newTreeBalanceVertical = $userCurrentBalancehorisontal->tree_balance + BalanceOperation::getMultiplicator($treeBalances->balance_operation_id) * $treeBalances->value;

        $userCurrentBalancehorisontal->update(['tree_balance' => $newTreeBalanceHorisental]);

        $userCurrentBalanceVertical = UserCurrentBalanceVertical::where('user_id', $treeBalances->beneficiary_id)
            ->where('balance_id', BalanceEnum::TREE)
            ->first();

        $userCurrentBalanceVertical->update(
            [
                'current_balance' => $userCurrentBalanceVertical->current_balance + BalanceOperation::getMultiplicator($treeBalances->balance_operation_id) * $newTreeBalanceVertical,
                'previous_balance' => $userCurrentBalanceVertical->current_balance,
                'last_operation_id' => $treeBalances->id,
                'last_operation_value' => $treeBalances->value,
                'last_operation_date' => $treeBalances->created_at,
            ]
        );
    }

}
