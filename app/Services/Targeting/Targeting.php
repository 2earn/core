<?php

namespace App\Services\Targeting;

use App\DAL\UserRepository;
use App\Models\Condition;
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
            return false;
        }
        if ($survey->targets->isNotEmpty()) {
            return self::CheckUserInTarget($survey->targets->first(), $user);
        }
        return false;

    }

    private static function formatOperand($condition)
    {

        if (in_array($condition->operator, Condition::$simpleOperands)) {
            return $condition;
        }

        if (in_array($condition->operator, Condition::$complexOperands)) {
            $formatedCondition = new Condition();
            $formatedCondition->operator = 'LIKE';
            $formatedCondition->operand = $condition->operand;
            switch ($condition->operator) {
                case 'END WITH':
                    $formatedCondition->operator = 'LIKE';
                    $formatedCondition->value = '%' . $condition->value;
                    break;
                case  'START WITH':
                    $formatedCondition->operator = 'LIKE';
                    $formatedCondition->value = $condition->value . '%';
                    break;
                case  'CONTAIN':
                    $formatedCondition->operator = 'LIKE';
                    $formatedCondition->value = '%' . $condition->value . '%';
                    break;
            }
            return $formatedCondition;

        }
    }

    private static function fillUsersQuery($usersQuery, $conditions, $groups)
    {
        if ($conditions->isNotEmpty()) {
            foreach ($conditions as $condition) {
                $formatedCondition = self::formatOperand($condition);
                $usersQuery = $usersQuery->where($formatedCondition->operand, $formatedCondition->operator, $formatedCondition->value);
            }
        }

        if ($groups->isNotEmpty()) {
            foreach ($groups as $group) {
                $usersQuery = $usersQuery->where(function ($query) use ($group) {
                    if ($group->operator == '||') {
                        foreach ($group->condition()->get() as $key => $condition) {
                            $formatedCondition = self::formatOperand($condition);
                            if ($key == 0) {
                                $query->where($formatedCondition->operand, $formatedCondition->operator, $formatedCondition->value);

                            } else {
                                $query->orWhere($formatedCondition->operand, $formatedCondition->operator, $formatedCondition->value);
                            }
                        }
                    }
                    if ($group->operator == '&&') {
                        foreach ($group->condition()->get() as $condition) {
                            $formatedCondition = self::formatOperand($condition);
                            $query->where($formatedCondition->operand, $formatedCondition->operator, $formatedCondition->value);
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
        $usersQuery = self::initUsersQuery();
        if ($preveiw) {
            $usersQuery = self::addPreveiewData($usersQuery);
        }

        if ($groups->isEmpty() && $conditions->isEmpty()) {
            return $usersQuery;
        }

        return self::fillUsersQuery($usersQuery, $conditions, $groups);
    }

    private static function CheckUserInTarget($target, $user)
    {
        return self::CheckUserIn(self::getTargetQuery($target), $user->id);
    }
}
