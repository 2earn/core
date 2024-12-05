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
            DB::table('settings')->insert([
                'ParameterName' => 'BALANCES_COMPTER',
                'IntegerValue' => 1976,
            ]);
        }

    }

}
