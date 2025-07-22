<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class Version4v6Seeder extends Seeder
{

    public function run()
    {
        Log::notice('Starting Seeder version 4.6');
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\DealsInsertSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\ItemSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\CouponSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\AddCashSeeder']);
        Log::notice('Finish Seeder version 4.6');
    }
}
