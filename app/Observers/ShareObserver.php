<?php

namespace App\Observers;

use App\Models\SharesBalances;
use App\Models\UserCurrentBalanceVertical;
use App\Services\Balances\Balances;
use Core\Enum\BalanceEnum;
use Core\Models\BalanceOperation;

class ShareObserver
{
    public function created(SharesBalances $shareBalances)
    {

        $userCurrentBalancehorisontal = Balances::getStoredUserBalances($shareBalances->beneficiary_id);
            $newShareBalanceHorisental = $newShareBalanceVertical = $userCurrentBalancehorisontal->share_balance + BalanceOperation::getMultiplicator($shareBalances->balance_operation_id) * $shareBalances->value;
            $userCurrentBalancehorisontal->update(['share_balance' => $newShareBalanceHorisental]);
        $userCurrentBalanceVertical = UserCurrentBalanceVertical::where('user_id', $shareBalances->beneficiary_id)
            ->where('balance_id', BalanceEnum::CHANCE)
            ->first();


            $userCurrentBalanceVertical->update(
            [
                'current_balance' => $newShareBalanceVertical,
                'previous_balance' => $userCurrentBalanceVertical->cash_balance,
                'last_operation_id' => $shareBalances->id,
                'last_operation_value' => $shareBalances->value,
                'last_operation_date' => $shareBalances->created_at,
            ]
        );
    }

}
