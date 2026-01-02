<?php

namespace App\Services;

use App\Models\UserCurrentBalanceVertical;

class UserCurrentBalanceVerticalService
{
    /**
     * Update calculated vertical balance
     *
     * @param int $idUser
     * @param string|int $type Balance type/ID
     * @param float|int $value
     * @return int Number of rows updated
     */
    public function updateCalculatedVertical(int $idUser, $type, $value): int
    {
        return UserCurrentBalanceVertical::where('user_id', $idUser)
            ->where('balance_id', $type)
            ->update(['current_balance' => $value]);
    }
}

