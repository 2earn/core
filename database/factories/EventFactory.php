<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraphs(3, true),
            'enabled' => true,
            'location' => $this->faker->address(),
            'published_at' => $this->faker->dateTimeBetween('-30 days', '+30 days'),
            'start_at' => $this->faker->dateTimeBetween('+1 day', '+60 days'),
            'end_at' => $this->faker->dateTimeBetween('+61 days', '+90 days'),
        ];
    }

    public function enabled(): static
    {
        return $this->state(fn (array $attributes) => [
            'enabled' => true,
        ]);
    }

    public function disabled(): static
    {
        return $this->state(fn (array $attributes) => [
            'enabled' => false,
        ]);
    }
}
