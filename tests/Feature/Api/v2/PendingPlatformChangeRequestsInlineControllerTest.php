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
 * Test Suite for PendingPlatformChangeRequestsInlineController (API v2)
 *
 * @package Tests\Feature\Api\v2
 * @see App\Http\Controllers\Api\v2\PendingPlatformChangeRequestsInlineController
 */
#[Group('api')]
#[Group('api_v2')]
#[Group('pending_platform_changes')]
class PendingPlatformChangeRequestsInlineControllerTest extends TestCase
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
    public function it_can_get_pending_platform_change_requests()
    {
        $response = $this->getJson('/api/v2/pending-platform-change-requests-inline');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data'
            ]);
    }

    #[Test]
    public function it_can_get_pending_changes_with_limit()
    {
        $response = $this->getJson('/api/v2/pending-platform-change-requests-inline?limit=10');

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_can_get_total_pending_count()
    {
        $response = $this->getJson('/api/v2/pending-platform-change-requests-inline/count');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'count'
            ]);
    }

    #[Test]
    public function it_can_get_pending_with_total()
    {
        $response = $this->getJson('/api/v2/pending-platform-change-requests-inline/with-total');

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_can_get_pending_with_total_and_limit()
    {
        $response = $this->getJson('/api/v2/pending-platform-change-requests-inline/with-total?limit=5');

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_handles_errors_gracefully()
    {
        // The service should handle edge cases
        $response = $this->getJson('/api/v2/pending-platform-changes-inline?limit=150');

        $response->assertStatus(200);
    }
}

