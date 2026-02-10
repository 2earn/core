<?php

namespace App\Observers;

use App\Enums\BalanceEnum;
use App\Models\SmsBalances;
use App\Services\Balances\Balances;
use App\Models\BalanceOperation;
use App\Services\UserBalances\UserCurrentBalanceHorisontalService;
use App\Services\UserBalances\UserCurrentBalanceVerticalService;
use Illuminate\Support\Facades\Log;

class SmsObserver
{
    public function __construct(
        private UserCurrentBalanceHorisontalService $userCurrentBalanceHorisontalService,
        private UserCurrentBalanceVerticalService $userCurrentBalanceVerticalService
    ) {
    }

    public function created(SmsBalances $smsBalances)
    {
        $balanceChange = BalanceOperation::getMultiplicator($smsBalances->balance_operation_id) * $smsBalances->value;

        $balanceData = $this->userCurrentBalanceHorisontalService->calculateNewBalance(
            $smsBalances->beneficiary_id,
            'sms_balance',
            $balanceChange
        );

        if ($balanceData) {
            $newSmsBalanceHorizontal = $balanceData['newBalance'];
            $balanceData['record']->update(['sms_balance' => $newSmsBalanceHorizontal]);

            $this->userCurrentBalanceVerticalService->updateBalanceAfterOperation(
                userId: $smsBalances->beneficiary_id,
                balanceId: BalanceEnum::SMS,
                balanceChange: $balanceChange,
                lastOperationId: $smsBalances->id,
                lastOperationValue: $smsBalances->value,
                lastOperationDate: $smsBalances->created_at
            );

            Log::info('SmsObserver current_balance ' . $newSmsBalanceHorizontal);
        }
    }

}
