<?php

namespace Tests\Feature\Api\v2;

use App\Models\AssignPlatformRole;
use App\Models\Platform;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Test Suite for AssignPlatformRoleController (API v2)
 *
 * @package Tests\Feature\Api\v2
 * @see App\Http\Controllers\Api\v2\AssignPlatformRoleController
 */
#[Group('api')]
#[Group('api_v2')]
#[Group('assign_platform_role')]
class AssignPlatformRoleControllerTest extends TestCase
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
    public function it_can_get_paginated_assignments()
    {
        $response = $this->getJson('/api/v2/assign-platform-roles?per_page=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data'
            ]);
    }

    #[Test]
    public function it_can_filter_assignments_by_status()
    {
        $response = $this->getJson('/api/v2/assign-platform-roles?status=pending');

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_can_search_assignments()
    {
        $response = $this->getJson('/api/v2/assign-platform-roles?search=test');

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_can_approve_assignment()
    {
        $platform = Platform::factory()->create();
        $targetUser = User::factory()->create();

        $assignment = AssignPlatformRole::factory()->create([
            'platform_id' => $platform->id,
            'user_id' => $targetUser->id,
            'role' => 'test_role',
            'status' => 'pending'
        ]);

        $response = $this->postJson("/api/v2/assign-platform-roles/{$assignment->id}/approve", [
            'approved_by' => $this->user->id
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_validates_approved_by_field_when_approving()
    {
        $assignment = AssignPlatformRole::factory()->create();

        $response = $this->postJson("/api/v2/assign-platform-roles/{$assignment->id}/approve", []);

        $response->assertStatus(422)
            ->assertJsonStructure(['success', 'message', 'errors']);
    }

    #[Test]
    public function it_returns_404_for_nonexistent_assignment_on_approve()
    {
        $response = $this->postJson('/api/v2/assign-platform-roles/999999/approve', [
            'approved_by' => $this->user->id
        ]);

        $response->assertStatus(404);
    }

    #[Test]
    public function it_can_reject_assignment()
    {
        $assignment = AssignPlatformRole::factory()->create(['status' => 'pending']);

        $response = $this->postJson("/api/v2/assign-platform-roles/{$assignment->id}/reject", [
            'rejected_by' => $this->user->id,
            'rejection_reason' => 'Test reason'
        ]);

        $response->assertStatus(200);
    }

    #[Test]
    public function it_validates_required_fields_when_rejecting()
    {
        $assignment = AssignPlatformRole::factory()->create();

        $response = $this->postJson("/api/v2/assign-platform-roles/{$assignment->id}/reject", []);

        $response->assertStatus(422);
    }

    #[Test]
    public function it_returns_404_for_nonexistent_assignment_on_reject()
    {
        $response = $this->postJson('/api/v2/assign-platform-roles/999999/reject', [
            'rejected_by' => $this->user->id,
            'rejection_reason' => 'Test reason'
        ]);

        $response->assertStatus(404);
    }
}

