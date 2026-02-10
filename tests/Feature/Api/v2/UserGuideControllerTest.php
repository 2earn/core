<?php

namespace Tests\Feature\Api\v2;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Test Suite for UserGuideController (API v2)
 *
 * @package Tests\Feature\Api\v2
 * @see App\Http\Controllers\Api\v2\UserGuideController
 */
#[Group('api')]
#[Group('api_v2')]
#[Group('user_guides')]
class UserGuideControllerTest extends TestCase
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
    public function it_can_get_paginated_user_guides()
    {
        $response = $this->getJson('/api/v2/user-guides?per_page=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data'
            ]);
    }

    #[Test]
    public function it_can_search_user_guides()
    {
        $response = $this->getJson('/api/v2/user-guides?search=test');

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_can_get_all_user_guides()
    {
        $response = $this->getJson('/api/v2/user-guides/all');

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_can_get_user_guide_by_id()
    {
        // Assuming there's at least one guide in DB or using factory
        $response = $this->getJson('/api/v2/user-guides/1');

        $response->assertStatus(200);
    }

    #[Test]
    public function it_returns_404_for_nonexistent_guide()
    {
        $response = $this->getJson('/api/v2/user-guides/99999');

        $response->assertStatus(404)
            ->assertJsonFragment(['success' => false]);
    }

    #[Test]
    public function it_can_search_guides_with_keyword()
    {
        $response = $this->postJson('/api/v2/user-guides/search', [
            'search' => 'test'
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_validates_search_request()
    {
        $response = $this->postJson('/api/v2/user-guides/search', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['search']);
    }

    #[Test]
    public function it_can_get_guides_by_route()
    {
        $response = $this->postJson('/api/v2/user-guides/by-route', [
            'route_name' => 'dashboard'
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_validates_route_name_request()
    {
        $response = $this->postJson('/api/v2/user-guides/by-route', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['route_name']);
    }

    #[Test]
    public function it_can_create_user_guide()
    {
        $data = [
            'title' => 'Test Guide',
            'content' => 'Test content',
            'route_name' => 'test.route'
        ];

        $response = $this->postJson('/api/v2/user-guides', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_validates_user_guide_creation()
    {
        $response = $this->postJson('/api/v2/user-guides', []);

        $response->assertStatus(422);
    }

    #[Test]
    public function it_can_update_user_guide()
    {
        $data = [
            'title' => 'Updated Guide',
            'content' => 'Updated content'
        ];

        $response = $this->putJson('/api/v2/user-guides/1', $data);

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_delete_user_guide()
    {
        $response = $this->deleteJson('/api/v2/user-guides/1');

        $response->assertStatus(200);
    }
}

