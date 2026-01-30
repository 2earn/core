<?php

namespace Database\Factories;

use App\Models\UserContactNumber;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserContactNumberFactory extends Factory
{
    protected $model = UserContactNumber::class;

    public function definition(): array
    {
        $mobile = $this->faker->numerify('##########');
        $countryCode = $this->faker->randomElement(['+1', '+44', '+33', '+49', '+966']);

        return [
            'idUser' => User::factory(),
            'mobile' => $mobile,
            'codeP' => $this->faker->numberBetween(1, 200),
            'active' => $this->faker->boolean(30), // 30% chance of being active
            'isoP' => $this->faker->randomElement(['US', 'GB', 'FR', 'DE', 'SA']),
            'fullNumber' => $countryCode . $mobile,
            'isID' => $this->faker->boolean(10), // 10% chance of being ID number
        ];
    }

    public function active(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'active' => 1,
            ];
        });
    }

    public function inactive(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'active' => 0,
            ];
        });
    }

    public function isIdentification(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'isID' => 1,
                'active' => 1,
            ];
        });
    }
}
