<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class DeleteTriggers extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $triggers = [
            'after_insert_user',
            'after_update_user_identification',
            'after_update_user_instructor',
            'after_update_user_signup',
        ];
        if (!App::isProduction()) {
            foreach ($triggers as $trigger) {
                dump("DROP TRIGGER IF EXISTS `" . $trigger . "`;");
                DB::statement("DROP TRIGGER IF EXISTS `" . $trigger . "`;");
            }
        }
    }
}
