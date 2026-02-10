<?php

namespace Tests\Feature\Api\v2;

use App\Models\EntityRole;
use App\Models\User;
use App\Models\Platform;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Test Suite for EntityRoleController (API v2)
 *
 * @package Tests\Feature\Api\v2
 * @see App\Http\Controllers\Api\v2\EntityRoleController
 */
#[Group('api')]
#[Group('api_v2')]
#[Group('entity_roles')]
class EntityRoleControllerTest extends TestCase
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
    public function it_can_get_all_roles()
    {
        EntityRole::factory()->count(3)->create();

        $response = $this->getJson('/api/v2/entity-roles');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_get_filtered_roles()
    {
        EntityRole::factory()->count(10)->create();

        $response = $this->getJson('/api/v2/entity-roles/filtered?per_page=15');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data',
                'pagination'
            ]);
    }


    #[Test]
    public function it_can_search_roles()
    {
        EntityRole::factory()->create(['name' => 'Test Role']);

        $response = $this->getJson('/api/v2/entity-roles/filtered?search=Test');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_get_role_by_id()
    {
        $role = EntityRole::factory()->create();

        $response = $this->getJson("/api/v2/entity-roles/{$role->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_returns_404_for_nonexistent_role()
    {
        $response = $this->getJson('/api/v2/entity-roles/99999');

        $response->assertStatus(404)
            ->assertJsonFragment(['status' => false]);
    }

    #[Test]
    public function it_can_get_platform_roles()
    {
        $response = $this->getJson('/api/v2/entity-roles/platform-roles');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_get_partner_roles()
    {
        $response = $this->getJson('/api/v2/entity-roles/partner-roles');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_get_roles_for_platform()
    {
        $platform = Platform::factory()->create();

        $response = $this->getJson("/api/v2/entity-roles/platforms/{$platform->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_get_roles_for_partner()
    {
        $partner = User::factory()->create();

        $response = $this->getJson("/api/v2/entity-roles/partners/{$partner->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_create_platform_role()
    {
        $platform = Platform::factory()->create();
        $user = User::factory()->create();

        $data = [
            'name' => 'admin',
            'user_id' => $user->id
        ];

        $response = $this->postJson("/api/v2/entity-roles/platforms/{$platform->id}", $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_validates_role_creation()
    {
        $platform = Platform::factory()->create();

        $response = $this->postJson("/api/v2/entity-roles/platforms/{$platform->id}", []);

        $response->assertStatus(422);
    }

    #[Test]
    public function it_can_update_role()
    {
        $role = EntityRole::factory()->create();

        $data = [
            'name' => 'manager'
        ];

        $response = $this->putJson("/api/v2/entity-roles/{$role->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_delete_role()
    {
        $role = EntityRole::factory()->create();

        $response = $this->deleteJson("/api/v2/entity-roles/{$role->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_validates_per_page_parameter()
    {
        $response = $this->getJson('/api/v2/entity-roles/filtered?per_page=150');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['per_page']);
    }
}

