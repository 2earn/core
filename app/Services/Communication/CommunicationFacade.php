<?php

namespace App\Services\Communication;
use Illuminate\Support\Facades\Facade;

class CommunicationFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Communication';
    }
}
