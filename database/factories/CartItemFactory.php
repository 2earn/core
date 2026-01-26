<?php

namespace Database\Factories;

use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartItemFactory extends Factory
{
    protected $model = CartItem::class;

    public function definition(): array
    {
        $qty = $this->faker->numberBetween(1, 5);
        $unitPrice = $this->faker->randomFloat(2, 10, 100);

        return [
            'qty' => $qty,
            'shipping' => $this->faker->randomFloat(2, 0, 20),
            'unit_price' => $unitPrice,
            'total_amount' => $qty * $unitPrice,
            'cart_id' => Cart::factory(),
            'item_id' => Item::factory(),
        ];
    }
}
