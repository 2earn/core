<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            if (!DB::table('settings')->where("ParameterName", "=", 'DEALS_' . $setting['name'])->exists()) {
                DB::table('settings')->insert([
                    'ParameterName' => 'DEALS_' . $setting['name'],
                    'DecimalValue' => $setting['value'],
                ]);
            }
        }
    }
}
