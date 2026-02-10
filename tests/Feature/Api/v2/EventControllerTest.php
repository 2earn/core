<?php

namespace Tests\Feature\Api\v2;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Test Suite for EventController (API v2)
 *
 * @package Tests\Feature\Api\v2
 * @see App\Http\Controllers\Api\v2\EventController
 */
#[Group('api')]
#[Group('api_v2')]
#[Group('events')]
class EventControllerTest extends TestCase
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
    public function it_can_get_paginated_events()
    {
        Event::factory()->count(5)->create();

        $response = $this->getJson('/api/v2/events?per_page=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data',
                'pagination'
            ]);
    }

    #[Test]
    public function it_can_search_events()
    {
        Event::factory()->create(['name' => 'Test Event']);

        $response = $this->getJson('/api/v2/events?search=Test');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_get_all_events()
    {
        Event::factory()->count(3)->create();

        $response = $this->getJson('/api/v2/events/all');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_get_enabled_events()
    {
        Event::factory()->count(2)->create(['enabled' => true]);
        Event::factory()->count(2)->create(['enabled' => false]);

        $response = $this->getJson('/api/v2/events/enabled');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_validates_per_page_parameter()
    {
        $response = $this->getJson('/api/v2/events?per_page=150');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['per_page']);
    }

    #[Test]
    public function it_handles_errors_gracefully()
    {
        // Force an error by passing invalid data that might cause service to fail
        $response = $this->getJson('/api/v2/events?per_page=-1');

        $response->assertStatus(422);
    }
}

