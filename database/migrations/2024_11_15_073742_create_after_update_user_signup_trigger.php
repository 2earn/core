<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {

        $viewSqlCode = "CREATE TRIGGER `after_update_user_signup` AFTER UPDATE ON `users` FOR EACH ROW if(new.status=0) THEN UPDATE database_learn.`users` SET `status` = 'active', `updated_at` = UNIX_TIMESTAMP(), password=new.password WHERE mobile=SUBSTRING(new.fullphone_number, 3); end if";
        DB::statement('DROP TRIGGER IF EXISTS after_update_user_signup;');
        DB::statement(formatSqlWithEnv($viewSqlCode));
    }


    public function down()
    {
        DB::statement('DROP TRIGGER IF EXISTS after_update_user_signup;');
    }
};
