<?php

namespace Tests\Unit\Services\Platform;

use App\Models\AssignPlatformRole;
use App\Models\EntityRole;
use App\Models\Platform;
use App\Models\User;
use App\Services\Platform\AssignPlatformRoleService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AssignPlatformRoleServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected AssignPlatformRoleService $assignPlatformRoleService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->assignPlatformRoleService = new AssignPlatformRoleService();
        Notification::fake();
    }

    /**
     * Test getPaginatedAssignments returns paginated results
     */
    public function test_get_paginated_assignments_returns_paginated_results()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();

        AssignPlatformRole::factory()->count(15)->create([
            'platform_id' => $platform->id,
            'user_id' => $user->id,
            'status' => AssignPlatformRole::STATUS_PENDING
        ]);

        // Act
        $result = $this->assignPlatformRoleService->getPaginatedAssignments([], 10);

        // Assert
        $this->assertInstanceOf(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class, $result);
        $this->assertGreaterThanOrEqual(15, $result->total());
    }

    /**
     * Test getPaginatedAssignments filters by status
     */
    public function test_get_paginated_assignments_filters_by_status()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();

        AssignPlatformRole::factory()->count(3)->create([
            'platform_id' => $platform->id,
            'user_id' => $user->id,
            'status' => AssignPlatformRole::STATUS_PENDING
        ]);
        AssignPlatformRole::factory()->count(2)->create([
            'platform_id' => $platform->id,
            'user_id' => $user->id,
            'status' => AssignPlatformRole::STATUS_APPROVED
        ]);

        // Act
        $result = $this->assignPlatformRoleService->getPaginatedAssignments([
            'status' => AssignPlatformRole::STATUS_PENDING
        ], 10);

        // Assert
        $this->assertGreaterThanOrEqual(3, $result->total());
    }

    /**
     * Test getPaginatedAssignments filters by search
     */
    public function test_get_paginated_assignments_filters_by_search()
    {
        // Arrange
        $platform = Platform::factory()->create(['name' => 'Unique Platform Name']);
        $user = User::factory()->create(['name' => 'John Doe']);

        AssignPlatformRole::factory()->create([
            'platform_id' => $platform->id,
            'user_id' => $user->id,
            'status' => AssignPlatformRole::STATUS_PENDING
        ]);

        // Act
        $result = $this->assignPlatformRoleService->getPaginatedAssignments([
            'search' => 'Unique'
        ], 10);

        // Assert
        $this->assertGreaterThanOrEqual(1, $result->total());
    }

    /**
     * Test approve creates EntityRole when no existing role
     */
    public function test_approve_creates_entity_role_when_no_existing()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();
        $approver = User::factory()->create();

        $assignment = AssignPlatformRole::factory()->create([
            'platform_id' => $platform->id,
            'user_id' => $user->id,
            'role' => 'manager',
            'status' => AssignPlatformRole::STATUS_PENDING
        ]);

        // Act
        $result = $this->assignPlatformRoleService->approve($assignment->id, $approver->id);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertStringContainsString('approved successfully', $result['message']);

        $assignment->refresh();
        $this->assertEquals(AssignPlatformRole::STATUS_APPROVED, $assignment->status);

        $this->assertDatabaseHas('entity_roles', [
            'roleable_type' => 'App\Models\Platform',
            'roleable_id' => $platform->id,
            'user_id' => $user->id,
            'name' => 'manager'
        ]);
    }

    /**
     * Test approve updates existing EntityRole
     */
    public function test_approve_updates_existing_entity_role()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $approver = User::factory()->create();

        // Create existing EntityRole with user1
        $existingRole = EntityRole::factory()->create([
            'roleable_type' => 'App\Models\Platform',
            'roleable_id' => $platform->id,
            'user_id' => $user1->id,
            'name' => 'manager'
        ]);

        // Create assignment to reassign role to user2
        $assignment = AssignPlatformRole::factory()->create([
            'platform_id' => $platform->id,
            'user_id' => $user2->id,
            'role' => 'manager',
            'status' => AssignPlatformRole::STATUS_PENDING
        ]);

        // Act
        $result = $this->assignPlatformRoleService->approve($assignment->id, $approver->id);

        // Assert
        $this->assertTrue($result['success']);

        $existingRole->refresh();
        $this->assertEquals($user2->id, $existingRole->user_id);
        $this->assertEquals($approver->id, $existingRole->updated_by);
    }

    /**
     * Test approve fails for already processed assignment
     */
    public function test_approve_fails_for_already_processed_assignment()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();
        $approver = User::factory()->create();

        $assignment = AssignPlatformRole::factory()->create([
            'platform_id' => $platform->id,
            'user_id' => $user->id,
            'status' => AssignPlatformRole::STATUS_APPROVED
        ]);

        // Act
        $result = $this->assignPlatformRoleService->approve($assignment->id, $approver->id);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('already been processed', $result['message']);
    }

    /**
     * Test approve handles non-existent assignment
     */
    public function test_approve_handles_non_existent_assignment()
    {
        // Arrange
        $approver = User::factory()->create();

        // Act
        $result = $this->assignPlatformRoleService->approve(99999, $approver->id);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Failed to approve', $result['message']);
    }

    /**
     * Test reject marks assignment as rejected
     */
    public function test_reject_marks_assignment_as_rejected()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();
        $rejector = User::factory()->create();

        $assignment = AssignPlatformRole::factory()->create([
            'platform_id' => $platform->id,
            'user_id' => $user->id,
            'status' => AssignPlatformRole::STATUS_PENDING
        ]);

        // Act
        $result = $this->assignPlatformRoleService->reject(
            $assignment->id,
            'Not qualified for this role',
            $rejector->id
        );

        // Assert
        $this->assertTrue($result['success']);
        $this->assertStringContainsString('rejected successfully', $result['message']);

        $assignment->refresh();
        $this->assertEquals(AssignPlatformRole::STATUS_REJECTED, $assignment->status);
        $this->assertEquals('Not qualified for this role', $assignment->rejection_reason);
        $this->assertEquals($rejector->id, $assignment->updated_by);
    }

    /**
     * Test reject does not create EntityRole
     */
    public function test_reject_does_not_create_entity_role()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();
        $rejector = User::factory()->create();

        $assignment = AssignPlatformRole::factory()->create([
            'platform_id' => $platform->id,
            'user_id' => $user->id,
            'role' => 'manager',
            'status' => AssignPlatformRole::STATUS_PENDING
        ]);

        $initialRoleCount = EntityRole::where('roleable_type', 'App\Models\Platform')
            ->where('roleable_id', $platform->id)
            ->where('name', 'manager')
            ->count();

        // Act
        $result = $this->assignPlatformRoleService->reject(
            $assignment->id,
            'Not suitable',
            $rejector->id
        );

        // Assert
        $this->assertTrue($result['success']);

        $finalRoleCount = EntityRole::where('roleable_type', 'App\Models\Platform')
            ->where('roleable_id', $platform->id)
            ->where('name', 'manager')
            ->count();

        $this->assertEquals($initialRoleCount, $finalRoleCount);
    }

    /**
     * Test reject fails for already processed assignment
     */
    public function test_reject_fails_for_already_processed_assignment()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();
        $rejector = User::factory()->create();

        $assignment = AssignPlatformRole::factory()->create([
            'platform_id' => $platform->id,
            'user_id' => $user->id,
            'status' => AssignPlatformRole::STATUS_REJECTED
        ]);

        // Act
        $result = $this->assignPlatformRoleService->reject(
            $assignment->id,
            'Test reason',
            $rejector->id
        );

        // Assert
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('already been processed', $result['message']);
    }

    /**
     * Test reject handles non-existent assignment
     */
    public function test_reject_handles_non_existent_assignment()
    {
        // Arrange
        $rejector = User::factory()->create();

        // Act
        $result = $this->assignPlatformRoleService->reject(99999, 'Test reason', $rejector->id);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Failed to reject', $result['message']);
    }

    /**
     * Test approve returns success response with correct structure
     */
    public function test_approve_returns_correct_response_structure()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();
        $approver = User::factory()->create();

        $assignment = AssignPlatformRole::factory()->create([
            'platform_id' => $platform->id,
            'user_id' => $user->id,
            'status' => AssignPlatformRole::STATUS_PENDING
        ]);

        // Act
        $result = $this->assignPlatformRoleService->approve($assignment->id, $approver->id);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('success', $result);
        $this->assertArrayHasKey('message', $result);
    }

    /**
     * Test reject returns correct response structure
     */
    public function test_reject_returns_correct_response_structure()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();
        $rejector = User::factory()->create();

        $assignment = AssignPlatformRole::factory()->create([
            'platform_id' => $platform->id,
            'user_id' => $user->id,
            'status' => AssignPlatformRole::STATUS_PENDING
        ]);

        // Act
        $result = $this->assignPlatformRoleService->reject(
            $assignment->id,
            'Test reason',
            $rejector->id
        );

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('success', $result);
        $this->assertArrayHasKey('message', $result);
    }
}
