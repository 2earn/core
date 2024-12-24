<?php

namespace Database\Seeders;

use App\Models\BFSsBalances;
use App\Models\CashBalances;
use App\Models\DiscountBalances;
use App\Models\SharesBalances;
use App\Models\SMSBalances;
use App\Models\TreeBalances;
use Core\Enum\BalanceOperationsEnum;
use Core\Models\user_balance;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BalancesSeeder extends Seeder
{
    public function run()
    {
        if (!DB::table('settings')->where("ParameterName", "=", 'BALANCES_COMPTER')->exists()) {
            DB::table('settings')->insert(['ParameterName' => 'BALANCES_COMPTER', 'IntegerValue' => 1976,]);
        }
        if (!DB::table('settings')->where("ParameterName", "=", 'INITIAL_CHANCE')->exists()) {
            DB::table('settings')->insert(['ParameterName' => 'INITIAL_CHANCE', 'IntegerValue' => 1,]);
        }
        if (!DB::table('settings')->where("ParameterName", "=", 'INITIAL_TREE')->exists()) {
            DB::table('settings')->insert(['ParameterName' => 'INITIAL_TREE', 'IntegerValue' => 2,]);
        }
        if (!DB::table('settings')->where("ParameterName", "=", 'discount By registering')->exists()) {
            DB::table('settings')->insert(['ParameterName' => 'INITIAL_DISCOUNT', 'IntegerValue' => 20,]);
        } else {
            DB::table('settings')
                ->where("ParameterName", "=", 'discount By registering')
                ->update(["ParameterName" => 'INITIAL_DISCOUNT']);
        }
        if (!DB::table('settings')->where("ParameterName", "=", 'TOTAL_TREE')->exists()) {
            DB::table('settings')->insert(['ParameterName' => 'TOTAL_TREE', 'IntegerValue' => 125,]);
        }
    }

}
