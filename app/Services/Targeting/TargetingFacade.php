<?php

namespace App\Services\Targeting;
use Illuminate\Support\Facades\Facade;

class TargetingFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Targeting';
    }
}
