<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class LimitSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::updateOrCreate(
            ['ParameterName' => 'EXPIRE_AT'],
            ['IntegerValue' => 30]
        );

        Setting::updateOrCreate(
            ['ParameterName' => 'MAX_ATTEMPT'],
            ['IntegerValue' => 3]
        );
    }
}
