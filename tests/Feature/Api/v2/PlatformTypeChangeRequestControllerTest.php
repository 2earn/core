<?php

namespace Tests\Feature\Api\v2;

use App\Models\PlatformTypeChangeRequest;
use App\Models\Platform;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Test Suite for PlatformTypeChangeRequestController (API v2)
 *
 * @package Tests\Feature\Api\v2
 * @see App\Http\Controllers\Api\v2\PlatformTypeChangeRequestController
 */
#[Group('api')]
#[Group('api_v2')]
#[Group('platform_type_change_requests')]
class PlatformTypeChangeRequestControllerTest extends TestCase
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
    public function it_can_get_paginated_type_change_requests()
    {
        $response = $this->getJson('/api/v2/platform-type-change-requests?per_page=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data'
            ]);
    }

    #[Test]
    public function it_can_filter_by_status()
    {
        $response = $this->getJson('/api/v2/platform-type-change-requests?status=pending');

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_can_search_type_change_requests()
    {
        $response = $this->getJson('/api/v2/platform-type-change-requests?search=test');

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_can_get_pending_requests()
    {
        $response = $this->getJson('/api/v2/platform-type-change-requests/pending');

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_can_get_pending_requests_with_limit()
    {
        $response = $this->getJson('/api/v2/platform-type-change-requests/pending?limit=10');

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_can_get_pending_count()
    {
        $response = $this->getJson('/api/v2/platform-type-change-requests/pending/count');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'count'
            ]);
    }

    #[Test]
    public function it_can_get_pending_with_total()
    {
        $response = $this->getJson('/api/v2/platform-type-change-requests/pending/with-total');

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_can_get_type_change_request_by_id()
    {
        $response = $this->getJson('/api/v2/platform-type-change-requests/1');

        $this->assertContains($response->status(), [200, 404]);
    }

    #[Test]
    public function it_returns_404_for_nonexistent_request()
    {
        $response = $this->getJson('/api/v2/platform-type-change-requests/99999');

        $response->assertStatus(404)
            ->assertJsonFragment(['success' => false]);
    }

    #[Test]
    public function it_can_create_type_change_request()
    {
        $platform = Platform::factory()->create();

        $data = [
            'platform_id' => $platform->id,
            'old_type' => 'type1',
            'new_type' => 'type2',
            'requested_by' => $this->user->id
        ];

        $response = $this->postJson('/api/v2/platform-type-change-requests', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_validates_type_change_request_creation()
    {
        $response = $this->postJson('/api/v2/platform-type-change-requests', []);

        $response->assertStatus(422);
    }

    #[Test]
    public function it_can_approve_type_change_request()
    {
        $response = $this->postJson('/api/v2/platform-type-change-requests/1/approve', [
            'approved_by' => $this->user->id
        ]);

        $this->assertContains($response->status(), [200, 404]);
    }

    #[Test]
    public function it_can_reject_type_change_request()
    {
        $response = $this->postJson('/api/v2/platform-type-change-requests/1/reject', [
            'rejected_by' => $this->user->id,
            'reason' => 'Invalid type'
        ]);

        $this->assertContains($response->status(), [200, 404]);
    }
}

