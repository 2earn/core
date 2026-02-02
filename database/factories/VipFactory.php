<?php

namespace Database\Factories;

use App\Models\vip;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class VipFactory extends Factory
{
    protected $model = vip::class;

    public function definition(): array
    {
        return [
            'idUser' => User::factory(),
            'flashCoefficient' => $this->faker->randomFloat(2, 1, 5),
            'flashDeadline' => $this->faker->numberBetween(24, 168), // 1-7 days in hours
            'flashNote' => $this->faker->optional()->sentence(),
            'flashMinAmount' => $this->faker->numberBetween(10, 100),
            'dateFNS' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'maxShares' => $this->faker->numberBetween(100, 1000),
            'solde' => $this->faker->numberBetween(0, 500),
            'declenched' => false,
            'declenchedDate' => null,
            'closed' => false,
            'closedDate' => null,
        ];
    }

    public function active(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'closed' => false,
                'dateFNS' => now()->subHours(12),
                'flashDeadline' => 48,
            ];
        });
    }

    public function closed(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'closed' => true,
                'closedDate' => now(),
            ];
        });
    }

    public function declenched(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'declenched' => true,
                'declenchedDate' => now(),
            ];
        });
    }
}
