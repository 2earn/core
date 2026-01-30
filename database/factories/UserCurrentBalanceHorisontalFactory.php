<?php

namespace Database\Factories;

use App\Models\UserCurrentBalanceHorisontal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserCurrentBalanceHorisontalFactory extends Factory
{
    protected $model = UserCurrentBalanceHorisontal::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'user_id_auto' => $this->faker->unique()->numberBetween(1000, 9999),
            'cash_balance' => $this->faker->randomFloat(2, 0, 10000),
            'bfss_balance' => [
                ['type' => 'type1', 'value' => $this->faker->randomFloat(2, 0, 1000)],
                ['type' => 'type2', 'value' => $this->faker->randomFloat(2, 0, 1000)],
            ],
            'discount_balance' => $this->faker->randomFloat(2, 0, 5000),
            'tree_balance' => $this->faker->randomFloat(2, 0, 1000),
            'sms_balance' => $this->faker->randomFloat(2, 0, 500),
            'share_balance' => $this->faker->randomFloat(2, 0, 10000),
            'chances_balance' => [
                ['pool_id' => 1, 'value' => $this->faker->numberBetween(0, 100)],
            ],
        ];
    }

    public function withCashBalance(float $amount): self
    {
        return $this->state(function (array $attributes) use ($amount) {
            return [
                'cash_balance' => $amount,
            ];
        });
    }

    public function withShareBalance(float $amount): self
    {
        return $this->state(function (array $attributes) use ($amount) {
            return [
                'share_balance' => $amount,
            ];
        });
    }
}
