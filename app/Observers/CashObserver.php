<?php

namespace App\Observers;

use App\Models\CashBalances;
use App\Models\UserCurrentBalanceHorisontal;
use App\Models\UserCurrentBalanceVertical;
use Core\Enum\BalanceEnum;
use Illuminate\Support\Facades\DB;

class CashObserver
{
    public function created(CashBalances $cashBalances)
    {
        DB::beginTransaction();
        try {
            $userCurrentBalancehorisontal = UserCurrentBalancehorisontal::where('user_id', $cashBalances->beneficiary_id)->first();

        $newCashBalanceHorisental = $userCurrentBalancehorisontal->cash_balance + $cashBalances->value;
        $userCurrentBalancehorisontal->update(
            [
                'cash_balance' => $newCashBalanceHorisental
            ]);

        $userCurrentBalanceVertical = UserCurrentBalanceVertical::where('user_id', $cashBalances->beneficiary_id)
            ->where('balance_id', BalanceEnum::CASH)
            ->first();
        $newCashBalanceVertical = $userCurrentBalanceVertical->cash_balance + $cashBalances->value;

        $userCurrentBalanceVertical->update(
            [
                'current_balance' => $newCashBalanceVertical,
                'previous_balance' => $userCurrentBalanceVertical->cash_balance,
                'last_operation_id' => $cashBalances->id,
                'last_operation_value' => $cashBalances->value,
                'last_operation_date' => $cashBalances->created_at,
            ]
        );
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

}
