<?php

namespace Database\Factories;

use App\Enums\CouponStatusEnum;
use App\Models\Coupon;
use App\Models\Platform;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    public function definition(): array
    {
        return [
            'pin' => strtoupper($this->faker->unique()->bothify('????##########')),
            'sn' => 'SN' . $this->faker->unique()->numerify('##############'),
            'attachment_date' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'purchase_date' => null,
            'consumption_date' => null,
            'value' => $this->faker->randomElement([10, 25, 50, 100, 200]),
            'consumed' => 0,
            'status' => CouponStatusEnum::available->value,
            'reserved_until' => null,
            'platform_id' => Platform::factory(),
            'user_id' => User::factory(), // Database requires NOT NULL
        ];
    }

    public function consumed(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'consumed' => 1,
                'status' => CouponStatusEnum::consumed->value,
                'consumption_date' => now(),
            ];
        });
    }

    public function available(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'consumed' => 0,
                'status' => CouponStatusEnum::available->value,
                'consumption_date' => null,
            ];
        });
    }

    public function purchased(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'consumed' => 0,
                'status' => CouponStatusEnum::purchased->value,
                'purchase_date' => now(),
            ];
        });
    }
}
