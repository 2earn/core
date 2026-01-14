<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class BeCommitedInvestorSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::updateOrCreate(
            ['ParameterName' => 'BE_COMMITED_INVESTOR'],
            ['IntegerValue' => 1000]
        );
    }
}
