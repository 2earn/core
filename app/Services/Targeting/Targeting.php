<?php

namespace App\Services\Targeting;

use App\DAL\UserRepository;
use Core\Services\BalancesManager;

class Targeting
{
    public function __construct(private UserRepository $userRepository, private BalancesManager $balancesManager)
    {
    }

    public function isInTarget()
    {
        return "Hello, Greetings";
    }
}
