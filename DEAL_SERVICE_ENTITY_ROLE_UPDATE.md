# Deal Service Entity Role Update

## Summary
Updated `DealService.php` to use the `entity_roles` table instead of directly checking `marketing_manager_id`, `financial_manager_id`, or `owner_id` columns in the platforms table.

## Changes Made

### Previous Implementation
```php
$query->whereHas('platform', function ($query) use ($userId) {
    $query->where(function ($q) use ($userId) {
        $q->where('marketing_manager_id', $userId)
            ->orWhere('financial_manager_id', $userId)
            ->orWhere('owner_id', $userId);
    });
});
```

### New Implementation
```php
$query->whereHas('platform', function ($query) use ($userId) {
    $query->whereHas('roles', function ($roleQuery) use ($userId) {
        $roleQuery->where('user_id', $userId);
    });
});
```

## Methods Updated

1. **getPartnerDeals()** - Line ~44
   - Filters deals based on user's entity roles in platforms

2. **getPartnerDealsCount()** - Line ~84
   - Counts deals accessible to user based on entity roles

3. **getPartnerDealById()** - Line ~108
   - Retrieves a single deal with permission check via entity roles

4. **userHasPermission()** - Line ~221
   - Checks if user has permission to access a deal via entity roles

5. **getFilteredDeals()** - Line ~307
   - Filters deals for index page based on entity roles

6. **getDashboardIndicators()** - Line ~469
   - Gets dashboard data for deals accessible via entity roles

7. **getAvailableDeals()** - Line ~744
   - Returns available deals based on user's entity roles

## Database Structure

The `entity_roles` table structure:
- `id` - Primary key
- `name` - Role name (e.g., 'marketing_manager', 'financial_manager', 'owner')
- `roleable_id` - ID of the entity (Platform or Partner)
- `roleable_type` - Type of entity ('App\Models\Platform' or 'App\Models\Partner')
- `user_id` - ID of the user who has this role
- `created_by` - User who created the role assignment
- `updated_by` - User who last updated the role assignment
- `created_at` - Timestamp
- `updated_at` - Timestamp

## Relationship

The Platform model has a polymorphic relationship:
```php
public function roles()
{
    return $this->morphMany(EntityRole::class, 'roleable');
}
```

## Benefits

1. **Flexibility** - Can add or remove roles without changing code
2. **Centralized** - All role management in one table
3. **Auditable** - Track who created/updated role assignments
4. **Scalable** - Easy to add new role types
5. **Consistent** - Same pattern used across the application

## Testing Recommendations

1. Test all methods with users who have entity roles assigned
2. Verify permissions work correctly for:
   - Marketing managers
   - Financial managers
   - Owners
   - Users with multiple roles
3. Ensure users without roles cannot access deals
4. Test pagination and filtering still work correctly

## Date
January 16, 2026
