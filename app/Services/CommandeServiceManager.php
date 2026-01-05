<?php
namespace App\Services;

use App\Models\User;
use Cassandra\Numeric;
use App\Interfaces\IUserRepository;

class CommandeServiceManager{
    public function __construct(private IUserRepository $userRepository)
    {
    }
    public function saveUser(User $user)
    {
        return $user->save();
    }
}
