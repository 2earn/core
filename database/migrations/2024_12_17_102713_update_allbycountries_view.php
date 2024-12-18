<?php

use Illuminate\Database\Migrations\Migration;


return new class extends Migration
{

    public function up()
    {
        dump(env('APP_NAME', '2Earn.test'));
        DB::statement(formatSqlWithEnv(getSqlFromPath('_update_allbycountries_view')));
    }


    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS allbycountries;');
    }
};
