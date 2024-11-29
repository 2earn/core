<?php

namespace App\Services\Balances;

use Illuminate\Support\Facades\Facade;

class BalancesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Balances';
    }
}
