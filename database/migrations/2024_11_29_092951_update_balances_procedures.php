<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedures = [
            'update_current_balance',
            'update_current_balance_bfs',
            'update_current_balance_discount',
            'update_current_balance_shares',
            'update_current_balance_sms',
            'update_current_balance_tree',
            'update_total_amount_shares',
        ];
        foreach ($procedures as $procedure) {
            DB::statement(formatSqlWithEnv(getSqlFromPath($procedure)));
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $procedures = [
            'UpdateCurrentBalance',
            'UpdateCurrentBalancebfs',
            'UpdateCurrentBalanceDiscount',
            'UpdateCurrentBalanceshares',
            'UpdateCurrentBalancesms',
            'UpdateCurrentBalancetree',
            'UpdateTotalAmountshares',
        ];
        foreach ($procedures as $procedure) {
            DB::statement('DROP PROCEDURE IF EXISTS ' . $procedure);
        }

    }
};
