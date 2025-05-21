<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GiftedShareSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        if (!DB::table('settings')->where("ParameterName", "=", 'GIFTED_SHARES')->exists()) {
            DB::table('settings')->insert([
                'ParameterName' => 'GIFTED_SHARES',
                'IntegerValue' => 750000,
            ]);
        }

    }
}
