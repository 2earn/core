<?php

namespace Tests\Feature\Api\v2;

use App\Models\Communication;
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
        $survey = Communication::factory()->create([
            'type' => 'survey',
            'title' => 'Original Survey'
        ]);

        $response = $this->postJson("/api/v2/communication/surveys/{$survey->id}/duplicate");

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data'
            ]);
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
        $news = Communication::factory()->create([
            'type' => 'news',
            'title' => 'Original News'
        ]);

        $response = $this->postJson("/api/v2/communication/news/{$news->id}/duplicate");

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data'
            ]);
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
        $event = Communication::factory()->create([
            'type' => 'event',
            'title' => 'Original Event'
        ]);

        $response = $this->postJson("/api/v2/communication/events/{$event->id}/duplicate");

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data'
            ]);
    }

    #[Test]
    public function it_returns_404_when_duplicating_nonexistent_event()
    {
        $response = $this->postJson('/api/v2/communication/events/999999/duplicate');

        $response->assertStatus(404)
            ->assertJsonFragment(['status' => false]);
    }
}

