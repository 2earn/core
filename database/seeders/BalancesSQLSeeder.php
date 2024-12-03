<?php

namespace Database\Seeders;

use App\Models\BFSsBalances;
use App\Models\CashBalances;
use App\Models\DiscountBalances;
use App\Models\SharesBalances;
use App\Models\SMSBalances;
use App\Models\TreeBalances;
use Core\Models\user_balance;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BalancesSQLSeeder extends Seeder
{
    public function run()
    {
        DB::statement(formatSqlWithEnv(getSqlFromPath('update_rectified_balance')));
    }
}
