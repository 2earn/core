<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class Sprint008Seeder extends Seeder
{

    public function run()
    {
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\PlatformSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\BalanceOperationsSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\DealsSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\CurrentBalancessSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\RoleSeeder']);
    }
}
