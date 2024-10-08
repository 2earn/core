<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WattsappHelpNumberSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!DB::table('settings')->where("ParameterName", "=", 'WHATSAPP_HELP_NUMBER')->exists()) {
            DB::table('settings')->insert([
                'ParameterName' => 'WHATSAPP_HELP_NUMBER',
                'StringValue' => '+966597555211',
            ]);
        }

    }
}
