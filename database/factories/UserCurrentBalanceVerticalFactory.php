<?php

namespace Database\Factories;

use App\Models\UserCurrentBalanceVertical;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserCurrentBalanceVerticalFactory extends Factory
{
    protected $model = UserCurrentBalanceVertical::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'user_id_auto' => $this->faker->unique()->numberBetween(1000, 9999),
            'current_balance' => $this->faker->randomFloat(2, 0, 10000),
            'previous_balance' => $this->faker->randomFloat(2, 0, 10000),
            'balance_id' => $this->faker->numberBetween(1, 6), // Corresponds to BalanceEnum values
            'last_operation_id' => $this->faker->optional()->numberBetween(1, 1000),
            'last_operation_date' => $this->faker->optional()->dateTime(),
            'last_operation_value' => $this->faker->optional()->randomFloat(2, -1000, 1000),
        ];
    }

    public function withBalance(int $balanceId, float $amount = 100.0): self
    {
        return $this->state(function (array $attributes) use ($balanceId, $amount) {
            return [
                'balance_id' => $balanceId,
                'current_balance' => $amount,
                'previous_balance' => $amount,
            ];
        });
    }
}
