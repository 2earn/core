<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        // CHECK IN BALANCES
        DB::statement(formatSqlWithEnv(getSqlFromPath('_create_user_balances_view')));
    }


    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS user_balances_view;');
    }
};
