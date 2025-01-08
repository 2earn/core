<?php

namespace App\Services\Orders;

use Core\Services\BalancesManager;

class OrderingSimulation
{
    public function __construct(private BalancesManager $balancesManager)
    {
    }

    public static function simulate(): bool
    {
        dd('simulation');
    }
}
