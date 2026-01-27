<?php

namespace Database\Factories;

use App\Models\DealChangeRequest;
use App\Models\Deal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DealChangeRequestFactory extends Factory
{
    protected $model = DealChangeRequest::class;

    public function definition(): array
    {
        return [
            'deal_id' => Deal::factory(),
            'changes' => [
                'field' => 'discount',
                'old_value' => $this->faker->randomFloat(2, 5, 20),
                'new_value' => $this->faker->randomFloat(2, 10, 30),
            ],
            'status' => DealChangeRequest::STATUS_PENDING,
            'rejection_reason' => null,
            'requested_by' => User::factory(),
            'reviewed_by' => null,
            'reviewed_at' => null,
        ];
    }

    public function pending(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => DealChangeRequest::STATUS_PENDING,
                'reviewed_by' => null,
                'reviewed_at' => null,
            ];
        });
    }

    public function approved(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => DealChangeRequest::STATUS_APPROVED,
                'reviewed_by' => User::factory(),
                'reviewed_at' => now(),
            ];
        });
    }

    public function rejected(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => DealChangeRequest::STATUS_REJECTED,
                'rejection_reason' => $this->faker->sentence(),
                'reviewed_by' => User::factory(),
                'reviewed_at' => now(),
            ];
        });
    }

    public function cancelled(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => DealChangeRequest::STATUS_CANCELLED,
            ];
        });
    }
}
