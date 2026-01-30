<?php

namespace Database\Factories;

use App\Models\MettaUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MettaUserFactory extends Factory
{
    protected $model = MettaUser::class;

    public function definition(): array
    {
        return [
            'idUser' => User::factory(),
            'arFirstName' => $this->faker->firstName(),
            'arLastName' => $this->faker->lastName(),
            'enFirstName' => $this->faker->firstName(),
            'enLastName' => $this->faker->lastName(),
            'personaltitle' => $this->faker->optional()->title(),
            'idCountry' => $this->faker->numberBetween(1, 200),
            'childrenCount' => $this->faker->numberBetween(0, 5),
            'birthday' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'email' => $this->faker->unique()->safeEmail(),
            'secondEmail' => $this->faker->optional()->safeEmail(),
            'idLanguage' => $this->faker->randomElement(['en', 'ar', 'fr']),
            'nationalID' => $this->faker->optional()->numerify('##########'),
            'internationalISD' => $this->faker->optional()->numerify('##########'),
            'adresse' => $this->faker->optional()->address(),
            'idState' => $this->faker->optional()->numberBetween(1, 50),
            'note' => $this->faker->optional()->sentence(),
            'interests' => $this->faker->words(3, true),
        ];
    }
}
