<?php

namespace App\Services\UserBalances;

use App\Enums\BalanceEnum;
use App\Models\UserCurrentBalanceVertical;
use Illuminate\Support\Facades\Log;

class UserCurrentBalanceVerticalService
{
    /**
     * Get user's current balance vertical record by user ID and balance type
     *
     * @param int $userId
     * @param BalanceEnum|int|string $balanceId Balance type/ID
     * @return UserCurrentBalanceVertical|null
     */
    public function getUserBalanceVertical(int $userId, BalanceEnum|int|string $balanceId): ?UserCurrentBalanceVertical
    {
        $balanceIdValue = $balanceId instanceof BalanceEnum ? $balanceId->value : $balanceId;

        return UserCurrentBalanceVertical::where('user_id', $userId)
            ->where('balance_id', $balanceIdValue)
            ->first();
    }

    /**
     * Update vertical balance after a balance operation
     *
     * @param int $userId
     * @param BalanceEnum|int|string $balanceId Balance type/ID
     * @param float $balanceChange The amount to add/subtract from current balance
     * @param int $lastOperationId Last operation ID
     * @param float $lastOperationValue Last operation value
     * @param string $lastOperationDate Last operation date
     * @return bool
     */
    public function updateBalanceAfterOperation(
        int $userId,
        BalanceEnum|int|string $balanceId,
        float $balanceChange,
        int $lastOperationId,
        float $lastOperationValue,
        string $lastOperationDate
    ): bool {
        try {
            $userCurrentBalanceVertical = $this->getUserBalanceVertical($userId, $balanceId);

            if (!$userCurrentBalanceVertical) {
                Log::warning('UserCurrentBalanceVertical not found', [
                    'user_id' => $userId,
                    'balance_id' => $balanceId instanceof BalanceEnum ? $balanceId->value : $balanceId
                ]);
                return false;
            }

            $userCurrentBalanceVertical->update([
                'current_balance' => $userCurrentBalanceVertical->current_balance + $balanceChange,
                'previous_balance' => $userCurrentBalanceVertical->current_balance,
                'last_operation_id' => $lastOperationId,
                'last_operation_value' => $lastOperationValue,
                'last_operation_date' => $lastOperationDate,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error updating vertical balance: ' . $e->getMessage(), [
                'user_id' => $userId,
                'balance_id' => $balanceId instanceof BalanceEnum ? $balanceId->value : $balanceId,
                'balance_change' => $balanceChange
            ]);
            return false;
        }
    }

    /**
     * Update calculated vertical balance
     *
     * @param int $idUser
     * @param BalanceEnum|int|string $type Balance type/ID
     * @param float|int $value
     * @return int Number of rows updated
     */
    public function updateCalculatedVertical(int $idUser, BalanceEnum|int|string $type, $value): int
    {
        $balanceIdValue = $type instanceof BalanceEnum ? $type->value : $type;

        return UserCurrentBalanceVertical::where('user_id', $idUser)
            ->where('balance_id', $balanceIdValue)
            ->update(['current_balance' => $value]);
    }

    /**
     * Get all vertical balances for a user
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllUserBalances(int $userId)
    {
        try {
            return UserCurrentBalanceVertical::where('user_id', $userId)->get();
        } catch (\Exception $e) {
            Log::error('Error getting all user balances', [
                'userId' => $userId,
                'error' => $e->getMessage()
            ]);
            return new \Illuminate\Database\Eloquent\Collection();
        }
    }
}


