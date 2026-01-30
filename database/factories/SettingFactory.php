<?php

namespace Database\Factories;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SettingFactory extends Factory
{
    protected $model = Setting::class;

    public function definition(): array
    {
        return [
            'ParameterName' => $this->faker->unique()->word(),
            'IntegerValue' => $this->faker->numberBetween(0, 1000),
            'StringValue' => $this->faker->sentence(),
            'DecimalValue' => $this->faker->randomFloat(2, 0, 1000),
            'Unit' => $this->faker->word(),
            'Automatically_calculated' => $this->faker->boolean(),
            'Description' => $this->faker->text(100),
            'created_by' => null,
            'updated_by' => null,
        ];
    }

    /**
     * Set a specific parameter name
     */
    public function withParameterName(string $name): self
    {
        return $this->state(function (array $attributes) use ($name) {
            return [
                'ParameterName' => $name,
            ];
        });
    }

    /**
     * Set a specific integer value
     */
    public function withIntegerValue(int $value): self
    {
        return $this->state(function (array $attributes) use ($value) {
            return [
                'IntegerValue' => $value,
            ];
        });
    }

    /**
     * Set a specific decimal value
     */
    public function withDecimalValue(float $value): self
    {
        return $this->state(function (array $attributes) use ($value) {
            return [
                'DecimalValue' => $value,
            ];
        });
    }

    /**
     * Set a specific string value
     */
    public function withStringValue(string $value): self
    {
        return $this->state(function (array $attributes) use ($value) {
            return [
                'StringValue' => $value,
            ];
        });
    }
}
