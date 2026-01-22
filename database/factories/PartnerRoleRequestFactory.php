<?php

namespace Database\Factories;

use App\Models\Partner;
use App\Models\PartnerRoleRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PartnerRoleRequest>
 */
class PartnerRoleRequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PartnerRoleRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'partner_id' => Partner::factory(),
            'user_id' => User::factory(),
            'role_name' => $this->faker->randomElement(['manager', 'admin', 'supervisor', 'coordinator']),
            'status' => PartnerRoleRequest::STATUS_PENDING,
            'reason' => $this->faker->sentence(10),
            'requested_by' => User::factory(),
        ];
    }

    /**
     * Indicate that the request is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PartnerRoleRequest::STATUS_PENDING,
        ]);
    }

    /**
     * Indicate that the request is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PartnerRoleRequest::STATUS_APPROVED,
            'reviewed_by' => User::factory(),
            'reviewed_at' => now(),
        ]);
    }

    /**
     * Indicate that the request is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PartnerRoleRequest::STATUS_REJECTED,
            'rejection_reason' => $this->faker->sentence(8),
            'reviewed_by' => User::factory(),
            'reviewed_at' => now(),
        ]);
    }

    /**
     * Indicate that the request is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PartnerRoleRequest::STATUS_CANCELLED,
            'cancelled_reason' => $this->faker->sentence(6),
            'cancelled_by' => User::factory(),
            'cancelled_at' => now(),
        ]);
    }
}
