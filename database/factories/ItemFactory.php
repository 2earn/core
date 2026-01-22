<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use App\Models\Deal;
use App\Models\Platform;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Item::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $price = $this->faker->randomFloat(2, 10, 500);
        $discount = $this->faker->randomFloat(2, 0, 30);
        $discount2Earn = $this->faker->randomFloat(2, 0, 15);

        return [
            'name' => $this->faker->words(3, true),
            'ref' => $this->faker->unique()->bothify('ITEM-####-???'),
            'price' => $price,
            'discount' => $discount,
            'discount_2earn' => $discount2Earn,
            'photo_link' => $this->faker->optional()->imageUrl(400, 400, 'products'),
            'description' => $this->faker->optional()->sentence(10),
            'stock' => $this->faker->numberBetween(0, 100),
            'deal_id' => null,
            'platform_id' => Platform::factory(),
            'created_by' => null,
            'updated_by' => null,
        ];
    }

    /**
     * Indicate that the item is in stock.
     */
    public function inStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => $this->faker->numberBetween(10, 100),
        ]);
    }

    /**
     * Indicate that the item is out of stock.
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
        ]);
    }

    /**
     * Indicate that the item has a high discount.
     */
    public function highDiscount(): static
    {
        return $this->state(fn (array $attributes) => [
            'discount' => $this->faker->randomFloat(2, 30, 50),
        ]);
    }

    /**
     * Indicate that the item is assigned to a deal.
     */
    public function withDeal($dealId = null): static
    {
        return $this->state(fn (array $attributes) => [
            'deal_id' => $dealId ?? Deal::factory(),
        ]);
    }
}
