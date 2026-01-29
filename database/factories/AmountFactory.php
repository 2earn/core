<?php
namespace Database\Factories;
use App\Models\Amount;
use Illuminate\Database\Eloquent\Factories\Factory;
class AmountFactory extends Factory
{
    protected $model = Amount::class;
    public function definition(): array
    {
        return [
            'amountsname' => $this->faker->word() . ' Amount',
            'amountsshortname' => $this->faker->lexify('???'),
        ];
    }
}
