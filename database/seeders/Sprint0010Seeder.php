<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;

class Sprint0010Seeder extends Seeder
{

    public function run($dataTranslation = false, $dataMoney = true, $dataDeal = true)
    {
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\ItemCouponSeeder']);

    }
}
