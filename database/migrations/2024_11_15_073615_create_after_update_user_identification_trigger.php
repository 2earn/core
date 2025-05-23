<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        DB::statement('DROP TRIGGER IF EXISTS after_update_user_identification;');
        DB::statement(formatSqlWithEnv(getSqlFromPath('_create_after_update_user_identification_trigger')));
    }


    public function down()
    {
        DB::statement('DROP TRIGGER IF EXISTS after_update_user_identification;');
    }
};
