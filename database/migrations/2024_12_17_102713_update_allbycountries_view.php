<?php

use Illuminate\Database\Migrations\Migration;


return new class extends Migration
{

    public function up()
    {
        DB::statement(formatSqlWithEnv(getSqlFromPath('_update_allbycountries_view')));
        DB::statement(formatSqlWithEnv(getSqlFromPath('tableau_croise_view')));
    }


    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS allbycountries;');
        DB::statement('DROP VIEW IF EXISTS tableau_croise_view;');
    }
};
