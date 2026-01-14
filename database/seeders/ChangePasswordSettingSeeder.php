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
        // NOTE:
        // The "OPT" suffix in the following ParameterName values is a legacy
        // abbreviation for "OPTION". These names are kept as-is to maintain
        // backward compatibility with existing data and configuration.
        // For new settings, prefer using the full word "OPTION" in names.
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
