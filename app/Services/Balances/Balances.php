<?php

namespace App\Services\Balances;

use App\Models\BFSsBalances;
use App\Models\User;
use App\Models\UserCurrentBalanceHorisontal;
use App\Models\UserCurrentBalanceVertical;
use Core\Enum\BalanceEnum;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Balances
{
    const DATE_FORMAT = 'dmY';
    const SYSTEM_SOURCE_ID = '11111111';
    const SHARE_BALANCE = 'share_balance';
    const CASH_BALANCE = 'cash_balance';
    const BFSS_BALANCE = 'bfss_balance';
    const DISCOUNT_BALANCE = 'discount_balance';

    const MIN_BFSS_TO_GET_DISCOUNT = 200;


    public function getBalanceCompter()
    {
        $value = getSettingIntegerParam('BALANCES_COMPTER', 1);

        $value++;
        $newValue = (string)$value;
        DB::table('settings')->where("ParameterName", "=", 'BALANCES_COMPTER')->update(['IntegerValue' => $newValue]);
        return substr((string)pow(10, 7 - strlen($newValue)), 1) . $newValue;
    }

    public function getReference($balancesOperationId)
    {
        $date = new \DateTime('now');
        return substr((string)pow(10, 3 - strlen(strval($balancesOperationId))), 1) . $balancesOperationId . $date->format(self::DATE_FORMAT) . $this->getBalanceCompter();
    }

    public static function getSoldMainQuery($balances)
    {
        return DB::table($balances . ' as u')
            ->select('u.beneficiary_id', DB::raw('SUM(CASE WHEN b.io = "I" THEN u.value ELSE -u.value END) as value'))
            ->join('balance_operations as b', 'u.balance_operation_id', '=', 'b.id')
            ->join('users as s', 'u.beneficiary_id', '=', 's.idUser');
    }

    public static function getSold($idUser, $balances)
    {
        $sold = self::getSoldMainQuery($balances)
            ->where('u.beneficiary_id', $idUser)
            ->groupBy('u.beneficiary_id')
            ->first();
        return $sold->value ?? 0.000;
    }

    public static function getCash($idUser)
    {
        return self::getSold($idUser, 'cash_balances');
    }

    public static function getBfss($idUser, $type = BFSsBalances::BFS_100)
    {
        return self::getSold($idUser, 'bfss_balances');
    }

    public static function getDiscount($idUser)
    {
        return self::getSold($idUser, 'discount_balances');
    }

    public static function getTree($idUser)
    {
        return self::getSold($idUser, 'tree_balances');
    }

    public static function getSms($idUser)
    {
        return self::getSold($idUser, 'sms_balances');
    }

    public static function getStoredUserBalances($idUser, $balances = null)
    {
        if (is_null($balances)) {
            return UserCurrentBalanceHorisontal::where('user_id', $idUser)->first();
        }
        return UserCurrentBalanceHorisontal::where('user_id', $idUser)->pluck($balances)->first();
    }

    public static function getStoredCash($idUser)
    {
        return Balances::getStoredUserBalances($idUser, 'cash_balances');
    }

    public static function getStoredBfss($idUser, $type)
    {
        $userCurrentBalanceHorisontal = Balances::getStoredUserBalances($idUser);
        return $userCurrentBalanceHorisontal->getBfssBalance($type);
    }

    public static function getStoredDiscount($idUser)
    {
        return Balances::getStoredUserBalances($idUser, 'discount_balances');
    }

    public static function getStoredTree($idUser)
    {
        return Balances::getStoredUserBalances($idUser, 'tree_balances');
    }

    public static function getStoredSms($idUser)
    {
        return Balances::getStoredUserBalances($idUser, 'sms_balances');
    }

    public static function addAutomatedFields($balances, $item_id, $deal_id, $order_id, $platform_id, $order_detail_id)
    {
        if (!is_null($item_id)) {
            $balances['item_id'] = $item_id;
        }
        if (!is_null($deal_id)) {
            $balances['deal_id'] = $deal_id;
        }
        if (!is_null($order_id)) {
            $balances['order_id'] = $order_id;
        }
        if (!is_null($platform_id)) {
            $balances['platform_id'] = $platform_id;
        } else {
            $balances['platform_id'] = 1;
        }
        if (!is_null($order_detail_id)) {
            $balances['order_detail_id'] = $order_detail_id;
        }

        if (!array_key_exists('beneficiary_id_auto', $balances) or is_null($balances['beneficiary_id_auto'])) {
            $balances['beneficiary_id_auto'] = User::where('idUser', $balances['beneficiary_id'])->first()->id;
        }

        if (array_key_exists('percentage', $balances)) {
            if (!str_ends_with($balances['percentage'], '.00') && fmod(floatval($balances['percentage']), 1) == 0) {
                $balances['percentage'] = $balances['percentage'] . '.00';
            }
        }

        Log::info(json_encode($balances));

        return $balances;
    }

    public static function SommeSold($type, $sumColomn = 'value')
    {
        $var = false;
        if ($type == 'shares_balances') {
            $var = true;
        }
        return DB::table(function ($query) use ($type, $sumColomn, $var) {
            $query->select('beneficiary_id', 'u.created_at', 'u.balance_operation_id', 'b.operation', DB::raw('CASE WHEN b.io = "I" THEN ' . $sumColomn . ' ELSE -' . $sumColomn . ' END as value'))
                ->from($type . ' as u')
                ->join('balance_operations as b', 'u.balance_operation_id', '=', 'b.id');
            if ($var) {
                $query->where('u.balance_operation_id', 44);
            }
        }, 'a')->get(DB::raw('sum(value) as somme'))->pluck('somme')->first();
    }

    public static function updateCalculatedHorisental($idUser, $type, $value)
    {
        UserCurrentBalanceHorisontal::where('user_id', $idUser)->update([$type => $value]);
    }

    public static function updateCalculatedVertical($idUser, $type, $value)
    {
        UserCurrentBalanceVertical::where('user_id', $idUser)->where('balance_id', $type)->update(['current_balance' => $value]);
    }

    public static function getTotalBfs($userCurrentBalancehorisontal)
    {
        $sum = 0;
        foreach ($userCurrentBalancehorisontal->bfss_balance as $valueItem) {
            $sum = $sum + $valueItem['value'];
        }
        return $sum;
    }

    public static function getTotalChance($userCurrentBalancehorisontal)
    {
        $sum = 0;
        if (!is_null($userCurrentBalancehorisontal)) {
            foreach ($userCurrentBalancehorisontal->chances_balance as $valueItem) {
                $sum = $sum + $valueItem['value'];
            }
        }
        return $sum;
    }

    public static function updateCalculatedSold($idUser, $type = BalanceEnum::CASH, $value)
    {
        switch ($type) {
            case BalanceEnum::CASH:
                Balances::updateCalculatedHorisental($idUser, Balances::CASH_BALANCE, $value);
                Balances::updateCalculatedVertical($idUser, $type, $value);
            case BalanceEnum::BFS:
                Balances::updateCalculatedHorisental($idUser, Balances::BFSS_BALANCE, $value);
                Balances::updateCalculatedVertical($idUser, $type, $value);
            case BalanceEnum::DB:
                Balances::updateCalculatedHorisental($idUser, Balances::DISCOUNT_BALANCE, $value);
                Balances::updateCalculatedVertical($idUser, $type, $value);
        }
    }

    public static function getDiscountEarnedFromBFS100I($bFSsBalancesValue): float
    {
        $minBfs = getSettingIntegerParam('MIN_BFSS_TO_GET_DISCOUNT', self::MIN_BFSS_TO_GET_DISCOUNT);
        $value = 0;
        if ($minBfs > $bFSsBalancesValue) {
            $pourcentage = $bFSsBalancesValue / $minBfs;
            $value = $pourcentage * $bFSsBalancesValue;
        } else {
            $value = $bFSsBalancesValue;
        }
        return $value;
    }
}
