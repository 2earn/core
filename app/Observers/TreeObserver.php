<?php

namespace App\Observers;

use App\Models\TreeBalances;
use App\Models\UserCurrentBalanceHorisontal;
use App\Models\UserCurrentBalanceVertical;
use Core\Enum\BalanceEnum;
use Core\Models\BalanceOperation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TreeObserver
{
    public function created(TreeBalances $treeBalances)
    {

            $userCurrentBalancehorisontal = UserCurrentBalanceHorisontal::where('user_id', $treeBalances->beneficiary_id)->first();
            $newTreeBalanceHorisental = $newTreeBalanceVertical = $userCurrentBalancehorisontal->treeBalance +  BalanceOperation::getMultiplicator($treeBalances->balance_operation_id)  * $treeBalances->value;

            $userCurrentBalancehorisontal->update(['tree_balance' => $newTreeBalanceHorisental]);

        $userCurrentBalanceVertical = UserCurrentBalanceVertical::where('user_id', $treeBalances->beneficiary_id)
            ->where('balance_id', BalanceEnum::CHANCE)
            ->first();

        $userCurrentBalanceVertical->update(
            [
                'current_balance' => $userCurrentBalanceVertical->tree_balance + $newTreeBalanceVertical,
                'previous_balance' => $userCurrentBalanceVertical->tree_balance,
                'last_operation_id' => $treeBalances->id,
                'last_operation_value' => $treeBalances->value,
                'last_operation_date' => $treeBalances->created_at,
            ]
        );
    }

}
