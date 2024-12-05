<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('DROP PROCEDURE IF EXISTS UpdateRectifiedBalance');
        DB::statement(formatSqlWithEnv(getSqlFromPath('update_rectified_balance_procedure')));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP PROCEDURE IF EXISTS UpdateRectifiedBalance');
    }
};
