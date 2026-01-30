<?php
namespace Database\Factories;
use App\Models\action_historys;
use Illuminate\Database\Eloquent\Factories\Factory;
class Action_historysFactory extends Factory
{
    protected $model = action_historys::class;
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'list_reponce' => $this->faker->optional()->text(),
            'reponce' => $this->faker->numberBetween(0, 1),
        ];
    }
}