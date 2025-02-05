<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration {

    public function up()
    {
     //   DB::statement(formatSqlWithEnv(getSqlFromPath('_update_calculated_userbalances_view')));
    }

    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS calculated_userbalances');

    }
};
