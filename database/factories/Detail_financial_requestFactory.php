<?php

namespace Database\Factories;

use App\Models\detail_financial_request;
use App\Models\FinancialRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class Detail_financial_requestFactory extends Factory
{
    protected $model = detail_financial_request::class;

    public function definition(): array
    {
        $financialRequest = FinancialRequest::factory()->create();

        return [
            'numeroRequest' => $financialRequest->numeroReq,
            'idUser' => User::factory(),
            'response' => null,
            'dateResponse' => null,
            'vu' => 0,
        ];
    }

    public function pending(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'response' => null,
                'dateResponse' => null,
            ];
        });
    }

    public function accepted(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'response' => 1,
                'dateResponse' => now(),
            ];
        });
    }

    public function rejected(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'response' => 2,
                'dateResponse' => now(),
            ];
        });
    }

    public function ignored(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'response' => 3,
                'dateResponse' => now(),
            ];
        });
    }
}
