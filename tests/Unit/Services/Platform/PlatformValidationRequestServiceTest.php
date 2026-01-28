<?php

namespace Tests\Unit\Services\Platform;

use App\Models\Platform;
use App\Models\PlatformValidationRequest;
use App\Models\User;
use App\Services\Platform\PlatformValidationRequestService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PlatformValidationRequestServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected PlatformValidationRequestService $platformValidationRequestService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->platformValidationRequestService = new PlatformValidationRequestService();
    }

    /**
     * Test getPendingRequests returns only pending requests
     */
    public function test_get_pending_requests_returns_only_pending()
    {
        // Arrange
        PlatformValidationRequest::factory()->pending()->count(3)->create();
        PlatformValidationRequest::factory()->approved()->count(2)->create();

        // Act
        $result = $this->platformValidationRequestService->getPendingRequests();

        // Assert
        $this->assertCount(3, $result);
        $this->assertTrue($result->every(fn($r) => $r->status === PlatformValidationRequest::STATUS_PENDING));
    }

    /**
     * Test getPendingRequests respects limit
     */
    public function test_get_pending_requests_respects_limit()
    {
        // Arrange
        PlatformValidationRequest::factory()->pending()->count(5)->create();

        // Act
        $result = $this->platformValidationRequestService->getPendingRequests(3);

        // Assert
        $this->assertCount(3, $result);
    }

    /**
     * Test getTotalPending returns correct count
     */
    public function test_get_total_pending_returns_correct_count()
    {
        // Arrange
        PlatformValidationRequest::factory()->pending()->count(4)->create();
        PlatformValidationRequest::factory()->approved()->count(2)->create();

        // Act
        $result = $this->platformValidationRequestService->getTotalPending();

        // Assert
        $this->assertEquals(4, $result);
    }

    /**
     * Test getTotalPending returns zero when no pending requests
     */
    public function test_get_total_pending_returns_zero_when_none()
    {
        // Arrange
        PlatformValidationRequest::factory()->approved()->count(2)->create();

        // Act
        $result = $this->platformValidationRequestService->getTotalPending();

        // Assert
        $this->assertEquals(0, $result);
    }

    /**
     * Test getPendingRequestsWithTotal returns correct structure
     */
    public function test_get_pending_requests_with_total_returns_correct_structure()
    {
        // Arrange
        PlatformValidationRequest::factory()->pending()->count(3)->create();

        // Act
        $result = $this->platformValidationRequestService->getPendingRequestsWithTotal();

        // Assert
        $this->assertArrayHasKey('pendingRequests', $result);
        $this->assertArrayHasKey('totalPending', $result);
        $this->assertCount(3, $result['pendingRequests']);
        $this->assertEquals(3, $result['totalPending']);
    }

    /**
     * Test createRequest creates new request
     */
    public function test_create_request_creates_new_request()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();

        // Act
        $result = $this->platformValidationRequestService->createRequest($platform->id, $user->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($platform->id, $result->platform_id);
        $this->assertEquals($user->id, $result->requested_by);
        $this->assertEquals(PlatformValidationRequest::STATUS_PENDING, $result->status);
    }

    /**
     * Test findRequest returns correct request
     */
    public function test_find_request_returns_correct_request()
    {
        // Arrange
        $request = PlatformValidationRequest::factory()->create();

        // Act
        $result = $this->platformValidationRequestService->findRequest($request->id);

        // Assert
        $this->assertNotNull($result);
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
        $this->platformValidationRequestService->findRequest(99999);
    }

    /**
     * Test findRequestWithRelations loads relations
     */
    public function test_find_request_with_relations_loads_relations()
    {
        // Arrange
        $request = PlatformValidationRequest::factory()->create();

        // Act
        $result = $this->platformValidationRequestService->findRequestWithRelations($request->id, ['platform', 'requestedBy']);

        // Assert
        $this->assertNotNull($result);
        $this->assertTrue($result->relationLoaded('platform'));
        $this->assertTrue($result->relationLoaded('requestedBy'));
    }

    /**
     * Test approveRequest approves and enables platform
     */
    public function test_approve_request_approves_and_enables_platform()
    {
        // Arrange
        $platform = Platform::factory()->create(['enabled' => false]);
        $request = PlatformValidationRequest::factory()->pending()->create(['platform_id' => $platform->id]);
        $reviewer = User::factory()->create();

        // Act
        $result = $this->platformValidationRequestService->approveRequest($request->id, $reviewer->id);

        // Assert
        $this->assertEquals(PlatformValidationRequest::STATUS_APPROVED, $result->status);
        $this->assertEquals($reviewer->id, $result->reviewed_by);
        $this->assertNotNull($result->reviewed_at);

        $platform->refresh();
        $this->assertTrue($platform->enabled);
    }

    /**
     * Test approveRequest throws exception when already processed
     */
    public function test_approve_request_throws_exception_when_already_processed()
    {
        // Arrange
        $request = PlatformValidationRequest::factory()->approved()->create();
        $reviewer = User::factory()->create();

        // Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('This request has already been processed');

        // Act
        $this->platformValidationRequestService->approveRequest($request->id, $reviewer->id);
    }

    /**
     * Test rejectRequest rejects request
     */
    public function test_reject_request_rejects_request()
    {
        // Arrange
        $request = PlatformValidationRequest::factory()->pending()->create();
        $reviewer = User::factory()->create();
        $rejectionReason = 'Invalid platform';

        // Act
        $result = $this->platformValidationRequestService->rejectRequest($request->id, $reviewer->id, $rejectionReason);

        // Assert
        $this->assertEquals(PlatformValidationRequest::STATUS_REJECTED, $result->status);
        $this->assertEquals($reviewer->id, $result->reviewed_by);
        $this->assertEquals($rejectionReason, $result->rejection_reason);
        $this->assertNotNull($result->reviewed_at);
    }

    /**
     * Test rejectRequest throws exception when already processed
     */
    public function test_reject_request_throws_exception_when_already_processed()
    {
        // Arrange
        $request = PlatformValidationRequest::factory()->approved()->create();
        $reviewer = User::factory()->create();

        // Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('This request has already been processed');

        // Act
        $this->platformValidationRequestService->rejectRequest($request->id, $reviewer->id, 'Reason');
    }

    /**
     * Test cancelRequest cancels pending request
     */
    public function test_cancel_request_cancels_pending_request()
    {
        // Arrange
        $request = PlatformValidationRequest::factory()->pending()->create();
        $user = User::factory()->create();
        $reason = 'User cancelled';

        // Act
        $result = $this->platformValidationRequestService->cancelRequest($request->id, $user->id, $reason);

        // Assert
        $this->assertEquals(PlatformValidationRequest::STATUS_CANCELLED, $result->status);
        $this->assertEquals($user->id, $result->reviewed_by);
        $this->assertEquals($reason, $result->rejection_reason);
        $this->assertNotNull($result->reviewed_at);
    }

    /**
     * Test cancelRequest throws exception when not pending
     */
    public function test_cancel_request_throws_exception_when_not_pending()
    {
        // Arrange
        $request = PlatformValidationRequest::factory()->approved()->create();
        $user = User::factory()->create();

        // Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Only pending requests can be canceled');

        // Act
        $this->platformValidationRequestService->cancelRequest($request->id, $user->id, 'Reason');
    }

    /**
     * Test getFilteredQuery filters by status
     */
    public function test_get_filtered_query_filters_by_status()
    {
        // Arrange
        PlatformValidationRequest::factory()->pending()->count(2)->create();
        PlatformValidationRequest::factory()->approved()->count(3)->create();

        // Act
        $result = $this->platformValidationRequestService->getFilteredQuery(PlatformValidationRequest::STATUS_APPROVED, null)->get();

        // Assert
        $this->assertCount(3, $result);
        $this->assertTrue($result->every(fn($r) => $r->status === PlatformValidationRequest::STATUS_APPROVED));
    }

    /**
     * Test getFilteredQuery filters by search
     */
    public function test_get_filtered_query_filters_by_search()
    {
        // Arrange
        $platform = Platform::factory()->create(['name' => 'SearchablePlatform']);
        PlatformValidationRequest::factory()->create(['platform_id' => $platform->id]);
        PlatformValidationRequest::factory()->count(2)->create();

        // Act
        $result = $this->platformValidationRequestService->getFilteredQuery(null, 'Searchable')->get();

        // Assert
        $this->assertCount(1, $result);
        $this->assertEquals($platform->id, $result->first()->platform_id);
    }

    /**
     * Test getPaginatedRequests returns paginated results
     */
    public function test_get_paginated_requests_returns_paginated_results()
    {
        // Arrange
        PlatformValidationRequest::factory()->count(15)->create();

        // Act
        $result = $this->platformValidationRequestService->getPaginatedRequests(null, null, 10);

        // Assert
        $this->assertCount(10, $result);
        $this->assertEquals(15, $result->total());
    }
}
