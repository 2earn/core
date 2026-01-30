<?php

namespace Database\Factories;

use App\Models\DealValidationRequest;
use App\Models\Deal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DealValidationRequestFactory extends Factory
{
    protected $model = DealValidationRequest::class;

    public function definition(): array
    {
        return [
            'deal_id' => Deal::factory(),
            'status' => 'pending',
            'rejection_reason' => null,
            'requested_by_id' => User::factory(),
            'reviewed_by' => null,
            'reviewed_at' => null,
        ];
    }

    public function pending(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'pending',
                'reviewed_by' => null,
                'reviewed_at' => null,
            ];
        });
    }

    public function approved(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'approved',
                'reviewed_by' => User::factory(),
                'reviewed_at' => now(),
            ];
        });
    }

    public function rejected(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'rejected',
                'rejection_reason' => $this->faker->sentence(),
                'reviewed_by' => User::factory(),
                'reviewed_at' => now(),
            ];
        });
    }
}
