<?php

namespace Database\Seeders;

use Core\Models\BalanceOperation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BalanceOperationsBFSShareSeeder extends Seeder
{

    public function run()
    {
        $balanceOperations = array(
            array(
                'operation' => 'SHARE FROM BFS 100',
                'direction' => 'IN',
                'balance_id' => 6,
                'parent_operation_id' => null,
                'ref' => 'BAL06-CAT010-NUM003',
                'operation_category_id' => 11,
                'relateble' => false,
                'relateble_model' => null,
                'relateble_types' => null
            ),
        );

        foreach ($balanceOperations as $operation) {
            BalanceOperation::create(array_merge($operation, ['created_at' => now(), 'updated_at' => now()]));
        }

        if (!DB::table('settings')->where("ParameterName", "=", 'MIN_BFSS_TO_GET_ACTION')->exists()) {
            DB::table('settings')->insert(['ParameterName' => 'MIN_BFSS_TO_GET_ACTION', 'IntegerValue' => 800]);
        }

        if (!DB::table('settings')->where("ParameterName", "=", 'MIN_BFSS_TO_GET_DISCOUNT')->exists()) {
            DB::table('settings')->insert(['ParameterName' => 'MIN_BFSS_TO_GET_DISCOUNT', 'IntegerValue' => 800]);
        }

    }
}
