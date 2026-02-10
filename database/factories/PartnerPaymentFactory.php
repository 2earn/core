<?php

namespace Database\Factories;

use App\Models\PartnerPayment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PartnerPaymentFactory extends Factory
{
    protected $model = PartnerPayment::class;

    public function definition(): array
    {
        return [
            'amount' => $this->faker->randomFloat(2, 100, 5000),
            'method' => $this->faker->randomElement(['bank_transfer', 'paypal', 'stripe', 'cash']),
            'payment_date' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'partner_id' => User::factory(),
            'validated_by' => null,
            'validated_at' => null,
            'rejected_by' => null,
            'rejected_at' => null,
            'rejection_reason' => null,
            'created_by' => User::factory(),
            'updated_by' => User::factory(),
        ];
    }

    public function pending(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'validated_by' => null,
                'validated_at' => null,
                'rejected_by' => null,
                'rejected_at' => null,
            ];
        });
    }

    public function validated(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'validated_by' => User::factory(),
                'validated_at' => now(),
            ];
        });
    }

    public function rejected(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'rejected_by' => User::factory(),
                'rejected_at' => now(),
                'rejection_reason' => $this->faker->sentence(),
            ];
        });
    }
}
