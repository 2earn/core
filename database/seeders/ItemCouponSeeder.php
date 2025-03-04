<?php

namespace Database\Seeders;

use App\Models\Deal;
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
            $dealsIds = Deal::where('platform_id', $platform->id)->pluck('id')->toArray();
            $dealsId = $dealsIds[array_rand($dealsIds)];
            $deal = Deal::find($dealsId);
            $params['platform_id'] = $platform->id;
            $params['deal_id'] = $deal->id;
            Item::create($params);
        }

    }
}
