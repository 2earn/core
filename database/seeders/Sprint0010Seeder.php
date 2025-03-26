<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class Sprint0010Seeder extends Seeder
{

    public function run($dataTranslation = false, $dataMoney = true, $dataDeal = true)
    {
        Log::notice('Starting Seeder Sprint008Seeder');
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\ItemCouponSeeder']);
        Log::notice('Ending Seeder Sprint0010Seeder');
    }
}
