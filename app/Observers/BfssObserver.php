<?php

namespace App\Observers;

use App\Enums\BalanceEnum;
use App\Enums\BalanceOperationsEnum;
use App\Models\BFSsBalances;
use App\Models\DiscountBalances;
use App\Models\SharesBalances;
use App\Services\Balances\Balances;
use App\Services\Balances\BalancesFacade;
use App\Models\BalanceOperation;
use App\Services\BalancesManager;
use App\Services\UserBalances\UserCurrentBalanceVerticalService;
use Illuminate\Support\Facades\Log;


class BfssObserver
{
    const MIN_BFSS_TO_GET_ACTION = 800;

    public function __construct(
        private BalancesManager $balancesManager,
        private UserCurrentBalanceVerticalService $userCurrentBalanceVerticalService
    ) {
    }

    public function checkDiscountFromGiftedBFs(BFSsBalances $bFSsBalances)
    {
        $balances = Balances::getStoredUserBalances($bFSsBalances->beneficiary_id);
        $value = Balances::getDiscountEarnedFromBFS100I($bFSsBalances->value);
        DiscountBalances::addLine([
                'balance_operation_id' => BalanceOperationsEnum::OLD_ID_47->value,
                'operator_id' => Balances::SYSTEM_SOURCE_ID,
                'beneficiary_id' => $bFSsBalances->beneficiary_id,
                'reference' => $bFSsBalances->reference,
                'value' => $value,
                'current_balance' => $balances->discount_balance + $value
            ]
        );
    }

    public function checkSharesFromGiftedBFs(BFSsBalances $bFSsBalances)
    {
        $minBfs = getSettingIntegerParam('MIN_BFSS_TO_GET_ACTION', self::MIN_BFSS_TO_GET_ACTION);
        if ($minBfs <= $bFSsBalances->value) {
            $actualActionValue = actualActionValue(getSelledActions(true), false);
            $ref = BalancesFacade::getReference(BalanceOperationsEnum::OLD_ID_64->value);
            $numberOfActions = intval($bFSsBalances->value / $actualActionValue);
            $balances = Balances::getStoredUserBalances(auth()->user()->idUser);
            SharesBalances::addLine([
                'balance_operation_id' => BalanceOperationsEnum::OLD_ID_64->value,
                'operator_id' => Balances::SYSTEM_SOURCE_ID,
                'beneficiary_id' => auth()->user()->idUser,
                'reference' => $ref,
                'unit_price' => 0,
                'description' => $numberOfActions . ' share(s) from BFS',
                'value' => $numberOfActions,
                'current_balance' => $balances->share_balance + $numberOfActions
            ]);
        }
    }

    public function created(BFSsBalances $bFSsBalances)
    {
        $balanceOperation = BalanceOperation::find($bFSsBalances->balance_operation_id);
        if ($bFSsBalances->percentage == "100.00" && $balanceOperation->direction == 'IN') {
            $this->checkDiscountFromGiftedBFs($bFSsBalances);
            $this->checkSharesFromGiftedBFs($bFSsBalances);
        }

        $userCurrentBalancehorisontal = Balances::getStoredUserBalances($bFSsBalances->beneficiary_id);
        $newBfssBalanceVertical = floatval($userCurrentBalancehorisontal->getBfssBalance($bFSsBalances->percentage)) + (BalanceOperation::getMultiplicator($bFSsBalances->balance_operation_id) * $bFSsBalances->value);
        $userCurrentBalancehorisontal->setBfssBalance($bFSsBalances->percentage, $newBfssBalanceVertical);

        $balanceChange = BalanceOperation::getMultiplicator($bFSsBalances->balance_operation_id) * $bFSsBalances->value;

        $this->userCurrentBalanceVerticalService->updateBalanceAfterOperation(
            userId: $bFSsBalances->beneficiary_id,
            balanceId: BalanceEnum::BFS,
            balanceChange: $balanceChange,
            lastOperationId: $bFSsBalances->id,
            lastOperationValue: $bFSsBalances->value,
            lastOperationDate: $bFSsBalances->created_at
        );

        $userCurrentBalanceVertical = $this->userCurrentBalanceVerticalService->getUserBalanceVertical(
            $bFSsBalances->beneficiary_id,
            BalanceEnum::BFS
        );

        Log::info('BfsObserver current_balance ' . $newBfssBalanceVertical . '(Percentage: ' . $bFSsBalances->percentage . ') => Total Bfss: ' . ($userCurrentBalanceVertical?->current_balance ?? 0));
    }
}
