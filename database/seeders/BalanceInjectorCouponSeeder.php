<?php

namespace Database\Seeders;

use App\Models\BalanceInjectorCoupon;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BalanceInjectorCouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(1, 50) as $i) {
            $category = rand(1, 3);
            BalanceInjectorCoupon::create([
                'pin' => strtoupper(Str::random(20)),
                'sn' => 'SN' . rand(100000, 999999),
                'attachment_date' => Carbon::now()->subDays(rand(1, 30)),
                'consumption_date' => rand(0, 1) ? Carbon::now()->subDays(rand(1, 10)) : null,
                'value' => rand(100, 200),
                'consumed' => 0,
                'status' => 1,
                'category' => $category,
                'type' => $category == 2 ? '100.00' : null,
            ]);

        }
    }
}
