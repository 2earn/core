<?php

namespace Database\Factories;

use App\Models\BalanceOperation;
use Illuminate\Database\Eloquent\Factories\Factory;

class BalanceOperationFactory extends Factory
{
    protected $model = BalanceOperation::class;

    public function definition(): array
    {
        return [
            'operation' => $this->faker->words(3, true),
            'io' => $this->faker->randomElement(['I', 'O']),
            'source' => $this->faker->randomElement(['web', 'mobile', 'admin', 'system']),
            'mode' => $this->faker->randomElement(['auto', 'manual', 'scheduled']),
            'amounts_id' => $this->faker->numberBetween(1, 100),
            'note' => $this->faker->optional()->sentence(),
            'modify_amount' => $this->faker->boolean(80),
            'parent_id' => null,
            'operation_category_id' => null,
            'ref' => $this->faker->optional()->uuid(),
            'direction' => $this->faker->randomElement(['IN', 'OUT']),
            'balance_id' => $this->faker->optional()->numberBetween(1, 1000),
            'parent_operation_id' => null,
        ];
    }
}
