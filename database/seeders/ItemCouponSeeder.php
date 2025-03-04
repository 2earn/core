<?php

namespace Database\Seeders;

use App\Models\Item;
use Core\Models\Platform;
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
        $platforms = Platform::all();
        foreach ($platforms as $platform) {
            $params['platform_id'] = $platform->id;
            Item::create($params);
        }
    }
}
