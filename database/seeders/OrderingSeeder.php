<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class OrderingSeeder extends Seeder
{

    public function run()
    {
        Log::notice('Starting OrderingSeeder Seeder');
        if (app()->environment('local')) {
            Log::notice('Starting Seeder DealsInsertSeeder');
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\DealsInsertSeeder']);
            Log::notice('Starting Seeder ItemSeeder');
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\ItemSeeder']);
            Log::notice('Starting Seeder CouponSeeder');
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\CouponSeeder']);
            Log::notice('Starting Seeder AddCashSeeder');
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\AddCashSeeder']);
        }
        Log::notice('Finish Seeder version 4.6');
    }
}
