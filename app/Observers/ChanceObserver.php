<?php

namespace App\Observers;

use App\Models\ChanceBalances;
use App\Models\UserCurrentBalanceHorisontal;
use App\Models\UserCurrentBalanceVertical;
use Core\Enum\BalanceEnum;
use Illuminate\Support\Facades\DB;

class ChanceObserver
{
    public function created(ChanceBalances $chanceBalances)
    {
        DB::beginTransaction();
        try {
            $userCurrentBalancehorisontal = UserCurrentBalancehorisontal::where('user_id', $chanceBalances->beneficiary_id)->first();
        // TO DO
        $newChanceBalanceHorisental = [];

        $userCurrentBalancehorisontal->update(
            [
                'chance_balance' => $newChanceBalanceHorisental
            ]);

        $userCurrentBalanceVertical = UserCurrentBalanceVertical::where('user_id', $chanceBalances->beneficiary_id)
            ->where('balance_id', BalanceEnum::CHANCE)
            ->first();
        // TO DO
        $newChanceBalanceVertical = [];

        $userCurrentBalanceVertical->update(
            [
                'current_balance' => $newChanceBalanceVertical,
                'previous_balance' => $userCurrentBalanceVertical->cash_balance,
                'last_operation_id' => $chanceBalances->id,
                'last_operation_value' => $chanceBalances->value,
                'last_operation_date' => $chanceBalances->created_at,
            ]
        );
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }


}
