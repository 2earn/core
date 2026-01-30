<?php

namespace Database\Factories;

use App\Models\Platform;
use App\Models\PlatformTypeChangeRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlatformTypeChangeRequestFactory extends Factory
{
    protected $model = PlatformTypeChangeRequest::class;

    public function definition(): array
    {
        $oldType = $this->faker->numberBetween(1, 3);
        $newType = $this->faker->numberBetween(1, 3);
        while ($newType === $oldType) {
            $newType = $this->faker->numberBetween(1, 3);
        }

        return [
            'platform_id' => Platform::factory(),
            'old_type' => $oldType,
            'new_type' => $newType,
            'status' => PlatformTypeChangeRequest::STATUS_PENDING,
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
                'status' => PlatformTypeChangeRequest::STATUS_PENDING,
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
                'status' => PlatformTypeChangeRequest::STATUS_APPROVED,
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
                'status' => PlatformTypeChangeRequest::STATUS_REJECTED,
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
                'status' => PlatformTypeChangeRequest::STATUS_CANCELLED,
                'rejection_reason' => $this->faker->sentence(),
                'reviewed_by' => User::factory(),
                'reviewed_at' => now(),
            ];
        });
    }
}
