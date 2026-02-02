<?php

namespace Database\Factories;

use App\Models\Sms;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmsFactory extends Factory
{
    protected $model = Sms::class;

    public function definition(): array
    {
        return [
            'message' => $this->faker->sentence(),
            'destination_number' => $this->faker->phoneNumber(),
            'source_number' => $this->faker->phoneNumber(),
            'created_by' => User::factory(),
            'updated_by' => null,
        ];
    }

    public function today(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'created_at' => now(),
            ];
        });
    }

    public function thisWeek(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'created_at' => now()->subDays(rand(0, 6)),
            ];
        });
    }

    public function thisMonth(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'created_at' => now()->subDays(rand(0, 29)),
            ];
        });
    }

    public function withDestination(string $number): self
    {
        return $this->state(function (array $attributes) use ($number) {
            return [
                'destination_number' => $number,
            ];
        });
    }
}
