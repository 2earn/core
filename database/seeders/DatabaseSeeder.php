<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        $this->call([
            OperationCategorySeeder::class,
            BalanceOperationsMappingSeeder::class,

            BalanceOperationsMigrateSeeder::class,

            BalanceOperationsCouponSeeder::class,
            BalanceOperationsBFSShareSeeder::class,

            UsersWithStatusMinusTwoAfterAttackSeeder::class,
            TruncateNotificationsTableSeeder::class,

            HashtagSeeder::class,

            UserGuideSeeder::class,

            CommissionFormulaSeeder::class
        ]);
    }
}
