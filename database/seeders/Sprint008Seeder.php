<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class Sprint008Seeder extends Seeder
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
        Log::notice('Ending Seeder Sprint008Seeder');

    }
}
