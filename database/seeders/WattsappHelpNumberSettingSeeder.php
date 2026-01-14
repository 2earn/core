<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class WattsappHelpNumberSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::updateOrCreate(
            ['ParameterName' => 'WHATSAPP_HELP_NUMBER'],
            ['StringValue' => '+966597555211']
        );
    }
}
