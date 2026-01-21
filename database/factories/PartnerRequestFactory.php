<?php

namespace Database\Factories;

use App\Models\PartnerRequest;
use App\Models\User;
use App\Models\BusinessSector;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PartnerRequest>
 */
class PartnerRequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PartnerRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_name' => $this->faker->company(),
            'business_sector_id' => 1, // Default sector
            'platform_url' => $this->faker->url(),
            'platform_description' => $this->faker->paragraph(),
            'partnership_reason' => $this->faker->sentence(),
            'status' => 'pending',
            'note' => $this->faker->optional()->sentence(),
            'request_date' => now(),
            'examination_date' => null,
            'user_id' => User::factory(),
            'examiner_id' => null,
            'created_by' => null,
            'updated_by' => null,
        ];
    }

    /**
     * Indicate that the request is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'examination_date' => null,
            'examiner_id' => null,
        ]);
    }

    /**
     * Indicate that the request is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'examination_date' => now(),
            'examiner_id' => User::factory(),
        ]);
    }

    /**
     * Indicate that the request is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
            'examination_date' => now(),
            'examiner_id' => User::factory(),
        ]);
    }
}
