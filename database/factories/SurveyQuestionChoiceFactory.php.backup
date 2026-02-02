<?php

namespace Database\Factories;

use App\Models\SurveyQuestionChoice;
use App\Models\SurveyQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

class SurveyQuestionChoiceFactory extends Factory
{
    protected $model = SurveyQuestionChoice::class;

    public function definition(): array
    {
        return [
            'surveyQuestion_id' => SurveyQuestion::factory(),
            'choice' => $this->faker->words(3, true),
            'order' => $this->faker->numberBetween(1, 5),
        ];
    }
}
