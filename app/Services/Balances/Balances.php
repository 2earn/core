<?php

namespace App\Services\Balances;

use App\DAL\UserRepository;
use App\Models\User;
use Core\Services\BalancesManager;
use Illuminate\Support\Facades\DB;

class Balances
{
    const DTAEFORMAT = 'dmY';
    const SYSTEM_SOURCE_ID = '11111111';

    public function __construct(private UserRepository $userRepository, private BalancesManager $balancesManager)
    {
    }

    public function getBalanceCompter()
    {
        $balanceCompter = DB::table('settings')->where("ParameterName", "=", 'BALANCES_COMPTER')->first();
        $value = (int)$balanceCompter->IntegerValue;
        $value++;
        $newValue = (string)$value;
        DB::table('settings')->where("ParameterName", "=", 'BALANCES_COMPTER')->update(['IntegerValue' => $newValue]);
        return substr((string)pow(10, 7 - strlen($newValue)), 1) . $newValue;
    }

    public function getReference($balancesOperationId)
    {
        $date = new \DateTime('now');
        return substr((string)pow(10, 3 - strlen($balancesOperationId)), 1) . $balancesOperationId . $date->format(self::DTAEFORMAT) . $this->getBalanceCompter();
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

    public static function getBfss($idUser)
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
    public static function addAutomatedFields($balances)
    {
        if (!array_key_exists('beneficiary_id_auto', $balances) or is_null($balances['beneficiary_id_auto'])) {
            $balances['beneficiary_id_auto'] = User::where('idUser', $balances['beneficiary_id'])->first()->id;
        }
        return $balances;
    }





}
