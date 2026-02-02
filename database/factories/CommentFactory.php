<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\User;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'content' => $this->faker->paragraph(),
            'validated' => false,
            'validatedBy_id' => null,
            'validatedAt' => null,
            'user_id' => User::factory(),
            'commentable_id' => Event::factory(),
            'commentable_type' => Event::class,
        ];
    }

    public function validated(): static
    {
        return $this->state(fn (array $attributes) => [
            'validated' => true,
            'validatedBy_id' => User::factory(),
            'validatedAt' => now(),
        ]);
    }

    public function unvalidated(): static
    {
        return $this->state(fn (array $attributes) => [
            'validated' => false,
            'validatedBy_id' => null,
            'validatedAt' => null,
        ]);
    }
}
