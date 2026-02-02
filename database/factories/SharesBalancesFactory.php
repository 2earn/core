<?php
namespace Database\Factories;
use App\Models\SharesBalances;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
class SharesBalancesFactory extends Factory
{
    protected $model = SharesBalances::class;
    public function definition(): array
    {
        return [
            'value' => $this->faker->randomFloat(2, 1, 1000),
            'description' => $this->faker->sentence(),
            'current_balance' => $this->faker->randomFloat(2, 0, 10000),
            'reference' => 'SHARE-' . $this->faker->unique()->numerify('######'),
            'total_amount' => $this->faker->randomFloat(2, 10, 50000),
            'unit_price' => $this->faker->randomFloat(2, 5, 100),
            'amount' => $this->faker->randomFloat(2, 10, 50000),
            'real_amount' => $this->faker->randomFloat(2, 10, 50000),
            'payed' => $this->faker->randomElement([0, 1]),
            'balance_operation_id' => 44,
            'beneficiary_id' => User::factory(),
            'operator_id' => null,
        ];
    }
    public function withOperation(int $operationId): static
    {
        return $this->state(fn (array $attributes) => [
            'balance_operation_id' => $operationId,
        ]);
    }
    public function withBeneficiary(int $beneficiaryId): static
    {
        return $this->state(fn (array $attributes) => [
            'beneficiary_id' => $beneficiaryId,
        ]);
    }
    public function payed(): static
    {
        return $this->state(fn (array $attributes) => [
            'payed' => 1,
        ]);
    }
    public function unpayed(): static
    {
        return $this->state(fn (array $attributes) => [
            'payed' => 0,
        ]);
    }
}