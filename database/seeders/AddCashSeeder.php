<?php

namespace Database\Seeders;

use App\Models\CashBalances;
use Core\Enum\BalanceOperationsEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddCashSeeder extends Seeder
{
    const DATE_FORMAT = 'dmY';

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
        return substr((string)pow(10, 3 - strlen(strval($balancesOperationId))), 1) . $balancesOperationId . $date->format(self::DATE_FORMAT) . $this->getBalanceCompter();
    }
    public function run()
    {
        $value = 10000;
        $idUsers = ["197604395","197604239","197604342"];

        foreach ($idUsers as $idUser) {
            $userCurrentBalancehorisontal = DB::table('user_current_balance_horisontals')->where('user_id', $idUser)->first();
            CashBalances::addLine([
                'balance_operation_id' => BalanceOperationsEnum::SI_CB->value,
                'operator_id' => $idUser,
                'beneficiary_id' => $idUser,
                'reference' => $this->getReference(BalanceOperationsEnum::SI_CB->value),
                'description' => "Add cash balance from AddCashSeeder",
                'value' => $value,
                'current_balance' => $userCurrentBalancehorisontal->cash_balance + $value
            ]);
        }
    }
}
