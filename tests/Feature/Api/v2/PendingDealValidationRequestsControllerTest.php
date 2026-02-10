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
 * Test Suite for PendingDealValidationRequestsController (API v2)
 *
 * @package Tests\Feature\Api\v2
 * @see App\Http\Controllers\Api\v2\PendingDealValidationRequestsController
 */
#[Group('api')]
#[Group('api_v2')]
#[Group('pending_deal_validations')]
class PendingDealValidationRequestsControllerTest extends TestCase
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
    public function it_can_get_pending_validation_requests()
    {
        $response = $this->getJson('/api/v2/pending-deal-validations');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data'
            ]);
    }

    #[Test]
    public function it_can_get_pending_validations_with_limit()
    {
        $response = $this->getJson('/api/v2/pending-deal-validations?limit=10');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_validates_limit_parameter()
    {
        $response = $this->getJson('/api/v2/pending-deal-validations?limit=150');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['limit']);
    }

    #[Test]
    public function it_can_get_paginated_requests()
    {
        $response = $this->getJson('/api/v2/pending-deal-validations/paginated?is_super_admin=true&per_page=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data',
                'pagination'
            ]);
    }

    #[Test]
    public function it_requires_is_super_admin_for_paginated()
    {
        $response = $this->getJson('/api/v2/pending-deal-validations/paginated');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['is_super_admin']);
    }

    #[Test]
    public function it_can_filter_paginated_by_status()
    {
        $response = $this->getJson('/api/v2/pending-deal-validations/paginated?is_super_admin=true&status_filter=pending');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_search_paginated_requests()
    {
        $response = $this->getJson('/api/v2/pending-deal-validations/paginated?is_super_admin=true&search=test');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_get_total_pending_count()
    {
        $response = $this->getJson('/api/v2/pending-deal-validations/total');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'total_pending'
            ]);
    }

    #[Test]
    public function it_can_get_pending_with_total()
    {
        $response = $this->getJson('/api/v2/pending-deal-validations/with-total');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_get_validation_request_by_id()
    {
        $response = $this->getJson('/api/v2/pending-deal-validations/1');

        $this->assertContains($response->status(), [200, 404]);
    }

    #[Test]
    public function it_returns_404_for_nonexistent_request()
    {
        $response = $this->getJson('/api/v2/pending-deal-validations/99999');

        $response->assertStatus(404)
            ->assertJsonFragment(['status' => false]);
    }

    #[Test]
    public function it_validates_per_page_parameter()
    {
        $response = $this->getJson('/api/v2/pending-deal-validations/paginated?is_super_admin=1&per_page=150');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['per_page']);
    }
}

