<?php

namespace App\Observers;

use App\Enums\BalanceEnum;
use App\Models\DiscountBalances;
use App\Services\Balances\Balances;
use App\Models\BalanceOperation;
use App\Services\UserCurrentBalanceHorisontalService;
use App\Services\UserCurrentBalanceVerticalService;
use Illuminate\Support\Facades\Log;

class DiscountObserver
{
    public function __construct(
        private UserCurrentBalanceHorisontalService $userCurrentBalanceHorisontalService,
        private UserCurrentBalanceVerticalService $userCurrentBalanceVerticalService
    ) {
    }

    public function created(DiscountBalances $discountBalances)
    {
        $balanceChange = BalanceOperation::getMultiplicator($discountBalances->balance_operation_id) * $discountBalances->value;

        $balanceData = $this->userCurrentBalanceHorisontalService->calculateNewBalance(
            $discountBalances->beneficiary_id,
            Balances::DISCOUNT_BALANCE,
            $balanceChange
        );

        if ($balanceData) {
            $newDiscountBalanceHorizontal = $balanceData['newBalance'];
            $balanceData['record']->update([Balances::DISCOUNT_BALANCE => $newDiscountBalanceHorizontal]);

            $this->userCurrentBalanceVerticalService->updateBalanceAfterOperation(
                userId: $discountBalances->beneficiary_id,
                balanceId: BalanceEnum::DB,
                balanceChange: $balanceChange,
                lastOperationId: $discountBalances->id,
                lastOperationValue: $discountBalances->value,
                lastOperationDate: $discountBalances->created_at
            );

            Log::info('DiscountObserver current_balance ' . $newDiscountBalanceHorizontal);
        }
    }

}
