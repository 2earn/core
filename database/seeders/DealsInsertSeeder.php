<?php

namespace Database\Seeders;

use App\Enums\DealStatus;
use App\Enums\DealTypeEnum;
use App\Models\Platform;
use Faker\Generator;
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
            $faker = app(Generator::class);
            $platforms = Platform::all();
            $initialCommission = rand(5, 25);
            foreach ($platforms as $platform) {
                    $platform->deals()->create([
                        'name' => $platform->name . ' ' . DealTypeEnum::public->name . ' - deal' ,
                        'description' => $faker->text() . ' RANDOM',
                        'validated' => TRUE,
                        'status' => DealStatus::Opened->value,
                        'type' => DealTypeEnum::public->value,
                        'current_turnover' => 0,
                        'target_turnover' => 10000,
                        'is_turnover' => true,
                        'discount' => rand(1, $initialCommission / 2),
                        'start_date' => $faker->dateTimeBetween('-2 week', '-1 week'),
                        'end_date' =>  $faker->dateTimeBetween('+1 week', '+3 week'),
                        'initial_commission' => $initialCommission,
                        'final_commission' => rand(20, 30),
                        'earn_profit' => $this->getDealParam('DEALS_EARN_PROFIT_PERCENTAGE'),
                        'jackpot' => $this->getDealParam('DEALS_JACKPOT_PERCENTAGE'),
                        'tree_remuneration' => $this->getDealParam('DEALS_TREE_REMUNERATION_PERCENTAGE'),
                        'proactive_cashback' => $this->getDealParam('DEALS_PROACTIVE_CASHBACK_PERCENTAGE'),
                        'total_commission_value' => 0,
                        'total_unused_cashback_value' => 0,
                    ]);
            }
        }
    }
}
