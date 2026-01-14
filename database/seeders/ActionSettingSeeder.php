<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class ActionSettingSeeder extends Seeder
{

    public function run()
    {

        Setting::updateOrCreate(
            ['ParameterName' => 'BFSS_TYPE_FOR_ACTION'],
            ['StringValue' => '50.00']
        );

        Setting::updateOrCreate(
            ['ParameterName' => 'BFSS_LIMIT_FOR_ACTION'],
            ['IntegerValue' => 500]
        );

        Setting::updateOrCreate(
            ['ParameterName' => 'BFSS_GIFT_FOR_ACTION'],
            ['IntegerValue' => 100]
        );

    }
}
