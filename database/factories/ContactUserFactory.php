<?php

namespace Database\Factories;

use App\Models\ContactUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactUserFactory extends Factory
{
    protected $model = ContactUser::class;

    public function definition(): array
    {
        return [
            'idUser' => User::factory(),
            'idContact' => User::factory(),
            'name' => $this->faker->firstName(),
            'lastName' => $this->faker->lastName(),
            'mobile' => $this->faker->numerify('##########'),
            'phonecode' => $this->faker->randomElement(['+1', '+44', '+33', '+49']),
            'fullphone_number' => $this->faker->e164PhoneNumber(),
            'availablity' => $this->faker->boolean(80),
            'disponible' => $this->faker->boolean(80),
        ];
    }
}
