<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BeCommitedInvestorSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!DB::table('settings')->where("ParameterName", "=", 'BE_COMMITED_INVESTOR')->exists()) {
            DB::table('settings')->insert([
                'ParameterName' => 'BE_COMMITED_INVESTOR',
                'IntegerValue' => 1000,
            ]);
        }

    }
}
