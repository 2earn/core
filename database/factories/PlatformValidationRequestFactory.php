<?php

namespace Database\Factories;

use App\Models\Platform;
use App\Models\PlatformValidationRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlatformValidationRequestFactory extends Factory
{
    protected $model = PlatformValidationRequest::class;

    public function definition(): array
    {
        return [
            'platform_id' => Platform::factory(),
            'status' => PlatformValidationRequest::STATUS_PENDING,
            'rejection_reason' => null,
            'requested_by' => User::factory(),
            'reviewed_by' => null,
            'reviewed_at' => null,
        ];
    }

    /**
     * Set status to pending
     */
    public function pending(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => PlatformValidationRequest::STATUS_PENDING,
                'reviewed_by' => null,
                'reviewed_at' => null,
            ];
        });
    }

    /**
     * Set status to approved
     */
    public function approved(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => PlatformValidationRequest::STATUS_APPROVED,
                'reviewed_by' => User::factory(),
                'reviewed_at' => now(),
            ];
        });
    }

    /**
     * Set status to rejected
     */
    public function rejected(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => PlatformValidationRequest::STATUS_REJECTED,
                'rejection_reason' => $this->faker->sentence(),
                'reviewed_by' => User::factory(),
                'reviewed_at' => now(),
            ];
        });
    }

    /**
     * Set status to cancelled
     */
    public function cancelled(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => PlatformValidationRequest::STATUS_CANCELLED,
                'rejection_reason' => $this->faker->sentence(),
                'reviewed_by' => User::factory(),
                'reviewed_at' => now(),
            ];
        });
    }
}
