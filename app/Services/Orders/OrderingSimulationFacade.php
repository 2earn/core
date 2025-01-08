<?php

namespace App\Services\Orders;

use Illuminate\Support\Facades\Facade;

class OrderingSimulationFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'OrderingSimulation';
    }
}
