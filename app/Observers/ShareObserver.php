<?php

namespace App\Observers;

use App\Enums\BalanceEnum;
use App\Models\SharesBalances;
use App\Services\Balances\Balances;
use App\Models\BalanceOperation;
use App\Services\UserCurrentBalanceHorisontalService;
use App\Services\UserCurrentBalanceVerticalService;
use Illuminate\Support\Facades\Log;

class ShareObserver
{
    public function __construct(
        private UserCurrentBalanceHorisontalService $userCurrentBalanceHorisontalService,
        private UserCurrentBalanceVerticalService $userCurrentBalanceVerticalService
    ) {
    }

    public function created(SharesBalances $shareBalances)
    {
        // Calculate balance change
        $balanceChange = BalanceOperation::getMultiplicator($shareBalances->balance_operation_id) * $shareBalances->value;

        // Update horizontal balance using service
        $balanceData = $this->userCurrentBalanceHorisontalService->calculateNewBalance(
            $shareBalances->beneficiary_id,
            Balances::SHARE_BALANCE,
            $balanceChange
        );

        if ($balanceData) {
            $newShareBalanceHorizontal = $balanceData['newBalance'];
            $balanceData['record']->update([Balances::SHARE_BALANCE => $newShareBalanceHorizontal]);

            // Update vertical balance using service
            $this->userCurrentBalanceVerticalService->updateBalanceAfterOperation(
                userId: $shareBalances->beneficiary_id,
                balanceId: BalanceEnum::SHARE,
                balanceChange: $balanceChange,
                lastOperationId: $shareBalances->id,
                lastOperationValue: $shareBalances->value,
                lastOperationDate: $shareBalances->created_at
            );

            Log::info('ShareObserver current_balance ' . $newShareBalanceHorizontal);
        }
    }

}
