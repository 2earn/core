<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Services\Orders\OrderingSimulation;
use Core\Models\Platform;
use Faker\Generator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemCouponSeeder extends Seeder
{

    public function run()
    {
        $params = [
            'name' => 'Coupon',
            'ref' => '#0001',
            'price' => 0,
            'discount' => 0,
            'discount_2earn' => 0,
        ];
       Item::create($params);
    }
}
