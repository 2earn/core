<?php

namespace App\Observers;

use App\Models\SmsBalances;
use App\Models\UserCurrentBalanceHorisontal;
use App\Models\UserCurrentBalanceVertical;
use Core\Enum\BalanceEnum;
use Illuminate\Support\Facades\DB;

class SmsObserver
{

    public function created(SmsBalances $smsBalances)
    {
        DB::beginTransaction();
        try {
            $userCurrentBalancehorisontal = UserCurrentBalancehorisontal::where('user_id', $smsBalances->beneficiary_id)->first();
        // TO DO
        $newSmsBalanceHorisental = [];

        $userCurrentBalancehorisontal->update(
            [
                'sms_balance' => $newSmsBalanceHorisental
            ]);

        $userCurrentBalanceVertical = UserCurrentBalanceVertical::where('user_id', $smsBalances->beneficiary_id)
            ->where('balance_id', BalanceEnum::CHANCE)
            ->first();
        // TO DO
        $newSmsBalanceVertical = [];

        $userCurrentBalanceVertical->update(
            [
                'current_balance' => $newSmsBalanceVertical,
                'previous_balance' => $userCurrentBalanceVertical->cash_balance,
                'last_operation_id' => $smsBalances->id,
                'last_operation_value' => $smsBalances->value,
                'last_operation_date' => $smsBalances->created_at,
            ]
        );
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

}
