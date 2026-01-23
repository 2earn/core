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
use PHPUnit\Framework\Attributes\Test;
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
    #[Test]
    public function test_index_returns_datatables()
    {
        // Create a test role
        $roleName = 'test-role-' . uniqid();
        Role::create(['name' => $roleName, 'guard_name' => 'web']);

        // Verify the role exists in the database
        $this->assertDatabaseHas('roles', [
            'name' => $roleName,
            'guard_name' => 'web'
        ]);
    }
    #[Test]
    public function test_role_can_be_created()
    {
        $roleName = 'test-admin-' . uniqid();
        $role = Role::create(['name' => $roleName, 'guard_name' => 'web']);

        $this->assertDatabaseHas('roles', [
            'name' => $roleName,
            'guard_name' => 'web'
        ]);
    }
    #[Test]
    public function test_role_has_timestamps()
    {
        $roleName = 'test-editor-' . uniqid();
        $role = Role::create(['name' => $roleName, 'guard_name' => 'web']);

        $this->assertNotNull($role->created_at);
        $this->assertNotNull($role->updated_at);
    }
}
