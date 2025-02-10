<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;

class Sprint009Seeder extends Seeder
{

    public function run($dataTranslation = false, $dataMoney = true, $dataDeal = true)
    {
        if (App::environment('local')) {
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\BusinessSectorSeeder']);
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\PlatformSeeder']);
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\DealsSeeder']);
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\AddCashSeeder']);
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\DealsInsertSeeder']);
            Artisan::call('db:seed', ['--class' => 'Database\Seeders\TranslateSeeder']);
        }
    }
}
