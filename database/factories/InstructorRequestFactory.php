<?php

namespace Database\Factories;

use App\Enums\BeInstructorRequestStatus;
use App\Models\InstructorRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstructorRequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InstructorRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'status' => BeInstructorRequestStatus::InProgress->value,
            'note' => $this->faker->optional()->sentence(),
            'request_date' => $this->faker->date(),
            'examination_date' => $this->faker->optional()->date(),
            'user_id' => User::factory(),
            'examiner_id' => null,
            'created_by' => null,
            'updated_by' => null,
        ];
    }

    /**
     * Indicate that the request is validated
     */
    public function validated(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BeInstructorRequestStatus::Validated->value,
            'examination_date' => $this->faker->date(),
            'examiner_id' => User::factory(),
        ]);
    }

    /**
     * Indicate that the request is rejected
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BeInstructorRequestStatus::Rejected->value,
            'examination_date' => $this->faker->date(),
            'examiner_id' => User::factory(),
            'note' => 'Request rejected: ' . $this->faker->sentence(),
        ]);
    }

    /**
     * Indicate that the request is in progress
     */
    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BeInstructorRequestStatus::InProgress->value,
            'examination_date' => null,
            'examiner_id' => null,
        ]);
    }

    /**
     * Indicate that the request is validated by 2earn
     */
    public function validated2earn(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BeInstructorRequestStatus::Validated2earn->value,
            'examination_date' => $this->faker->date(),
            'examiner_id' => User::factory(),
        ]);
    }
}
