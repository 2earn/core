<?php
namespace Database\Factories;
use App\Models\ChanceBalances;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
class ChanceBalancesFactory extends Factory
{
    protected $model = ChanceBalances::class;
    public function definition(): array
    {
        return [
            'value' => $this->faker->numberBetween(1, 20),
            'description' => $this->faker->sentence(),
            'current_balance' => $this->faker->numberBetween(0, 100),
            'reference' => 'REF-' . $this->faker->unique()->numerify('######'),
            'balance_operation_id' => 1,
            'beneficiary_id' => User::factory(),
            'operator_id' => null,
            'pool_id' => $this->faker->numberBetween(1, 10),
        ];
    }
}