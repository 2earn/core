<?php

namespace App\Observers;

use App\Models\SmsBalances;
use App\Models\UserCurrentBalanceHorisontal;
use App\Models\UserCurrentBalanceVertical;
use App\Services\Balances\Balances;
use Core\Enum\BalanceEnum;
use Core\Models\BalanceOperation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SmsObserver
{

    public function created(SmsBalances $smsBalances)
    {
            $userCurrentBalancehorisontal = Balances::getStoredUserBalances($smsBalances->beneficiary_id);
            $newSmsBalanceHorisental = $newSmsBalanceVertical = $userCurrentBalancehorisontal->sms_balance + BalanceOperation::getMultiplicator($smsBalances->balance_operation_id) * $smsBalances->value;

            $userCurrentBalancehorisontal->update(['sms_balance' => $newSmsBalanceHorisental]);

        $userCurrentBalanceVertical = UserCurrentBalanceVertical::where('user_id', $smsBalances->beneficiary_id)
            ->where('balance_id', BalanceEnum::CHANCE)
            ->first();

        $userCurrentBalanceVertical->update(
            [
                'current_balance' => $userCurrentBalanceVertical->sms_balance + $newSmsBalanceVertical,
                'previous_balance' => $userCurrentBalanceVertical->sms_balance,
                'last_operation_id' => $smsBalances->id,
                'last_operation_value' => $smsBalances->value,
                'last_operation_date' => $smsBalances->created_at,
            ]
        );
    }

}
