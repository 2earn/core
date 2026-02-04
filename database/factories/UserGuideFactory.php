<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserGuide;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserGuideFactory extends Factory
{
    protected $model = UserGuide::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'file_path' => 'guides/' . $this->faker->slug() . '.pdf',
            'user_id' => User::factory(),
            'routes' => $this->faker->randomElements(['home', 'dashboard', 'profile', 'settings', 'users', 'reports'], $this->faker->numberBetween(1, 3)),
        ];
    }
}
