<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        $this->call([
            BalanceOperationsCouponSeeder::class,
            BalanceOperationsBFSShareSeeder::class,
            OperationCategorySeeder::class,
            BalanceOperationsMappingSeeder::class,
            BalanceOperationsMigrateSeeder::class,
            UsersWithStatusMinusTwoAfterAttackSeeder::class,
            TruncateNotificationsTableSeeder::class,
        ]);
    }
}

