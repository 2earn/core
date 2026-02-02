<?php

namespace Database\Factories;

use App\Models\countrie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\countrie>
 */
class countrieFactory extends Factory
{
    protected $model = countrie::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'apha2' => strtoupper($this->faker->unique()->lexify('??')),
            'name' => $this->faker->country(),
            'continant' => $this->faker->randomElement(['Africa', 'Asia', 'Europe', 'North America', 'South America', 'Oceania', 'Antarctica']),
            'phonecode' => $this->faker->unique()->numerify('###'),
            'langage' => $this->faker->randomElement(['en', 'fr', 'ar', 'es', 'de']),
            'ExchangeRate' => $this->faker->randomFloat(2, 0.5, 10),
            'local_currency' => strtoupper($this->faker->currencyCode()),
            'lang' => $this->faker->randomElement(['en', 'fr', 'ar', 'es', 'de']),
            'created_by' => null,
            'updated_by' => null,
        ];
    }

    /**
     * Indicate that the country has a specific phone code.
     */
    public function withPhoneCode(string $phoneCode): static
    {
        return $this->state(fn (array $attributes) => [
            'phonecode' => $phoneCode,
        ]);
    }

    /**
     * Indicate that the country is in a specific continent.
     */
    public function inContinent(string $continent): static
    {
        return $this->state(fn (array $attributes) => [
            'continant' => $continent,
        ]);
    }
}
