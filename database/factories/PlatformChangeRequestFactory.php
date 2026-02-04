<?php

namespace Database\Factories;

use App\Models\PlatformChangeRequest;
use App\Models\Platform;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PlatformChangeRequest>
 */
class PlatformChangeRequestFactory extends Factory
{
    protected $model = PlatformChangeRequest::class;

    public function definition(): array
    {
        return [
            'platform_id' => Platform::factory(),
            'changes' => ['name' => ['old' => 'Old Name', 'new' => 'New Name']],
            'status' => PlatformChangeRequest::STATUS_PENDING,
            'rejection_reason' => null,
            'requested_by' => User::factory(),
            'reviewed_by' => null,
            'reviewed_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn(array $attributes) => ['status' => PlatformChangeRequest::STATUS_PENDING]);
    }

    public function approved(): static
    {
        return $this->state(fn(array $attributes) => ['status' => PlatformChangeRequest::STATUS_APPROVED]);
    }

    public function rejected(): static
    {
        return $this->state(fn(array $attributes) => ['status' => PlatformChangeRequest::STATUS_REJECTED]);
    }

    public function cancelled(): static
    {
        return $this->state(fn(array $attributes) => ['status' => PlatformChangeRequest::STATUS_CANCELLED]);
    }
}
