<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class Sprint008Seeder extends Seeder
{

    public function run($dataTranslation = false)
    {
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\PlatformSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\BalanceOperationsSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\DealsSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\CurrentBalancesSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\RoleSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\BalancesSQLSeeder']);
        if ($dataTranslation) {
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\TranslateSeeder']);
        }



    }
}
