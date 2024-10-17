<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TargetDateSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!DB::table('settings')->where("ParameterName", "=", 'TARGET_DATE')->exists()) {
            DB::table('settings')->insert([
                'ParameterName' => 'TARGET_DATE',
                'StringValue' =>  '2025/04/07',
            ]);
        }

    }
}
