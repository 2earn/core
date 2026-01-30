<?php

namespace Database\Factories;

use App\Models\Condition;
use App\Models\Group;
use App\Models\Target;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConditionFactory extends Factory
{
    protected $model = Condition::class;

    public function definition(): array
    {
        return [
            'operand' => $this->faker->randomElement(Condition::operands()),
            'operator' => $this->faker->randomElement(Condition::$operators)['value'],
            'value' => $this->faker->word(),
            'group_id' => null,
            'target_id' => Target::factory(),
        ];
    }
}
