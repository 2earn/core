<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class Version4v2Seeder extends Seeder
{

    public function run()
    {
        Log::notice('Starting Seeder version 4.2');
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\ActionSettingSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\UserBalancesValueUpdater']);
        Log::notice('Finish Seeder version 4.2');
    }
}
