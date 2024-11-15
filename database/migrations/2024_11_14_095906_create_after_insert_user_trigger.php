<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up()
    {

        $viewSqlCode = "CREATE TRIGGER `after_insert_user` AFTER INSERT ON `users` FOR EACH ROW IF(new.status = -2) THEN INSERT INTO database_learn.`users` ( `full_name`, `role_name`, `role_id`, `mobile`, `status`, `created_at`, `updated_at`, `avatar`) VALUES ( SUBSTRING(new.fullphone_number, 3), 'user', '1', SUBSTRING(new.fullphone_number, 3), 'pending', UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), '/store/1/default_images/user.jpg' ); ELSE INSERT INTO prod_learn.`users` ( `full_name`, `role_name`, `role_id`, `mobile`, `status`, `created_at`, `updated_at`, `avatar` ) VALUES ( SUBSTRING(new.fullphone_number, 3), 'user', '1', SUBSTRING(new.fullphone_number, 3), 'active', UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), '/store/1/default_images/user.jpg' ); END IF";
        DB::statement('DROP TRIGGER IF EXISTS after_insert_user;');
        DB::statement(formatSqlWithEnv($viewSqlCode));
    }


    public function down()
    {
        DB::statement('DROP TRIGGER IF EXISTS after_insert_user;');
    }
};
