<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActionSettingSeeder extends Seeder
{

    public function run()
    {

        if (!DB::table('settings')->where("ParameterName", "=", 'BFSS_TYPE_FOR_ACTION')->exists()) {
            DB::table('settings')->insert([
                'ParameterName' => 'BFSS_TYPE_FOR_ACTION',
                'StringValue' => '50.00',
            ]);
        }

        if (!DB::table('settings')->where("ParameterName", "=", 'BFSS_LIMIT_FOR_ACTION')->exists()) {
            DB::table('settings')->insert([
                'ParameterName' => 'BFSS_LIMIT_FOR_ACTION',
                'IntegerValue' => 500,
            ]);
        }

        if (!DB::table('settings')->where("ParameterName", "=", 'BFSS_GIFT_FOR_ACTION')->exists()) {
            DB::table('settings')->insert([
                'ParameterName' => 'BFSS_GIFT_FOR_ACTION',
                'IntegerValue' => 100,
            ]);
        }

    }
}
