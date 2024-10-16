<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class Sprint007Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\BeCommitedInvestorSettingSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\ChangePasswordSettingSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\GiftedShareSettingSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\WattsappHelpNumberSettingSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\ComingSoonSeeder']);
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\StaticNewsSettingSeeder']);
    }
}
