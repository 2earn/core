<?php

namespace Tests\Unit\Services\Platform;

use App\Models\Platform;
use App\Models\PlatformTypeChangeRequest;
use App\Models\User;
use App\Services\Platform\PlatformTypeChangeRequestService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PlatformTypeChangeRequestServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected PlatformTypeChangeRequestService $platformTypeChangeRequestService;

    protected function setUp(): void
    {
        parent::setUp();
        // Ensure clean state for platform type change requests to avoid interference
        PlatformTypeChangeRequest::query()->delete();
        $this->platformTypeChangeRequestService = new PlatformTypeChangeRequestService();
    }

    /**
     * Test getPendingRequests returns only pending requests
     */
    public function test_get_pending_requests_returns_only_pending()
    {
        // Arrange
        PlatformTypeChangeRequest::factory()->pending()->count(3)->create();
        PlatformTypeChangeRequest::factory()->approved()->count(2)->create();

        // Act
        $result = $this->platformTypeChangeRequestService->getPendingRequests();

        // Assert
        $this->assertCount(3, $result);
        $this->assertTrue($result->every(fn($r) => $r->status === PlatformTypeChangeRequest::STATUS_PENDING));
    }

    /**
     * Test getPendingRequests respects limit
     */
    public function test_get_pending_requests_respects_limit()
    {
        // Arrange
        PlatformTypeChangeRequest::factory()->pending()->count(5)->create();

        // Act
        $result = $this->platformTypeChangeRequestService->getPendingRequests(3);

        // Assert
        $this->assertCount(3, $result);
    }

    /**
     * Test getTotalPending returns correct count
     */
    public function test_get_total_pending_returns_correct_count()
    {
        // Arrange
        PlatformTypeChangeRequest::factory()->pending()->count(4)->create();
        PlatformTypeChangeRequest::factory()->approved()->count(2)->create();

        // Act
        $result = $this->platformTypeChangeRequestService->getTotalPending();

        // Assert
        $this->assertEquals(4, $result);
    }

    /**
     * Test getTotalPending returns zero when no pending requests
     */
    public function test_get_total_pending_returns_zero_when_none()
    {
        // Arrange
        PlatformTypeChangeRequest::factory()->approved()->count(2)->create();

        // Act
        $result = $this->platformTypeChangeRequestService->getTotalPending();

        // Assert
        $this->assertEquals(0, $result);
    }

    /**
     * Test getPendingRequestsWithTotal returns correct structure
     */
    public function test_get_pending_requests_with_total_returns_correct_structure()
    {
        // Arrange
        PlatformTypeChangeRequest::factory()->pending()->count(3)->create();

        // Act
        $result = $this->platformTypeChangeRequestService->getPendingRequestsWithTotal();

        // Assert
        $this->assertArrayHasKey('pendingRequests', $result);
        $this->assertArrayHasKey('totalPending', $result);
        $this->assertCount(3, $result['pendingRequests']);
        $this->assertEquals(3, $result['totalPending']);
    }

    /**
     * Test findRequest returns correct request
     */
    public function test_find_request_returns_correct_request()
    {
        // Arrange
        $request = PlatformTypeChangeRequest::factory()->create();

        // Act
        $result = $this->platformTypeChangeRequestService->findRequest($request->id);

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
        $this->platformTypeChangeRequestService->findRequest(99999);
    }

    /**
     * Test findRequestWithRelations loads relations
     */
    public function test_find_request_with_relations_loads_relations()
    {
        // Arrange
        $request = PlatformTypeChangeRequest::factory()->create();

        // Act
        $result = $this->platformTypeChangeRequestService->findRequestWithRelations($request->id, ['platform', 'requestedBy']);

        // Assert
        $this->assertNotNull($result);
        $this->assertTrue($result->relationLoaded('platform'));
        $this->assertTrue($result->relationLoaded('requestedBy'));
    }

    /**
     * Test approveRequest approves and changes platform type
     */
    public function test_approve_request_approves_and_changes_platform_type()
    {
        // Arrange
        $platform = Platform::factory()->create(['type' => 1]);
        $request = PlatformTypeChangeRequest::factory()->pending()->create([
            'platform_id' => $platform->id,
            'old_type' => 1,
            'new_type' => 2
        ]);
        $reviewer = User::factory()->create();

        // Act
        $result = $this->platformTypeChangeRequestService->approveRequest($request->id, $reviewer->id);

        // Assert
        $this->assertEquals(PlatformTypeChangeRequest::STATUS_APPROVED, $result->status);
        $this->assertEquals($reviewer->id, $result->reviewed_by);
        $this->assertNotNull($result->reviewed_at);

        $platform->refresh();
        $this->assertEquals(2, $platform->type);
    }

    /**
     * Test approveRequest throws exception when already processed
     */
    public function test_approve_request_throws_exception_when_already_processed()
    {
        // Arrange
        $request = PlatformTypeChangeRequest::factory()->approved()->create();
        $reviewer = User::factory()->create();

        // Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('This request has already been processed');

        // Act
        $this->platformTypeChangeRequestService->approveRequest($request->id, $reviewer->id);
    }

    /**
     * Test rejectRequest rejects request
     */
    public function test_reject_request_rejects_request()
    {
        // Arrange
        $request = PlatformTypeChangeRequest::factory()->pending()->create();
        $reviewer = User::factory()->create();
        $rejectionReason = 'Invalid type change';

        // Act
        $result = $this->platformTypeChangeRequestService->rejectRequest($request->id, $reviewer->id, $rejectionReason);

        // Assert
        $this->assertEquals(PlatformTypeChangeRequest::STATUS_REJECTED, $result->status);
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
        $request = PlatformTypeChangeRequest::factory()->approved()->create();
        $reviewer = User::factory()->create();

        // Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('This request has already been processed');

        // Act
        $this->platformTypeChangeRequestService->rejectRequest($request->id, $reviewer->id, 'Reason');
    }

    /**
     * Test getFilteredQuery filters by status
     */
    public function test_get_filtered_query_filters_by_status()
    {
        // Arrange
        PlatformTypeChangeRequest::factory()->pending()->count(2)->create();
        PlatformTypeChangeRequest::factory()->approved()->count(3)->create();

        // Act
        $result = $this->platformTypeChangeRequestService->getFilteredQuery(PlatformTypeChangeRequest::STATUS_APPROVED, null)->get();

        // Assert
        $this->assertCount(3, $result);
        $this->assertTrue($result->every(fn($r) => $r->status === PlatformTypeChangeRequest::STATUS_APPROVED));
    }

    /**
     * Test getFilteredQuery filters by search
     */
    public function test_get_filtered_query_filters_by_search()
    {
        // Arrange
        $platform = Platform::factory()->create(['name' => 'SearchablePlatform']);
        PlatformTypeChangeRequest::factory()->create(['platform_id' => $platform->id]);
        PlatformTypeChangeRequest::factory()->count(2)->create();

        // Act
        $result = $this->platformTypeChangeRequestService->getFilteredQuery(null, 'Searchable')->get();

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
        PlatformTypeChangeRequest::factory()->count(15)->create();

        // Act
        $result = $this->platformTypeChangeRequestService->getPaginatedRequests(null, null, 10);

        // Assert
        $this->assertCount(10, $result);
        $this->assertEquals(15, $result->total());
    }

    /**
     * Test createRequest creates new request
     */
    public function test_create_request_creates_new_request()
    {
        // Arrange
        $platform = Platform::factory()->create(['type' => 1]);
        $user = User::factory()->create();

        // Act
        $result = $this->platformTypeChangeRequestService->createRequest($platform->id, 1, 2, $user->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($platform->id, $result->platform_id);
        $this->assertEquals(1, $result->old_type);
        $this->assertEquals(2, $result->new_type);
        $this->assertEquals($user->id, $result->requested_by);
        $this->assertEquals(PlatformTypeChangeRequest::STATUS_PENDING, $result->status);
    }

    /**
     * Test createRequest sets correct initial status
     */
    public function test_create_request_sets_pending_status()
    {
        // Arrange
        $platform = Platform::factory()->create(['type' => 1]);
        $user = User::factory()->create();

        // Act
        $result = $this->platformTypeChangeRequestService->createRequest($platform->id, 1, 3, $user->id);

        // Assert
        $this->assertEquals(PlatformTypeChangeRequest::STATUS_PENDING, $result->status);
        $this->assertNull($result->reviewed_by);
        $this->assertNull($result->reviewed_at);
    }
}
