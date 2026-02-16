<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaxUserIdSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $max = DB::table('users')->max('iduser');
        $max = is_null($max) ? 0 : (int)$max;
        $ts = date('Y-m-d H:i:s');
            DB::table('settings')->insert([
                'ParameterName' => 'MAX_USER_ID',
                'IntegerValue' => $max,
                'created_at' => $ts,
                'updated_at' => $ts,
            ]);
    }
}
