<?php

namespace Tests\Unit\Services\Platform;

use App\Models\Platform;
use App\Models\PlatformChangeRequest;
use App\Models\User;
use App\Services\Platform\PendingPlatformChangeRequestsInlineService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PendingPlatformChangeRequestsInlineServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected PendingPlatformChangeRequestsInlineService $pendingPlatformChangeRequestsInlineService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pendingPlatformChangeRequestsInlineService = new PendingPlatformChangeRequestsInlineService();
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
            'requested_by' => $user->id,
            'status' => PlatformChangeRequest::STATUS_PENDING
        ]);

        // Act
        $result = $this->pendingPlatformChangeRequestsInlineService->getPendingRequests();

        // Assert
        $this->assertGreaterThanOrEqual(3, $result->count());
        $this->assertTrue($result->every(fn($request) => $request->status == PlatformChangeRequest::STATUS_PENDING));
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
            'requested_by' => $user->id,
            'status' => PlatformChangeRequest::STATUS_PENDING
        ]);

        // Act
        $result = $this->pendingPlatformChangeRequestsInlineService->getPendingRequests(5);

        // Assert
        $this->assertEquals(5, $result->count());
    }

    /**
     * Test getPendingRequests without limit returns all
     */
    public function test_get_pending_requests_without_limit_returns_all()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();

        $initialCount = PlatformChangeRequest::where('status', PlatformChangeRequest::STATUS_PENDING)->count();

        PlatformChangeRequest::factory()->count(7)->create([
            'platform_id' => $platform->id,
            'requested_by' => $user->id,
            'status' => PlatformChangeRequest::STATUS_PENDING
        ]);

        // Act
        $result = $this->pendingPlatformChangeRequestsInlineService->getPendingRequests();

        // Assert
        $this->assertEquals($initialCount + 7, $result->count());
    }

    /**
     * Test getPendingRequests loads relationships
     */
    public function test_get_pending_requests_loads_relationships()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();

        PlatformChangeRequest::factory()->create([
            'platform_id' => $platform->id,
            'requested_by' => $user->id,
            'status' => PlatformChangeRequest::STATUS_PENDING
        ]);

        // Act
        $result = $this->pendingPlatformChangeRequestsInlineService->getPendingRequests();

        // Assert
        $this->assertGreaterThan(0, $result->count());
        $this->assertTrue($result->first()->relationLoaded('platform'));
        $this->assertTrue($result->first()->relationLoaded('requestedBy'));
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
            'requested_by' => $user->id,
            'status' => PlatformChangeRequest::STATUS_PENDING
        ]);

        // Act
        $result = $this->pendingPlatformChangeRequestsInlineService->getTotalPending();

        // Assert
        $this->assertEquals($initialCount + 5, $result);
    }

    /**
     * Test getTotalPending returns zero when no pending
     */
    public function test_get_total_pending_returns_zero_when_no_pending()
    {
        // Arrange - Clear all pending requests for this test
        PlatformChangeRequest::where('status', PlatformChangeRequest::STATUS_PENDING)->delete();

        // Act
        $result = $this->pendingPlatformChangeRequestsInlineService->getTotalPending();

        // Assert
        $this->assertEquals(0, $result);
    }

    /**
     * Test getPendingRequestsWithTotal returns array with both data
     */
    public function test_get_pending_requests_with_total_returns_array()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();

        PlatformChangeRequest::factory()->count(3)->create([
            'platform_id' => $platform->id,
            'requested_by' => $user->id,
            'status' => PlatformChangeRequest::STATUS_PENDING
        ]);

        // Act
        $result = $this->pendingPlatformChangeRequestsInlineService->getPendingRequestsWithTotal();

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('pendingRequests', $result);
        $this->assertArrayHasKey('totalPending', $result);
        $this->assertGreaterThanOrEqual(3, $result['totalPending']);
        $this->assertGreaterThanOrEqual(3, $result['pendingRequests']->count());
    }

    /**
     * Test getPendingRequestsWithTotal respects limit
     */
    public function test_get_pending_requests_with_total_respects_limit()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();

        PlatformChangeRequest::factory()->count(10)->create([
            'platform_id' => $platform->id,
            'requested_by' => $user->id,
            'status' => PlatformChangeRequest::STATUS_PENDING
        ]);

        // Act
        $result = $this->pendingPlatformChangeRequestsInlineService->getPendingRequestsWithTotal(5);

        // Assert
        $this->assertEquals(5, $result['pendingRequests']->count());
        $this->assertGreaterThanOrEqual(10, $result['totalPending']);
    }

    /**
     * Test getPendingRequests orders by created_at desc
     */
    public function test_get_pending_requests_orders_by_created_at_desc()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();

        $older = PlatformChangeRequest::factory()->create([
            'platform_id' => $platform->id,
            'requested_by' => $user->id,
            'status' => PlatformChangeRequest::STATUS_PENDING,
            'created_at' => now()->subDays(2)
        ]);

        $newer = PlatformChangeRequest::factory()->create([
            'platform_id' => $platform->id,
            'requested_by' => $user->id,
            'status' => PlatformChangeRequest::STATUS_PENDING,
            'created_at' => now()->subDay()
        ]);

        // Act
        $result = $this->pendingPlatformChangeRequestsInlineService->getPendingRequests();

        // Assert
        $this->assertGreaterThan(0, $result->count());
        // First item should be the newer one
        $this->assertEquals($newer->id, $result->first()->id);
    }
}
