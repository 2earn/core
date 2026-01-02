<?php

namespace App\Observers;

use App\Enums\BalanceEnum;
use App\Models\ChanceBalances;
use App\Services\Balances\Balances;
use App\Models\BalanceOperation;
use App\Services\UserCurrentBalanceVerticalService;
use Illuminate\Support\Facades\Log;

class ChanceObserver
{
    public function __construct(
        private UserCurrentBalanceVerticalService $userCurrentBalanceVerticalService
    ) {
    }

    public function created(ChanceBalances $chanceBalances)
    {
        // Update horizontal balance
        $userCurrentBalancehorisontal = Balances::getStoredUserBalances($chanceBalances->beneficiary_id);
        $newChanceBalanceVertical = $userCurrentBalancehorisontal->getChancesBalance($chanceBalances->pool_id) + BalanceOperation::getMultiplicator($chanceBalances->balance_operation_id) * $chanceBalances->value;
        $userCurrentBalancehorisontal->setChancesBalance($chanceBalances->pool_id, $newChanceBalanceVertical);

        // Update vertical balance using service
        $balanceChange = BalanceOperation::getMultiplicator($chanceBalances->balance_operation_id) * $chanceBalances->value;

        $this->userCurrentBalanceVerticalService->updateBalanceAfterOperation(
            userId: $chanceBalances->beneficiary_id,
            balanceId: BalanceEnum::CHANCE,
            balanceChange: $balanceChange,
            lastOperationId: $chanceBalances->id,
            lastOperationValue: $chanceBalances->value,
            lastOperationDate: $chanceBalances->created_at
        );

        // Get updated vertical balance for logging
        $userCurrentBalanceVertical = $this->userCurrentBalanceVerticalService->getUserBalanceVertical(
            $chanceBalances->beneficiary_id,
            BalanceEnum::CHANCE
        );

        Log::info('ChanceObserver current_balance ' . $newChanceBalanceVertical . '(Pool: ' . $chanceBalances->pool_id . ') => Total Chances: ' . ($userCurrentBalanceVertical?->current_balance ?? 0));
    }
}
