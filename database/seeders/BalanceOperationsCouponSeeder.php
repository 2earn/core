<?php

namespace Database\Seeders;

use App\Models\BalanceOperation;
use Illuminate\Database\Seeder;

class BalanceOperationsCouponSeeder extends Seeder
{

    public function run()
    {
        $balanceOperations = array(
            array(
                'operation' => 'COUPONS DISCOUNT',
                'direction' => 'IN',
                'balance_id' => 3,
                'parent_operation_id' => null,
                'ref' => 'BAL03-CAT011-NUM002',
                'operation_category_id' => 10, // Example new category for coupons
                'relateble' => false,
                'relateble_model' => null,
                'relateble_types' => null
            ),
            array(
                'operation' => 'COUPONS BFS',
                'direction' => 'IN',
                'balance_id' => 2,
                'parent_operation_id' => null,
                'ref' => 'BAL02-CAT011-NUM002',
                'operation_category_id' => 10,
                'relateble' => false,
                'relateble_model' => null,
                'relateble_types' => null
            ),
            array(
                'operation' => 'COUPONS CASH',
                'direction' => 'IN',
                'balance_id' => 1,
                'parent_operation_id' => null,
                'ref' => 'BAL01-CAT011-NUM002',
                'operation_category_id' => 10,
                'relateble' => false,
                'relateble_model' => null,
                'relateble_types' => null
            ),
        );

        foreach ($balanceOperations as $operation) {
            BalanceOperation::create(array_merge($operation, ['created_at' => now(), 'updated_at' => now()]));
        }
    }
}
