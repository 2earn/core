<?php

namespace App\Services\Targeting;

use App\DAL\UserRepository;
use Core\Services\BalancesManager;

class Targeting
{
    public function __construct(private UserRepository $userRepository, private BalancesManager $balancesManager)
    {
    }

    public static function isSurveyInTarget($survey, $user): bool
    {
        if ($survey->targets->isEmpty()) {
            return true;
        } else {
            return self::CheckUserInTarget($survey->targets->first(), $user);
        }
        return false;
    }

    public static function CheckUserInTarget($target, $user): bool
    {
        $groups = $target->group()->get();
        $conditions = $target->condition()->get();
        if ($groups->isEmpty() && $conditions->isEmpty()) {
            return true;
        }
      // FROM_HERE dd($groups, $conditions);


        return false;

    }
}
