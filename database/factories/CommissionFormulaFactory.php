<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CommissionFormula>
 */
class CommissionFormulaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $initialCommission = $this->faker->randomFloat(2, 1, 15);
        $finalCommission = $this->faker->randomFloat(2, $initialCommission + 1, 30);

        return [
            'initial_commission' => $initialCommission,
            'final_commission' => $finalCommission,
            'name' => $this->faker->words(3, true) . ' Commission Plan',
            'description' => $this->faker->sentence(10),
            'is_active' => $this->faker->boolean(80), // 80% chance of being active
            'created_by' => null,
            'updated_by' => null,
        ];
    }

    /**
     * Indicate that the formula is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the formula is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Set specific commission range.
     */
    public function withRange(float $initial, float $final): static
    {
        return $this->state(fn (array $attributes) => [
            'initial_commission' => $initial,
            'final_commission' => $final,
        ]);
    }
}

