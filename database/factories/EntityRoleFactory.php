<?php

namespace Database\Factories;

use App\Models\EntityRole;
use App\Models\Platform;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EntityRoleFactory extends Factory
{
    protected $model = EntityRole::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['owner', 'admin', 'manager', 'partner']),
            'roleable_id' => Platform::factory(),
            'roleable_type' => Platform::class,
            'user_id' => User::factory(),
            'created_by' => null,
            'updated_by' => null,
        ];
    }

    /**
     * Indicate the role is for a platform
     */
    public function forPlatform(int $platformId = null): static
    {
        return $this->state(function (array $attributes) use ($platformId) {
            return [
                'roleable_id' => $platformId ?? Platform::factory(),
                'roleable_type' => Platform::class,
            ];
        });
    }

    /**
     * Indicate the role is for a specific user
     */
    public function forUser(int $userId): static
    {
        return $this->state(function (array $attributes) use ($userId) {
            return [
                'user_id' => $userId,
            ];
        });
    }

    /**
     * Indicate the role has a specific name
     */
    public function withName(string $name): static
    {
        return $this->state(function (array $attributes) use ($name) {
            return [
                'name' => $name,
            ];
        });
    }

    /**
     * Owner role
     */
    public function owner(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'owner',
            ];
        });
    }

    /**
     * Admin role
     */
    public function admin(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'admin',
            ];
        });
    }

    /**
     * Manager role
     */
    public function manager(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'manager',
            ];
        });
    }

    /**
     * Partner role
     */
    public function partner(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'partner',
            ];
        });
    }
}
