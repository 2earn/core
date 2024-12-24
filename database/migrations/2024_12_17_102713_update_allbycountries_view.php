<?php

use Illuminate\Database\Migrations\Migration;


return new class extends Migration
{

    public function up()
    {
        DB::statement('DROP VIEW IF EXISTS allbycountries;');
        DB::statement('CREATE VIEW `allbycountries` AS ' . formatSqlWithEnv(getSqlFromPath('_update_allbycountries_view')));
    }


    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS allbycountries;');
    }
};
