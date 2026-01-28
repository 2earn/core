<?php

namespace Tests\Unit\Services\Platform;

use App\Models\Platform;
use App\Models\PlatformChangeRequest;
use App\Models\User;
use App\Services\Platform\PlatformChangeRequestService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PlatformChangeRequestServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected PlatformChangeRequestService $platformChangeRequestService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->platformChangeRequestService = new PlatformChangeRequestService();
    }

    /**
     * Test getPendingRequestsPaginated returns paginated pending requests
     */
    public function test_get_pending_requests_paginated_returns_pending_requests()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();

        PlatformChangeRequest::factory()->count(3)->create([
            'platform_id' => $platform->id,
            'status' => PlatformChangeRequest::STATUS_PENDING,
            'requested_by' => $user->id
        ]);

        // Act
        $result = $this->platformChangeRequestService->getPendingRequestsPaginated(1, 10);

        // Assert
        $this->assertGreaterThanOrEqual(3, $result->total());
    }

    /**
     * Test getPendingRequestsPaginated filters by platform
     */
    public function test_get_pending_requests_paginated_filters_by_platform()
    {
        // Arrange
        $platform1 = Platform::factory()->create();
        $platform2 = Platform::factory()->create();
        $user = User::factory()->create();

        PlatformChangeRequest::factory()->create([
            'platform_id' => $platform1->id,
            'status' => PlatformChangeRequest::STATUS_PENDING,
            'requested_by' => $user->id
        ]);
        PlatformChangeRequest::factory()->create([
            'platform_id' => $platform2->id,
            'status' => PlatformChangeRequest::STATUS_PENDING,
            'requested_by' => $user->id
        ]);

        // Act
        $result = $this->platformChangeRequestService->getPendingRequestsPaginated(1, 10, $platform1->id);

        // Assert
        $this->assertGreaterThanOrEqual(1, $result->total());
        foreach ($result->items() as $item) {
            $this->assertEquals($platform1->id, $item->platform_id);
        }
    }

    /**
     * Test getChangeRequestsPaginated returns paginated results
     */
    public function test_get_change_requests_paginated_returns_results()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();

        PlatformChangeRequest::factory()->count(5)->create([
            'platform_id' => $platform->id,
            'requested_by' => $user->id
        ]);

        // Act
        $result = $this->platformChangeRequestService->getChangeRequestsPaginated(null, null, 1, 10);

        // Assert
        $this->assertGreaterThanOrEqual(5, $result->total());
    }

    /**
     * Test getChangeRequestsPaginated filters by status
     */
    public function test_get_change_requests_paginated_filters_by_status()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();

        PlatformChangeRequest::factory()->count(2)->create([
            'platform_id' => $platform->id,
            'status' => PlatformChangeRequest::STATUS_PENDING,
            'requested_by' => $user->id
        ]);
        PlatformChangeRequest::factory()->count(3)->create([
            'platform_id' => $platform->id,
            'status' => PlatformChangeRequest::STATUS_APPROVED,
            'requested_by' => $user->id
        ]);

        // Act
        $result = $this->platformChangeRequestService->getChangeRequestsPaginated(
            PlatformChangeRequest::STATUS_APPROVED,
            null,
            1,
            10
        );

        // Assert
        $this->assertGreaterThanOrEqual(3, $result->total());
    }

    /**
     * Test getChangeRequestById returns request
     */
    public function test_get_change_request_by_id_returns_request()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();
        $request = PlatformChangeRequest::factory()->create([
            'platform_id' => $platform->id,
            'requested_by' => $user->id
        ]);

        // Act
        $result = $this->platformChangeRequestService->getChangeRequestById($request->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($request->id, $result->id);
    }

    /**
     * Test getChangeRequestById returns null when not found
     */
    public function test_get_change_request_by_id_returns_null_when_not_found()
    {
        // Act
        $result = $this->platformChangeRequestService->getChangeRequestById(99999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test findRequest returns request
     */
    public function test_find_request_returns_request()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();
        $request = PlatformChangeRequest::factory()->create([
            'platform_id' => $platform->id,
            'requested_by' => $user->id
        ]);

        // Act
        $result = $this->platformChangeRequestService->findRequest($request->id);

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
        $this->platformChangeRequestService->findRequest(99999);
    }

    /**
     * Test findRequestWithRelations loads relationships
     */
    public function test_find_request_with_relations_loads_relationships()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();
        $request = PlatformChangeRequest::factory()->create([
            'platform_id' => $platform->id,
            'requested_by' => $user->id
        ]);

        // Act
        $result = $this->platformChangeRequestService->findRequestWithRelations(
            $request->id,
            ['platform', 'requestedBy']
        );

        // Assert
        $this->assertNotNull($result);
        $this->assertTrue($result->relationLoaded('platform'));
        $this->assertTrue($result->relationLoaded('requestedBy'));
    }

    /**
     * Test approveRequest approves and applies changes
     */
    public function test_approve_request_approves_and_applies_changes()
    {
        // Arrange
        $platform = Platform::factory()->create(['name' => 'Original Name']);
        $user = User::factory()->create();
        $reviewer = User::factory()->create();

        $request = PlatformChangeRequest::factory()->create([
            'platform_id' => $platform->id,
            'requested_by' => $user->id,
            'status' => PlatformChangeRequest::STATUS_PENDING,
            'changes' => [
                'name' => [
                    'old' => 'Original Name',
                    'new' => 'Updated Name'
                ]
            ]
        ]);

        // Act
        $result = $this->platformChangeRequestService->approveRequest($request->id, $reviewer->id);

        // Assert
        $this->assertEquals(PlatformChangeRequest::STATUS_APPROVED, $result->status);
        $this->assertEquals($reviewer->id, $result->reviewed_by);
        $this->assertNotNull($result->reviewed_at);

        $platform->refresh();
        $this->assertEquals('Updated Name', $platform->name);
    }

    /**
     * Test approveRequest throws exception for already processed request
     */
    public function test_approve_request_throws_exception_for_already_processed()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();
        $reviewer = User::factory()->create();

        $request = PlatformChangeRequest::factory()->create([
            'platform_id' => $platform->id,
            'requested_by' => $user->id,
            'status' => PlatformChangeRequest::STATUS_APPROVED,
            'reviewed_by' => $reviewer->id
        ]);

        // Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('already been processed');

        // Act
        $this->platformChangeRequestService->approveRequest($request->id, $reviewer->id);
    }

    /**
     * Test rejectRequest rejects request
     */
    public function test_reject_request_rejects_request()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();
        $reviewer = User::factory()->create();

        $request = PlatformChangeRequest::factory()->create([
            'platform_id' => $platform->id,
            'requested_by' => $user->id,
            'status' => PlatformChangeRequest::STATUS_PENDING
        ]);

        // Act
        $result = $this->platformChangeRequestService->rejectRequest(
            $request->id,
            $reviewer->id,
            'Not approved'
        );

        // Assert
        $this->assertEquals('rejected', $result->status);
        $this->assertEquals('Not approved', $result->rejection_reason);
        $this->assertEquals($reviewer->id, $result->reviewed_by);
        $this->assertNotNull($result->reviewed_at);
    }

    /**
     * Test getFilteredQuery returns query builder
     */
    public function test_get_filtered_query_returns_query_builder()
    {
        // Act
        $result = $this->platformChangeRequestService->getFilteredQuery();

        // Assert
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);
    }

    /**
     * Test getFilteredQuery filters by status
     */
    public function test_get_filtered_query_filters_by_status()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();

        PlatformChangeRequest::factory()->create([
            'platform_id' => $platform->id,
            'status' => PlatformChangeRequest::STATUS_PENDING,
            'requested_by' => $user->id
        ]);

        // Act
        $query = $this->platformChangeRequestService->getFilteredQuery(PlatformChangeRequest::STATUS_PENDING);
        $result = $query->get();

        // Assert
        $this->assertGreaterThanOrEqual(1, $result->count());
    }

    /**
     * Test getPaginatedRequests returns paginated results
     */
    public function test_get_paginated_requests_returns_paginated_results()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();

        PlatformChangeRequest::factory()->count(15)->create([
            'platform_id' => $platform->id,
            'requested_by' => $user->id
        ]);

        // Act
        $result = $this->platformChangeRequestService->getPaginatedRequests(null, null, 10);

        // Assert
        $this->assertGreaterThanOrEqual(15, $result->total());
        $this->assertLessThanOrEqual(10, $result->count());
    }

    /**
     * Test createRequest creates new request
     */
    public function test_create_request_creates_new_request()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();
        $changes = ['name' => ['old' => 'Old', 'new' => 'New']];

        // Act
        $result = $this->platformChangeRequestService->createRequest(
            $platform->id,
            $changes,
            $user->id
        );

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($platform->id, $result->platform_id);
        $this->assertEquals($changes, $result->changes);
        $this->assertEquals(PlatformChangeRequest::STATUS_PENDING, $result->status);
        $this->assertDatabaseHas('platform_change_requests', [
            'platform_id' => $platform->id,
            'requested_by' => $user->id
        ]);
    }

    /**
     * Test cancelRequest cancels pending request
     */
    public function test_cancel_request_cancels_pending_request()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();
        $request = PlatformChangeRequest::factory()->create([
            'platform_id' => $platform->id,
            'requested_by' => $user->id,
            'status' => PlatformChangeRequest::STATUS_PENDING
        ]);

        // Act
        $result = $this->platformChangeRequestService->cancelRequest($request->id);

        // Assert
        $this->assertEquals(PlatformChangeRequest::STATUS_CANCELLED, $result->status);
    }

    /**
     * Test getPendingRequests returns pending requests
     */
    public function test_get_pending_requests_returns_pending_requests()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();

        PlatformChangeRequest::factory()->count(3)->create([
            'platform_id' => $platform->id,
            'status' => PlatformChangeRequest::STATUS_PENDING,
            'requested_by' => $user->id
        ]);

        // Act
        $result = $this->platformChangeRequestService->getPendingRequests();

        // Assert
        $this->assertGreaterThanOrEqual(3, $result->count());
    }

    /**
     * Test getPendingRequests respects limit
     */
    public function test_get_pending_requests_respects_limit()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();

        PlatformChangeRequest::factory()->count(10)->create([
            'platform_id' => $platform->id,
            'status' => PlatformChangeRequest::STATUS_PENDING,
            'requested_by' => $user->id
        ]);

        // Act
        $result = $this->platformChangeRequestService->getPendingRequests(5);

        // Assert
        $this->assertEquals(5, $result->count());
    }

    /**
     * Test getStatistics returns statistics array
     */
    public function test_get_statistics_returns_statistics_array()
    {
        // Act
        $result = $this->platformChangeRequestService->getStatistics();

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('pending_count', $result);
        $this->assertArrayHasKey('approved_count', $result);
        $this->assertArrayHasKey('rejected_count', $result);
        $this->assertArrayHasKey('total_count', $result);
        $this->assertArrayHasKey('recent_pending', $result);
        $this->assertArrayHasKey('today_count', $result);
        $this->assertArrayHasKey('this_week_count', $result);
    }

    /**
     * Test getTotalPending returns correct count
     */
    public function test_get_total_pending_returns_correct_count()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();
        $initialCount = PlatformChangeRequest::where('status', PlatformChangeRequest::STATUS_PENDING)->count();

        PlatformChangeRequest::factory()->count(5)->create([
            'platform_id' => $platform->id,
            'status' => PlatformChangeRequest::STATUS_PENDING,
            'requested_by' => $user->id
        ]);

        // Act
        $result = $this->platformChangeRequestService->getTotalPending();

        // Assert
        $this->assertEquals($initialCount + 5, $result);
    }
}
