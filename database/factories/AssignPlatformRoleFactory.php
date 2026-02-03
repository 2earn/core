<?php

namespace Database\Factories;

use App\Models\AssignPlatformRole;
use App\Models\Platform;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssignPlatformRoleFactory extends Factory
{
    protected $model = AssignPlatformRole::class;

    public function definition()
    {
        return [
            'platform_id' => Platform::factory(),
            'user_id' => User::factory(),
            // Generate a reproducibly-unique role name per record to avoid DB unique constraint collisions
            'role' => 'role_' . $this->faker->unique()->numberBetween(1, 1000000),
            'status' => AssignPlatformRole::STATUS_PENDING,
            'rejection_reason' => null,
            'created_by' => null,
            'updated_by' => null,
        ];
    }

    public function pending()
    {
        return $this->state(function (array $attributes) {
            return ['status' => AssignPlatformRole::STATUS_PENDING];
        });
    }

    public function approved()
    {
        return $this->state(function (array $attributes) {
            return ['status' => AssignPlatformRole::STATUS_APPROVED];
        });
    }

    public function rejected()
    {
        return $this->state(function (array $attributes) {
            return ['status' => AssignPlatformRole::STATUS_REJECTED];
        });
    }
}
