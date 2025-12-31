<?php

namespace App\Services;

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
}

