<?php

namespace App\Services\Targeting;

use App\DAL\UserRepository;
use Core\Services\BalancesManager;
use Illuminate\Support\Facades\DB;

class Targeting
{
    public function __construct(private UserRepository $userRepository, private BalancesManager $balancesManager)
    {
    }

    private static function initUsersQuery()
    {
        return DB::table('users as u')->whereNotNull('fullphone_number')
            ->join('metta_users as meta', 'meta.idUser', '=', 'u.idUser')
            ->join('model_has_roles', 'u.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id');
    }

    private static function addPreveiewData($usersQuery)
    {
        return $usersQuery->select('u.id', 'u.name', 'u.email', 'u.fullphone_number');
    }

    private static function CheckUserIn($usersQuery, $userId)
    {
        $usersQuery = $usersQuery->where('u.id', '=', $userId);

        if ($usersQuery->exists()) {
            return true;
        }
        return false;

    }

    public static function isSurveyInTarget($survey, $user): bool
    {
        if ($survey->targets->isEmpty()) {
            return true;
        } else {
            return self::CheckUserInTarget($survey->targets->first(), $user);
        }
    }

    private static function fillUsersQuery($usersQuery, $conditions, $groups)
    {
        if ($conditions->isNotEmpty()) {
            foreach ($conditions as $condition) {
                if (in_array($condition->operator, ['=', '!=', '<', '>', '<=', '>='])) {
                    {
                        $usersQuery = $usersQuery->where($condition->operand, $condition->operator, $condition->value);
                    }
                }
            }
        }

        if ($groups->isNotEmpty()) {
            foreach ($groups as $group) {
                $usersQuery = $usersQuery->where(function ($query) use ($group) {
                    if ($group->operator == '||') {
                        foreach ($group->condition()->get() as $key => $condition) {
                            if ($key == 0) {
                                $query->where($condition->operand, $condition->operator, $condition->value);
                            } else {
                                $query->orWhere($condition->operand, $condition->operator, $condition->value);
                            }
                        }
                    }
                    if ($group->operator == '&&') {
                        foreach ($group->condition()->get() as $key => $condition) {
                            $query->where($condition->operand, $condition->operator, $condition->value);
                        }
                    }
                });
            }
        }
        return $usersQuery;
    }


    public static function getTargetQuery($target, $preveiw = false)
    {
        $groups = $target->group()->get();
        $conditions = $target->condition()->get();
        if ($groups->isEmpty() && $conditions->isEmpty()) {
            return true;
        }

        $usersQuery = self::initUsersQuery();
        if ($preveiw) {
            $usersQuery = self::addPreveiewData($usersQuery);
        }
        return self::fillUsersQuery($usersQuery, $conditions, $groups);
    }

    public static function CheckUserInTarget($target, $user)
    {
        return self::CheckUserIn(self::getTargetQuery($target), $user->id);
    }
}
