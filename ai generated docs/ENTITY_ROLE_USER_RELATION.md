# Entity Role - User Relation Implementation

## Overview
Added a direct user relationship to the EntityRole model to track which user is assigned to each entity role.

## Changes Made

### 1. Database Migration
**File**: `database/migrations/2026_01_16_082234_add_user_id_to_entity_roles_table.php`

- Added `user_id` column (unsigned big integer, nullable)
- Added foreign key constraint referencing `users.id` with cascade on delete
- Added index on `user_id` for performance

### 2. EntityRole Model
**File**: `app/Models/EntityRole.php`

- Added `user_id` to the `$fillable` array
- Added `user()` relationship method that returns a `belongsTo` relationship with the User model

### 3. User Model  
**File**: `app/Models/User.php`

- Added `entityRoles()` relationship method that returns a `hasMany` relationship with the EntityRole model

## Relationships Structure

### EntityRole Model Relationships:
1. **roleable()** - Polymorphic relationship to Platform or Partner (the entity)
2. **user()** - BelongsTo User (the user assigned to this role)
3. **creator()** - BelongsTo User (the user who created this role record)
4. **updater()** - BelongsTo User (the user who last updated this role record)

### User Model Relationship:
- **entityRoles()** - HasMany EntityRole (all entity roles assigned to this user)

### Partner Model Relationship:
- **roles()** - MorphMany EntityRole (all roles for this partner)

### Platform Model Relationship:
- **roles()** - MorphMany EntityRole (all roles for this platform)

## Usage Examples

### Get all entity roles for a specific user:
```php
$user = User::find(1);
$roles = $user->entityRoles;
```

### Get the user assigned to an entity role:
```php
$entityRole = EntityRole::find(1);
$assignedUser = $entityRole->user;
```

### Create an entity role and assign it to a user:
```php
$partner = Partner::find(1);
$user = User::find(5);

$entityRole = EntityRole::create([
    'name' => 'Admin',
    'roleable_id' => $partner->id,
    'roleable_type' => Partner::class,
    'user_id' => $user->id,
    'created_by' => auth()->id(),
]);
```

### Get all roles for a platform/partner with their assigned users:
```php
$platform = Platform::find(1);
$rolesWithUsers = $platform->roles()->with('user')->get();
```

## Database Schema

### entity_roles table:
- `id` - Primary key
- `name` - Role name
- `roleable_id` - ID of the entity (Platform or Partner)
- `roleable_type` - Type of the entity (Platform or Partner)
- `user_id` - ID of the user assigned to this role (NEW)
- `created_by` - ID of the user who created this record
- `updated_by` - ID of the user who last updated this record
- `created_at` - Timestamp
- `updated_at` - Timestamp

## Notes
- The `user_id` is nullable to allow flexibility in role assignment
- The foreign key uses cascade delete, so if a user is deleted, their entity roles will also be deleted
- Indexes are added for better query performance on polymorphic and user relationships
