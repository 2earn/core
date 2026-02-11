<?php

namespace App\Services\UserBalances;

use App\Models\UserCurrentBalanceHorisontal;

class UserCurrentBalanceHorisontalService
{
    /**
     * Get user balance record or specific balance field
     *
     * @param int $idUser
     * @param string|null $balances The balance field to retrieve (e.g., 'cash_balances')
     * @return UserCurrentBalanceHorisontal|mixed|null
     */
    public function getStoredUserBalances(int $idUser, ?string $balances = null)
    {
        if (is_null($balances)) {
            return UserCurrentBalanceHorisontal::where('user_id', $idUser)->first();
        }
        return UserCurrentBalanceHorisontal::where('user_id', $idUser)->pluck($balances)->first();
    }

    /**
     * Get user's cash balance
     *
     * @param int $idUser
     * @return mixed
     */
    public function getStoredCash(int $idUser)
    {
        return $this->getStoredUserBalances($idUser, 'cash_balance');
    }

    /**
     * Get user's BFSS balance
     *
     * @param int $idUser
     * @param string $type
     * @return float|int
     */
    public function getStoredBfss(int $idUser, string $type)
    {
        $userCurrentBalanceHorisontal = $this->getStoredUserBalances($idUser);
        return $userCurrentBalanceHorisontal?->getBfssBalance($type) ?? 0;
    }

    /**
     * Get user's discount balance
     *
     * @param int $idUser
     * @return mixed
     */
    public function getStoredDiscount(int $idUser)
    {
        return $this->getStoredUserBalances($idUser, 'discount_balance');
    }

    /**
     * Get user's tree balance
     *
     * @param int $idUser
     * @return mixed
     */
    public function getStoredTree(int $idUser)
    {
        return $this->getStoredUserBalances($idUser, 'tree_balance');
    }

    /**
     * Get user's SMS balance
     *
     * @param int $idUser
     * @return mixed
     */
    public function getStoredSms(int $idUser)
    {
        return $this->getStoredUserBalances($idUser, 'sms_balance');
    }

    /**
     * Update calculated horizontal balance
     *
     * @param int $idUser
     * @param string $type Balance type field name (e.g., 'cash_balance')
     * @param float|int $value
     * @return int Number of rows updated
     */
    public function updateCalculatedHorisental(int $idUser, string $type, $value): int
    {
        return UserCurrentBalanceHorisontal::where('user_id', $idUser)->update([$type => $value]);
    }

    /**
     * Update horizontal balance after a balance operation
     *
     * @param int $userId
     * @param string $balanceField The balance field to update (e.g., 'share_balance', 'cash_balance')
     * @param float $newBalance The new balance value to set
     * @return bool
     */
    public function updateBalanceField(int $userId, string $balanceField, float $newBalance): bool
    {
        $userBalance = $this->getStoredUserBalances($userId);

        if (!$userBalance) {
            return false;
        }

        return (bool) $userBalance->update([$balanceField => $newBalance]);
    }

    /**
     * Get user balance and calculate new value after operation
     *
     * @param int $userId
     * @param string $balanceField The balance field (e.g., 'share_balance')
     * @param float $changeAmount The amount to add/subtract
     * @return array ['record' => UserCurrentBalanceHorisontal, 'newBalance' => float]|null
     */
    public function calculateNewBalance(int $userId, string $balanceField, float $changeAmount): ?array
    {
        $userBalance = $this->getStoredUserBalances($userId);

        if (!$userBalance) {
            return null;
        }

        $currentBalance = $userBalance->{$balanceField} ?? 0;
        $newBalance = $currentBalance + $changeAmount;

        return [
            'record' => $userBalance,
            'currentBalance' => $currentBalance,
            'newBalance' => $newBalance
        ];
    }
}

