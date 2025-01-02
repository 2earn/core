<?php

namespace App\Services\Balances;

use Illuminate\Support\Facades\Facade;

class OrderingFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Ordering';
    }
}
