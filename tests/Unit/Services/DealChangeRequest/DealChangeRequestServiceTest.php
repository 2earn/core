<?php

namespace Tests\Unit\Services\DealChangeRequest;

use App\Models\Deal;
use App\Models\DealChangeRequest;
use App\Models\User;
use App\Services\DealChangeRequest\DealChangeRequestService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DealChangeRequestServiceTest extends TestCase
{
    use RefreshDatabase;

    protected DealChangeRequestService $dealChangeRequestService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dealChangeRequestService = new DealChangeRequestService();
    }

    /**
     * Test getPaginatedRequests method
     */
    public function test_get_paginated_requests_works()
    {
        // Arrange
        DealChangeRequest::factory()->count(15)->create(['status' => DealChangeRequest::STATUS_PENDING]);
        DealChangeRequest::factory()->count(5)->create(['status' => DealChangeRequest::STATUS_APPROVED]);

        // Act
        $result = $this->dealChangeRequestService->getPaginatedRequests(null, 'pending', 10);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $result);
        $this->assertEquals(15, $result->total());
    }

    /**
     * Test getAllRequests method
     */
    public function test_get_all_requests_works()
    {
        // Arrange
        DealChangeRequest::factory()->count(10)->create(['status' => DealChangeRequest::STATUS_PENDING]);
        DealChangeRequest::factory()->count(3)->create(['status' => DealChangeRequest::STATUS_APPROVED]);

        // Act
        $result = $this->dealChangeRequestService->getAllRequests(null, 'pending');

        // Assert
        $this->assertCount(10, $result);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
    }

    /**
     * Test getRequestById method
     */
    public function test_get_request_by_id_works()
    {
        // Arrange
        $request = DealChangeRequest::factory()->create();

        // Act
        $result = $this->dealChangeRequestService->getRequestById($request->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($request->id, $result->id);
        $this->assertInstanceOf(DealChangeRequest::class, $result);
    }

    /**
     * Test getRequestByIdWithRelations method
     */
    public function test_get_request_by_id_with_relations_works()
    {
        // Arrange
        $request = DealChangeRequest::factory()->create();

        // Act
        $result = $this->dealChangeRequestService->getRequestByIdWithRelations($request->id, ['deal', 'requestedBy']);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($request->id, $result->id);
        $this->assertTrue($result->relationLoaded('deal'));
        $this->assertTrue($result->relationLoaded('requestedBy'));
    }

    /**
     * Test createRequest method
     */
    public function test_create_request_works()
    {
        // Arrange
        $deal = Deal::factory()->create();
        $user = User::factory()->create();
        $data = [
            'deal_id' => $deal->id,
            'changes' => ['field' => 'discount', 'old_value' => 10, 'new_value' => 15],
            'status' => DealChangeRequest::STATUS_PENDING,
            'requested_by' => $user->id,
        ];

        // Act
        $result = $this->dealChangeRequestService->createRequest($data);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(DealChangeRequest::class, $result);
        $this->assertEquals($deal->id, $result->deal_id);
        $this->assertEquals(DealChangeRequest::STATUS_PENDING, $result->status);
        $this->assertDatabaseHas('deal_change_requests', [
            'deal_id' => $deal->id,
            'status' => DealChangeRequest::STATUS_PENDING,
        ]);
    }

    /**
     * Test updateRequest method
     */
    public function test_update_request_works()
    {
        // Arrange
        $request = DealChangeRequest::factory()->create(['status' => DealChangeRequest::STATUS_PENDING]);
        $updateData = [
            'changes' => ['field' => 'discount', 'old_value' => 10, 'new_value' => 20],
        ];

        // Act
        $result = $this->dealChangeRequestService->updateRequest($request->id, $updateData);

        // Assert
        $this->assertTrue($result);
        $request->refresh();
        $this->assertEquals(['field' => 'discount', 'old_value' => 10, 'new_value' => 20], $request->changes);
    }

    /**
     * Test approveRequest method
     */
    public function test_approve_request_works()
    {
        // Arrange
        $request = DealChangeRequest::factory()->create(['status' => DealChangeRequest::STATUS_PENDING]);
        $reviewer = User::factory()->create();

        // Act
        $result = $this->dealChangeRequestService->approveRequest($request->id, $reviewer->id);

        // Assert
        $this->assertTrue($result);
        $request->refresh();
        $this->assertEquals(DealChangeRequest::STATUS_APPROVED, $request->status);
        $this->assertEquals($reviewer->id, $request->reviewed_by);
        $this->assertNotNull($request->reviewed_at);
    }

    /**
     * Test rejectRequest method
     */
    public function test_reject_request_works()
    {
        // Arrange
        $request = DealChangeRequest::factory()->create(['status' => DealChangeRequest::STATUS_PENDING]);
        $reviewer = User::factory()->create();
        $rejectionReason = 'Not meeting business requirements';

        // Act
        $result = $this->dealChangeRequestService->rejectRequest($request->id, $reviewer->id, $rejectionReason);

        // Assert
        $this->assertTrue($result);
        $request->refresh();
        $this->assertEquals(DealChangeRequest::STATUS_REJECTED, $request->status);
        $this->assertEquals($reviewer->id, $request->reviewed_by);
        $this->assertEquals($rejectionReason, $request->rejection_reason);
        $this->assertNotNull($request->reviewed_at);
    }

    /**
     * Test getRequestsByStatus method
     * TODO: Implement actual test logic
     */
    public function test_get_requests_by_status_works()
    {
        // Arrange
        // TODO: Set up test data

        // Act
        // $result = $this->service->getRequestsByStatus();

        // Assert
        // TODO: Add assertions
        $this->markTestIncomplete('Test for getRequestsByStatus not yet implemented');
    }

    /**
     * Test getRequestsByDealId method
     */
    public function test_get_requests_by_deal_id_works()
    {
        // Arrange
        $deal = Deal::factory()->create();
        DealChangeRequest::factory()->count(5)->create(['deal_id' => $deal->id]);
        DealChangeRequest::factory()->count(3)->create(); // Different deal

        // Act
        $result = $this->dealChangeRequestService->getRequestsByDealId($deal->id);

        // Assert
        $this->assertCount(5, $result);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
        $this->assertTrue($result->every(fn($req) => $req->deal_id === $deal->id));
    }

    /**
     * Test countPendingRequests method
     */
    public function test_count_pending_requests_works()
    {
        // Arrange
        DealChangeRequest::factory()->count(8)->create(['status' => DealChangeRequest::STATUS_PENDING]);
        DealChangeRequest::factory()->count(4)->create(['status' => DealChangeRequest::STATUS_APPROVED]);
        DealChangeRequest::factory()->count(2)->create(['status' => DealChangeRequest::STATUS_REJECTED]);

        // Act
        $result = $this->dealChangeRequestService->countPendingRequests();

        // Assert
        $this->assertEquals(8, $result);
    }
}
