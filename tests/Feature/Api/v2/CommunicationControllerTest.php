<?php

namespace Tests\Feature\Api\v2;

use App\Models\Survey;
use App\Models\News;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Test Suite for CommunicationController (API v2)
 *
 * @package Tests\Feature\Api\v2
 * @see App\Http\Controllers\Api\v2\CommunicationController
 */
#[Group('api')]
#[Group('api_v2')]
#[Group('communication')]
class CommunicationControllerTest extends TestCase
{
    use WithFaker, DatabaseTransactions;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    #[Test]
    public function it_can_duplicate_survey()
    {
        // Create a complete survey with all required relationships
        $survey = Survey::factory()->create([
            'name' => 'Original Survey',
            'description' => 'Original Description'
        ]);

        // Create a target and attach it to the survey
        $target = \App\Models\Target::factory()->create();
        $survey->targets()->attach($target->id);

        // Create a question for the survey
        $question = \App\Models\SurveyQuestion::factory()->create([
            'survey_id' => $survey->id,
            'content' => 'Test Question'
        ]);

        // Create question choices
        \App\Models\SurveyQuestionChoice::factory()->count(3)->create([
            'question_id' => $question->id
        ]);

        $response = $this->postJson("/api/v2/communication/surveys/{$survey->id}/duplicate");

        $response->assertStatus(201)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_returns_404_when_duplicating_nonexistent_survey()
    {
        $response = $this->postJson('/api/v2/communication/surveys/999999/duplicate');

        $response->assertStatus(404)
            ->assertJsonFragment(['status' => false]);
    }

    #[Test]
    public function it_can_duplicate_news()
    {
        $news = News::factory()->create([
            'title' => 'Original News'
        ]);

        $response = $this->postJson("/api/v2/communication/news/{$news->id}/duplicate");

        $response->assertStatus(201)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_returns_404_when_duplicating_nonexistent_news()
    {
        $response = $this->postJson('/api/v2/communication/news/999999/duplicate');

        $response->assertStatus(404)
            ->assertJsonFragment(['status' => false]);
    }

    #[Test]
    public function it_can_duplicate_event()
    {
        $event = Event::factory()->create([
            'title' => 'Original Event'
        ]);

        $response = $this->postJson("/api/v2/communication/events/{$event->id}/duplicate");

        $response->assertStatus(201)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_returns_404_when_duplicating_nonexistent_event()
    {
        $response = $this->postJson('/api/v2/communication/events/999999/duplicate');

        $response->assertStatus(404)
            ->assertJsonFragment(['status' => false]);
    }
}

