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
            foreach ($platforms as $platform) {
                for ($i = 1; $i <= $dealNumber; $i++) {
                    $platform->deals()->create([
                        'name' => $platform->name . ' - ' . $faker->name(),
                        'description' => $faker->text() . ' RANDOM',
                        'validated' => TRUE,
                        'status' => DealStatus::Opened->value,
                        'precision' => $this->getDealParam('DEALS_PRECISION'),
                        'cash_back_margin_percentage' => $this->getDealParam('DEALS_CASH_BACK_MARGIN_PERCENTAGE'),
                        'proactive_consumption_margin_percentage' => $this->getDealParam('DEALS_PROACTIVE_CONSUMPTION_MARGIN_PERCENTAGE'),
                        'shareholder_benefits_margin_percentage' => $this->getDealParam('DEALS_SHAREHOLDER_BENEFITS_MARGIN_PERCENTAGE'),
                        'tree_margin_percentage' => $this->getDealParam('DEALS_TREE_MARGIN_PERCENTAGE'),
                        'start_date' => $faker->dateTimeBetween('-2 week', '-1 week'),
                        'end_date' => $faker->dateTimeBetween('+1 week', '+3 week'),
                        'provider_turnover' => rand(1, 100),
                        'items_profit_average' => rand(1, 100),
                        'initial_commission' => rand(1, 100),
                        'final_commission' => rand(1, 100),
                        'progressive_commission' => rand(1, 100),
                        'margin_percentage' => rand(1, 100),
                        'discount' => rand(1, 20),
                        'discount2earn' => rand(1, 20),
                        'objective_turnover' => rand(1, 100),
                        'platform_id' => $platform->id,
                    ]);
                }
            }
        }
    }
}
