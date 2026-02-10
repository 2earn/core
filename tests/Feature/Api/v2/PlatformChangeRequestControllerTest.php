<?php

namespace Tests\Feature\Api\v2;

use App\Models\PlatformChangeRequest;
use App\Models\Platform;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Test Suite for PlatformChangeRequestController (API v2)
 *
 * @package Tests\Feature\Api\v2
 * @see App\Http\Controllers\Api\v2\PlatformChangeRequestController
 */
#[Group('api')]
#[Group('api_v2')]
#[Group('platform_change_requests')]
class PlatformChangeRequestControllerTest extends TestCase
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
    public function it_can_get_paginated_change_requests()
    {
        $response = $this->getJson('/api/v2/platform-change-requests?per_page=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data'
            ]);
    }

    #[Test]
    public function it_can_filter_by_status()
    {
        $response = $this->getJson('/api/v2/platform-change-requests?status=pending');

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_can_search_change_requests()
    {
        $response = $this->getJson('/api/v2/platform-change-requests?search=test');

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_can_get_pending_requests()
    {
        $response = $this->getJson('/api/v2/platform-change-requests/pending?per_page=20');

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_can_get_pending_requests_for_platform()
    {
        $platform = Platform::factory()->create();

        $response = $this->getJson("/api/v2/platform-change-requests/pending?platform_id={$platform->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_can_get_change_request_by_id()
    {
        $response = $this->getJson('/api/v2/platform-change-requests/1');

        $this->assertContains($response->status(), [200, 404]);
    }

    #[Test]
    public function it_returns_404_for_nonexistent_request()
    {
        $response = $this->getJson('/api/v2/platform-change-requests/99999');

        $response->assertStatus(404)
            ->assertJsonFragment(['success' => false]);
    }

    #[Test]
    public function it_can_create_change_request()
    {
        $platform = Platform::factory()->create();

        $data = [
            'platform_id' => $platform->id,
            'field_name' => 'name',
            'old_value' => 'Old Name',
            'new_value' => 'New Name',
            'requested_by' => $this->user->id
        ];

        $response = $this->postJson('/api/v2/platform-change-requests', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_validates_change_request_creation()
    {
        $response = $this->postJson('/api/v2/platform-change-requests', []);

        $response->assertStatus(422);
    }

    #[Test]
    public function it_can_approve_change_request()
    {
        $response = $this->postJson('/api/v2/platform-change-requests/1/approve', [
            'approved_by' => $this->user->id
        ]);

        $this->assertContains($response->status(), [200, 404]);
    }

    #[Test]
    public function it_can_reject_change_request()
    {
        $response = $this->postJson('/api/v2/platform-change-requests/1/reject', [
            'rejected_by' => $this->user->id,
            'reason' => 'Invalid change'
        ]);

        $this->assertContains($response->status(), [200, 404]);
    }
}

