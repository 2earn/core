<?php

namespace Database\Factories;

use App\Models\OperationCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class OperationCategoryFactory extends Factory
{
    protected $model = OperationCategory::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'code' => strtoupper($this->faker->unique()->bothify('??###')),
            'description' => $this->faker->sentence(),
        ];
    }
}
