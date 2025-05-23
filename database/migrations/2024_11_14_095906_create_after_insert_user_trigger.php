<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up()
    {
        DB::statement('DROP TRIGGER IF EXISTS after_insert_user;');
        DB::statement(formatSqlWithEnv(getSqlFromPath('_create_after_insert_user_trigger')));
    }


    public function down()
    {
        DB::statement('DROP TRIGGER IF EXISTS after_insert_user;');
    }
};
