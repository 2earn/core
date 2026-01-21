<?php

namespace Database\Factories;

use App\Models\PlanLabel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PlanLabel>
 */
class PlanLabelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PlanLabel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $step = $this->faker->numberBetween(1, 10);
        $stars = $this->faker->numberBetween(1, 5);
        $initialCommission = $this->faker->randomFloat(2, 1, 10);
        $finalCommission = $this->faker->randomFloat(2, $initialCommission, 20);

        return [
            'step' => $step,
            'rate' => $this->faker->randomFloat(2, 0, 100),
            'stars' => $stars,
            'initial_commission' => $initialCommission,
            'final_commission' => $finalCommission,
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'is_active' => $this->faker->boolean(80),
            'created_by' => null,
            'updated_by' => null,
        ];
    }

    /**
     * Indicate that the plan label is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the plan label is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Set a specific star rating.
     */
    public function stars(int $stars): static
    {
        return $this->state(fn (array $attributes) => [
            'stars' => $stars,
        ]);
    }

    /**
     * Set a specific step level.
     */
    public function step(int $step): static
    {
        return $this->state(fn (array $attributes) => [
            'step' => $step,
        ]);
    }
}
