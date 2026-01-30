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
            'ref' => 'REF-' . $this->faker->unique()->uuid(),
            'operation_category_id' => $this->faker->numberBetween(1, 10),
            'operation' => $this->faker->words(3, true),
            'direction' => $this->faker->randomElement(['IN', 'OUT']),
            'balance_id' => $this->faker->numberBetween(1, 100),
            'parent_operation_id' => null,
            'relateble' => $this->faker->boolean(20),
            'relateble_model' => $this->faker->optional()->word(),
            'relateble_types' => $this->faker->optional()->word(),
        ];
    }
}
