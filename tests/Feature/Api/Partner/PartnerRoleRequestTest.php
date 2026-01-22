<?php

namespace Tests\Feature\Api\Partner;

use Tests\TestCase;
use App\Models\PartnerRoleRequest;
use App\Models\Partner;
use App\Models\User;
use App\Models\EntityRole;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PartnerRoleRequestTest extends TestCase
{

    protected $partner;
    protected $user;
    protected $requestedBy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->partner = Partner::factory()->create();
        $this->user = User::factory()->create();
        $this->requestedBy = User::factory()->create();
    }

    /** @test */
    public function it_can_create_a_partner_role_request()
    {
        $response = $this->postJson('/api/partner/role-requests', [
            'partner_id' => $this->partner->id,
            'user_id' => $this->user->id,
            'role_name' => 'manager',
            'reason' => 'Need a manager for the sales department',
            'requested_by' => $this->requestedBy->id,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'Success',
                'message' => 'Partner role request created successfully',
            ]);

        $this->assertDatabaseHas('partner_role_requests', [
            'partner_id' => $this->partner->id,
            'user_id' => $this->user->id,
            'role_name' => 'manager',
            'status' => 'pending',
        ]);
    }

    /** @test */
    public function it_prevents_duplicate_pending_requests()
    {
        PartnerRoleRequest::create([
            'partner_id' => $this->partner->id,
            'user_id' => $this->user->id,
            'role_name' => 'manager',
            'status' => 'pending',
            'requested_by' => $this->requestedBy->id,
        ]);

        $response = $this->postJson('/api/partner/role-requests', [
            'partner_id' => $this->partner->id,
            'user_id' => $this->user->id,
            'role_name' => 'manager',
            'requested_by' => $this->requestedBy->id,
        ]);

        $response->assertStatus(409)
            ->assertJson([
                'status' => 'Failed',
                'message' => 'A pending request already exists for this user and role',
            ]);
    }

    /** @test */
    public function it_can_list_partner_role_requests()
    {
        PartnerRoleRequest::factory()->count(5)->create([
            'partner_id' => $this->partner->id,
        ]);

        $response = $this->getJson('/api/partner/role-requests?partner_id=' . $this->partner->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'requests',
                    'statistics',
                    'pagination',
                ],
            ]);
    }


    /** @test */
    public function it_can_cancel_a_pending_request()
    {
        $request = PartnerRoleRequest::create([
            'partner_id' => $this->partner->id,
            'user_id' => $this->user->id,
            'role_name' => 'manager',
            'status' => 'pending',
            'requested_by' => $this->requestedBy->id,
        ]);

        $canceller = User::factory()->create();

        $response = $this->postJson("/api/partner/role-requests/{$request->id}/cancel", [
            'cancelled_by' => $canceller->id,
            'cancelled_reason' => 'No longer needed',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'Success',
                'message' => 'Partner role request cancelled successfully',
            ]);

        $this->assertDatabaseHas('partner_role_requests', [
            'id' => $request->id,
            'status' => 'cancelled',
            'cancelled_by' => $canceller->id,
        ]);
    }


    /** @test */
    public function it_filters_by_status()
    {
        PartnerRoleRequest::create([
            'partner_id' => $this->partner->id,
            'user_id' => $this->user->id,
            'role_name' => 'manager',
            'status' => 'pending',
            'requested_by' => $this->requestedBy->id,
        ]);

        PartnerRoleRequest::create([
            'partner_id' => $this->partner->id,
            'user_id' => User::factory()->create()->id,
            'role_name' => 'admin',
            'status' => 'approved',
            'requested_by' => $this->requestedBy->id,
        ]);

        $response = $this->getJson('/api/partner/role-requests?partner_id=' . $this->partner->id . '&status=pending');

        $response->assertStatus(200);
        $data = $response->json('data.requests');

        $this->assertCount(1, $data);
        $this->assertEquals('pending', $data[0]['status']);
    }
}
