<?php
namespace Core\Services;

use App\Models\User;
use Cassandra\Numeric;
use Core\Interfaces\IUserRepository;

class CommandeServiceManager{
    public function __construct(private IUserRepository $userRepository)
    {
    }
    public function saveUser(User $user)
    {
        return $user->save();
    }
}
