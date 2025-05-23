<?php

namespace App\Services\Orders;

use Illuminate\Support\Facades\Facade;

class OrderingFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Cart';
    }
}
