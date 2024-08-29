<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SurveySettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!DB::table('settings')->where("ParameterName", "=", 'DELAY_AFTER_CLOSED')->exists()) {
            DB::table('settings')->insert([
                'ParameterName' => 'DELAY_AFTER_CLOSED',
                'IntegerValue' => 9,
            ]);
        }
        if (!DB::table('settings')->where("ParameterName", "=", 'DELAY_AFTER_CLOSED')->exists()) {
            DB::table('settings')->insert([
                'ParameterName' => 'DELAY_AFTER_ARCHIVED',
                'IntegerValue' => 99,
            ]);
        }
    }
}
