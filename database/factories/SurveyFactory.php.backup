<?php

namespace Database\Factories;

use App\Models\Survey;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SurveyFactory extends Factory
{
    protected $model = Survey::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'is_active' => $this->faker->boolean(70),
            'start_date' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'end_date' => $this->faker->dateTimeBetween('now', '+60 days'),
            'created_by' => User::factory(),
        ];
    }

    public function active(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => true,
            ];
        });
    }

    public function inactive(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => false,
            ];
        });
    }
}
