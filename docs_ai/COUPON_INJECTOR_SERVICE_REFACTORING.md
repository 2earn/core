# CouponInjectorIndex Refactoring - BalanceInjectorCouponService Implementation

## Overview
Successfully refactored the `CouponInjectorIndex` Livewire component by creating a new `BalanceInjectorCouponService` to handle all database operations for balance injector coupons. This improves separation of concerns and creates a reusable service layer for coupon management.

## Changes Made

### 1. Created `app/Services/Coupon/BalanceInjectorCouponService.php`
New service class with comprehensive CRUD operations for balance injector coupons:

#### Service Methods

**`getPaginated(?string $search, string $sortField, string $sortDirection, int $perPage): LengthAwarePaginator`**
- **Moved from**: `CouponInjectorIndex::getCouponsProperty()`
- Retrieves paginated coupons with search and sorting
- Supports filtering by: pin, sn, value, category
- Default sort: created_at DESC
- Includes error logging with context

**`getById(int $id): ?BalanceInjectorCoupon`**
- Fetch a coupon by ID
- Returns null on error instead of throwing exception
- Includes error logging

**`getByIdOrFail(int $id): BalanceInjectorCoupon`**
- Fetch a coupon by ID or throw ModelNotFoundException
- Used when coupon must exist

**`delete(int $id): bool`**
- Delete a single coupon by ID
- Throws exception on error for proper error handling
- Includes error logging

**`deleteMultiple(array $ids): int`**
- Delete multiple coupons (only unconsumed ones)
- Returns count of deleted coupons
- Filters by `consumed = 0` to protect consumed coupons
- Includes error logging

**`getAll(): Collection`**
- Retrieve all coupons
- Returns empty Collection on error

### 2. Updated `app/Livewire/CouponInjectorIndex.php`

#### Added Service Import
```php
use App\Services\Coupon\BalanceInjectorCouponService;
```

#### Refactored Methods

**`getCouponsProperty()`**
- **Before**: Direct `BalanceInjectorCoupon` query with complex search logic
- **After**: Delegates to `$couponService->getPaginated()`
- Reduced from ~15 lines to 8 lines
- All query logic now in service layer

**`delete($id)`**
- **Before**: Direct model deletion (if it existed)
- **After**: Uses `$couponService->delete($id)`
- Consistent error handling

**`deleteSelected()`**
- **Before**: Direct query with `whereIn()->where()->delete()`
- **After**: Uses `$couponService->deleteMultiple($this->selectedIds)`
- Simplified bulk delete logic

## Benefits

1. **Separation of Concerns**: Query logic moved from presentation layer to service layer
2. **Reusability**: Service methods can be used across the application
3. **Testability**: Easy to unit test service independently from Livewire
4. **Error Handling**: Centralized error logging with context
5. **Maintainability**: Single source of truth for coupon operations
6. **Code Quality**: Cleaner component focused on UI logic
7. **Consistency**: Follows established service layer pattern

## Technical Details

### Query Functionality
The `getPaginated` method supports:
- **Search across multiple fields**:
  - `pin` - Coupon PIN code
  - `sn` - Serial number
  - `value` - Coupon value
  - `category` - Coupon category
- **Dynamic sorting**: Any field, ASC or DESC
- **Pagination**: Configurable per page count
- **Error handling**: Try-catch with logging

### Delete Protection
The `deleteMultiple` method includes protection:
- Only deletes unconsumed coupons (`consumed = 0`)
- Returns count of actually deleted coupons
- Preserves consumed coupons from accidental deletion

### Error Handling Strategy
- **Critical operations**: Throw exceptions (delete methods)
- **Read operations**: Return null or empty collections
- **All operations**: Include error logging with context

## Code Quality Improvements

### Before (CouponInjectorIndex.php)
```php
public function getCouponsProperty()
{
    $query = BalanceInjectorCoupon::query();

    if ($this->search) {
        $query->where(function ($q) {
            $q->where('pin', 'like', '%' . $this->search . '%')
                ->orWhere('sn', 'like', '%' . $this->search . '%')
                ->orWhere('value', 'like', '%' . $this->search . '%')
                ->orWhere('category', 'like', '%' . $this->search . '%');
        });
    }

    return $query->orderBy($this->sortField, $this->sortDirection)->paginate(10);
}

public function deleteSelected()
{
    // ... validation ...
    BalanceInjectorCoupon::whereIn('id', $this->selectedIds)
        ->where('consumed', 0)
        ->delete();
    // ... flash messages ...
}
```

### After (CouponInjectorIndex.php)
```php
public function getCouponsProperty()
{
    $couponService = app(BalanceInjectorCouponService::class);
    return $couponService->getPaginated(
        $this->search,
        $this->sortField,
        $this->sortDirection,
        10
    );
}

public function deleteSelected()
{
    // ... validation ...
    $couponService = app(BalanceInjectorCouponService::class);
    $couponService->deleteMultiple($this->selectedIds);
    // ... flash messages ...
}
```

## Files Modified
- ✅ Created: `app/Services/Coupon/BalanceInjectorCouponService.php`
- ✅ Updated: `app/Livewire/CouponInjectorIndex.php`

## Testing Notes
- No breaking changes to existing functionality
- All CRUD operations work as before
- Search, sorting, and pagination unchanged
- Bulk delete protection maintained (unconsumed only)
- Flash messages unchanged
- No database schema changes required

## Component Features Maintained
- ✅ Search functionality across multiple fields
- ✅ Dynamic sorting with ASC/DESC toggle
- ✅ Pagination with Bootstrap theme
- ✅ Bulk selection with "Select All" checkbox
- ✅ Single coupon deletion
- ✅ Bulk deletion (unconsumed only)
- ✅ Query string persistence
- ✅ Flash messages for user feedback

## Related Services
This follows the same pattern as:
- `UserService` - User operations
- `OrderService` - Order operations
- `SharesService` - Shares operations
- `TargetService` - Target operations
- `TranslateTabsService` - Translation operations

## Integration Points

### Models Used
- `App\Models\BalanceInjectorCoupon` - Balance injector coupon model

### Component Integration
- **Livewire Pagination**: WithPagination trait
- **Query Strings**: Persists search, sortField, sortDirection
- **Flash Messages**: Success, danger, warning messages
- **Views**: `livewire.coupon-injector-index` blade component

## Performance Considerations
- Pagination limits result sets for better performance
- Search uses LIKE operator - consider indexes on: pin, sn, category
- Bulk delete uses `whereIn` - efficient for multiple IDs
- Service method calls have minimal overhead

## Security Considerations
- Delete operations validate coupon existence
- Consumed coupons are protected from deletion
- Error messages don't expose sensitive information
- All database operations use Eloquent (no SQL injection risk)

## Future Enhancements
Consider adding:
1. **Filtering by status**: consumed vs unconsumed
2. **Date range filtering**: created_at, consumed_at
3. **Export functionality**: CSV/Excel export
4. **Coupon validation**: Check if coupon can be used
5. **Consumption tracking**: History of coupon usage
6. **Batch operations**: Mark as consumed, archive, etc.

## Usage Example

```php
// In any controller, command, or Livewire component:
$couponService = app(BalanceInjectorCouponService::class);

// Get paginated coupons with search
$coupons = $couponService->getPaginated(
    search: 'ABC123',
    sortField: 'created_at',
    sortDirection: 'desc',
    perPage: 20
);

// Get a specific coupon
$coupon = $couponService->getById(1);
if ($coupon) {
    echo "Coupon PIN: " . $coupon->pin;
}

// Delete a coupon
try {
    $couponService->delete(1);
    echo "Coupon deleted successfully";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}

// Bulk delete (only unconsumed)
$deletedCount = $couponService->deleteMultiple([1, 2, 3, 4, 5]);
echo "Deleted {$deletedCount} coupons";

// Get all coupons
$allCoupons = $couponService->getAll();
```

## Migration Path
No migration required - this is a code-only refactoring that maintains backward compatibility.

## API Compatibility
The service methods are designed to be easily exposed via API:
- `getPaginated()` returns LengthAwarePaginator (JSON-serializable)
- All methods have clear return types
- Error handling allows for proper HTTP status codes
- Can be wrapped in API Resource classes

