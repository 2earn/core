<?php

namespace App\Services\Balances;

use Core\Services\BalancesManager;

class Ordering
{
    public function __construct(private BalancesManager $balancesManager)
    {
    }

}
