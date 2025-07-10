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
            array('id' => '64', 'operation' => 'SHARE FROM BFS 100', 'io' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '6', 'parent_id' => null, 'note' => 'SHARE FROM BFS 100', 'modify_amount' => '1'),
        );

        foreach ($balanceOperations as $operation) {
            BalanceOperation::create(array_merge($operation, ['created_at' => now(), 'updated_at' => now()]));
        }

        if (!DB::table('settings')->where("ParameterName", "=", 'MIN_BFSS_TO_GET_ACTION')->exists()) {
            DB::table('settings')->insert(['ParameterName' => 'MIN_BFSS_TO_GET_ACTION', 'IntegerValue' => 800]);
        }

    }
}
