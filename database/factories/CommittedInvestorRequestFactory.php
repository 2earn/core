<?php

namespace Database\Factories;

use App\Models\CommittedInvestorRequest;
use App\Models\User;
use App\Enums\RequestStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommittedInvestorRequestFactory extends Factory
{
    protected $model = CommittedInvestorRequest::class;

    public function definition(): array
    {
        return [
            'status' => RequestStatus::InProgress->value,
            'note' => $this->faker->sentence(),
            'request_date' => now(),
            'examination_date' => null,
            'user_id' => User::factory(),
            'examiner_id' => null,
        ];
    }

    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => RequestStatus::InProgress->value,
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => RequestStatus::Approved->value,
            'examination_date' => now(),
            'examiner_id' => User::factory(),
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => RequestStatus::Rejected->value,
            'examination_date' => now(),
            'examiner_id' => User::factory(),
        ]);
    }
}
