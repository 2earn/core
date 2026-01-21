# Migration: Remove Role Columns from Platforms Table

## Summary
Created migration to remove the `owner_id`, `marketing_manager_id`, and `financial_manager_id` columns from the `platforms` table. These columns are now replaced by the `entity_roles` table which provides a more flexible and centralized role management system.

## Migration Details

**File:** `database/migrations/2026_01_16_153617_remove_role_columns_from_platforms_table.php`

### Up Migration

The `up()` method performs the following operations:

1. **Drop Foreign Keys:**
   - `platforms_owner_id_foreign`
   - `platforms_marketing_manager_id_foreign`
   - `platforms_financial_manager_id_foreign`

2. **Drop Columns:**
   - `owner_id`
   - `marketing_manager_id`
   - `financial_manager_id`

```php
public function up(): void
{
    Schema::table('platforms', function (Blueprint $table) {
        // Drop foreign keys if they exist
        $table->dropForeign(['owner_id']);
        $table->dropForeign(['marketing_manager_id']);
        $table->dropForeign(['financial_manager_id']);
        
        // Drop the columns
        $table->dropColumn(['owner_id', 'marketing_manager_id', 'financial_manager_id']);
    });
}
```

### Down Migration

The `down()` method allows rollback by:

1. **Restoring Columns:**
   - `owner_id` (nullable, unsigned big integer)
   - `marketing_manager_id` (nullable, unsigned big integer)
   - `financial_manager_id` (nullable, unsigned big integer)

2. **Restoring Foreign Keys:**
   - All pointing to `users.id`
   - `onDelete('set null')` behavior

```php
public function down(): void
{
    Schema::table('platforms', function (Blueprint $table) {
        // Restore the columns
        $table->unsignedBigInteger('owner_id')->nullable()->after('image_link');
        $table->unsignedBigInteger('marketing_manager_id')->nullable()->after('owner_id');
        $table->unsignedBigInteger('financial_manager_id')->nullable()->after('marketing_manager_id');
        
        // Restore foreign keys
        $table->foreign('owner_id')
            ->references('id')
            ->on('users')
            ->onDelete('set null');
            
        $table->foreign('marketing_manager_id')
            ->references('id')
            ->on('users')
            ->onDelete('set null');
            
        $table->foreign('financial_manager_id')
            ->references('id')
            ->on('users')
            ->onDelete('set null');
    });
}
```

## Prerequisites

⚠️ **IMPORTANT:** Before running this migration, ensure:

1. **Data Migration Complete:**
   All existing role data from `owner_id`, `marketing_manager_id`, and `financial_manager_id` must be migrated to the `entity_roles` table.

2. **Code Updated:**
   All code that references these columns has been updated to use the `entity_roles` table instead:
   - ✅ DealService
   - ✅ PartnerPaymentController
   - ✅ UserPartnerController
   - ✅ PartnerPaymentManage (Livewire)
   - ✅ Platform Model
   - ✅ PendingDealValidationRequestsInlineService
   - ✅ PlatformService

3. **Backup Created:**
   Create a database backup before running this migration.

## Running the Migration

### Step 1: Check Current Database State
```bash
# View current platforms table structure
php artisan tinker
>>> Schema::getColumnListing('platforms');
```

### Step 2: Run Migration
```bash
php artisan migrate
```

### Step 3: Verify
```bash
# Check that columns are removed
php artisan tinker
>>> Schema::hasColumn('platforms', 'owner_id');
>>> Schema::hasColumn('platforms', 'marketing_manager_id');
>>> Schema::hasColumn('platforms', 'financial_manager_id');
# All should return false
```

## Rollback

If you need to rollback:

```bash
php artisan migrate:rollback
```

This will restore the three columns and their foreign keys.

## Data Migration Script (If Needed)

If you haven't migrated the data yet, here's a script to migrate existing role data to entity_roles:

```php
<?php

use App\Models\Platform;
use App\Models\EntityRole;
use Illuminate\Support\Facades\DB;

// Run this BEFORE the migration
DB::transaction(function () {
    $platforms = Platform::all();
    
    foreach ($platforms as $platform) {
        // Migrate owner
        if ($platform->owner_id) {
            EntityRole::firstOrCreate([
                'user_id' => $platform->owner_id,
                'roleable_type' => Platform::class,
                'roleable_id' => $platform->id,
                'name' => 'owner'
            ]);
        }
        
        // Migrate marketing manager
        if ($platform->marketing_manager_id) {
            EntityRole::firstOrCreate([
                'user_id' => $platform->marketing_manager_id,
                'roleable_type' => Platform::class,
                'roleable_id' => $platform->id,
                'name' => 'marketing_manager'
            ]);
        }
        
        // Migrate financial manager
        if ($platform->financial_manager_id) {
            EntityRole::firstOrCreate([
                'user_id' => $platform->financial_manager_id,
                'roleable_type' => Platform::class,
                'roleable_id' => $platform->id,
                'name' => 'financial_manager'
            ]);
        }
    }
});

echo "Data migration complete!\n";
```

Save this as `database/scripts/migrate_platform_roles_to_entity_roles.php` and run:
```bash
php database/scripts/migrate_platform_roles_to_entity_roles.php
```

## Verification Query

After migration, verify all roles are in entity_roles:

```sql
-- Check platform role distribution
SELECT 
    name as role_name,
    COUNT(*) as count
FROM entity_roles
WHERE roleable_type = 'App\\Models\\Platform'
GROUP BY name;

-- Check platforms without roles (should investigate these)
SELECT p.id, p.name
FROM platforms p
LEFT JOIN entity_roles er ON 
    er.roleable_type = 'App\\Models\\Platform' 
    AND er.roleable_id = p.id
WHERE er.id IS NULL;

-- Check for duplicate roles (same user, same platform, same role)
SELECT 
    user_id,
    roleable_id,
    name,
    COUNT(*) as duplicates
FROM entity_roles
WHERE roleable_type = 'App\\Models\\Platform'
GROUP BY user_id, roleable_id, name
HAVING COUNT(*) > 1;
```

## Impact Analysis

### Removed Columns:
- `platforms.owner_id` (unsignedBigInteger, nullable)
- `platforms.marketing_manager_id` (unsignedBigInteger, nullable)
- `platforms.financial_manager_id` (unsignedBigInteger, nullable)

### Replaced By:
```
entity_roles table:
- id (primary key)
- name (varchar) - e.g., 'owner', 'marketing_manager', 'financial_manager'
- roleable_type (varchar) - 'App\Models\Platform'
- roleable_id (unsignedBigInteger) - platform id
- user_id (unsignedBigInteger) - user id
- created_by (unsignedBigInteger, nullable)
- updated_by (unsignedBigInteger, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

### Benefits:
1. **Flexible:** Can add unlimited role types
2. **Normalized:** Proper many-to-many relationship
3. **Auditable:** Tracks who created/updated roles
4. **Scalable:** Easy to add new role types
5. **Consistent:** Same pattern for platforms and partners

## Testing After Migration

```php
use App\Models\Platform;
use App\Models\User;
use App\Models\EntityRole;

// Test 1: Check platform with roles
$platform = Platform::first();
$roles = $platform->roles;
echo "Platform {$platform->name} has " . $roles->count() . " roles\n";

// Test 2: Check user platforms
$user = User::first();
$platforms = Platform::whereHas('roles', function($q) use ($user) {
    $q->where('user_id', $user->id);
})->get();
echo "User {$user->name} has access to " . $platforms->count() . " platforms\n";

// Test 3: Check specific role
$hasOwnerRole = Platform::where('id', 1)
    ->whereHas('roles', function($q) use ($user) {
        $q->where('user_id', $user->id)
          ->where('name', 'owner');
    })
    ->exists();
echo "User " . ($hasOwnerRole ? "has" : "doesn't have") . " owner role\n";
```

## Troubleshooting

### Error: Foreign key doesn't exist
```
SQLSTATE[42000]: Syntax error or access violation: 1091 Can't DROP 'platforms_owner_id_foreign'; check that column/key exists
```

**Solution:** The foreign key might have a different name. Check actual constraint names:
```sql
SELECT CONSTRAINT_NAME 
FROM information_schema.KEY_COLUMN_USAGE 
WHERE TABLE_NAME = 'platforms' 
AND TABLE_SCHEMA = 'your_database_name'
AND COLUMN_NAME IN ('owner_id', 'marketing_manager_id', 'financial_manager_id');
```

Then update migration with actual constraint names.

### Error: Column doesn't exist
If columns were already removed, you can skip this migration:
```bash
php artisan migrate:status
# If already migrated, skip or mark as migrated
```

## Related Files

- `database/migrations/2026_01_15_120000_create_entity_roles_table.php` - Creates entity_roles table
- `database/migrations/2026_01_16_082234_add_user_id_to_entity_roles_table.php` - Adds user_id column
- `app/Models/Platform.php` - Platform model (columns removed from fillable)
- `app/Models/EntityRole.php` - EntityRole model

## Date
January 16, 2026

## Status
✅ **Migration Created** - Ready to run after data migration is complete
