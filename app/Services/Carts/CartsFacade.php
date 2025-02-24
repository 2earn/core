<?php

namespace App\Services\Carts;

use Illuminate\Support\Facades\Facade;

class CartsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Carts';
    }
}
