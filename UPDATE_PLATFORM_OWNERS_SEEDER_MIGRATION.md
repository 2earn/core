# UpdatePlatformOwnersSeeder - Entity Role Migration

## Summary
Updated the `UpdatePlatformOwnersSeeder` to use the new `entity_roles` table instead of the removed `owner_id` field in the platforms table.

## Changes Made

### Before (Old Approach)
The seeder directly updated the `owner_id` column in the platforms table:

```php
public function run()
{
    $userId = 384;

    // Update all platforms to have owner_id = 384
    $updatedCount = Platform::query()->update([
        'owner_id' => $userId
    ]);

    $this->command->info("Successfully updated {$updatedCount} platform(s) to have owner_id = {$userId}");
}
```

**Issues:**
- ❌ Uses removed `owner_id` column
- ❌ Would fail since column no longer exists
- ❌ Doesn't follow new entity_roles architecture

### After (New Approach)
The seeder now creates entity role records for the owner role:

```php
public function run()
{
    $userId = 384;

    // Get all platforms
    $platforms = Platform::all();
    $createdCount = 0;

    foreach ($platforms as $platform) {
        // Create or update owner role for each platform
        $role = EntityRole::updateOrCreate(
            [
                'user_id' => $userId,
                'roleable_type' => Platform::class,
                'roleable_id' => $platform->id,
                'name' => 'owner'
            ],
            [
                'created_by' => $userId,
                'updated_by' => $userId
            ]
        );

        if ($role->wasRecentlyCreated) {
            $createdCount++;
        }
    }

    $this->command->info("Successfully assigned owner role to user {$userId} for {$createdCount} platform(s)");
    $this->command->info("Total platforms processed: {$platforms->count()}");
}
```

**Benefits:**
- ✅ Uses entity_roles table
- ✅ Follows new architecture
- ✅ Tracks created_by and updated_by
- ✅ Uses updateOrCreate for idempotency
- ✅ Provides detailed feedback

## How It Works

### 1. Fetch All Platforms
```php
$platforms = Platform::all();
```
Gets all platforms from the database.

### 2. Create Entity Role Records
```php
EntityRole::updateOrCreate(
    [
        'user_id' => $userId,
        'roleable_type' => Platform::class,
        'roleable_id' => $platform->id,
        'name' => 'owner'
    ],
    [
        'created_by' => $userId,
        'updated_by' => $userId
    ]
);
```

**First Array (Matching Criteria):**
- `user_id` - The user receiving the role
- `roleable_type` - Platform model class name
- `roleable_id` - Specific platform ID
- `name` - Role name ('owner')

**Second Array (Additional Data):**
- `created_by` - Who created the role assignment
- `updated_by` - Who last updated the role assignment

### 3. Track Statistics
```php
if ($role->wasRecentlyCreated) {
    $createdCount++;
}
```
Counts how many new roles were created vs. existing ones.

### 4. Output Results
```php
$this->command->info("Successfully assigned owner role to user {$userId} for {$createdCount} platform(s)");
$this->command->info("Total platforms processed: {$platforms->count()}");
```

## Running the Seeder

```bash
# Run the seeder
php artisan db:seed --class=UpdatePlatformOwnersSeeder
```

## Expected Output

```
Successfully assigned owner role to user 384 for 15 platform(s)
Total platforms processed: 25
```

This indicates:
- 15 new owner roles were created
- 10 platforms already had the owner role for this user
- 25 total platforms were processed

## Use Cases

### 1. Initial Setup
When setting up a new environment and you want to assign a default owner to all platforms:

```bash
php artisan db:seed --class=UpdatePlatformOwnersSeeder
```

### 2. Change Default Owner
Update the `$userId` variable in the seeder:

```php
$userId = 384; // Change this to the desired user ID
```

Then run:
```bash
php artisan db:seed --class=UpdatePlatformOwnersSeeder
```

### 3. Add Multiple Roles
Extend the seeder to add multiple role types:

```php
public function run()
{
    $userId = 384;
    $roles = ['owner', 'marketing_manager', 'financial_manager'];
    
    $platforms = Platform::all();
    
    foreach ($platforms as $platform) {
        foreach ($roles as $roleName) {
            EntityRole::updateOrCreate(
                [
                    'user_id' => $userId,
                    'roleable_type' => Platform::class,
                    'roleable_id' => $platform->id,
                    'name' => $roleName
                ],
                [
                    'created_by' => $userId,
                    'updated_by' => $userId
                ]
            );
        }
    }
}
```

## Database Records Created

For each platform, a record is created in the `entity_roles` table:

```sql
INSERT INTO entity_roles (
    user_id,
    roleable_type,
    roleable_id,
    name,
    created_by,
    updated_by,
    created_at,
    updated_at
) VALUES (
    384,
    'App\Models\Platform',
    1, -- platform_id
    'owner',
    384,
    384,
    NOW(),
    NOW()
);
```

## Verification

After running the seeder, verify the results:

```php
// In tinker
php artisan tinker

>>> $userId = 384;
>>> $roleCount = \App\Models\EntityRole::where('user_id', $userId)
        ->where('roleable_type', 'App\Models\Platform')
        ->where('name', 'owner')
        ->count();
>>> echo "User $userId has owner role in $roleCount platforms\n";
```

Or via SQL:

```sql
SELECT 
    er.user_id,
    er.name as role_name,
    p.id as platform_id,
    p.name as platform_name
FROM entity_roles er
JOIN platforms p ON er.roleable_id = p.id
WHERE er.user_id = 384
AND er.roleable_type = 'App\\Models\\Platform'
AND er.name = 'owner';
```

## Testing

### Test 1: First Run
```bash
php artisan db:seed --class=UpdatePlatformOwnersSeeder
# Expected: All platforms get new owner roles
```

### Test 2: Second Run (Idempotency)
```bash
php artisan db:seed --class=UpdatePlatformOwnersSeeder
# Expected: No new roles created, all existing roles remain
# Output should show 0 created
```

### Test 3: After Adding New Platform
```php
// Create a new platform
Platform::create([
    'name' => 'Test Platform',
    'description' => 'Test Description',
    // ... other fields
]);

// Run seeder
php artisan db:seed --class=UpdatePlatformOwnersSeeder
# Expected: 1 new role created for the new platform
```

## Related Files

- `app/Models/EntityRole.php` - EntityRole model
- `app/Models/Platform.php` - Platform model with roles relationship
- `database/migrations/2026_01_16_153617_remove_role_columns_from_platforms_table.php` - Migration that removed owner_id

## Notes

### Idempotency
The seeder uses `updateOrCreate` which makes it safe to run multiple times:
- First run: Creates all owner roles
- Subsequent runs: Updates existing roles (updates timestamps and updated_by)
- No duplicate roles are created

### Performance
For large numbers of platforms, consider using batch inserts:

```php
use Illuminate\Support\Facades\DB;

public function run()
{
    $userId = 384;
    $platforms = Platform::all();
    
    $roles = $platforms->map(function ($platform) use ($userId) {
        return [
            'user_id' => $userId,
            'roleable_type' => Platform::class,
            'roleable_id' => $platform->id,
            'name' => 'owner',
            'created_by' => $userId,
            'updated_by' => $userId,
            'created_at' => now(),
            'updated_at' => now()
        ];
    });
    
    // Insert in chunks to avoid memory issues
    $roles->chunk(100)->each(function ($chunk) {
        DB::table('entity_roles')->insertOrIgnore($chunk->toArray());
    });
}
```

## Date
January 16, 2026

## Status
✅ **Updated** - Seeder now works with entity_roles table
