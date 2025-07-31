<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class Version4v6Seeder extends Seeder
{

    public function run()
    {
        Log::notice('Starting Seeder version 4.6');
        if (App::environment('local')) {
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\DealsInsertSeeder']);
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\ItemSeeder']);
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\CouponSeeder']);
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\AddCashSeeder']);
        }
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\OperationCategorySeeder']);

        Log::notice('Finish Seeder version 4.6');
    }
}
