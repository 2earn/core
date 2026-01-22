<?php

namespace Database\Factories;

use App\Models\Partner;
use Illuminate\Database\Eloquent\Factories\Factory;

class PartnerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Partner::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            // Match the partners table columns
            'company_name' => $this->faker->company(),
            'business_sector_id' => null,
            'platform_url' => $this->faker->url(),
            'platform_description' => $this->faker->paragraph(),
            'partnership_reason' => $this->faker->sentence(),
            'created_by' => null,
            'updated_by' => null,
            // timestamps will be handled by Eloquent when creating the model
        ];
    }
}
