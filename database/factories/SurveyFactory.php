<?php
namespace Database\Factories;
use App\Enums\StatusSurvey;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
class SurveyFactory extends Factory
{
    protected $model = Survey::class;
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'enabled' => $this->faker->boolean(70),
            'published' => $this->faker->boolean(70),
            'status' => $this->faker->randomElement([StatusSurvey::NEW->value, StatusSurvey::OPEN->value, StatusSurvey::CLOSED->value]),
            'show' => 1,
            'showAttchivementChrono' => 1,
            'showAttchivementGool' => 1,
            'showAfterArchiving' => 1,
            'updatable' => $this->faker->boolean(50),
            'showResult' => 1,
            'commentable' => 1,
            'likable' => 1,
            'show_results_as_number' => $this->faker->boolean(50),
            'show_results_as_percentage' => $this->faker->boolean(50),
            'created_by' => User::factory(),
        ];
    }
    public function active(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'enabled' => true,
            ];
        });
    }
    public function inactive(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'enabled' => false,
            ];
        });
    }
}
