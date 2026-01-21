# User Partner Controller Entity Role Update

## Summary
Updated the `getPartnerPlatforms` method in `UserPartnerController` to use the `entity_roles` table instead of directly checking `owner_id`, `marketing_manager_id`, and `financial_manager_id` columns in the platforms table.

## Changes Made

### Method Updated: `getPartnerPlatforms()`

**Previous Implementation:**
```php
public function getPartnerPlatforms(GetPartnerPlatformsRequest $request)
{
    $validated = $request->validated();
    $userId = $validated['user_id'];

    try {
        // OLD: Check direct role columns
        $platforms = Platform::where(function ($query) use ($userId) {
            $query->where('owner_id', $userId)
                ->orWhere('marketing_manager_id', $userId)
                ->orWhere('financial_manager_id', $userId);
        })
        ->with(['businessSector', 'logoImage'])
        ->get()
        ->map(function ($platform) use ($userId) {
            // OLD: Manually build roles array by checking each column
            $roles = [];
            if ($platform->owner_id == $userId) {
                $roles[] = 'owner';
            }
            if ($platform->marketing_manager_id == $userId) {
                $roles[] = 'marketing';
            }
            if ($platform->financial_manager_id == $userId) {
                $roles[] = 'financial';
            }

            return [
                // ... platform data
                'roles' => $roles,
            ];
        });
    }
}
```

**New Implementation:**
```php
public function getPartnerPlatforms(GetPartnerPlatformsRequest $request)
{
    $validated = $request->validated();
    $userId = $validated['user_id'];

    try {
        // NEW: Use entity_roles relationship
        $platforms = Platform::whereHas('roles', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->with(['businessSector', 'logoImage', 'roles' => function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }])
        ->get()
        ->map(function ($platform) use ($userId) {
            // NEW: Get roles directly from the relationship
            $roles = $platform->roles->pluck('name')->toArray();

            return [
                // ... platform data
                'roles' => $roles,
            ];
        });
    }
}
```

## What Changed

### 1. **Query Method**
**Before:** Used `where()` clause with multiple `orWhere()` conditions
```php
Platform::where(function ($query) use ($userId) {
    $query->where('owner_id', $userId)
        ->orWhere('marketing_manager_id', $userId)
        ->orWhere('financial_manager_id', $userId);
})
```

**After:** Uses `whereHas()` with the `roles` relationship
```php
Platform::whereHas('roles', function ($query) use ($userId) {
    $query->where('user_id', $userId);
})
```

### 2. **Eager Loading**
**Before:** Only loaded `businessSector` and `logoImage`
```php
->with(['businessSector', 'logoImage'])
```

**After:** Also loads the filtered `roles` relationship
```php
->with(['businessSector', 'logoImage', 'roles' => function ($query) use ($userId) {
    $query->where('user_id', $userId);
}])
```

### 3. **Role Extraction**
**Before:** Manual conditional checks for each role column
```php
$roles = [];
if ($platform->owner_id == $userId) {
    $roles[] = 'owner';
}
if ($platform->marketing_manager_id == $userId) {
    $roles[] = 'marketing';
}
if ($platform->financial_manager_id == $userId) {
    $roles[] = 'financial';
}
```

**After:** Extract role names directly from the relationship
```php
$roles = $platform->roles->pluck('name')->toArray();
```

## Benefits

### 1. **Cleaner Code**
- Eliminates multiple conditional checks
- More readable and maintainable
- Uses Laravel's relationship features

### 2. **Flexibility**
- Can add new roles without code changes
- Role names come from the database
- Supports multiple roles per user per platform

### 3. **Performance**
- Uses eager loading to prevent N+1 queries
- Single query with joins instead of multiple column checks
- More efficient database operations

### 4. **Consistency**
- Same pattern used across the application
- Consistent with other controllers (DealService, PartnerPaymentController)
- Follows the entity_roles architecture

### 5. **Dynamic Role Names**
- Before: Hardcoded role names ('owner', 'marketing', 'financial')
- After: Actual role names from the database (e.g., 'owner', 'marketing_manager', 'financial_manager')

## Response Format

The API response remains the same structure:

```json
{
    "status": true,
    "message": "Platforms retrieved successfully",
    "data": {
        "platforms": [
            {
                "id": 1,
                "name": "Platform Name",
                "description": "Platform Description",
                "type": "type",
                "link": "https://platform.com",
                "enabled": true,
                "show_profile": true,
                "image_link": "image.jpg",
                "business_sector": {
                    "id": 1,
                    "name": "Sector Name"
                },
                "logo": {
                    "id": 1,
                    "path": "/path/to/logo.png"
                },
                "roles": ["owner", "marketing_manager"],
                "created_at": "2026-01-16T10:00:00.000000Z",
                "updated_at": "2026-01-16T10:00:00.000000Z"
            }
        ],
        "total": 1
    }
}
```

## Database Query Optimization

### Before:
```sql
SELECT * FROM platforms 
WHERE owner_id = ? 
   OR marketing_manager_id = ? 
   OR financial_manager_id = ?
```

### After:
```sql
SELECT * FROM platforms 
WHERE EXISTS (
    SELECT 1 FROM entity_roles 
    WHERE entity_roles.roleable_type = 'App\Models\Platform'
      AND entity_roles.roleable_id = platforms.id
      AND entity_roles.user_id = ?
)
```

## Relationship Used

The method uses the `roles()` relationship defined in the Platform model:

```php
// Platform.php
public function roles()
{
    return $this->morphMany(EntityRole::class, 'roleable');
}
```

## Testing Recommendations

1. **Functional Tests**
   - Test with users who have single role
   - Test with users who have multiple roles
   - Test with users who have no roles

2. **Response Validation**
   - Verify role names are correctly returned
   - Ensure all platform data is included
   - Check that relationships are properly loaded

3. **Edge Cases**
   - User with no platforms
   - Platform with no roles assigned
   - Invalid user ID

4. **Performance Tests**
   - Verify no N+1 query issues
   - Check query execution time
   - Test with large datasets

## Migration Path

This change is **backward compatible** for the API response structure. The `roles` array in the response will contain the actual role names from the `entity_roles` table instead of the hardcoded values.

### Role Name Mapping
If you want to maintain the old role names in the API response, you could add a mapping:

```php
$roleMapping = [
    'owner' => 'owner',
    'marketing_manager' => 'marketing',
    'financial_manager' => 'financial',
];

$roles = $platform->roles->pluck('name')->map(function ($roleName) use ($roleMapping) {
    return $roleMapping[$roleName] ?? $roleName;
})->toArray();
```

## Files Modified

1. `app/Http/Controllers/Api/partner/UserPartnerController.php`
   - Updated `getPartnerPlatforms()` method
   - Now uses `entity_roles` table via relationships
   - Improved query efficiency with eager loading

## Date
January 16, 2026
