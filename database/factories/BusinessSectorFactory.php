<?php

namespace Database\Factories;

use App\Models\BusinessSector;
use Illuminate\Database\Eloquent\Factories\Factory;

class BusinessSectorFactory extends Factory
{
    protected $model = BusinessSector::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->optional()->sentence(),
            'color' => $this->faker->optional()->hexColor(),
        ];
    }
}
