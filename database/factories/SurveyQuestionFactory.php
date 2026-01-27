<?php

namespace Database\Factories;

use App\Models\SurveyQuestion;
use App\Models\Survey;
use Illuminate\Database\Eloquent\Factories\Factory;

class SurveyQuestionFactory extends Factory
{
    protected $model = SurveyQuestion::class;

    public function definition(): array
    {
        return [
            'survey_id' => Survey::factory(),
            'question' => $this->faker->sentence() . '?',
            'type' => $this->faker->randomElement(['single', 'multiple', 'text']),
            'order' => $this->faker->numberBetween(1, 10),
        ];
    }
}
