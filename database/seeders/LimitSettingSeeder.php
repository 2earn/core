<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LimitSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!DB::table('settings')->where("ParameterName", "=", 'EXPIRE_AT')->exists()) {
            DB::table('settings')->insert(['ParameterName' => 'EXPIRE_AT', 'IntegerValue' => 30]);
        }
        if (!DB::table('settings')->where("ParameterName", "=", 'MAX_ATTEMPT')->exists()) {
            DB::table('settings')->insert(['ParameterName' => 'MAX_ATTEMPT', 'IntegerValue' => 3]);
        }
    }
}
