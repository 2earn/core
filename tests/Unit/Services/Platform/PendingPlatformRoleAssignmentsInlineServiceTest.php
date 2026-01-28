<?php

namespace Tests\Unit\Services\Platform;

use App\Models\AssignPlatformRole;
use App\Models\Platform;
use App\Models\User;
use App\Services\Platform\PendingPlatformRoleAssignmentsInlineService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PendingPlatformRoleAssignmentsInlineServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected PendingPlatformRoleAssignmentsInlineService $pendingPlatformRoleAssignmentsInlineService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pendingPlatformRoleAssignmentsInlineService = new PendingPlatformRoleAssignmentsInlineService();
    }

    /**
     * Test getPendingAssignments returns pending assignments
     */
    public function test_get_pending_assignments_returns_pending_assignments()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();

        AssignPlatformRole::factory()->count(3)->create([
            'platform_id' => $platform->id,
            'user_id' => $user->id,
            'status' => AssignPlatformRole::STATUS_PENDING
        ]);

        // Act
        $result = $this->pendingPlatformRoleAssignmentsInlineService->getPendingAssignments();

        // Assert
        $this->assertGreaterThanOrEqual(3, $result->count());
        $this->assertTrue($result->every(fn($assignment) => $assignment->status == AssignPlatformRole::STATUS_PENDING));
    }

    /**
     * Test getPendingAssignments respects limit
     */
    public function test_get_pending_assignments_respects_limit()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();

        AssignPlatformRole::factory()->count(10)->create([
            'platform_id' => $platform->id,
            'user_id' => $user->id,
            'status' => AssignPlatformRole::STATUS_PENDING
        ]);

        // Act
        $result = $this->pendingPlatformRoleAssignmentsInlineService->getPendingAssignments(5);

        // Assert
        $this->assertEquals(5, $result->count());
    }

    /**
     * Test getPendingAssignments without limit returns all
     */
    public function test_get_pending_assignments_without_limit_returns_all()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();

        $initialCount = AssignPlatformRole::where('status', AssignPlatformRole::STATUS_PENDING)->count();

        AssignPlatformRole::factory()->count(7)->create([
            'platform_id' => $platform->id,
            'user_id' => $user->id,
            'status' => AssignPlatformRole::STATUS_PENDING
        ]);

        // Act
        $result = $this->pendingPlatformRoleAssignmentsInlineService->getPendingAssignments();

        // Assert
        $this->assertEquals($initialCount + 7, $result->count());
    }

    /**
     * Test getPendingAssignments loads relationships
     */
    public function test_get_pending_assignments_loads_relationships()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();

        AssignPlatformRole::factory()->create([
            'platform_id' => $platform->id,
            'user_id' => $user->id,
            'status' => AssignPlatformRole::STATUS_PENDING
        ]);

        // Act
        $result = $this->pendingPlatformRoleAssignmentsInlineService->getPendingAssignments();

        // Assert
        $this->assertGreaterThan(0, $result->count());
        $this->assertTrue($result->first()->relationLoaded('platform'));
        $this->assertTrue($result->first()->relationLoaded('user'));
    }

    /**
     * Test getTotalPending returns correct count
     */
    public function test_get_total_pending_returns_correct_count()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();

        $initialCount = AssignPlatformRole::where('status', AssignPlatformRole::STATUS_PENDING)->count();

        AssignPlatformRole::factory()->count(5)->create([
            'platform_id' => $platform->id,
            'user_id' => $user->id,
            'status' => AssignPlatformRole::STATUS_PENDING
        ]);

        // Act
        $result = $this->pendingPlatformRoleAssignmentsInlineService->getTotalPending();

        // Assert
        $this->assertEquals($initialCount + 5, $result);
    }

    /**
     * Test getTotalPending returns zero when no pending
     */
    public function test_get_total_pending_returns_zero_when_no_pending()
    {
        // Arrange - Clear all pending assignments for this test
        AssignPlatformRole::where('status', AssignPlatformRole::STATUS_PENDING)->delete();

        // Act
        $result = $this->pendingPlatformRoleAssignmentsInlineService->getTotalPending();

        // Assert
        $this->assertEquals(0, $result);
    }

    /**
     * Test getPendingAssignmentsWithTotal returns array with both data
     */
    public function test_get_pending_assignments_with_total_returns_array()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();

        AssignPlatformRole::factory()->count(3)->create([
            'platform_id' => $platform->id,
            'user_id' => $user->id,
            'status' => AssignPlatformRole::STATUS_PENDING
        ]);

        // Act
        $result = $this->pendingPlatformRoleAssignmentsInlineService->getPendingAssignmentsWithTotal();

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('pendingAssignments', $result);
        $this->assertArrayHasKey('totalPending', $result);
        $this->assertGreaterThanOrEqual(3, $result['totalPending']);
        $this->assertGreaterThanOrEqual(3, $result['pendingAssignments']->count());
    }

    /**
     * Test getPendingAssignmentsWithTotal respects limit
     */
    public function test_get_pending_assignments_with_total_respects_limit()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();

        AssignPlatformRole::factory()->count(10)->create([
            'platform_id' => $platform->id,
            'user_id' => $user->id,
            'status' => AssignPlatformRole::STATUS_PENDING
        ]);

        // Act
        $result = $this->pendingPlatformRoleAssignmentsInlineService->getPendingAssignmentsWithTotal(5);

        // Assert
        $this->assertEquals(5, $result['pendingAssignments']->count());
        $this->assertGreaterThanOrEqual(10, $result['totalPending']);
    }
}
