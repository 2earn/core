<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChangePasswordSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        if (!DB::table('settings')->where("ParameterName", "=", 'SEND_PASSWORD_CHANGE_OPT')->exists()) {
            DB::table('settings')->insert([
                'ParameterName' => 'SEND_PASSWORD_CHANGE_OPT',
                'IntegerValue' => 1,
            ]);
        }

        if (!DB::table('settings')->where("ParameterName", "=", 'INVALID_OPT_COUNTRIES')->exists()) {
            DB::table('settings')->insert([
                'ParameterName' => 'INVALID_OPT_COUNTRIES',
                'StringValue' => '222,119',
            ]);
        }
    }
}
