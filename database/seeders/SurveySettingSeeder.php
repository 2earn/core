<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SurveySettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::updateOrCreate(
            ['ParameterName' => 'DELAY_AFTER_CLOSED'],
            ['IntegerValue' => 9]
        );

        Setting::updateOrCreate(
            ['ParameterName' => 'DELAY_AFTER_ARCHIVED'],
            ['IntegerValue' => 99]
        );
    }
}
