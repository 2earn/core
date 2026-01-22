<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class DealsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            ['name' => 'EARN_PROFIT_PERCENTAGE', 'value' => 50],
            ['name' => 'JACKPOT_PERCENTAGE', 'value' => 15],
            ['name' => 'TREE_REMUNERATION_PERCENTAGE', 'value' => 10],
            ['name' => 'PROACTIVE_CASHBACK_PERCENTAGE', 'value' => 25],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['ParameterName' => 'DEALS_' . $setting['name']],
                ['DecimalValue' => $setting['value']]
            );
        }
    }
}
