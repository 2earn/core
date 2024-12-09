<?php

namespace App\Observers;

use App\Models\TreeBalances;
use App\Models\UserCurrentBalanceHorisontal;
use App\Models\UserCurrentBalanceVertical;
use Core\Enum\BalanceEnum;
use Illuminate\Support\Facades\DB;

class TreeObserver
{
    public function created(TreeBalances $treeBalances)
    {
        DB::beginTransaction();
        try {
            $userCurrentBalancehorisontal = UserCurrentBalancehorisontal::where('user_id', $treeBalances->beneficiary_id)->first();
        // TO DO
        $newTreeBalanceHorisental = [];

        $userCurrentBalancehorisontal->update(
            [
                'tree_balance' => $newTreeBalanceHorisental
            ]);

        $userCurrentBalanceVertical = UserCurrentBalanceVertical::where('user_id', $treeBalances->beneficiary_id)
            ->where('balance_id', BalanceEnum::CHANCE)
            ->first();
        // TO DO
        $newTreeBalanceVertical = [];

        $userCurrentBalanceVertical->update(
            [
                'current_balance' => $newTreeBalanceVertical,
                'previous_balance' => $userCurrentBalanceVertical->cash_balance,
                'last_operation_id' => $treeBalances->id,
                'last_operation_value' => $treeBalances->value,
                'last_operation_date' => $treeBalances->created_at,
            ]
        );
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

}
