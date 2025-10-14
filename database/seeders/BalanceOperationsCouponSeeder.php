<?php

namespace Database\Seeders;

use Core\Models\BalanceOperation;
use Illuminate\Database\Seeder;

class BalanceOperationsCouponSeeder extends Seeder
{

    public function run()
    {
        $balanceOperations = array(
            array('operation' => 'COUPONS DISCOUNT', 'direction' => 'IN', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '3', 'parent_id' => null, 'note' => 'DISCOUNT COUPONS', 'modify_amount' => '1'),
            array('operation' => 'COUPONS BFS', 'direction' => 'IN', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '2', 'parent_id' => null, 'note' => 'BFS COUPONS', 'modify_amount' => '1'),
            array('operation' => 'COUPONS CASH', 'direction' => 'IN', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '1', 'parent_id' => null, 'note' => 'CASH COUPONS', 'modify_amount' => '1'),
        );

        foreach ($balanceOperations as $operation) {
            BalanceOperation::create(array_merge($operation, ['created_at' => now(), 'updated_at' => now()]));
        }

    }
}
