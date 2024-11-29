<?php

namespace App\Services\Balances;

use App\DAL\UserRepository;
use Core\Services\BalancesManager;
use Illuminate\Support\Facades\DB;

class Balances
{
    const DTAEFORMAT = 'dmY';

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
        return '0' . $balancesOperationId . $date->format(self::DTAEFORMAT) . $this->getBalanceCompter();
    }


}
