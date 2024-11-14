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
            ['name' => 'PRECISION', 'value' => 0.01],
            ['name' => '2EARN_CASH_MARGIN_PERCENTAGE', 'value' => 35],
            ['name' => 'CASH_BACK_MARGIN_PERCENTAGE', 'value' => 30],
            ['name' => 'PROACTIVE_CONSUMPTION_MARGIN_PERCENTAGE', 'value' => 10],
            ['name' => 'SHAREHOLDER_BENEFITS_MARGIN_PERCENTAGE', 'value' => 15],
            ['name' => 'TREE_MARGIN_PERCENTAGE', 'value' => 10],
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
