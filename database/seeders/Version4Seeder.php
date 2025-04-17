<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class Version4Seeder extends Seeder
{

    public function run($dataTranslation = false, $dataMoney = true, $dataDeal = true)
    {
        Log::notice('Starting Seeder Sprint008Seeder');
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\BalanceOperationsSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\RoleSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\BalancesSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\BalancesSQLSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\DeleteTriggers']);

        Artisan::call('db:seed', ['--class' => 'Database\Seeders\BalanceOperationsSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\RoleSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\BalancesSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\BalancesSQLSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\DeleteTriggers']);

        if (App::environment('local')) {
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\BusinessSectorSeeder']);
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\PlatformSeeder']);
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\DealsSeeder']);
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\AddCashSeeder']);
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\DealsInsertSeeder']);
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\TranslateSeeder']);
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\ItemSeeder']);
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\CouponSeeder']);
        }

        Artisan::call('db:seed', ['--class' => 'Database\Seeders\ItemCouponSeeder']);

    }
}
