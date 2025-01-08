<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class Sprint008Seeder extends Seeder
{

    public function run($dataTranslation = false, $dataMoney = true, $dataDeal = true)
    {
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\PlatformSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\BalanceOperationsSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\DealsSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\RoleSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\BalancesSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\BalancesSQLSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\DeleteTriggers']);
        if ($dataMoney) {
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\AddCashSeeder']);
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\AddCashSeeder']);
        }
        if ($dataDeal) {
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\DealsInsertSeeder']);
        }
        if ($dataTranslation) {
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\TranslateSeeder']);
        }
    }
}
