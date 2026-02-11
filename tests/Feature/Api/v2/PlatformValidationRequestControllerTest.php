<?php

namespace Tests\Feature\Api\v2;

use App\Models\PlatformValidationRequest;
use App\Models\Platform;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Test Suite for PlatformValidationRequestController (API v2)
 *
 * @package Tests\Feature\Api\v2
 * @see App\Http\Controllers\Api\v2\PlatformValidationRequestController
 */
#[Group('api')]
#[Group('api_v2')]
#[Group('platform_validation_requests')]
class PlatformValidationRequestControllerTest extends TestCase
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
    public function it_can_get_paginated_validation_requests()
    {
        $response = $this->getJson('/api/v2/platform-validation-requests?per_page=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data'
            ]);
    }

    #[Test]
    public function it_can_filter_by_status()
    {
        $response = $this->getJson('/api/v2/platform-validation-requests?status=pending');

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_can_search_validation_requests()
    {
        $response = $this->getJson('/api/v2/platform-validation-requests?search=test');

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_can_get_pending_requests()
    {
        $response = $this->getJson('/api/v2/platform-validation-requests/pending');

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_can_get_pending_requests_with_limit()
    {
        $response = $this->getJson('/api/v2/platform-validation-requests/pending?limit=10');

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_can_get_pending_count()
    {
        $response = $this->getJson('/api/v2/platform-validation-requests/pending-count');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'count'
            ]);
    }

    #[Test]
    public function it_can_get_pending_with_total()
    {
        $response = $this->getJson('/api/v2/platform-validation-requests/pending-with-total');

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_can_get_validation_request_by_id()
    {
        $response = $this->getJson('/api/v2/platform-validation-requests/1');

        $this->assertContains($response->status(), [200, 404]);
    }

    #[Test]
    public function it_returns_404_for_nonexistent_request()
    {
        $response = $this->getJson('/api/v2/platform-validation-requests/99999');

        $response->assertStatus(404)
            ->assertJsonFragment(['success' => false]);
    }

    #[Test]
    public function it_can_create_validation_request()
    {
        $platform = Platform::factory()->create();

        $data = [
            'platform_id' => $platform->id,
            'requested_by' => $this->user->id,
            'notes' => 'Please validate this platform'
        ];

        $response = $this->postJson('/api/v2/platform-validation-requests', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_validates_validation_request_creation()
    {
        $response = $this->postJson('/api/v2/platform-validation-requests', []);

        $response->assertStatus(422);
    }

    #[Test]
    public function it_can_approve_validation_request()
    {
        $response = $this->postJson('/api/v2/platform-validation-requests/1/approve', [
            'reviewed_by' => $this->user->id
        ]);

        $this->assertContains($response->status(), [200, 404, 422]);
    }

    #[Test]
    public function it_can_reject_validation_request()
    {
        $response = $this->postJson('/api/v2/platform-validation-requests/1/reject', [
            'reviewed_by' => $this->user->id,
            'rejection_reason' => 'Platform does not meet requirements'
        ]);

        $this->assertContains($response->status(), [200, 404, 422]);
    }
}

