# Pending Deal Validation Requests Service - Entity Role Update

## Summary
Updated the `PendingDealValidationRequestsInlineService` to use the `entity_roles` table instead of directly checking `owner_id`, `marketing_manager_id`, and `financial_manager_id` columns when filtering deal validation requests by platform roles.

## Changes Made

### File: `app/Services/Deals/PendingDealValidationRequestsInlineService.php`

#### Method Updated: `applyFilters()`

**Before:**
```php
if (!$isSuperAdmin && $userId) {
    $query->whereHas('deal.platform', function ($q) use ($userId) {
        $q->where('financial_manager_id', $userId)
            ->orWhere('marketing_manager_id', $userId)
            ->orWhere('owner_id', $userId);
    });
}
```

**After:**
```php
if (!$isSuperAdmin && $userId) {
    $query->whereHas('deal.platform', function ($q) use ($userId) {
        $q->whereHas('roles', function ($roleQuery) use ($userId) {
            $roleQuery->where('user_id', $userId);
        });
    });
}
```

## What This Does

The `applyFilters()` method is used to filter deal validation requests based on user permissions. For non-super admin users, it restricts the results to only show validation requests for deals whose platforms the user has a role in.

### Query Chain Explanation

**Nested Relationships:**
1. `DealValidationRequest` → `deal` → `platform` → `roles`
2. The query checks if the platform has any entity role where the `user_id` matches

**SQL Query Comparison:**

**Before:**
```sql
SELECT * FROM deal_validation_requests
WHERE EXISTS (
    SELECT 1 FROM deals
    WHERE deals.id = deal_validation_requests.deal_id
    AND EXISTS (
        SELECT 1 FROM platforms
        WHERE platforms.id = deals.platform_id
        AND (
            platforms.financial_manager_id = ?
            OR platforms.marketing_manager_id = ?
            OR platforms.owner_id = ?
        )
    )
)
```

**After:**
```sql
SELECT * FROM deal_validation_requests
WHERE EXISTS (
    SELECT 1 FROM deals
    WHERE deals.id = deal_validation_requests.deal_id
    AND EXISTS (
        SELECT 1 FROM platforms
        WHERE platforms.id = deals.platform_id
        AND EXISTS (
            SELECT 1 FROM entity_roles
            WHERE entity_roles.roleable_type = 'App\Models\Platform'
            AND entity_roles.roleable_id = platforms.id
            AND entity_roles.user_id = ?
        )
    )
)
```

## Benefits

### 1. **Centralized Role Management**
- All role checks now use the `entity_roles` table
- Consistent with other parts of the application
- Single source of truth for user-platform relationships

### 2. **Flexibility**
- Can add unlimited roles per platform
- Role names are dynamic and data-driven
- No code changes needed for new role types

### 3. **Maintainability**
- Consistent pattern across all services
- Easier to understand and debug
- Reduces technical debt

### 4. **Better Data Model**
- Proper many-to-many relationship through entity_roles
- Supports multiple users with same role on one platform
- Supports one user with multiple roles on one platform

## Usage Context

This service is used in:
- Dashboard widgets showing pending validation requests
- Admin panels displaying validation queue
- Partner dashboards showing their platform's validation requests

### Method Flow
```php
// 1. Build base query
$query = DealValidationRequest::with(['deal.platform', 'requestedBy']);

// 2. Apply filters (status, search, etc.)
$query = $this->applyFilters($query, $statusFilter, $search, $userId, $isSuperAdmin);

// 3. Get results
return $query->paginate($perPage);
```

## Testing Recommendations

### 1. Unit Test - Partner Access
```php
public function test_partner_sees_only_their_platform_validation_requests()
{
    $partner = User::factory()->create();
    $otherPartner = User::factory()->create();
    
    $platform1 = Platform::factory()->create();
    $platform2 = Platform::factory()->create();
    
    EntityRole::create([
        'user_id' => $partner->id,
        'roleable_type' => Platform::class,
        'roleable_id' => $platform1->id,
        'name' => 'owner'
    ]);
    
    EntityRole::create([
        'user_id' => $otherPartner->id,
        'roleable_type' => Platform::class,
        'roleable_id' => $platform2->id,
        'name' => 'owner'
    ]);
    
    $deal1 = Deal::factory()->create(['platform_id' => $platform1->id]);
    $deal2 = Deal::factory()->create(['platform_id' => $platform2->id]);
    
    $request1 = DealValidationRequest::factory()->create(['deal_id' => $deal1->id]);
    $request2 = DealValidationRequest::factory()->create(['deal_id' => $deal2->id]);
    
    $service = new PendingDealValidationRequestsInlineService();
    $results = $service->getFilteredPaginatedRequests(
        statusFilter: 'pending',
        search: null,
        userId: $partner->id,
        isSuperAdmin: false,
        perPage: 10
    );
    
    $this->assertTrue($results->contains('id', $request1->id));
    $this->assertFalse($results->contains('id', $request2->id));
}
```

### 2. Integration Test - Super Admin
```php
public function test_super_admin_sees_all_validation_requests()
{
    $admin = User::factory()->create(['role' => 'super_admin']);
    
    $requests = DealValidationRequest::factory()->count(5)->create();
    
    $service = new PendingDealValidationRequestsInlineService();
    $results = $service->getFilteredPaginatedRequests(
        statusFilter: 'pending',
        search: null,
        userId: $admin->id,
        isSuperAdmin: true,
        perPage: 10
    );
    
    $this->assertEquals(5, $results->total());
}
```

### 3. Test - User Without Roles
```php
public function test_user_without_roles_sees_no_requests()
{
    $user = User::factory()->create();
    
    DealValidationRequest::factory()->count(3)->create();
    
    $service = new PendingDealValidationRequestsInlineService();
    $results = $service->getFilteredPaginatedRequests(
        statusFilter: 'pending',
        search: null,
        userId: $user->id,
        isSuperAdmin: false,
        perPage: 10
    );
    
    $this->assertEquals(0, $results->total());
}
```

## Performance Considerations

### Query Optimization
- The nested `whereHas` creates a subquery with EXISTS clause
- Uses indexed columns (`user_id`, `roleable_type`, `roleable_id`)
- Should perform similarly to the old query

### Future Optimization
If performance becomes an issue, consider:
1. Adding composite indexes on entity_roles table
2. Caching user platform IDs
3. Using eager loading strategically

```php
// Potential optimization with caching
public function getUserPlatformIds(int $userId): array
{
    return Cache::remember("user_{$userId}_platform_ids", 3600, function() use ($userId) {
        return EntityRole::where('user_id', $userId)
            ->where('roleable_type', Platform::class)
            ->pluck('roleable_id')
            ->toArray();
    });
}

// Then use whereIn instead of nested whereHas
$query->whereHas('deal', function($q) use ($platformIds) {
    $q->whereIn('platform_id', $platformIds);
});
```

## Related Files

This update complements previous refactorings:
- ✅ `DealService.php` - Deal filtering
- ✅ `PartnerPaymentController.php` - Payment verification
- ✅ `UserPartnerController.php` - Platform listing
- ✅ `PartnerPaymentManage.php` - Livewire component
- ✅ `Platform.php` - Model helper methods
- ✅ `PendingDealValidationRequestsInlineService.php` - This service

## Migration Notes

### Data Integrity Check
Ensure all existing platform managers have corresponding entity_roles:

```sql
-- Check for platform roles not in entity_roles
SELECT 
    'owner' as role_type,
    p.id as platform_id,
    p.owner_id as user_id
FROM platforms p
LEFT JOIN entity_roles er ON 
    er.user_id = p.owner_id 
    AND er.roleable_id = p.id 
    AND er.roleable_type = 'App\\Models\\Platform'
WHERE p.owner_id IS NOT NULL 
AND er.id IS NULL

UNION ALL

SELECT 
    'marketing_manager' as role_type,
    p.id as platform_id,
    p.marketing_manager_id as user_id
FROM platforms p
LEFT JOIN entity_roles er ON 
    er.user_id = p.marketing_manager_id 
    AND er.roleable_id = p.id 
    AND er.roleable_type = 'App\\Models\\Platform'
WHERE p.marketing_manager_id IS NOT NULL 
AND er.id IS NULL

UNION ALL

SELECT 
    'financial_manager' as role_type,
    p.id as platform_id,
    p.financial_manager_id as user_id
FROM platforms p
LEFT JOIN entity_roles er ON 
    er.user_id = p.financial_manager_id 
    AND er.roleable_id = p.id 
    AND er.roleable_type = 'App\\Models\\Platform'
WHERE p.financial_manager_id IS NOT NULL 
AND er.id IS NULL;
```

## Files Modified

1. **app/Services/Deals/PendingDealValidationRequestsInlineService.php**
   - Updated `applyFilters()` method
   - Changed platform role check to use entity_roles relationship

## Date
January 16, 2026

## Status
✅ **Complete** - Deal validation request filtering now uses entity_roles table
