<?php

namespace App\Observers;

use App\Enums\BalanceEnum;
use App\Models\CashBalances;
use App\Services\Balances\Balances;
use App\Models\BalanceOperation;
use App\Services\UserCurrentBalanceHorisontalService;
use App\Services\UserCurrentBalanceVerticalService;
use Illuminate\Support\Facades\Log;

class CashObserver
{
    public function __construct(
        private UserCurrentBalanceHorisontalService $userCurrentBalanceHorisontalService,
        private UserCurrentBalanceVerticalService $userCurrentBalanceVerticalService
    ) {
    }

    public function created(CashBalances $cashBalances)
    {
        $balanceChange = BalanceOperation::getMultiplicator($cashBalances->balance_operation_id) * $cashBalances->value;

        $balanceData = $this->userCurrentBalanceHorisontalService->calculateNewBalance(
            $cashBalances->beneficiary_id,
            Balances::CASH_BALANCE,
            $balanceChange
        );

        if ($balanceData) {
            $newCashBalanceHorizontal = $balanceData['newBalance'];
            $balanceData['record']->update([Balances::CASH_BALANCE => $newCashBalanceHorizontal]);

            $this->userCurrentBalanceVerticalService->updateBalanceAfterOperation(
                userId: $cashBalances->beneficiary_id,
                balanceId: BalanceEnum::CASH,
                balanceChange: $balanceChange,
                lastOperationId: $cashBalances->id,
                lastOperationValue: $cashBalances->value,
                lastOperationDate: $cashBalances->created_at
            );

            Log::info('CashObserver current_balance ' . $newCashBalanceHorizontal);
        }
    }
}
