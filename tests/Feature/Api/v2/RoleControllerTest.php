<?php

namespace Tests\Feature\Api\v2;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Test Suite for RoleController (API v2)
 *
 * @package Tests\Feature\Api\v2
 * @see App\Http\Controllers\Api\v2\RoleController
 */
#[Group('api')]
#[Group('api_v2')]
#[Group('roles')]
class RoleControllerTest extends TestCase
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
    public function it_can_get_paginated_roles()
    {
        // Get max ID to avoid auto-increment issues
        $maxId = \DB::table('roles')->max('id') ?? 4;

        for ($i = 1; $i <= 5; $i++) {
            \DB::table('roles')->insert([
                'id' => $maxId + $i,
                'name' => 'Test Paginated Role ' . $i . uniqid(),
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        $response = $this->getJson('/api/v2/roles/?per_page=10');

        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'data', 'pagination']);
    }

    #[Test]
    public function it_can_search_roles()
    {
        $maxId = \DB::table('roles')->max('id') ?? 4;
        $uniqueId = uniqid();

        \DB::table('roles')->insert([
            'id' => $maxId + 1,
            'name' => 'Admin Role ' . $uniqueId,
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        \DB::table('roles')->insert([
            'id' => $maxId + 2,
            'name' => 'User Role ' . $uniqueId,
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $response = $this->getJson('/api/v2/roles/?search=Admin');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_get_all_roles()
    {
        $maxId = \DB::table('roles')->max('id') ?? 4;

        for ($i = 1; $i <= 3; $i++) {
            \DB::table('roles')->insert([
                'id' => $maxId + $i,
                'name' => 'Test Role ' . $i . uniqid(),
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        $response = $this->getJson('/api/v2/roles/all');

        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'data']);
    }

    #[Test]
    public function it_can_get_role_by_id()
    {
        $maxId = \DB::table('roles')->max('id') ?? 4;
        $roleId = $maxId + 1;

        \DB::table('roles')->insert([
            'id' => $roleId,
            'name' => 'Test Role ' . uniqid(),
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $response = $this->getJson("/api/v2/roles/{$roleId}");

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_returns_404_for_nonexistent_role()
    {
        $response = $this->getJson('/api/v2/roles/999999');

        $response->assertStatus(404);
    }

    #[Test]
    public function it_can_create_role()
    {
        $data = [
            'name' => 'New Role ' . uniqid(),
            'guard_name' => 'web'
        ];

        $response = $this->postJson('/api/v2/roles/', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('roles', ['name' => $data['name']]);
    }

    #[Test]
    public function it_validates_unique_role_name()
    {
        $uniqueName = 'Existing Role ' . uniqid();
        Role::create(['name' => $uniqueName, 'guard_name' => 'web']);

        $data = ['name' => $uniqueName];

        $response = $this->postJson('/api/v2/roles/', $data);

        $response->assertStatus(422);
    }

    #[Test]
    public function it_can_update_role()
    {
        $role = Role::create(['name' => 'Original Name ' . uniqid(), 'guard_name' => 'web']);

        $data = ['name' => 'Updated Name ' . uniqid()];

        $response = $this->putJson("/api/v2/roles/{$role->id}", $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'name' => $data['name']
        ]);
    }

    #[Test]
    public function it_can_delete_role()
    {
        // Get the maximum ID to ensure we create a role with ID > 4
        $maxId = \DB::table('roles')->max('id') ?? 4;
        $roleId = $maxId + 1;

        // Create a role that can be deleted (ID will be > 4)
        \DB::table('roles')->insert([
            'id' => $roleId,
            'name' => 'Role To Delete ' . uniqid(),
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Verify the role was created with ID > 4
        $this->assertGreaterThan(4, $roleId, 'Role ID should be greater than 4 to test deletion');

        $response = $this->deleteJson("/api/v2/roles/{$roleId}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('roles', ['id' => $roleId]);
    }

    #[Test]
    public function it_cannot_delete_protected_roles()
    {
        // System roles with ID <= 4 are protected and cannot be deleted
        $protectedRoleId = 1; // Admin role (assuming it exists from seeders)

        $response = $this->deleteJson("/api/v2/roles/{$protectedRoleId}");

        $response->assertStatus(500);
        $this->assertDatabaseHas('roles', ['id' => $protectedRoleId]);
    }


    #[Test]
    public function it_can_get_user_roles()
    {
        $response = $this->getJson('/api/v2/roles/user-roles');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }
}

