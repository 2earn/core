<?php

namespace Tests\Feature\Api\Partner;

use Tests\TestCase;
use App\Models\User;
use App\Models\Platform;
use App\Models\UserCurrentBalanceHorisontal;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Group;

#[Group('api')]
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

        $response = $this->postJson($this->baseUrl . '/users/platforms/add-role', $data);

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

        $firstResponse = $this->postJson($this->baseUrl . '/users/platforms/add-role', $data);
        $firstResponse->assertStatus(201);

        // Try to add the same role again
        $secondResponse = $this->postJson($this->baseUrl . '/users/platforms/add-role', $data);

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

        $firstResponse = $this->postJson($this->baseUrl . '/users/platforms/add-role', $firstRole);
        $firstResponse->assertStatus(201);

        // Add second different role to same user on same platform
        $secondRole = [
            'user_id' => $this->user->id,
            'platform_id' => $this->platform->id,
            'role' => 'manager'
        ];

        $secondResponse = $this->postJson($this->baseUrl . '/users/platforms/add-role', $secondRole);
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
        $response = $this->postJson($this->baseUrl . '/users/platforms/add-role', $data);
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

        $addResponse = $this->postJson($this->baseUrl . '/users/platforms/add-role', $data);
        $addResponse->assertStatus(201);

        $roleId = $addResponse->json('data.role.id');

        // Update the role name
        $updateData = [
            'role_id' => $roleId,
            'role_name' => 'super_admin'
        ];

        $response = $this->postJson($this->baseUrl . '/users/platforms/update-role', $updateData);

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

        $response = $this->postJson($this->baseUrl . '/users/platforms/update-role', $updateData);

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

        $addResponse = $this->postJson($this->baseUrl . '/users/platforms/add-role', $data);
        $roleId = $addResponse->json('data.role.id');

        // Try to update without role_name
        $updateData = [
            'role_id' => $roleId
        ];

        $response = $this->postJson($this->baseUrl . '/users/platforms/update-role', $updateData);

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

        $response = $this->postJson($this->baseUrl . '/users/platforms/update-role', $updateData);

        $response->assertStatus(422)
                 ->assertJson([
                     'status' => false,
                     'message' => 'Validation failed'
                 ]);
    }

    public function test_can_delete_role()
    {
        // First, add a role
        $data = [
            'user_id' => $this->user->id,
            'platform_id' => $this->platform->id,
            'role' => 'admin'
        ];

        $addResponse = $this->postJson($this->baseUrl . '/users/platforms/add-role', $data);
        $addResponse->assertStatus(201);

        $roleId = $addResponse->json('data.role.id');

        // Delete the role
        $deleteData = [
            'role_id' => $roleId
        ];

        $response = $this->postJson($this->baseUrl . '/users/platforms/delete-role', $deleteData);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         'deleted_role' => [
                             'id',
                             'name',
                             'user_id',
                             'platform_id'
                         ]
                     ]
                 ])
                 ->assertJson([
                     'status' => true,
                     'message' => 'Role deleted successfully'
                 ]);

        // Verify the role was actually deleted from the database
        $this->assertDatabaseMissing('entity_roles', [
            'id' => $roleId
        ]);
    }

    public function test_delete_role_fails_with_invalid_role_id()
    {
        $deleteData = [
            'role_id' => 99999
        ];

        $response = $this->postJson($this->baseUrl . '/users/platforms/delete-role', $deleteData);

        $response->assertStatus(422)
                 ->assertJson([
                     'status' => false,
                     'message' => 'Validation failed'
                 ]);
    }

    public function test_delete_role_fails_without_role_id()
    {
        $deleteData = [];

        $response = $this->postJson($this->baseUrl . '/users/platforms/delete-role', $deleteData);

        $response->assertStatus(422)
                 ->assertJson([
                     'status' => false,
                     'message' => 'Validation failed'
                 ]);
    }

    public function test_can_get_discount_balance_for_user()
    {
        // Create a balance record for the user
        UserCurrentBalanceHorisontal::create([
            'user_id' => $this->user->idUser,  // Use idUser, not id
            'user_id_auto' => $this->user->id,
            'cash_balance' => 100.0,
            'bfss_balance' => [],
            'discount_balance' => 250.50,
            'tree_balance' => 50.0,
            'sms_balance' => 10.0,
            'share_balance' => 75.0,
            'chances_balance' => []
        ]);

        $response = $this->getJson($this->baseUrl . '/users/discount-balance?user_id=' . $this->user->id);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         'user_id',
                         'idUser',
                         'user_name',
                         'user_email',
                         'discount_balance'
                     ]
                 ])
                 ->assertJson([
                     'status' => true,
                     'message' => 'Discount balance retrieved successfully',
                     'data' => [
                         'user_id' => $this->user->id,
                         'idUser' => $this->user->idUser,
                         'discount_balance' => 250.50
                     ]
                 ]);
    }

    public function test_get_discount_balance_returns_zero_when_no_balance_record()
    {
        // Don't create a balance record
        $response = $this->getJson($this->baseUrl . '/users/discount-balance?user_id=' . $this->user->id);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         'user_id',
                         'idUser',
                         'user_name',
                         'user_email',
                         'discount_balance'
                     ]
                 ])
                 ->assertJson([
                     'status' => true,
                     'message' => 'No balance record found. Discount balance is 0.',
                     'data' => [
                         'user_id' => $this->user->id,
                         'idUser' => $this->user->idUser,
                         'discount_balance' => 0
                     ]
                 ]);
    }

    public function test_get_discount_balance_returns_zero_when_discount_is_null()
    {
        // Create a balance record with null discount_balance
        UserCurrentBalanceHorisontal::create([
            'user_id' => $this->user->idUser,  // Use idUser, not id
            'user_id_auto' => $this->user->id,
            'cash_balance' => 100.0,
            'bfss_balance' => [],
            'discount_balance' => null,
            'tree_balance' => 50.0,
            'sms_balance' => 10.0,
            'share_balance' => 75.0,
            'chances_balance' => []
        ]);

        $response = $this->getJson($this->baseUrl . '/users/discount-balance?user_id=' . $this->user->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'data' => [
                         'user_id' => $this->user->id,
                         'idUser' => $this->user->idUser,
                         'discount_balance' => 0
                     ]
                 ]);
    }

    public function test_get_discount_balance_fails_without_user_id()
    {
        $response = $this->getJson($this->baseUrl . '/users/discount-balance');

        $response->assertStatus(422)
                 ->assertJson([
                     'status' => false,
                     'message' => 'Validation failed'
                 ]);
    }

    public function test_get_discount_balance_fails_with_invalid_user_id()
    {
        $response = $this->getJson($this->baseUrl . '/users/discount-balance?user_id=99999');

        $response->assertStatus(422)
                 ->assertJson([
                     'status' => false,
                     'message' => 'Validation failed'
                 ]);
    }

    public function test_get_discount_balance_fails_with_non_integer_user_id()
    {
        $response = $this->getJson($this->baseUrl . '/users/discount-balance?user_id=invalid');

        $response->assertStatus(422)
                 ->assertJson([
                     'status' => false,
                     'message' => 'Validation failed'
                 ]);
    }

    public function test_get_discount_balance_with_different_balance_values()
    {
        $testCases = [
            ['balance' => 0, 'expected' => 0],
            ['balance' => 0.01, 'expected' => 0.01],
            ['balance' => 999.99, 'expected' => 999.99],
            ['balance' => 10000.50, 'expected' => 10000.50],
        ];

        foreach ($testCases as $index => $testCase) {
            $testUser = User::factory()->create();

            UserCurrentBalanceHorisontal::create([
                'user_id' => $testUser->idUser,  // Use idUser, not id
                'user_id_auto' => $testUser->id,
                'cash_balance' => 0,
                'bfss_balance' => [],
                'discount_balance' => $testCase['balance'],
                'tree_balance' => 0,
                'sms_balance' => 0,
                'share_balance' => 0,
                'chances_balance' => []
            ]);

            $response = $this->getJson($this->baseUrl . '/users/discount-balance?user_id=' . $testUser->id);

            $response->assertStatus(200)
                     ->assertJson([
                         'status' => true,
                         'data' => [
                             'user_id' => $testUser->id,
                             'idUser' => $testUser->idUser,
                             'discount_balance' => $testCase['expected']
                         ]
                     ]);
        }
    }

    public function test_get_discount_balance_fails_without_valid_ip()
    {
        $this->withServerVariables(['REMOTE_ADDR' => '192.168.1.1']);

        $response = $this->getJson($this->baseUrl . '/users/discount-balance?user_id=' . $this->user->id);

        $response->assertStatus(403)
                 ->assertJson(['error' => 'Unauthorized. Invalid IP.']);
    }
}

