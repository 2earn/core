<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StaticNewsSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!DB::table('settings')->where("ParameterName", "=", 'ENABLE_STATIC_NEWS')->exists()) {
            DB::table('settings')->insert([
                'ParameterName' => 'ENABLE_STATIC_NEWS',
                'IntegerValue' => '1',
            ]);
        }

    }
}
