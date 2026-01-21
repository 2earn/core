<?php

namespace Database\Factories;

use App\Models\Platform;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Platform>
 */
class PlatformFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Platform::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company() . ' Platform',
            'description' => $this->faker->sentence(10),
            'enabled' => $this->faker->boolean(70), // 70% chance of being enabled
            'type' => $this->faker->numberBetween(1, 3), // Types: 1=Full, 2=Partial, 3=Limited
            'link' => $this->faker->url(),
            'show_profile' => $this->faker->boolean(80),
            'image_link' => $this->faker->imageUrl(640, 480, 'business', true),
            'business_sector_id' => $this->faker->numberBetween(1, 10),
            'created_by' => User::factory(),
            'updated_by' => null,
        ];
    }

    /**
     * Indicate that the platform is enabled.
     */
    public function enabled(): static
    {
        return $this->state(fn (array $attributes) => [
            'enabled' => true,
        ]);
    }

    /**
     * Indicate that the platform is disabled.
     */
    public function disabled(): static
    {
        return $this->state(fn (array $attributes) => [
            'enabled' => false,
        ]);
    }

    /**
     * Set platform type to Full (Type 1).
     */
    public function typeFull(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 1,
        ]);
    }

    /**
     * Set platform type to Partial (Type 2).
     */
    public function typePartial(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 2,
        ]);
    }

    /**
     * Set platform type to Limited (Type 3).
     */
    public function typeLimited(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 3,
        ]);
    }

    /**
     * Set a specific creator.
     */
    public function createdBy(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'created_by' => $user->id,
        ]);
    }
}
