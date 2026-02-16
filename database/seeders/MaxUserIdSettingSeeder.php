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
        // get max iduser from users table, default to 0 when no users present
        $max = DB::table('users')->max('iduser');
        $max = is_null($max) ? 0 : (int)$max;

        // insert or update the setting param MAX_USER_ID with current max
        DB::table('settings')->updateOrInsert(
            ['ParameterName' => 'MAX_USER_ID'],
            ['IntegerValue' => $max]
        );
    }
}

