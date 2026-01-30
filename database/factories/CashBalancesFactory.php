<?php

namespace Database\Factories;

use App\Models\CashBalances;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CashBalancesFactory extends Factory
{
    protected $model = CashBalances::class;

    public function definition(): array
    {
        return [
            'value' => $this->faker->randomFloat(2, 10, 1000),
            'description' => $this->faker->sentence(),
            'current_balance' => $this->faker->randomFloat(2, 0, 10000),
            'reference' => 'REF-' . $this->faker->unique()->numerify('######'),
            'balance_operation_id' => 42, // Default sales operation
            'beneficiary_id' => User::factory(),
            'operator_id' => null,
            'order_id' => null,
        ];
    }

    public function withOperation(int $operationId): static
    {
        return $this->state(fn (array $attributes) => [
            'balance_operation_id' => $operationId,
        ]);
    }

    public function withDescription(string $description): static
    {
        return $this->state(fn (array $attributes) => [
            'description' => $description,
        ]);
    }

    public function noDescription(): static
    {
        return $this->state(fn (array $attributes) => [
            'description' => null,
        ]);
    }
}
