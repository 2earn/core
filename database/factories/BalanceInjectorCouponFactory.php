<?php

namespace Database\Factories;

use App\Models\BalanceInjectorCoupon;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BalanceInjectorCouponFactory extends Factory
{
    protected $model = BalanceInjectorCoupon::class;

    public function definition(): array
    {
        return [
            'pin' => strtoupper($this->faker->unique()->bothify('????##########')),
            'sn' => 'SN' . $this->faker->unique()->numerify('##############'),
            'attachment_date' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'value' => $this->faker->randomElement([10, 25, 50, 100, 200]),
            'consumed' => 0,
            'status' => 1,
            'category' => $this->faker->numberBetween(1, 3),
            'type' => $this->faker->randomElement(['50.00', '100.00', '200.00']),
        ];
    }

    public function consumed(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'consumed' => 1,
                'consumption_date' => now(),
                'user_id' => User::factory(),
            ];
        });
    }

    public function unconsumed(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'consumed' => 0,
                'consumption_date' => null,
                'user_id' => null,
            ];
        });
    }
}
