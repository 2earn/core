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
            BalanceInjectorCoupon::create([
                'pin' => strtoupper(Str::random(10)),
                'sn' => 'SN' . rand(10000, 99999),
                'attachment_date' => Carbon::now()->subDays(rand(1, 30)),
                'purchase_date' => Carbon::now()->subDays(rand(1, 20)),
                'consumption_date' => rand(0, 1) ? Carbon::now()->subDays(rand(1, 10)) : null,
                'value' => rand(100, 200),
                'consumed' => rand(0, 1),
                'status' => 1,
                'category' => 1,
                'type' => null,
                'reserved_until' => rand(0, 1) ? Carbon::now()->addDays(rand(1, 15)) : null,
            ]);

        }
    }
}
