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

            $this->command->info('Starting Seeder DealsInsertSeeder');
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\DealsInsertSeeder']);
            $this->command->info('End Seeder DealsInsertSeeder');

            $this->command->info('Starting Seeder ItemSeeder');
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\ItemSeeder']);

            $this->command->info('Starting Seeder CouponSeeder');
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\CouponSeeder']);

            $this->command->info('Starting Seeder AddCashSeeder');
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\AddCashSeeder']);

            $this->command->info('Starting Seeder Update Platform Owners Seeder');
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\UpdatePlatformOwnersSeeder']);

            $this->command->info('Starting Seeder Order Simulation Payment Seeder');
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\OrderSimulationPaymentSeeder']);
        }
        Log::notice('Finish OrderingSeeder');
    }
}
