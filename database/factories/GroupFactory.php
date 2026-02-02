<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\Target;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupFactory extends Factory
{
    protected $model = Group::class;

    public function definition(): array
    {
        return [
            'operator' => $this->faker->randomElement(['AND', 'OR']),
            'target_id' => Target::factory(),
        ];
    }
}
