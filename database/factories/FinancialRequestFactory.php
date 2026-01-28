<?php

namespace Database\Factories;

use App\Models\FinancialRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FinancialRequestFactory extends Factory
{
    protected $model = FinancialRequest::class;

    public function definition(): array
    {
        $date = $this->faker->dateTimeBetween('-30 days', 'now');
        $year = $date->format('y');
        $randomNumber = $this->faker->numberBetween(1, 999999);
        $numeroReq = $year . str_pad($randomNumber, 6, '0', STR_PAD_LEFT);

        return [
            'numeroReq' => $numeroReq,
            'idSender' => User::factory(),
            'date' => $date,
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'status' => $this->faker->randomElement([0, 1, 3, 5]), // 0=open, 1=accepted, 3=canceled, 5=refused
            'securityCode' => strtoupper($this->faker->bothify('??####')),
            'vu' => $this->faker->boolean(30),
        ];
    }

    public function pending(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 0,
            ];
        });
    }

    public function accepted(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 1,
                'idUserAccepted' => User::factory(),
                'dateAccepted' => now(),
            ];
        });
    }

    public function canceled(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 3,
            ];
        });
    }

    public function refused(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 5,
            ];
        });
    }
}
