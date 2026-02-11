<?php

namespace App\Observers;

use App\Enums\BalanceEnum;
use App\Models\TreeBalances;
use App\Services\Balances\Balances;
use App\Models\BalanceOperation;
use App\Services\UserBalances\UserCurrentBalanceHorisontalService;
use App\Services\UserBalances\UserCurrentBalanceVerticalService;
use Illuminate\Support\Facades\Log;

class TreeObserver
{
    public function __construct(
        private UserCurrentBalanceHorisontalService $userCurrentBalanceHorisontalService,
        private UserCurrentBalanceVerticalService $userCurrentBalanceVerticalService
    ) {
    }

    public function created(TreeBalances $treeBalances)
    {
        $balanceChange = BalanceOperation::getMultiplicator($treeBalances->balance_operation_id) * $treeBalances->value;

        $balanceData = $this->userCurrentBalanceHorisontalService->calculateNewBalance(
            $treeBalances->beneficiary_id,
            'tree_balance',
            $balanceChange
        );

        if ($balanceData) {
            $newTreeBalanceHorizontal = $balanceData['newBalance'];
            $balanceData['record']->update(['tree_balance' => $newTreeBalanceHorizontal]);

            $this->userCurrentBalanceVerticalService->updateBalanceAfterOperation(
                userId: $treeBalances->beneficiary_id,
                balanceId: BalanceEnum::TREE,
                balanceChange: $balanceChange,
                lastOperationId: $treeBalances->id,
                lastOperationValue: $treeBalances->value,
                lastOperationDate: $treeBalances->created_at
            );

            Log::info('TreeObserver current_balance ' . $newTreeBalanceHorizontal);
        }
    }
}
