<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class Version4Seeder extends Seeder
{

    public function run()
    {
        Log::notice('Starting Seeder Sprint008Seeder');
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\BalanceOperationsSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\RoleSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\BalancesSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\BalancesSQLSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\DeleteTriggers']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\CouponSettingSeeder']);

        if (App::environment('local')) {
            Log::notice('Starting Seeder Sprint008Seeder local');
            Log::notice('Starting Seeder Sprint008Seeder BusinessSectorSeeder');
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\BusinessSectorSeeder']);
            Log::notice('Starting Seeder Sprint008Seeder PlatformSeeder');
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\PlatformSeeder']);
            Log::notice('Starting Seeder Sprint008Seeder DealsSeeder');
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\DealsSeeder']);
            Log::notice('Starting Seeder Sprint008Seeder AddCashSeeder');
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\AddCashSeeder']);
              Log::notice('Starting Seeder Sprint008Seeder DealsInsertSeeder');
               Artisan::call('db:seed', ['--class' => 'Database\Seeders\DealsInsertSeeder']);
               Log::notice('Starting Seeder Sprint008Seeder TranslateSeeder');
               Artisan::call('db:seed', ['--class' => 'Database\Seeders\TranslateSeeder']);
               Log::notice('Starting Seeder Sprint008Seeder ItemSeeder');
               Artisan::call('db:seed', ['--class' => 'Database\Seeders\ItemSeeder']);
               Log::notice('Starting Seeder Sprint008Seeder CouponSeeder');
               Artisan::call('db:seed', ['--class' => 'Database\Seeders\CouponSeeder']);
               Log::notice('Finish Seeder Sprint008Seeder local');
           }

        Artisan::call('db:seed', ['--class' => 'Database\Seeders\ItemCouponSeeder']);
        Log::notice('Finish Seeder Sprint008Seeder');

    }
}
