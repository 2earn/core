<?php

namespace Tests\Feature\Api\Partner;

use Tests\TestCase;
use App\Models\User;
use App\Models\Platform;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserPartnerControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $platform;
    protected $baseUrl = '/api/partner';

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->platform = Platform::factory()->create(['created_by' => $this->user->id]);
        $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1']);
    }

    public function test_can_add_role_to_user()
    {
        $data = [
            'user_id' => $this->user->id,
            'platform_id' => $this->platform->id,
            'role' => 'admin'
        ];

        $response = $this->postJson($this->baseUrl . '/users/add-role', $data);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         'role' => [
                             'id',
                             'name',
                             'user' => ['id', 'name', 'email'],
                             'platform' => ['id', 'name'],
                             'created_at',
                             'updated_at'
                         ]
                     ]
                 ])
                 ->assertJson([
                     'status' => true,
                     'message' => 'Role assigned successfully'
                 ]);

        // Verify the role was actually created in the database
        $this->assertDatabaseHas('entity_roles', [
            'user_id' => $this->user->id,
            'roleable_id' => $this->platform->id,
            'roleable_type' => 'App\\Models\\Platform',
            'name' => 'admin'
        ]);
    }

    public function test_can_get_partner_platforms()
    {
        Platform::factory()->count(3)->create(['created_by' => $this->user->id]);
        $response = $this->getJson($this->baseUrl . '/users/platforms?user_id=' . $this->user->id);
        $response->assertStatus(200)->assertJsonStructure(['status', 'data']);
    }

    public function test_cannot_add_duplicate_role()
    {
        // First, add a role
        $data = [
            'user_id' => $this->user->id,
            'platform_id' => $this->platform->id,
            'role' => 'manager'
        ];

        $firstResponse = $this->postJson($this->baseUrl . '/users/add-role', $data);
        $firstResponse->assertStatus(201);

        // Try to add the same role again
        $secondResponse = $this->postJson($this->baseUrl . '/users/add-role', $data);

        $secondResponse->assertStatus(409)
                      ->assertJsonStructure([
                          'status',
                          'message',
                          'data' => [
                              'existing_role' => ['id', 'name', 'created_at']
                          ]
                      ])
                      ->assertJson([
                          'status' => false,
                          'message' => 'This role is already assigned to the user for this platform'
                      ]);
    }

    public function test_can_add_multiple_different_roles_to_same_user()
    {
        // Add first role
        $firstRole = [
            'user_id' => $this->user->id,
            'platform_id' => $this->platform->id,
            'role' => 'owner'
        ];

        $firstResponse = $this->postJson($this->baseUrl . '/users/add-role', $firstRole);
        $firstResponse->assertStatus(201);

        // Add second different role to same user on same platform
        $secondRole = [
            'user_id' => $this->user->id,
            'platform_id' => $this->platform->id,
            'role' => 'manager'
        ];

        $secondResponse = $this->postJson($this->baseUrl . '/users/add-role', $secondRole);
        $secondResponse->assertStatus(201);

        // Verify both roles exist in database
        $this->assertDatabaseHas('entity_roles', [
            'user_id' => $this->user->id,
            'roleable_id' => $this->platform->id,
            'name' => 'owner'
        ]);

        $this->assertDatabaseHas('entity_roles', [
            'user_id' => $this->user->id,
            'roleable_id' => $this->platform->id,
            'name' => 'manager'
        ]);
    }

    public function test_add_role_fails_with_invalid_data()
    {
        $data = ['user_id' => $this->user->id];
        $response = $this->postJson($this->baseUrl . '/users/add-role', $data);
        $response->assertStatus(422);
    }

    public function test_get_platforms_fails_without_user_id()
    {
        $response = $this->getJson($this->baseUrl . '/users/platforms');
        $response->assertStatus(422);
    }

    public function test_fails_without_valid_ip()
    {
        $this->withServerVariables(['REMOTE_ADDR' => '192.168.1.1']);
        $response = $this->getJson($this->baseUrl . '/users/platforms?user_id=' . $this->user->id);
        $response->assertStatus(403)->assertJson(['error' => 'Unauthorized. Invalid IP.']);
    }

    public function test_can_update_role_name()
    {
        // First, add a role
        $data = [
            'user_id' => $this->user->id,
            'platform_id' => $this->platform->id,
            'role' => 'admin'
        ];

        $addResponse = $this->postJson($this->baseUrl . '/users/add-role', $data);
        $addResponse->assertStatus(201);

        $roleId = $addResponse->json('data.role.id');

        // Update the role name
        $updateData = [
            'role_id' => $roleId,
            'role_name' => 'super_admin'
        ];

        $response = $this->postJson($this->baseUrl . '/users/update-role', $updateData);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         'role' => [
                             'id',
                             'name',
                             'user' => ['id', 'name', 'email'],
                             'platform' => ['id', 'name'],
                             'created_at',
                             'updated_at'
                         ]
                     ]
                 ])
                 ->assertJson([
                     'status' => true,
                     'message' => 'Role updated successfully',
                     'data' => [
                         'role' => [
                             'id' => $roleId,
                             'name' => 'super_admin'
                         ]
                     ]
                 ]);

        // Verify the role was actually updated in the database
        $this->assertDatabaseHas('entity_roles', [
            'id' => $roleId,
            'user_id' => $this->user->id,
            'roleable_id' => $this->platform->id,
            'roleable_type' => 'App\\Models\\Platform',
            'name' => 'super_admin'
        ]);

        // Verify the old name doesn't exist
        $this->assertDatabaseMissing('entity_roles', [
            'id' => $roleId,
            'name' => 'admin'
        ]);
    }

    public function test_update_role_fails_with_invalid_role_id()
    {
        $updateData = [
            'role_id' => 99999,
            'role_name' => 'new_role'
        ];

        $response = $this->postJson($this->baseUrl . '/users/update-role', $updateData);

        $response->assertStatus(422)
                 ->assertJson([
                     'status' => false,
                     'message' => 'Validation failed'
                 ]);
    }

    public function test_update_role_fails_without_role_name()
    {
        // First, add a role
        $data = [
            'user_id' => $this->user->id,
            'platform_id' => $this->platform->id,
            'role' => 'admin'
        ];

        $addResponse = $this->postJson($this->baseUrl . '/users/add-role', $data);
        $roleId = $addResponse->json('data.role.id');

        // Try to update without role_name
        $updateData = [
            'role_id' => $roleId
        ];

        $response = $this->postJson($this->baseUrl . '/users/update-role', $updateData);

        $response->assertStatus(422)
                 ->assertJson([
                     'status' => false,
                     'message' => 'Validation failed'
                 ]);
    }

    public function test_update_role_fails_without_role_id()
    {
        $updateData = [
            'role_name' => 'new_role'
        ];

        $response = $this->postJson($this->baseUrl . '/users/update-role', $updateData);

        $response->assertStatus(422)
                 ->assertJson([
                     'status' => false,
                     'message' => 'Validation failed'
                 ]);
    }
}
