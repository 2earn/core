# Platform Partner Role Implementation

## Overview
A new `PlatformPartnerRole` model has been created to handle roles for both Platform and Partner models using a polymorphic relationship. This is completely separate from the Spatie Permission package's Role model.

## Files Created

### 1. Model: `app/Models/PlatformPartnerRole.php`
**Purpose**: Represents a role that can belong to either a Platform or Partner

**Fields**:
- `id` - Primary key
- `name` - Role name
- `roleable_id` - Foreign key to Platform or Partner
- `roleable_type` - Model type (Platform or Partner)
- `created_by` - User who created the role
- `updated_by` - User who last updated the role
- `created_at`, `updated_at` - Timestamps

**Relationships**:
- `roleable()` - Polymorphic relationship to Platform or Partner
- `creator()` - User who created the role
- `updater()` - User who updated the role

**Scopes**:
- `platformRoles()` - Get only platform roles
- `partnerRoles()` - Get only partner roles
- `searchByName($name)` - Search roles by name

### 2. Migration: `database/migrations/2026_01_15_120000_create_platform_partner_roles_table.php`
**Status**: ✅ Migrated successfully

**Table Structure**:
```sql
CREATE TABLE platform_partner_roles (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    roleable_id BIGINT UNSIGNED,
    roleable_type VARCHAR(255),
    created_by BIGINT UNSIGNED NULL,
    updated_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    INDEX (roleable_type, roleable_id),
    INDEX (name),
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
);
```

### 3. Service: `app/Services/PlatformPartnerRole/PlatformPartnerRoleService.php`
**Purpose**: Business logic for managing platform/partner roles

**Methods**:
- `getAllRoles()` - Get all roles
- `getRoleById($id)` - Get role by ID
- `getPlatformRoles()` - Get all platform roles
- `getPartnerRoles()` - Get all partner roles
- `getRolesForPlatform($platformId)` - Get roles for specific platform
- `getRolesForPartner($partnerId)` - Get roles for specific partner
- `createPlatformRole($platformId, $data)` - Create role for platform
- `createPartnerRole($partnerId, $data)` - Create role for partner
- `updateRole($id, $data)` - Update a role
- `deleteRole($id)` - Delete a role
- `searchRolesByName($name)` - Search roles by name
- `getFilteredRoles($searchTerm, $type, $perPage)` - Get paginated filtered roles
- `roleNameExistsForRoleable($name, $roleableId, $roleableType, $excludeId)` - Check if role name exists

### 4. Model Updates

**Platform Model** (`app/Models/Platform.php`):
Added relationship method:
```php
public function roles()
{
    return $this->morphMany(PlatformPartnerRole::class, 'roleable');
}
```

**Partner Model** (`app/Models/Partner.php`):
Added relationship method:
```php
public function roles()
{
    return $this->morphMany(PlatformPartnerRole::class, 'roleable');
}
```

## Usage Examples

### Create a Role for Platform
```php
use App\Services\PlatformPartnerRole\PlatformPartnerRoleService;

$roleService = new PlatformPartnerRoleService();

// Create platform role
$role = $roleService->createPlatformRole(1, [
    'name' => 'Manager',
    'created_by' => auth()->id()
]);
```

### Create a Role for Partner
```php
// Create partner role
$role = $roleService->createPartnerRole(1, [
    'name' => 'Administrator',
    'created_by' => auth()->id()
]);
```

### Get All Roles for a Platform
```php
$platform = Platform::find(1);
$roles = $platform->roles; // Using relationship

// Or using service
$roles = $roleService->getRolesForPlatform(1);
```

### Get All Roles for a Partner
```php
$partner = Partner::find(1);
$roles = $partner->roles; // Using relationship

// Or using service
$roles = $roleService->getRolesForPartner(1);
```

### Update a Role
```php
$roleService->updateRole($roleId, [
    'name' => 'Senior Manager',
    'updated_by' => auth()->id()
]);
```

### Delete a Role
```php
$roleService->deleteRole($roleId);
```

### Search and Filter Roles
```php
// Get paginated roles with search
$roles = $roleService->getFilteredRoles('Manager', 'platform', 20);

// Search by name
$roles = $roleService->searchRolesByName('Admin');

// Get only platform roles
$platformRoles = $roleService->getPlatformRoles();

// Get only partner roles
$partnerRoles = $roleService->getPartnerRoles();
```

## Key Features

1. **Polymorphic Relationship**: One role model can belong to either Platform or Partner
2. **Auditing**: Tracks who created and updated roles
3. **Separate from Spatie**: Completely independent from Spatie Permission package
4. **Service Layer**: Business logic separated in a dedicated service
5. **Scopes**: Easy filtering by platform/partner type
6. **Foreign Keys**: Proper database constraints for data integrity

## Database Relationships

```
Platform (1) ----< (Many) EntityRole
Partner (1)  ----< (Many) EntityRole
User (1)     ----< (Many) EntityRole (creator)
User (1)     ----< (Many) EntityRole (updater)
```

## Notes

- This role system is **not** related to Spatie Permission's role system
- It's designed for business roles specific to platforms and partners
- Uses Laravel's morphMany/morphTo for polymorphic relationships
- Includes proper indexing for performance
- Transaction support in service methods for data integrity
