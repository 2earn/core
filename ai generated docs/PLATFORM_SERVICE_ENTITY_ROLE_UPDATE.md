# Platform Service - Entity Role Update

## Summary
Updated the `PlatformService` to use the `entity_roles` table instead of directly checking `owner_id`, `marketing_manager_id`, and `financial_manager_id` columns when filtering platforms by user roles.

## Changes Made

### File: `app/Services/Platform/PlatformService.php`

Three methods were updated to use the entity_roles relationship:

### 1. `getPlatformsForPartner()` - Line 293

**Purpose:** Get all platforms where a user has a role (owner, marketing manager, or financial manager)

**Before:**
```php
public function getPlatformsForPartner(int $userId, ?int $page = 1, ?string $search = null, int $limit = 8): array
{
    try {
        $query = Platform::where(function ($q) use ($userId) {
            $q->where('owner_id', $userId)
                ->orWhere('marketing_manager_id', $userId)
                ->orWhere('financial_manager_id', $userId);
        });
        // ... rest of method
    }
}
```

**After:**
```php
public function getPlatformsForPartner(int $userId, ?int $page = 1, ?string $search = null, int $limit = 8): array
{
    try {
        $query = Platform::whereHas('roles', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
        // ... rest of method
    }
}
```

### 2. `userHasRoleInPlatform()` - Line 349

**Purpose:** Check if a user has any role in a specific platform

**Before:**
```php
public function userHasRoleInPlatform(int $userId, int $platformId): bool
{
    try {
        return Platform::where('id', $platformId)
            ->where(function ($q) use ($userId) {
                $q->where('owner_id', $userId)
                    ->orWhere('marketing_manager_id', $userId)
                    ->orWhere('financial_manager_id', $userId);
            })
            ->exists();
    } catch (\Exception $e) {
        // ... error handling
    }
}
```

**After:**
```php
public function userHasRoleInPlatform(int $userId, int $platformId): bool
{
    try {
        return Platform::where('id', $platformId)
            ->whereHas('roles', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->exists();
    } catch (\Exception $e) {
        // ... error handling
    }
}
```

### 3. `getPlatformForPartner()` - Line 372

**Purpose:** Get a single platform with relationships if the user has a role in it

**Before:**
```php
public function getPlatformForPartner(int $platformId, int $userId): ?Platform
{
    try {
        return Platform::where('id', $platformId)
            ->where(function ($q) use ($userId) {
                $q->where('owner_id', $userId)
                    ->orWhere('marketing_manager_id', $userId)
                    ->orWhere('financial_manager_id', $userId);
            })
            ->with([...])
            ->first();
    } catch (\Exception $e) {
        // ... error handling
    }
}
```

**After:**
```php
public function getPlatformForPartner(int $platformId, int $userId): ?Platform
{
    try {
        return Platform::where('id', $platformId)
            ->whereHas('roles', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->with([...])
            ->first();
    } catch (\Exception $e) {
        // ... error handling
    }
}
```

## Benefits

### 1. **Consistent Architecture**
- All platform-user role checks now use the same entity_roles table
- Consistent with other services (DealService, PartnerPaymentController, etc.)
- Single source of truth for role management

### 2. **Cleaner Queries**
**Before:** 3 OR conditions checking different columns
```sql
WHERE (owner_id = ? OR marketing_manager_id = ? OR financial_manager_id = ?)
```

**After:** Simple relationship check
```sql
WHERE EXISTS (
    SELECT 1 FROM entity_roles 
    WHERE user_id = ? 
    AND roleable_type = 'App\Models\Platform'
    AND roleable_id = platforms.id
)
```

### 3. **Flexibility**
- Can add unlimited role types without code changes
- Supports multiple users with same role on one platform
- Supports one user with multiple roles on one platform

### 4. **Better Code Reusability**
All three methods now use the same pattern:
```php
->whereHas('roles', function ($q) use ($userId) {
    $q->where('user_id', $userId);
})
```

## Usage Examples

### Get Partner Platforms
```php
$platformService = new PlatformService();

// Get all platforms where user has a role (paginated)
$result = $platformService->getPlatformsForPartner(
    userId: $userId,
    page: 1,
    search: 'Platform Name',
    limit: 10
);

// Returns:
[
    'platforms' => Collection<Platform>,
    'total_count' => 25
]
```

### Check User Permission
```php
// Check if user can access a specific platform
if ($platformService->userHasRoleInPlatform($userId, $platformId)) {
    // User has a role in this platform
    // Allow access to platform management
}
```

### Get Platform Details
```php
// Get platform with all relationships if user has access
$platform = $platformService->getPlatformForPartner($platformId, $userId);

if ($platform) {
    // User has role in this platform
    // Show platform details with deals, business sector, etc.
} else {
    // User doesn't have access
    // Show 403 or redirect
}
```

## Testing Recommendations

### 1. Test Partner Platform Access
```php
public function test_get_platforms_for_partner_returns_only_user_platforms()
{
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    
    $platform1 = Platform::factory()->create();
    $platform2 = Platform::factory()->create();
    
    // Give user role in platform1
    EntityRole::create([
        'user_id' => $user->id,
        'roleable_type' => Platform::class,
        'roleable_id' => $platform1->id,
        'name' => 'owner'
    ]);
    
    // Give otherUser role in platform2
    EntityRole::create([
        'user_id' => $otherUser->id,
        'roleable_type' => Platform::class,
        'roleable_id' => $platform2->id,
        'name' => 'marketing_manager'
    ]);
    
    $service = new PlatformService();
    $result = $service->getPlatformsForPartner($user->id);
    
    $this->assertCount(1, $result['platforms']);
    $this->assertEquals($platform1->id, $result['platforms']->first()->id);
    $this->assertEquals(1, $result['total_count']);
}
```

### 2. Test Role Verification
```php
public function test_user_has_role_in_platform()
{
    $user = User::factory()->create();
    $platform = Platform::factory()->create();
    
    EntityRole::create([
        'user_id' => $user->id,
        'roleable_type' => Platform::class,
        'roleable_id' => $platform->id,
        'name' => 'financial_manager'
    ]);
    
    $service = new PlatformService();
    
    $this->assertTrue($service->userHasRoleInPlatform($user->id, $platform->id));
}

public function test_user_without_role_in_platform()
{
    $user = User::factory()->create();
    $platform = Platform::factory()->create();
    
    $service = new PlatformService();
    
    $this->assertFalse($service->userHasRoleInPlatform($user->id, $platform->id));
}
```

### 3. Test Multiple Roles
```php
public function test_user_with_multiple_roles_in_same_platform()
{
    $user = User::factory()->create();
    $platform = Platform::factory()->create();
    
    // User has two roles in same platform
    EntityRole::create([
        'user_id' => $user->id,
        'roleable_type' => Platform::class,
        'roleable_id' => $platform->id,
        'name' => 'owner'
    ]);
    
    EntityRole::create([
        'user_id' => $user->id,
        'roleable_type' => Platform::class,
        'roleable_id' => $platform->id,
        'name' => 'marketing_manager'
    ]);
    
    $service = new PlatformService();
    $result = $service->getPlatformsForPartner($user->id);
    
    // Should return platform only once
    $this->assertCount(1, $result['platforms']);
    $this->assertTrue($service->userHasRoleInPlatform($user->id, $platform->id));
}
```

### 4. Test Search Functionality
```php
public function test_get_platforms_for_partner_with_search()
{
    $user = User::factory()->create();
    
    $platform1 = Platform::factory()->create(['name' => 'ABC Platform']);
    $platform2 = Platform::factory()->create(['name' => 'XYZ Platform']);
    
    EntityRole::create([
        'user_id' => $user->id,
        'roleable_type' => Platform::class,
        'roleable_id' => $platform1->id,
        'name' => 'owner'
    ]);
    
    EntityRole::create([
        'user_id' => $user->id,
        'roleable_type' => Platform::class,
        'roleable_id' => $platform2->id,
        'name' => 'owner'
    ]);
    
    $service = new PlatformService();
    $result = $service->getPlatformsForPartner($user->id, page: 1, search: 'ABC');
    
    $this->assertCount(1, $result['platforms']);
    $this->assertEquals($platform1->id, $result['platforms']->first()->id);
}
```

## Performance Considerations

### Query Comparison

**Before (3 column checks):**
```sql
SELECT * FROM platforms 
WHERE (
    owner_id = ? OR 
    marketing_manager_id = ? OR 
    financial_manager_id = ?
)
```

**After (relationship check):**
```sql
SELECT * FROM platforms 
WHERE EXISTS (
    SELECT 1 FROM entity_roles 
    WHERE entity_roles.roleable_type = 'App\Models\Platform'
    AND entity_roles.roleable_id = platforms.id
    AND entity_roles.user_id = ?
)
```

### Optimization Tips

1. **Ensure indexes exist:**
```sql
-- On entity_roles table
CREATE INDEX idx_entity_roles_user_roleable 
ON entity_roles(user_id, roleable_type, roleable_id);
```

2. **Use eager loading:**
```php
// Load roles with platforms to get role names
$platforms = $service->getPlatformsForPartner($userId);
foreach ($platforms['platforms'] as $platform) {
    $platform->load('roles');
    // Access $platform->roles
}
```

## Related Updates

This update is consistent with previous refactorings:
- ✅ DealService
- ✅ PartnerPaymentController  
- ✅ UserPartnerController
- ✅ PartnerPaymentManage (Livewire)
- ✅ Platform Model
- ✅ PendingDealValidationRequestsInlineService
- ✅ PlatformService (this file)

## Files Modified

1. **app/Services/Platform/PlatformService.php**
   - `getPlatformsForPartner()` - Line 293
   - `userHasRoleInPlatform()` - Line 349
   - `getPlatformForPartner()` - Line 372

## Date
January 16, 2026

## Status
✅ **Complete** - All platform-partner role checks now use entity_roles table
