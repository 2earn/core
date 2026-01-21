<?php

namespace Database\Factories;

use App\Models\ItemDealHistory;
use App\Models\Item;
use App\Models\Deal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ItemDealHistory>
 */
class ItemDealHistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ItemDealHistory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-30 days', 'now');
        $endDate = $this->faker->optional()->dateTimeBetween($startDate, '+30 days');

        return [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'item_id' => Item::factory(),
            'deal_id' => Deal::factory(),
            'user_id' => User::factory(),
            'created_by' => null,
            'updated_by' => null,
        ];
    }

    /**
     * Indicate that the history entry is currently active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'start_date' => now()->subDays(5),
            'end_date' => now()->addDays(25),
        ]);
    }

    /**
     * Indicate that the history entry has ended.
     */
    public function ended(): static
    {
        return $this->state(fn (array $attributes) => [
            'start_date' => now()->subDays(30),
            'end_date' => now()->subDays(1),
        ]);
    }

    /**
     * Indicate that the history entry is upcoming.
     */
    public function upcoming(): static
    {
        return $this->state(fn (array $attributes) => [
            'start_date' => now()->addDays(1),
            'end_date' => now()->addDays(30),
        ]);
    }
}
