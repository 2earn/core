<?php

namespace Database\Factories;

use App\Enums\StatusRequest;
use App\Models\identificationuserrequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class IdentificationUserRequestFactory extends Factory
{
    protected $model = identificationuserrequest::class;

    public function definition(): array
    {
        return [
            'idUser' => User::factory(),
            'status' => StatusRequest::InProgressNational->value,
            'response' => null,
            'note' => null,
            'responseDate' => null,
            'idUserResponse' => null,
        ];
    }

    /**
     * In progress national status
     */
    public function inProgressNational(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => StatusRequest::InProgressNational->value,
            ];
        });
    }

    /**
     * In progress international status
     */
    public function inProgressInternational(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => StatusRequest::InProgressInternational->value,
            ];
        });
    }

    /**
     * In progress global status
     */
    public function inProgressGlobal(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => StatusRequest::InProgressGlobal->value,
            ];
        });
    }

    /**
     * Validated status
     */
    public function validated(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => StatusRequest::OptValidated->value,
                'response' => 1,
                'responseDate' => now(),
                'idUserResponse' => User::factory(),
            ];
        });
    }

    /**
     * Rejected status
     */
    public function rejected(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => StatusRequest::OptValidated->value,
                'response' => 1,
                'note' => $this->faker->sentence(),
                'responseDate' => now(),
                'idUserResponse' => User::factory(),
            ];
        });
    }
}
