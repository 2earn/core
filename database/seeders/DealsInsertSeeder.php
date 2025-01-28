<?php

namespace Database\Seeders;

use App\Models\CashBalances;
use App\Services\Balances\Balances;
use Core\Enum\BalanceOperationsEnum;
use Core\Enum\DealStatus;
use Core\Models\Platform;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class DealsInsertSeeder extends Seeder
{
    public function getDealParam($name)
    {
        $param = DB::table('settings')->where("ParameterName", "=", $name)->first();
        if (!is_null($param)) {
            return $param->DecimalValue;
        }
        return 0;
    }

    public function run()
    {
        if (!App::isProduction()) {
            $faker = Factory::create();
            $platforms = Platform::all();
            $dealNumber = rand(1, 3);
            $initialCOmmission = rand(5, 10);
            foreach ($platforms as $platform) {
                for ($i = 1; $i <= $dealNumber; $i++) {
                    $platform->deals()->create([
                        'name' => $platform->name . ' - ' . $faker->name(),
                        'description' => $faker->text() . ' RANDOM',
                        'validated' => TRUE,
                        'status' => DealStatus::Opened->value,
                        'current_turnover' => 0,
                        'target_turnover' => 10000,
                        'is_turnover' => true,
                        'discount' => rand(1, $initialCOmmission - 2),
                        'start_date' => $faker->dateTimeBetween('-2 week', '-1 week'),
                        'end_date' => $faker->dateTimeBetween('+1 week', '+3 week'),
                        'initial_commission' => $initialCOmmission,
                        'final_commission' => rand(20, 30),
                        'earn_profit' => $this->getDealParam('DEALS_EARN_PROFIT_PERCENTAGE'),
                        'jackpot' => $this->getDealParam('DEALS_JACKPOT_PERCENTAGE'),
                        'tree_remuneration' => $this->getDealParam('DEALS_TREE_REMUNERATION_PERCENTAGE'),
                        'proactive_cashback' => $this->getDealParam('DEALS_PROACTIVE_CASHBACK_PERCENTAGE'),
                        'min_percentage_cashback' => rand(5, 10),
                        'max_percentage_cashback' => rand(20, 50),
                        'total_commission_value' => 0,
                        'total_unused_cashback_value' => 0,
                        'platform_id' => $platform->id,
                    ]);
                }
            }
        }
    }
}
