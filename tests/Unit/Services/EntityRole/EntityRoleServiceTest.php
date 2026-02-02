<?php

namespace Tests\Unit\Services\EntityRole;

use App\Models\EntityRole;
use App\Models\Partner;
use App\Models\Platform;
use App\Models\User;
use App\Services\EntityRole\EntityRoleService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class EntityRoleServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected EntityRoleService $entityRoleService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->entityRoleService = new EntityRoleService();
    }

    /**
     * Test getAllRoles returns all roles
     */
    public function test_get_all_roles_returns_all_roles()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();
        EntityRole::factory()->count(3)->create([
            'roleable_type' => Platform::class,
            'roleable_id' => $platform->id,
            'user_id' => $user->id
        ]);

        // Act
        $result = $this->entityRoleService->getAllRoles();

        // Assert
        $this->assertGreaterThanOrEqual(3, $result->count());
    }

    /**
     * Test getRoleById returns specific role
     */
    public function test_get_role_by_id_returns_role()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();
        $role = EntityRole::factory()->create([
            'roleable_type' => Platform::class,
            'roleable_id' => $platform->id,
            'user_id' => $user->id
        ]);

        // Act
        $result = $this->entityRoleService->getRoleById($role->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($role->id, $result->id);
    }

    /**
     * Test getRoleById returns null when not found
     */
    public function test_get_role_by_id_returns_null_when_not_found()
    {
        // Act
        $result = $this->entityRoleService->getRoleById(99999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getPlatformRoles returns only platform roles
     */
    public function test_get_platform_roles_returns_only_platform_roles()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $partner = Partner::factory()->create();
        $user = User::factory()->create();

        EntityRole::factory()->count(2)->create([
            'roleable_type' => Platform::class,
            'roleable_id' => $platform->id,
            'user_id' => $user->id
        ]);
        EntityRole::factory()->create([
            'roleable_type' => Partner::class,
            'roleable_id' => $partner->id,
            'user_id' => $user->id
        ]);

        // Act
        $result = $this->entityRoleService->getPlatformRoles();

        // Assert
        $this->assertGreaterThanOrEqual(2, $result->count());
        $this->assertTrue($result->every(fn($role) => $role->roleable_type === Platform::class));
    }

    /**
     * Test getPartnerRoles returns only partner roles
     */
    public function test_get_partner_roles_returns_only_partner_roles()
    {
        // Arrange
        $partner = Partner::factory()->create();
        $platform = Platform::factory()->create();
        $user = User::factory()->create();

        EntityRole::factory()->count(2)->create([
            'roleable_type' => Partner::class,
            'roleable_id' => $partner->id,
            'user_id' => $user->id
        ]);
        EntityRole::factory()->create([
            'roleable_type' => Platform::class,
            'roleable_id' => $platform->id,
            'user_id' => $user->id
        ]);

        // Act
        $result = $this->entityRoleService->getPartnerRoles();

        // Assert
        $this->assertGreaterThanOrEqual(2, $result->count());
        $this->assertTrue($result->every(fn($role) => $role->roleable_type === Partner::class));
    }

    /**
     * Test getRolesForPlatform returns roles for specific platform
     */
    public function test_get_roles_for_platform_returns_platform_roles()
    {
        // Arrange
        $platform1 = Platform::factory()->create();
        $platform2 = Platform::factory()->create();
        $user = User::factory()->create();

        EntityRole::factory()->count(3)->create([
            'roleable_type' => Platform::class,
            'roleable_id' => $platform1->id,
            'user_id' => $user->id
        ]);
        EntityRole::factory()->create([
            'roleable_type' => Platform::class,
            'roleable_id' => $platform2->id,
            'user_id' => $user->id
        ]);

        // Act
        $result = $this->entityRoleService->getRolesForPlatform($platform1->id);

        // Assert
        $this->assertCount(3, $result);
        $this->assertTrue($result->every(fn($role) => $role->roleable_id == $platform1->id));
    }

    /**
     * Test getRolesForPlatform with pagination
     */
    public function test_get_roles_for_platform_with_pagination()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();

        EntityRole::factory()->count(15)->create([
            'roleable_type' => Platform::class,
            'roleable_id' => $platform->id,
            'user_id' => $user->id
        ]);

        // Act
        $result = $this->entityRoleService->getRolesForPlatform($platform->id, true, 10);

        // Assert
        $this->assertInstanceOf(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class, $result);
        $this->assertEquals(15, $result->total());
    }

    /**
     * Test getEntityRolesKeyedByName returns keyed collection
     */
    public function test_get_entity_roles_keyed_by_name_returns_keyed_collection()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();

        EntityRole::factory()->create([
            'roleable_type' => Platform::class,
            'roleable_id' => $platform->id,
            'user_id' => $user->id,
            'name' => 'owner'
        ]);
        EntityRole::factory()->create([
            'roleable_type' => Platform::class,
            'roleable_id' => $platform->id,
            'user_id' => $user->id,
            'name' => 'admin'
        ]);

        // Act
        $result = $this->entityRoleService->getEntityRolesKeyedByName($platform->id);

        // Assert
        $this->assertCount(2, $result);
        $this->assertArrayHasKey('owner', $result->toArray());
        $this->assertArrayHasKey('admin', $result->toArray());
    }

    /**
     * Test getRolesForPartner returns roles for specific partner
     */
    public function test_get_roles_for_partner_returns_partner_roles()
    {
        // Arrange
        $partner1 = Partner::factory()->create();
        $partner2 = Partner::factory()->create();
        $user = User::factory()->create();

        EntityRole::factory()->count(2)->create([
            'roleable_type' => Partner::class,
            'roleable_id' => $partner1->id,
            'user_id' => $user->id
        ]);
        EntityRole::factory()->create([
            'roleable_type' => Partner::class,
            'roleable_id' => $partner2->id,
            'user_id' => $user->id
        ]);

        // Act
        $result = $this->entityRoleService->getRolesForPartner($partner1->id);

        // Assert
        $this->assertCount(2, $result);
        $this->assertTrue($result->every(fn($role) => $role->roleable_id == $partner1->id));
    }

    /**
     * Test createPlatformRole creates new role
     */
    public function test_create_platform_role_creates_new_role()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();
        $creator = User::factory()->create();

        $data = [
            'name' => 'manager',
            'user_id' => $user->id,
            'created_by' => $creator->id
        ];

        // Act
        $result = $this->entityRoleService->createPlatformRole($platform->id, $data);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals('manager', $result->name);
        $this->assertEquals($platform->id, $result->roleable_id);
        $this->assertEquals(Platform::class, $result->roleable_type);
        $this->assertDatabaseHas('entity_roles', [
            'name' => 'manager',
            'roleable_id' => $platform->id,
            'roleable_type' => Platform::class
        ]);
    }

    /**
     * Test createPartnerRole creates new role
     */
    public function test_create_partner_role_creates_new_role()
    {
        // Arrange
        $partner = Partner::factory()->create();
        $user = User::factory()->create();
        $creator = User::factory()->create();

        $data = [
            'name' => 'manager',
            'user_id' => $user->id,
            'created_by' => $creator->id
        ];

        // Act
        $result = $this->entityRoleService->createPartnerRole($partner->id, $data);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals('manager', $result->name);
        $this->assertEquals($partner->id, $result->roleable_id);
        $this->assertEquals(Partner::class, $result->roleable_type);
    }

    /**
     * Test updateRole updates existing role
     */
    public function test_update_role_updates_existing_role()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $role = EntityRole::factory()->create([
            'roleable_type' => Platform::class,
            'roleable_id' => $platform->id,
            'user_id' => $user1->id,
            'name' => 'original-name'
        ]);

        $updateData = [
            'name' => 'updated-name',
            'user_id' => $user2->id,
            'updated_by' => $user2->id
        ];

        // Act
        $result = $this->entityRoleService->updateRole($role->id, $updateData);

        // Assert
        $this->assertEquals('updated-name', $result->name);
        $this->assertEquals($user2->id, $result->user_id);
    }

    /**
     * Test updateRole throws exception when not found
     */
    public function test_update_role_throws_exception_when_not_found()
    {
        // Assert
        $this->expectException(ModelNotFoundException::class);

        // Act
        $this->entityRoleService->updateRole(99999, ['name' => 'test']);
    }

    /**
     * Test deleteRole deletes role
     */
    public function test_delete_role_deletes_role()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();
        $role = EntityRole::factory()->create([
            'roleable_type' => Platform::class,
            'roleable_id' => $platform->id,
            'user_id' => $user->id
        ]);

        // Act
        $result = $this->entityRoleService->deleteRole($role->id);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('entity_roles', ['id' => $role->id]);
    }

    /**
     * Test searchRolesByName finds roles by name
     */
    public function test_search_roles_by_name_finds_roles()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();

        EntityRole::factory()->create([
            'roleable_type' => Platform::class,
            'roleable_id' => $platform->id,
            'user_id' => $user->id,
            'name' => 'unique-manager-role'
        ]);

        // Act
        $result = $this->entityRoleService->searchRolesByName('unique-manager');

        // Assert
        $this->assertGreaterThanOrEqual(1, $result->count());
    }

    /**
     * Test getFilteredRoles returns filtered results
     */
    public function test_get_filtered_roles_returns_filtered_results()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();

        EntityRole::factory()->count(5)->create([
            'roleable_type' => Platform::class,
            'roleable_id' => $platform->id,
            'user_id' => $user->id
        ]);

        // Act
        $result = $this->entityRoleService->getFilteredRoles(null, 'platform', 10);

        // Assert
        $this->assertGreaterThanOrEqual(5, $result->total());
    }

    /**
     * Test roleNameExistsForRoleable returns true when exists
     */
    public function test_role_name_exists_for_roleable_returns_true_when_exists()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();

        EntityRole::factory()->create([
            'roleable_type' => Platform::class,
            'roleable_id' => $platform->id,
            'user_id' => $user->id,
            'name' => 'existing-role'
        ]);

        // Act
        $result = $this->entityRoleService->roleNameExistsForRoleable(
            'existing-role',
            $platform->id,
            Platform::class
        );

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Test roleNameExistsForRoleable returns false when not exists
     */
    public function test_role_name_exists_for_roleable_returns_false_when_not_exists()
    {
        // Arrange
        $platform = Platform::factory()->create();

        // Act
        $result = $this->entityRoleService->roleNameExistsForRoleable(
            'non-existing-role',
            $platform->id,
            Platform::class
        );

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test userHasPlatformRole returns true when user has platform role
     */
    public function test_user_has_platform_role_returns_true_when_has_role()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();

        EntityRole::factory()->create([
            'roleable_type' => Platform::class,
            'roleable_id' => $platform->id,
            'user_id' => $user->id
        ]);

        // Act
        $result = $this->entityRoleService->userHasPlatformRole($user->id);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Test userHasPartnerRole returns true when user has partner role
     */
    public function test_user_has_partner_role_returns_true_when_has_role()
    {
        // Arrange
        $partner = Partner::factory()->create();
        $user = User::factory()->create();

        EntityRole::factory()->create([
            'roleable_type' => Partner::class,
            'roleable_id' => $partner->id,
            'user_id' => $user->id
        ]);

        // Act
        $result = $this->entityRoleService->userHasPartnerRole($user->id);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Test getUserPlatformIds returns platform IDs
     */
    public function test_get_user_platform_ids_returns_platform_ids()
    {
        // Arrange
        $platform1 = Platform::factory()->create();
        $platform2 = Platform::factory()->create();
        $user = User::factory()->create();

        EntityRole::factory()->create([
            'roleable_type' => Platform::class,
            'roleable_id' => $platform1->id,
            'user_id' => $user->id
        ]);
        EntityRole::factory()->create([
            'roleable_type' => Platform::class,
            'roleable_id' => $platform2->id,
            'user_id' => $user->id
        ]);

        // Act
        $result = $this->entityRoleService->getUserPlatformIds($user->id);

        // Assert
        $this->assertIsArray($result);
        $this->assertContains($platform1->id, $result);
        $this->assertContains($platform2->id, $result);
    }

    /**
     * Test getUserPartnerIds returns partner IDs
     */
    public function test_get_user_partner_ids_returns_partner_ids()
    {
        // Arrange
        $partner1 = Partner::factory()->create();
        $partner2 = Partner::factory()->create();
        $user = User::factory()->create();

        EntityRole::factory()->create([
            'roleable_type' => Partner::class,
            'roleable_id' => $partner1->id,
            'user_id' => $user->id
        ]);
        EntityRole::factory()->create([
            'roleable_type' => Partner::class,
            'roleable_id' => $partner2->id,
            'user_id' => $user->id
        ]);

        // Act
        $result = $this->entityRoleService->getUserPartnerIds($user->id);

        // Assert
        $this->assertIsArray($result);
        $this->assertContains($partner1->id, $result);
        $this->assertContains($partner2->id, $result);
    }

    /**
     * Test getAllPlatformPartnerUserIds returns user IDs
     */
    public function test_get_all_platform_partner_user_ids_returns_user_ids()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        EntityRole::factory()->create([
            'roleable_type' => Platform::class,
            'roleable_id' => $platform->id,
            'user_id' => $user1->id
        ]);
        EntityRole::factory()->create([
            'roleable_type' => Platform::class,
            'roleable_id' => $platform->id,
            'user_id' => $user2->id
        ]);

        // Act
        $result = $this->entityRoleService->getAllPlatformPartnerUserIds();

        // Assert
        $this->assertIsArray($result);
        $this->assertContains($user1->id, $result);
        $this->assertContains($user2->id, $result);
    }

    /**
     * Test getPlatformsWithRolesForUser returns platforms
     */
    public function test_get_platforms_with_roles_for_user_returns_platforms()
    {
        // Arrange
        $platform1 = Platform::factory()->create();
        $platform2 = Platform::factory()->create();
        $user = User::factory()->create();

        EntityRole::factory()->create([
            'roleable_type' => Platform::class,
            'roleable_id' => $platform1->id,
            'user_id' => $user->id
        ]);
        EntityRole::factory()->create([
            'roleable_type' => Platform::class,
            'roleable_id' => $platform2->id,
            'user_id' => $user->id
        ]);

        // Act
        $result = $this->entityRoleService->getPlatformsWithRolesForUser($user->id);

        // Assert
        $this->assertCount(2, $result);
        $this->assertTrue($result->contains('id', $platform1->id));
        $this->assertTrue($result->contains('id', $platform2->id));
    }

    /**
     * Test getUserRolesForPlatform returns role names
     */
    public function test_get_user_roles_for_platform_returns_role_names()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();

        EntityRole::factory()->create([
            'roleable_type' => Platform::class,
            'roleable_id' => $platform->id,
            'user_id' => $user->id,
            'name' => 'admin'
        ]);
        EntityRole::factory()->create([
            'roleable_type' => Platform::class,
            'roleable_id' => $platform->id,
            'user_id' => $user->id,
            'name' => 'moderator'
        ]);

        // Act
        $result = $this->entityRoleService->getUserRolesForPlatform($user->id, $platform->id);

        // Assert
        $this->assertIsArray($result);
        $this->assertContains('admin', $result);
        $this->assertContains('moderator', $result);
    }

    /**
     * Test getRoleByUserPlatformAndName returns specific role
     */
    public function test_get_role_by_user_platform_and_name_returns_role()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();

        $role = EntityRole::factory()->create([
            'roleable_type' => Platform::class,
            'roleable_id' => $platform->id,
            'user_id' => $user->id,
            'name' => 'owner'
        ]);

        // Act
        $result = $this->entityRoleService->getRoleByUserPlatformAndName($user->id, $platform->id, 'owner');

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($role->id, $result->id);
        $this->assertEquals('owner', $result->name);
    }

    /**
     * Test getRoleByPlatformAndName returns role
     */
    public function test_get_role_by_platform_and_name_returns_role()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();

        $role = EntityRole::factory()->create([
            'roleable_type' => Platform::class,
            'roleable_id' => $platform->id,
            'user_id' => $user->id,
            'name' => 'admin'
        ]);

        // Act
        $result = $this->entityRoleService->getRoleByPlatformAndName($platform->id, 'admin');

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($role->id, $result->id);
    }

    /**
     * Test getPlatformOwnerRole returns owner role
     */
    public function test_get_platform_owner_role_returns_owner_role()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $user = User::factory()->create();

        $ownerRole = EntityRole::factory()->create([
            'roleable_type' => Platform::class,
            'roleable_id' => $platform->id,
            'user_id' => $user->id,
            'name' => 'owner'
        ]);

        // Act
        $result = $this->entityRoleService->getPlatformOwnerRole($platform->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($ownerRole->id, $result->id);
        $this->assertEquals('owner', $result->name);
    }
}
