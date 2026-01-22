<?php

namespace Database\Factories;

use App\Enums\DealStatus;
use App\Enums\DealTypeEnum;
use App\Models\Deal;
use App\Models\Platform;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Deal>
 */
class DealFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Deal::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-30 days', 'now');
        $endDate = $this->faker->dateTimeBetween('now', '+60 days');

        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'validated' => $this->faker->boolean(70),
            'type' => $this->faker->randomElement([DealTypeEnum::public->value, DealTypeEnum::coupons->value]),
            'status' => $this->faker->randomElement([
                DealStatus::New->value,
                DealStatus::Opened->value,
                DealStatus::Closed->value,
                DealStatus::Archived->value
            ]),
            'current_turnover' => $this->faker->randomFloat(2, 0, 50000),
            'target_turnover' => $this->faker->randomFloat(2, 10000, 100000),
            'second_target_turnover' => $this->faker->optional()->randomFloat(2, 100000, 200000),
            'is_turnover' => $this->faker->boolean(60),
            'discount' => $this->faker->randomFloat(2, 0, 50),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'initial_commission' => $this->faker->randomFloat(2, 1, 10),
            'final_commission' => $this->faker->randomFloat(2, 5, 20),
            'plan' => $this->faker->optional()->numberBetween(1, 5),
            'earn_profit' => $this->faker->randomFloat(2, 5, 30),
            'jackpot' => $this->faker->randomFloat(2, 1, 10),
            'tree_remuneration' => $this->faker->randomFloat(2, 1, 10),
            'proactive_cashback' => $this->faker->randomFloat(2, 1, 10),
            'total_commission_value' => $this->faker->randomFloat(2, 0, 10000),
            'total_unused_cashback_value' => $this->faker->randomFloat(2, 0, 5000),
            'created_by_id' => User::factory(),
            'platform_id' => Platform::factory(),
            'cash_company_profit' => $this->faker->randomFloat(2, 0, 1000),
            'cash_jackpot' => $this->faker->randomFloat(2, 0, 500),
            'cash_tree' => $this->faker->randomFloat(2, 0, 500),
            'cash_cashback' => $this->faker->randomFloat(2, 0, 500),
            'items_profit_average' => $this->faker->randomFloat(2, 0, 100),
            'created_by' => null,
            'updated_by' => null,
        ];
    }

    /**
     * Indicate that the deal is enabled/validated.
     */
    public function enabled(): static
    {
        return $this->state(fn (array $attributes) => [
            'validated' => true,
            'status' => DealStatus::Opened->value,
        ]);
    }

    /**
     * Indicate that the deal is disabled.
     */
    public function disabled(): static
    {
        return $this->state(fn (array $attributes) => [
            'validated' => false,
            'status' => DealStatus::Closed->value,
        ]);
    }

    /**
     * Indicate that the deal is new.
     */
    public function newStatus(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => DealStatus::New->value,
        ]);
    }

    /**
     * Indicate that the deal is opened.
     */
    public function opened(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => DealStatus::Opened->value,
            'validated' => true,
        ]);
    }

    /**
     * Indicate that the deal is closed.
     */
    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => DealStatus::Closed->value,
        ]);
    }

    /**
     * Indicate that the deal is archived.
     */
    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => DealStatus::Archived->value,
        ]);
    }

    /**
     * Indicate that the deal is of type public.
     */
    public function publicType(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => DealTypeEnum::public->value,
        ]);
    }

    /**
     * Indicate that the deal is of type coupons.
     */
    public function couponType(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => DealTypeEnum::coupons->value,
        ]);
    }
}
