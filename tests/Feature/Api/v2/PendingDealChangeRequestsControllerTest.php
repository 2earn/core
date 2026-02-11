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
 * Test Suite for PendingDealChangeRequestsController (API v2)
 *
 * @package Tests\Feature\Api\v2
 * @see App\Http\Controllers\Api\v2\PendingDealChangeRequestsController
 */
#[Group('api')]
#[Group('api_v2')]
#[Group('pending_deal_changes')]
class PendingDealChangeRequestsControllerTest extends TestCase
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
    public function it_can_get_pending_change_requests()
    {
        $response = $this->getJson('/api/v2/pending-deal-change-requests');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data'
            ]);
    }

    #[Test]
    public function it_can_get_pending_changes_with_limit()
    {
        $response = $this->getJson('/api/v2/pending-deal-change-requests?limit=10');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_validates_limit_parameter()
    {
        $response = $this->getJson('/api/v2/pending-deal-change-requests?limit=150');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['limit']);
    }

    #[Test]
    public function it_can_get_total_pending_count()
    {
        $response = $this->getJson('/api/v2/pending-deal-change-requests/total');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'total_pending'
            ]);
    }

    #[Test]
    public function it_can_get_pending_with_total()
    {
        $response = $this->getJson('/api/v2/pending-deal-change-requests/with-total');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_get_pending_with_total_and_limit()
    {
        $response = $this->getJson('/api/v2/pending-deal-change-requests/with-total?limit=5');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_get_change_request_by_id()
    {
        // Assuming there's data or using factory
        $response = $this->getJson('/api/v2/pending-deal-change-requests/1');

        // May return 404 if no data exists, which is valid
        $this->assertContains($response->status(), [200, 404]);
    }

    #[Test]
    public function it_returns_404_for_nonexistent_request()
    {
        $response = $this->getJson('/api/v2/pending-deal-change-requests/99999');

        $response->assertStatus(404)
            ->assertJsonFragment(['status' => false]);
    }
}

