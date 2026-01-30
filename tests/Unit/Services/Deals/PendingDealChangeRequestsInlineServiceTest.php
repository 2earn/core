<?php

namespace Tests\Unit\Services\Deals;

use App\Models\DealChangeRequest;
use App\Services\Deals\PendingDealChangeRequestsInlineService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PendingDealChangeRequestsInlineServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected PendingDealChangeRequestsInlineService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PendingDealChangeRequestsInlineService();
    }

    /**
     * Test getPendingRequests returns pending requests
     */
    public function test_get_pending_requests_returns_pending_requests()
    {
        // Arrange
        DealChangeRequest::factory()->pending()->count(3)->create();
        DealChangeRequest::factory()->approved()->count(2)->create();

        // Act
        $result = $this->service->getPendingRequests();

        // Assert
        $this->assertCount(3, $result);
        foreach ($result as $request) {
            $this->assertEquals(DealChangeRequest::STATUS_PENDING, $request->status);
        }
    }

    /**
     * Test getPendingRequests with limit
     */
    public function test_get_pending_requests_respects_limit()
    {
        // Arrange
        DealChangeRequest::factory()->pending()->count(10)->create();

        // Act
        $result = $this->service->getPendingRequests(5);

        // Assert
        $this->assertCount(5, $result);
    }

    /**
     * Test getPendingRequests loads relationships
     */
    public function test_get_pending_requests_loads_relationships()
    {
        // Arrange
        DealChangeRequest::factory()->pending()->create();

        // Act
        $result = $this->service->getPendingRequests();

        // Assert
        $this->assertTrue($result->first()->relationLoaded('deal'));
        $this->assertTrue($result->first()->relationLoaded('requestedBy'));
    }

    /**
     * Test getPendingRequests orders by created_at DESC
     */
    public function test_get_pending_requests_orders_by_created_at_desc()
    {
        // Arrange
        $request1 = DealChangeRequest::factory()->pending()->create(['created_at' => now()->subDays(2)]);
        $request2 = DealChangeRequest::factory()->pending()->create(['created_at' => now()]);

        // Act
        $result = $this->service->getPendingRequests();

        // Assert
        $this->assertEquals($request2->id, $result->first()->id);
    }

    /**
     * Test getTotalPending returns correct count
     */
    public function test_get_total_pending_returns_correct_count()
    {
        // Arrange
        DealChangeRequest::factory()->pending()->count(5)->create();
        DealChangeRequest::factory()->approved()->count(3)->create();

        // Act
        $result = $this->service->getTotalPending();

        // Assert
        $this->assertEquals(5, $result);
    }

    /**
     * Test getTotalPending returns zero when no pending
     */
    public function test_get_total_pending_returns_zero_when_no_pending()
    {
        // Arrange
        DealChangeRequest::factory()->approved()->count(2)->create();

        // Act
        $result = $this->service->getTotalPending();

        // Assert
        $this->assertEquals(0, $result);
    }

    /**
     * Test getPendingRequestsWithTotal returns array with both values
     */
    public function test_get_pending_requests_with_total_returns_array()
    {
        // Arrange
        DealChangeRequest::factory()->pending()->count(5)->create();

        // Act
        $result = $this->service->getPendingRequestsWithTotal();

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('pendingRequests', $result);
        $this->assertArrayHasKey('totalPending', $result);
        $this->assertCount(5, $result['pendingRequests']);
        $this->assertEquals(5, $result['totalPending']);
    }

    /**
     * Test getPendingRequestsWithTotal with limit
     */
    public function test_get_pending_requests_with_total_respects_limit()
    {
        // Arrange
        $initialCount = DealChangeRequest::where('status', DealChangeRequest::STATUS_PENDING)->count();
        DealChangeRequest::factory()->pending()->count(10)->create();

        // Act
        $result = $this->service->getPendingRequestsWithTotal(3);

        // Assert
        $this->assertCount(3, $result['pendingRequests']);
        $this->assertGreaterThanOrEqual($initialCount + 10, $result['totalPending']);
    }

    /**
     * Test findRequest returns request
     */
    public function test_find_request_returns_request()
    {
        // Arrange
        $request = DealChangeRequest::factory()->create();

        // Act
        $result = $this->service->findRequest($request->id);

        // Assert
        $this->assertEquals($request->id, $result->id);
    }

    /**
     * Test findRequest throws exception when not found
     */
    public function test_find_request_throws_exception_when_not_found()
    {
        // Assert
        $this->expectException(ModelNotFoundException::class);

        // Act
        $this->service->findRequest(99999);
    }

    /**
     * Test findRequestWithRelations loads relationships
     */
    public function test_find_request_with_relations_loads_relationships()
    {
        // Arrange
        $request = DealChangeRequest::factory()->create();

        // Act
        $result = $this->service->findRequestWithRelations($request->id, ['deal', 'requestedBy']);

        // Assert
        $this->assertTrue($result->relationLoaded('deal'));
        $this->assertTrue($result->relationLoaded('requestedBy'));
    }
}
