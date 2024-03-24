<?php
namespace Core\Services;

use App\Models\User;
use Cassandra\Numeric;
use Core\Interfaces\IUserRepository;

class CommandeServiceManager{
    private IUserRepository $userRepository;
    private  settingsManager $settingsManager ;
    public function __construct(
        IUserRepository $userRepository,
         settingsManager  $settingsManager
    )
    {
        $this->userRepository = $userRepository;
    }
    public function saveUser(User $user)
    {
      $user=  $user->save();
      return $user ;
    }
}
