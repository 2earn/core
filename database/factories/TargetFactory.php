<?php

namespace Database\Factories;

use App\Models\Target;
use Illuminate\Database\Eloquent\Factories\Factory;

class TargetFactory extends Factory
{
    protected $model = Target::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
        ];
    }
}
