<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{


public function up()
{

    DB::statement('DROP VIEW IF EXISTS soldes_view;');
    DB::statement(formatSqlWithEnv(getSqlFromPath('_update_soldes_view')));
}


public function down()
{
    DB::statement('DROP VIEW IF EXISTS soldes_view;');
}
};
