<?php

namespace Database\Factories;

use App\Models\UserNotificationSettings;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserNotificationSettingsFactory extends Factory
{
    protected $model = UserNotificationSettings::class;

    public function definition(): array
    {
        return [
            'idUser' => User::factory(),
            'idNotification' => $this->faker->numberBetween(1, 20),
            'value' => $this->faker->randomElement([0, 1, true, false]),
        ];
    }

    public function enabled(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'value' => 1,
            ];
        });
    }

    public function disabled(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'value' => 0,
            ];
        });
    }
}
