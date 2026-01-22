<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class CouponSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::updateOrCreate(
            ['ParameterName' => 'DELAY_FOR_COUPONS_SIMULATION'],
            ['IntegerValue' => 10]
        );
    }
}
