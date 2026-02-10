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
    public function it_can_filter_by_type()
    {
        EntityRole::factory()->count(3)->create(['type' => 'platform']);
        EntityRole::factory()->count(2)->create(['type' => 'partner']);

        $response = $this->getJson('/api/v2/entity-roles/filtered?type=platform');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
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
        EntityRole::factory()->count(3)->create(['type' => 'platform']);

        $response = $this->getJson('/api/v2/entity-roles/platform');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_get_partner_roles()
    {
        EntityRole::factory()->count(3)->create(['type' => 'partner']);

        $response = $this->getJson('/api/v2/entity-roles/partner');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_get_roles_for_platform()
    {
        $platform = Platform::factory()->create();

        $response = $this->getJson("/api/v2/entity-roles/platform/{$platform->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_get_roles_for_partner()
    {
        $partner = User::factory()->create();

        $response = $this->getJson("/api/v2/entity-roles/partner/{$partner->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_create_role()
    {
        $data = [
            'name' => 'Test Role',
            'type' => 'platform',
            'description' => 'Test description'
        ];

        $response = $this->postJson('/api/v2/entity-roles', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_validates_role_creation()
    {
        $response = $this->postJson('/api/v2/entity-roles', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'type']);
    }

    #[Test]
    public function it_validates_type_field()
    {
        $data = [
            'name' => 'Test Role',
            'type' => 'invalid'
        ];

        $response = $this->postJson('/api/v2/entity-roles', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['type']);
    }

    #[Test]
    public function it_can_update_role()
    {
        $role = EntityRole::factory()->create();

        $data = [
            'name' => 'Updated Role',
            'description' => 'Updated description'
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

