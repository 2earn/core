<?php

namespace App\Services\Sponsorship;

use Illuminate\Support\Facades\Facade;

class SponsorshipFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Sponsorship';
    }
}
