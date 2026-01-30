<?php
namespace Database\Factories;
use App\Models\SMSBalances;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
class SMSBalancesFactory extends Factory
{
    protected $model = SMSBalances::class;
    public function definition(): array
    {
        return [
            'value' => $this->faker->numberBetween(1, 100),
            'description' => $this->faker->sentence(),
            'current_balance' => $this->faker->numberBetween(0, 1000),
            'reference' => 'REF-' . $this->faker->unique()->numerify('######'),
            'amount' => $this->faker->randomFloat(2, 10, 500),
            'balance_operation_id' => 1,
            'beneficiary_id' => User::factory(),
            'operator_id' => null,
            'order_id' => null,
        ];
    }
}