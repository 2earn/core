<?php

namespace App\Observers;

use App\Models\SmsBalances;
use App\Models\UserCurrentBalanceVertical;
use App\Services\Balances\Balances;
use Core\Enum\BalanceEnum;
use Core\Models\BalanceOperation;

class SmsObserver
{

    public function created(SmsBalances $smsBalances)
    {
        $userCurrentBalancehorisontal = Balances::getStoredUserBalances($smsBalances->beneficiary_id);
        $newSmsBalanceHorisental = $newSmsBalanceVertical = $userCurrentBalancehorisontal->sms_balance + BalanceOperation::getMultiplicator($smsBalances->balance_operation_id) * $smsBalances->value;

        $userCurrentBalancehorisontal->update(['sms_balance' => $newSmsBalanceHorisental]);

        $userCurrentBalanceVertical = UserCurrentBalanceVertical::where('user_id', $smsBalances->beneficiary_id)
            ->where('balance_id', BalanceEnum::SMS)
            ->first();

        $userCurrentBalanceVertical->update(
            [
                'current_balance' => $userCurrentBalanceVertical->current_balance + BalanceOperation::getMultiplicator($smsBalances->balance_operation_id)* $newSmsBalanceVertical,
                'previous_balance' => $userCurrentBalanceVertical->current_balance,
                'last_operation_id' => $smsBalances->id,
                'last_operation_value' => $smsBalances->value,
                'last_operation_date' => $smsBalances->created_at,
            ]
        );
    }

}
