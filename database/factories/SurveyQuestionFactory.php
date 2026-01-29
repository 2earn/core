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
            'content' => $this->faker->sentence() . '?',
            'selection' => $this->faker->randomElement(['single', 'multiple']),
            'maxResponse' => $this->faker->numberBetween(1, 5),
        ];
    }
}
