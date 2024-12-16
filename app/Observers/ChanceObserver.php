<?php

namespace App\Observers;

use App\Models\ChanceBalances;
use App\Models\UserCurrentBalanceHorisontal;
use App\Models\UserCurrentBalanceVertical;
use App\Services\Balances\Balances;
use Core\Enum\BalanceEnum;
use Core\Models\BalanceOperation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChanceObserver
{
    public function created(ChanceBalances $chanceBalances)
    {
            $userCurrentBalancehorisontal = Balances::getStoredUserBalances($chanceBalances->beneficiary_id);
            $old = json_decode($chanceBalances->chance_balance);
            if (array_key_exists($chanceBalances->persontage, $old)) {
                $old[$chanceBalances->persontage] = $newChanceBalanceVertical = $old[$chanceBalances->persontage] + BalanceOperation::getMultiplicator($chanceBalances->balance_operation_id) * $chanceBalances->value;
            } else {
                $old[$chanceBalances->persontage] = $newChanceBalanceVertical = BalanceOperation::getMultiplicator($chanceBalances->balance_operation_id) * $chanceBalances->value;
            }
            $newChanceBalanceHorisental = json_encode($old);
            $userCurrentBalancehorisontal->update(['chance_balance' => $newChanceBalanceHorisental]);

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
