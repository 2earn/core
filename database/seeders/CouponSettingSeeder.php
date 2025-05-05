<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        if (!DB::table('settings')->where("ParameterName", "=", 'DELAY_FOR_COUPONS_SIMULATION')->exists()) {
            DB::table('settings')->insert([
                'ParameterName' => 'DELAY_FOR_COUPONS_SIMULATION',
                'IntegerValue' => 10,
            ]);
        }

    }
}
