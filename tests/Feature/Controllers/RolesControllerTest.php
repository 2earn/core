<?php
/**
 * Test Suite for RolesController
 *
 * @package Tests\Feature\Controllers
 * @see App\Http\Controllers\RolesController
 * @author 2earn Development Team
 * @created 2026-01-22
 */
namespace Tests\Feature\Controllers;
use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;
class RolesControllerTest extends TestCase
{
    use DatabaseTransactions;
    protected $user;
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }
    /** @test */
    public function test_index_returns_datatables()
    {
        Role::create(['name' => 'test-role', 'guard_name' => 'web']);

        $response = $this->getJson('/api/roles/datatables');

        $response->assertStatus(200);
    }
    /** @test */
    public function test_role_can_be_created()
    {
        $role = Role::create(['name' => 'admin', 'guard_name' => 'web']);

        $this->assertDatabaseHas('roles', [
            'name' => 'admin',
            'guard_name' => 'web'
        ]);
    }
    /** @test */
    public function test_role_has_timestamps()
    {
        $role = Role::create(['name' => 'editor', 'guard_name' => 'web']);

        $this->assertNotNull($role->created_at);
        $this->assertNotNull($role->updated_at);
    }
}
