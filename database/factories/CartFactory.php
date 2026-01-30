<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartFactory extends Factory
{
    protected $model = Cart::class;

    public function definition(): array
    {
        return [
            'total_cart' => $this->faker->randomFloat(2, 10, 1000),
            'total_cart_quantity' => $this->faker->numberBetween(1, 10),
            'shipping' => $this->faker->randomFloat(2, 0, 50),
            'user_id' => User::factory(),
        ];
    }
}
