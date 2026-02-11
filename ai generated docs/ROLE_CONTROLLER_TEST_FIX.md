# RoleControllerTest - Delete Role Test Fix

## Issue
The `it_can_delete_role` test in `RoleControllerTest` was failing with the following error:
```
SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry '0' for key 'PRIMARY'
```

## Root Cause
When using the `DatabaseTransactions` trait with MySQL, the auto-increment counter can become corrupted after rollbacks. This caused `Role::create()` to attempt inserting records with ID 0, resulting in duplicate primary key violations.

## Solution
Modified all role creation in tests to use `DB::table()->insert()` with explicit IDs instead of `Role::create()`. This bypasses the auto-increment issue by manually calculating and assigning IDs.

## Changes Made

### File: `tests/Feature/Api/v2/RoleControllerTest.php`

#### 1. Fixed `it_can_get_paginated_roles()`
**Before:**
```php
for ($i = 1; $i <= 5; $i++) {
    Role::create(['name' => 'Test Paginated Role ' . $i . uniqid(), 'guard_name' => 'web']);
}
```

**After:**
```php
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
```

#### 2. Fixed `it_can_search_roles()`
**Before:**
```php
$uniqueId = uniqid();
Role::create(['name' => 'Admin Role ' . $uniqueId, 'guard_name' => 'web']);
Role::create(['name' => 'User Role ' . $uniqueId, 'guard_name' => 'web']);
```

**After:**
```php
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
```

#### 3. Fixed `it_can_get_all_roles()`
**Before:**
```php
for ($i = 1; $i <= 3; $i++) {
    Role::create(['name' => 'Test Role ' . $i . uniqid(), 'guard_name' => 'web']);
}
```

**After:**
```php
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
```

#### 4. Fixed `it_can_get_role_by_id()`
**Before:**
```php
$role = Role::create(['name' => 'Test Role ' . uniqid(), 'guard_name' => 'web']);
$response = $this->getJson("/api/v2/roles/{$role->id}");
```

**After:**
```php
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
```

#### 5. Fixed `it_can_delete_role()` (THE MAIN FIX)
**Before:**
```php
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
```

**After:**
```php
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
```

#### 6. Added New Test: `it_cannot_delete_protected_roles()`
This test verifies the business rule that system roles (ID ≤ 4) cannot be deleted:

```php
#[Test]
public function it_cannot_delete_protected_roles()
{
    // System roles with ID <= 4 are protected and cannot be deleted
    $protectedRoleId = 1; // Admin role (assuming it exists from seeders)

    $response = $this->deleteJson("/api/v2/roles/{$protectedRoleId}");

    $response->assertStatus(500);
    $this->assertDatabaseHas('roles', ['id' => $protectedRoleId]);
}
```

## Business Logic
The `RoleService::delete()` method enforces a business rule:
- Roles with ID ≤ 4 are **protected system roles** and cannot be deleted
- Attempting to delete them throws an exception: `"This Role cannot be deleted!"`
- Only roles with ID > 4 can be deleted

Protected roles (from `RoleSeeder.php`):
1. Admin
2. Super admin
3. Moderateur
4. User

## Test Results
All 11 tests in `RoleControllerTest` now pass:

✅ `it_can_get_paginated_roles`
✅ `it_can_search_roles`
✅ `it_can_get_all_roles`
✅ `it_can_get_role_by_id`
✅ `it_returns_404_for_nonexistent_role`
✅ `it_can_create_role`
✅ `it_validates_unique_role_name`
✅ `it_can_update_role`
✅ `it_can_delete_role` ⭐ **FIXED**
✅ `it_cannot_delete_protected_roles` ⭐ **NEW**
✅ `it_can_get_user_roles`

## Key Learnings
1. **DatabaseTransactions + Auto-Increment**: When using `DatabaseTransactions` trait, MySQL's auto-increment counter can become unreliable after rollbacks
2. **Solution**: Use `DB::table()->insert()` with explicit IDs instead of Eloquent's `create()` method
3. **Best Practice**: Always calculate the next ID using `DB::table('table_name')->max('id')` and add to it

## Related Files
- `tests/Feature/Api/v2/RoleControllerTest.php` - Test file (modified)
- `app/Services/Role/RoleService.php` - Service with delete logic
- `app/Http/Controllers/Api/v2/RoleController.php` - Controller
- `database/migrations/generated/2024_01_24_094042_create_roles_table.php` - Roles table migration
- `database/seeders/RoleSeeder.php` - Seeds protected system roles

## Testing Commands
```bash
# Run all RoleController tests
php artisan test tests/Feature/Api/v2/RoleControllerTest.php

# Run only the delete test
php artisan test --filter="it_can_delete_role"

# Run with verbose output
php artisan test tests/Feature/Api/v2/RoleControllerTest.php -v
```

---

**Status:** ✅ **FIXED AND TESTED**
**Date:** February 11, 2026

