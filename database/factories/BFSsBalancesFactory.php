<?php
namespace Database\Factories;
use App\Models\BFSsBalances;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
class BFSsBalancesFactory extends Factory
{
    protected $model = BFSsBalances::class;
    public function definition(): array
    {
        return [
            'value' => $this->faker->randomFloat(2, 10, 1000),
            'description' => $this->faker->sentence(),
            'current_balance' => $this->faker->randomFloat(2, 0, 10000),
            'reference' => 'REF-' . $this->faker->unique()->numerify('######'),
            'percentage' => $this->faker->randomElement([BFSsBalances::BFS_100, BFSsBalances::BFS_50]),
            'balance_operation_id' => 1,
            'beneficiary_id' => User::factory(),
            'operator_id' => null,
            'order_id' => null,
        ];
    }
}