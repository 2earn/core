<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {

        $viewSqlCode = "CREATE TRIGGER `after_update_user_instructor` AFTER UPDATE ON `users` FOR EACH ROW BEGIN DECLARE iduser int; SELECT id into iduser FROM prod_learn.users where mobile=SUBSTRING(new.fullphone_number, 3); IF NEW.instructor = 1 AND OLD.instructor = 0 THEN INSERT INTO database_learn.`become_instructors`( `user_id`, `role`, `package_id`, `status`, `created_at`) VALUES( iduser, 'teacher', 2, 'pending', UNIX_TIMESTAMP() ); end if; END";
        DB::statement('DROP TRIGGER IF EXISTS after_update_user_instructor;');
        DB::statement(formatSqlWithEnv($viewSqlCode));
    }


    public function down()
    {
        DB::statement('DROP TRIGGER IF EXISTS after_update_user_instructor;');
    }
};
