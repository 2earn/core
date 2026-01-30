<?php

namespace Database\Factories;

use App\Models\translatetabs;
use Illuminate\Database\Eloquent\Factories\Factory;

class TranslatetabsFactory extends Factory
{
    protected $model = translatetabs::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->slug(3),
            'value' => $this->faker->sentence(),
            'valueFr' => $this->faker->sentence(),
            'valueEn' => $this->faker->sentence(),
            'valueTr' => $this->faker->sentence(),
            'valueEs' => $this->faker->sentence(),
            'valueRu' => $this->faker->sentence(),
            'valueDe' => $this->faker->sentence(),
        ];
    }
}
