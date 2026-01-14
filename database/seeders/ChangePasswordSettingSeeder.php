<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class ChangePasswordSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::updateOrCreate(
            ['ParameterName' => 'SEND_PASSWORD_CHANGE_OPT'],
            ['IntegerValue' => 1]
        );

        Setting::updateOrCreate(
            ['ParameterName' => 'INVALID_OPT_COUNTRIES'],
            ['StringValue' => '222,119']
        );
    }
}
