<?php

namespace Database\Factories;

use App\Models\Deal;
use App\Models\DealProductChange;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DealProductChangeFactory extends Factory
{
    protected $model = DealProductChange::class;

    public function definition(): array
    {
        return [
            'deal_id' => Deal::factory(),
            'item_id' => Item::factory(),
            'action' => $this->faker->randomElement(['added', 'removed']),
            'changed_by' => User::factory(),
            'note' => $this->faker->optional()->sentence(),
        ];
    }

    public function added(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'action' => 'added',
            ];
        });
    }

    public function removed(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'action' => 'removed',
            ];
        });
    }
}
