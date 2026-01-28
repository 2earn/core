<?php

namespace Tests\Unit\Services\Role;

use App\Services\Role\RoleService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected RoleService $roleService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->roleService = new RoleService();
    }

    /**
     * Test getById returns role
     */
    public function test_get_by_id_returns_role()
    {
        // Arrange
        $role = Role::create(['name' => 'test-role', 'guard_name' => 'web']);

        // Act
        $result = $this->roleService->getById($role->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($role->id, $result->id);
        $this->assertEquals('test-role', $result->name);
    }

    /**
     * Test getById returns null when not found
     */
    public function test_get_by_id_returns_null_when_not_found()
    {
        // Act
        $result = $this->roleService->getById(99999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getByIdOrFail returns role
     */
    public function test_get_by_id_or_fail_returns_role()
    {
        // Arrange
        $role = Role::create(['name' => 'test-role', 'guard_name' => 'web']);

        // Act
        $result = $this->roleService->getByIdOrFail($role->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($role->id, $result->id);
    }

    /**
     * Test getByIdOrFail throws exception when not found
     */
    public function test_get_by_id_or_fail_throws_exception_when_not_found()
    {
        // Assert
        $this->expectException(ModelNotFoundException::class);

        // Act
        $this->roleService->getByIdOrFail(99999);
    }

    /**
     * Test getPaginated returns paginated results
     */
    public function test_get_paginated_returns_paginated_results()
    {
        // Arrange
        Role::create(['name' => 'test-role-1', 'guard_name' => 'web']);
        Role::create(['name' => 'test-role-2', 'guard_name' => 'web']);
        Role::create(['name' => 'test-role-3', 'guard_name' => 'web']);

        // Act
        $result = $this->roleService->getPaginated(null, 10);

        // Assert
        $this->assertGreaterThanOrEqual(3, $result->total());
    }

    /**
     * Test getPaginated filters by search
     */
    public function test_get_paginated_filters_by_search()
    {
        // Arrange
        Role::create(['name' => 'unique-test-role', 'guard_name' => 'web']);
        Role::create(['name' => 'other-role', 'guard_name' => 'web']);

        // Act
        $result = $this->roleService->getPaginated('unique-test', 10);

        // Assert
        $this->assertGreaterThanOrEqual(1, $result->total());
        $this->assertStringContainsString('unique', strtolower($result->items()[0]->name));
    }

    /**
     * Test getAll returns all roles
     */
    public function test_get_all_returns_all_roles()
    {
        // Arrange
        $initialCount = Role::count();
        Role::create(['name' => 'test-role-a', 'guard_name' => 'web']);
        Role::create(['name' => 'test-role-b', 'guard_name' => 'web']);

        // Act
        $result = $this->roleService->getAll();

        // Assert
        $this->assertCount($initialCount + 2, $result);
    }

    /**
     * Test create creates new role
     */
    public function test_create_creates_new_role()
    {
        // Arrange
        $data = [
            'name' => 'new-test-role',
            'guard_name' => 'web'
        ];

        // Act
        $result = $this->roleService->create($data);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals('new-test-role', $result->name);
        $this->assertDatabaseHas('roles', ['name' => 'new-test-role']);
    }

    /**
     * Test update updates role
     */
    public function test_update_updates_role()
    {
        // Arrange
        $role = Role::create(['name' => 'original-name', 'guard_name' => 'web']);
        $updateData = ['name' => 'updated-name'];

        // Act
        $result = $this->roleService->update($role->id, $updateData);

        // Assert
        $this->assertTrue($result);
        $role->refresh();
        $this->assertEquals('updated-name', $role->name);
    }

    /**
     * Test update throws exception when not found
     */
    public function test_update_throws_exception_when_not_found()
    {
        // Assert
        $this->expectException(ModelNotFoundException::class);

        // Act
        $this->roleService->update(99999, ['name' => 'test']);
    }

    /**
     * Test delete deletes role
     */
    public function test_delete_deletes_role()
    {
        // Arrange
        $role = Role::create(['name' => 'deletable-role', 'guard_name' => 'web']);
        $roleId = $role->id;

        // Act
        $result = $this->roleService->delete($roleId);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('roles', ['id' => $roleId]);
    }

    /**
     * Test delete throws exception for protected roles
     */
    public function test_delete_throws_exception_for_protected_roles()
    {
        // Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('This Role cannot be deleted!');

        // Act - IDs 1-4 are protected
        $this->roleService->delete(1);
    }

    /**
     * Test delete throws exception when not found
     */
    public function test_delete_throws_exception_when_not_found()
    {
        // Assert
        $this->expectException(ModelNotFoundException::class);

        // Act
        $this->roleService->delete(99999);
    }

    /**
     * Test canDelete returns true for deletable roles
     */
    public function test_can_delete_returns_true_for_deletable_roles()
    {
        // Act
        $result = $this->roleService->canDelete(5);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Test canDelete returns false for protected roles
     */
    public function test_can_delete_returns_false_for_protected_roles()
    {
        // Act
        $result1 = $this->roleService->canDelete(1);
        $result2 = $this->roleService->canDelete(4);

        // Assert
        $this->assertFalse($result1);
        $this->assertFalse($result2);
    }

    /**
     * Test getUserRoles returns paginated results
     */
    public function test_get_user_roles_returns_paginated_results()
    {
        // Act
        $result = $this->roleService->getUserRoles('', 10);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class, $result);
    }

    /**
     * Test getUserRoles filters by search
     */
    public function test_get_user_roles_filters_by_search()
    {
        // Act
        $result = $this->roleService->getUserRoles('test', 10);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class, $result);
    }
}
