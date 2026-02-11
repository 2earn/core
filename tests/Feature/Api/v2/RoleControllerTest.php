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
        for ($i = 1; $i <= 5; $i++) {
            Role::create(['name' => 'Test Paginated Role ' . $i . uniqid(), 'guard_name' => 'web']);
        }

        $response = $this->getJson('/api/v2/roles/?per_page=10');

        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'data', 'pagination']);
    }

    #[Test]
    public function it_can_search_roles()
    {
        $uniqueId = uniqid();
        Role::create(['name' => 'Admin Role ' . $uniqueId, 'guard_name' => 'web']);
        Role::create(['name' => 'User Role ' . $uniqueId, 'guard_name' => 'web']);

        $response = $this->getJson('/api/v2/roles/?search=Admin');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_get_all_roles()
    {
        for ($i = 1; $i <= 3; $i++) {
            Role::create(['name' => 'Test Role ' . $i . uniqid(), 'guard_name' => 'web']);
        }

        $response = $this->getJson('/api/v2/roles/all');

        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'data']);
    }

    #[Test]
    public function it_can_get_role_by_id()
    {
        $role = Role::create(['name' => 'Test Role ' . uniqid(), 'guard_name' => 'web']);

        $response = $this->getJson("/api/v2/roles/{$role->id}");

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
        // Create multiple roles to ensure ID > 4 (system roles are protected in RoleService)
        for ($i = 1; $i <= 5; $i++) {
            Role::create(['name' => 'Temp Role ' . $i . uniqid(), 'guard_name' => 'web']);
        }

        $role = Role::create(['name' => 'Role To Delete ' . uniqid(), 'guard_name' => 'web']);

        // Only test deletion if ID > 4 (business rule in RoleService)
        if ($role->id > 4) {
            $response = $this->deleteJson("/api/v2/roles/{$role->id}");
            $response->assertStatus(200);
            $this->assertDatabaseMissing('roles', ['id' => $role->id]);
        } else {
            // If somehow ID <= 4, test that deletion is prevented
            $response = $this->deleteJson("/api/v2/roles/{$role->id}");
            $response->assertStatus(500);
        }
    }


    #[Test]
    public function it_can_get_user_roles()
    {
        $response = $this->getJson('/api/v2/roles/user-roles');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }
}

