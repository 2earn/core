<?php

namespace Database\Factories;

use App\Models\action_historys;
use Illuminate\Database\Eloquent\Factories\Factory;

class Action_historysFactory extends Factory
{
    protected $model = action_historys::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'action_type' => $this->faker->randomElement(['create', 'update', 'delete', 'view']),
            'user_id' => $this->faker->numberBetween(1, 100),
        ];
    }
}
