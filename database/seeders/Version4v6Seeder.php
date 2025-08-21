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
            Log::notice('Starting Seeder DealsInsertSeeder');
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\DealsInsertSeeder']);

            Log::notice('Starting Seeder ItemSeeder');
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\ItemSeeder']);

            Log::notice('Starting Seeder CouponSeeder');
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\CouponSeeder']);

        }

        Log::notice('Starting Seeder BalanceOperationsCouponSeeder');
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\BalanceOperationsCouponSeeder']);

        Log::notice('Starting Seeder BalanceOperationsBFSShareSeeder');
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\BalanceOperationsBFSShareSeeder']);

        Log::notice('Starting Seeder OperationCategorySeeder');
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\OperationCategorySeeder']);

        Log::notice('Starting Seeder BalanceOperationsMappingSeeder');
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\BalanceOperationsMappingSeeder']);

        if (App::environment('local')) {

            Log::notice('Starting Seeder BalancesMigrationSeeder');
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\BalancesMigrationSeeder']);

            Log::notice('Starting Seeder UsersWithStatusMinusTwoAfterAttackSeeder');
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\UsersWithStatusMinusTwoAfterAttackSeeder']);

            Log::notice('Starting Seeder AddCashSeeder');
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\AddCashSeeder']);
        }
        Log::notice('Finish Seeder version 4.6');
    }
}
