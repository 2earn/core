<?php

namespace App\Observers;

use App\Models\SharesBalances;
use App\Models\UserCurrentBalanceVertical;
use App\Services\Balances\Balances;
use Core\Enum\BalanceEnum;
use Core\Models\BalanceOperation;
use Illuminate\Support\Facades\Log;

class ShareObserver
{
    public function created(SharesBalances $shareBalances)
    {

        $userCurrentBalancehorisontal = Balances::getStoredUserBalances($shareBalances->beneficiary_id);
        $newShareBalanceHorisental = $newShareBalanceVertical = $userCurrentBalancehorisontal->share_balance + BalanceOperation::getMultiplicator($shareBalances->balance_operation_id) * $shareBalances->value;
        $userCurrentBalancehorisontal->update([Balances::SHARE_BALANCE => $newShareBalanceHorisental]);
        $userCurrentBalanceVertical = UserCurrentBalanceVertical::where('user_id', $shareBalances->beneficiary_id)
            ->where('balance_id', BalanceEnum::SHARE)
            ->first();

        $userCurrentBalanceVertical->update(
            [
                'current_balance' => $newShareBalanceVertical,
                'previous_balance' => $userCurrentBalanceVertical->current_balance,
                'last_operation_id' => $shareBalances->id,
                'last_operation_value' => $shareBalances->value,
                'last_operation_date' => $shareBalances->created_at,
            ]
        );

        Log::info('ShareObserver current_balance '. $newShareBalanceVertical,);

    }

}
