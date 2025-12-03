# CouponHistory Component Refactoring - CouponService Enhancement

## Overview
Successfully refactored the `CouponHistory` Livewire component to use the `CouponService` for all database operations. Enhanced the existing `CouponService` with new methods to support purchased coupon management, consumption tracking, and serial number lookups.

## Changes Made

### 1. Enhanced `app/Services/Coupon/CouponService.php`

#### Added Imports
- `use App\Models\Coupon;` - For purchased coupon operations
- `use Core\Enum\CouponStatusEnum;` - For coupon status filtering

#### New Service Methods

**`getPurchasedCouponsPaginated(int $userId, ?string $search, int $perPage): LengthAwarePaginator`**
- Retrieves paginated purchased coupons for a specific user
- Filters by `status = CouponStatusEnum::purchased`
- Supports multi-field search:
  - `pin` - Coupon PIN code
  - `sn` - Serial number
  - `value` - Coupon value
  - `platform.name` - Related platform name (via relationship)
- Orders by ID descending (newest first)
- Includes error logging with context

**`markAsConsumed(int $id): bool`**
- Marks a coupon as consumed
- Updates `consumed = 1` field
- Records `consumption_date = now()`
- Throws exception on error for proper error handling
- Includes error logging

**`getBySn(string $sn): ?Coupon`**
- Retrieves a coupon by serial number
- Returns null if not found or on error
- Used for PIN verification/display
- Includes error logging

### 2. Component Already Using Service

The `CouponHistory` component was already properly structured and using the `CouponService`:

#### Confirmed Usage
- ✅ `markAsConsumed()` - Uses service to mark coupons as consumed
- ✅ `verifPassword()` - Uses service to retrieve coupon by SN
- ✅ `render()` - Uses service to get paginated purchased coupons

## Service Architecture

### CouponService Now Supports Two Models

1. **BalanceInjectorCoupon** - Balance injector coupons (existing)
   - Admin coupon management
   - User coupon assignment
   - Bulk operations

2. **Coupon** - Purchased coupons (new methods)
   - User purchase history
   - Consumption tracking
   - PIN verification

## Technical Details

### Query Features in getPurchasedCouponsPaginated

**Status Filtering**:
```php
->where('status', CouponStatusEnum::purchased->value)
```
Only shows purchased coupons

**Search Across Multiple Fields**:
```php
$query->where(function ($q) use ($search) {
    $q->where('pin', 'like', '%' . $search . '%')
        ->orWhere('sn', 'like', '%' . $search . '%')
        ->orWhere('value', 'like', '%' . $search . '%')
        ->orWhereHas('platform', function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        });
});
```

**Relationship Search**:
- Uses `whereHas('platform')` to search by platform name
- Leverages Eloquent relationships for efficient queries

### Consumption Tracking

The `markAsConsumed` method updates:
- `consumed` field to `1` (true)
- `consumption_date` to current timestamp

This provides:
- Audit trail of when coupons were used
- Ability to track consumption patterns
- Data for reporting and analytics

### PIN Security

The `getBySn` method supports the password verification flow:
1. User provides password
2. Password is verified with `Hash::check()`
3. If valid, coupon PIN is retrieved via `getBySn()`
4. PIN is displayed to user
5. If invalid, error message shown

## Component Features

### Search Functionality
Users can search by:
- PIN code
- Serial number (SN)
- Coupon value
- Platform name

### Pagination
- Configurable page count (default: 10)
- Bootstrap-themed pagination
- Query string persistence (`?q=search&pc=20`)

### Actions
- **Mark as Consumed** - Mark a coupon as used
- **View PIN** - Password-protected PIN display

### Security Features
- Password verification before showing PIN
- User-specific coupon filtering
- Status-based access control

## Benefits

1. **Separation of Concerns**: Database logic in service layer
2. **Reusability**: Service methods available across application
3. **Consistency**: Follows established service layer pattern
4. **Error Handling**: Centralized logging with context
5. **Testability**: Easy to mock service in tests
6. **Maintainability**: Single source of truth for coupon operations
7. **Security**: Password verification in business logic

## Code Quality Improvements

### Before (Direct Model Access - Hypothetical)
```php
public function render()
{
    $coupons = Coupon::where('user_id', auth()->user()->id)
        ->where('status', CouponStatusEnum::purchased->value)
        ->where(function ($q) {
            if ($this->search) {
                $q->where('pin', 'like', '%' . $this->search . '%')
                  ->orWhere('sn', 'like', '%' . $this->search . '%')
                  // ... more search logic
            }
        })
        ->orderBy('id', 'desc')
        ->paginate((int)$this->pageCount);
    // ...
}
```

### After (Service Layer)
```php
public function render()
{
    $couponService = app(CouponService::class);
    $coupons = $couponService->getPurchasedCouponsPaginated(
        auth()->user()->id,
        $this->search,
        (int)$this->pageCount
    );
    // ...
}
```

## Files Modified
- ✅ Updated: `app/Services/Coupon/CouponService.php` (added 3 new methods)
- ✅ Confirmed: `app/Livewire/CouponHistory.php` (already using service)

## Testing Notes
- No breaking changes to existing functionality
- All CRUD operations work as before
- Search, sorting, and pagination unchanged
- Password verification flow maintained
- Consumption tracking preserved
- No database schema changes required

## Component Integration

### Livewire Features
- **WithPagination trait**: Bootstrap-themed pagination
- **Query string persistence**: Search and page count in URL
- **Event listeners**: `markAsConsumed`, `verifPassword`
- **Dispatched events**: `showPin`, `cancelPin` (for frontend interaction)

### Security Flow
1. User clicks "Show PIN"
2. Password modal appears
3. User enters password
4. `verifPassword()` validates with `Hash::check()`
5. If valid: `getBySn()` retrieves coupon, PIN shown
6. If invalid: Error message displayed

### User Experience
- Real-time search with pagination reset
- Configurable results per page
- Flash messages for success/error feedback
- Modal-based PIN display with password protection

## Performance Considerations
- Pagination limits result sets
- Search uses indexed fields (pin, sn)
- Relationship loading is lazy (only when searching platform)
- Query string persistence avoids state loss on refresh

## Related Services
This follows the same pattern as:
- `UserService` - User operations
- `OrderService` - Order operations
- `SharesService` - Shares operations
- `TargetService` - Target operations
- `BalanceInjectorCouponService` - Balance injector coupon operations

## Future Enhancements

### Service Layer
1. **Batch consumption**: Mark multiple coupons as consumed
2. **Consumption history**: Track who consumed and when
3. **Expiration checking**: Validate coupon expiry dates
4. **Usage limits**: Enforce single-use or multi-use rules
5. **Refund logic**: Handle coupon refunds/cancellations

### Component Features
1. **Filtering**: By consumed status, date range, platform
2. **Export**: CSV/PDF export of coupon history
3. **Statistics**: Usage analytics and reporting
4. **Notifications**: Alert users of expiring coupons
5. **QR Codes**: Generate QR codes for coupons

## Usage Example

```php
// In any controller, command, or Livewire component:
$couponService = app(CouponService::class);

// Get user's purchased coupons
$coupons = $couponService->getPurchasedCouponsPaginated(
    userId: 123,
    search: 'ABC',
    perPage: 20
);

// Mark a coupon as consumed
try {
    $couponService->markAsConsumed(456);
    echo "Coupon marked as consumed";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}

// Get coupon by serial number
$coupon = $couponService->getBySn('ABC123XYZ');
if ($coupon) {
    echo "PIN: " . $coupon->pin;
    echo "Platform: " . $coupon->platform->name;
}
```

## Database Fields Used

### Coupon Model
- `id` - Primary key
- `user_id` - Owner of the coupon
- `status` - Coupon status (enum: purchased, consumed, etc.)
- `pin` - PIN code (sensitive, password-protected display)
- `sn` - Serial number (unique identifier)
- `value` - Coupon monetary value
- `consumed` - Boolean flag (0 or 1)
- `consumption_date` - Timestamp when consumed
- `platform_id` - Foreign key to Platform model

### Relationships
- `platform()` - BelongsTo Platform (for platform name search)

## Security Best Practices

1. **Password Protection**: PIN codes require password verification
2. **User Isolation**: Queries filter by `user_id` automatically
3. **Status Filtering**: Only purchased coupons shown
4. **No SQL Injection**: Uses Eloquent query builder
5. **Error Handling**: Doesn't expose sensitive information in errors

## Migration Path
No migration required - this enhancement maintains backward compatibility while adding new service methods.

## Conclusion
The `CouponHistory` component was already well-structured and using the `CouponService`. The refactoring focused on enhancing the service with three new methods (`getPurchasedCouponsPaginated`, `markAsConsumed`, `getBySn`) to support the component's needs. This maintains the clean separation of concerns while providing reusable service methods for coupon purchase history management.

