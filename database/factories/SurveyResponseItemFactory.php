<?php

namespace Database\Factories;

use App\Models\SurveyResponseItem;
use App\Models\SurveyResponse;
use App\Models\SurveyQuestion;
use App\Models\SurveyQuestionChoice;
use Illuminate\Database\Eloquent\Factories\Factory;

class SurveyResponseItemFactory extends Factory
{
    protected $model = SurveyResponseItem::class;

    public function definition(): array
    {
        return [
            'surveyResponse_id' => SurveyResponse::factory(),
            'surveyQuestion_id' => SurveyQuestion::factory(),
            'surveyQuestionChoice_id' => SurveyQuestionChoice::factory(),
        ];
    }
}
