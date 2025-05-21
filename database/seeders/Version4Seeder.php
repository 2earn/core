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
        Log::notice('Starting Seeder version 4');
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\BalanceOperationsSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\RoleSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\BalancesSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\BalancesSQLSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\DeleteTriggers']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\CouponSettingSeeder']);

        Artisan::call('db:seed', ['--class' => 'Database\Seeders\ItemCouponSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\UserRoleSeeder']);
        Log::notice('Finish Seeder version 4');

    }
}
