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
            'question_id' => SurveyQuestion::factory(),
            'title' => $this->faker->words(3, true),
        ];
    }
}
