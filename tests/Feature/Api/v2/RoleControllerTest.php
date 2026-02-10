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
        Role::factory()->count(5)->create();

        $response = $this->getJson('/api/v2/roles/?per_page=10');

        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'data', 'pagination']);
    }

    #[Test]
    public function it_can_search_roles()
    {
        Role::factory()->create(['name' => 'Admin Role']);
        Role::factory()->create(['name' => 'User Role']);

        $response = $this->getJson('/api/v2/roles/?search=Admin');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_get_all_roles()
    {
        Role::factory()->count(3)->create();

        $response = $this->getJson('/api/v2/roles/all');

        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'data']);
    }

    #[Test]
    public function it_can_get_role_by_id()
    {
        $role = Role::factory()->create(['name' => 'Test Role']);

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
            'name' => 'New Role',
            'guard_name' => 'web'
        ];

        $response = $this->postJson('/api/v2/roles/', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('roles', ['name' => 'New Role']);
    }

    #[Test]
    public function it_validates_unique_role_name()
    {
        Role::factory()->create(['name' => 'Existing Role']);

        $data = ['name' => 'Existing Role'];

        $response = $this->postJson('/api/v2/roles/', $data);

        $response->assertStatus(422);
    }

    #[Test]
    public function it_can_update_role()
    {
        $role = Role::factory()->create(['name' => 'Original Name']);

        $data = ['name' => 'Updated Name'];

        $response = $this->putJson("/api/v2/roles/{$role->id}", $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'name' => 'Updated Name'
        ]);
    }

    #[Test]
    public function it_can_delete_role()
    {
        $role = Role::factory()->create();

        $response = $this->deleteJson("/api/v2/roles/{$role->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }

    #[Test]
    public function it_can_check_if_role_can_be_deleted()
    {
        $role = Role::factory()->create();

        $response = $this->getJson("/api/v2/roles/{$role->id}/can-delete");

        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'data']);
    }

    #[Test]
    public function it_can_get_user_roles()
    {
        $response = $this->getJson('/api/v2/roles/user-roles');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }
}

