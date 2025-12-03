# Trading Component Refactoring - SharesService Implementation

## Overview
Successfully refactored the `Trading` Livewire component to use a new `SharesService` for all shares-related database operations. This improves separation of concerns and creates a reusable service layer for shares management across the application.

## Changes Made

### 1. Created `app/Services/SharesService.php`
New service class with three key methods:

#### `getSharesData()`
- Handles paginated shares data retrieval for a user
- Supports search functionality (ID, created_at, value)
- Supports sorting by any field
- Returns `LengthAwarePaginator` for pagination support

**Method Signature:**
```php
public function getSharesData(
    int $userId,
    ?string $search = null,
    string $sortField = 'id',
    string $sortDirection = 'desc',
    int $perPage = 10
): LengthAwarePaginator
```

#### `getUserSoldSharesValue()`
- Calculates total sold shares value for a user
- Uses balance_operation_id (default: 44)
- Returns float value with proper error handling

**Method Signature:**
```php
public function getUserSoldSharesValue(int $userId, int $balanceOperationId = 44): float
```

#### `getUserTotalPaid()`
- Calculates total amount paid by user for shares
- Uses balance_operation_id (default: 44)
- Returns float value with proper error handling

**Method Signature:**
```php
public function getUserTotalPaid(int $userId, int $balanceOperationId = 44): float
```

### 2. Updated `app/Livewire/Trading.php`

#### Added Service Import
```php
use App\Services\SharesService;
```

#### Refactored `getSharesData()` Method
- **Before**: 17 lines of direct `SharesBalances` model query logic
- **After**: 8 lines delegating to `SharesService`
- Simplified from complex query building to clean service call

#### Refactored `mount()` Method
- **Before**: Direct `SharesBalances::where()->selectRaw()` calls with complex aggregations
- **After**: Clean service method calls: `getUserSoldSharesValue()` and `getUserTotalPaid()`
- Reduced complexity and improved readability

## Benefits

1. **Separation of Concerns**: Database query logic moved from presentation layer to service layer
2. **Reusability**: Service methods can be used by other components, controllers, or API endpoints
3. **Testability**: Easier to unit test service methods independently from Livewire lifecycle
4. **Error Handling**: Centralized error logging in service layer
5. **Maintainability**: Changes to shares queries only need to be made in one place
6. **Code Quality**: Reduced complexity in Livewire component (removed ~20 lines of query logic)

## Technical Details

### Database Operations
All methods query the `SharesBalances` model with:
- `beneficiary_id` - The user's ID
- `balance_operation_id` - Operation type (default: 44 for sold shares)
- Aggregations using `SUM()` for calculations

### Search Functionality
The service supports searching across:
- Share ID
- Created date
- Share value

### Error Handling
All service methods include:
- Try-catch blocks
- Detailed error logging with context
- Graceful fallback values (0 for numeric methods)
- Exception re-throwing for query errors

## Code Quality Improvements

### Before (Trading.php)
```php
public function mount()
{
    // ... other code ...
    $this->userSelledActionNumber = round(
        SharesBalances::where('balance_operation_id', 44)
            ->where('beneficiary_id', Auth()->user()->idUser)
            ->selectRaw('SUM(value) as total_sum')
            ->first()
            ->total_sum
    );
    
    $this->totalPaied = round(
        SharesBalances::where('balance_operation_id', 44)
            ->where('beneficiary_id', Auth()->user()->idUser)
            ->selectRaw('SUM(total_amount) as total_sum')
            ->first()
            ->total_sum, 
        3
    );
}
```

### After (Trading.php)
```php
public function mount()
{
    $sharesService = app(SharesService::class);
    
    // ... other code ...
    $this->userSelledActionNumber = round(
        $sharesService->getUserSoldSharesValue(Auth()->user()->idUser)
    );
    
    $this->totalPaied = round(
        $sharesService->getUserTotalPaid(Auth()->user()->idUser), 
        3
    );
}
```

## Files Modified
- ✅ Created: `app/Services/SharesService.php`
- ✅ Updated: `app/Livewire/Trading.php`

## Testing Notes
- No breaking changes to existing functionality
- All pagination, search, and sorting continue to work as before
- Share calculations remain accurate
- No database schema changes required
- Component interface unchanged

## Integration Points

### Models Used
- `App\Models\SharesBalances` - Main shares model

### Related Services
This follows the same pattern as:
- `UserService` - User query operations
- `OrderService` - Order query operations
- `RoleService` - Role operations
- `TranslateTabsService` - Translation operations

### Component Dependencies
The Trading component still integrates with:
- `BalancesManager` - For balance operations
- `SettingService` - For system settings
- `vip` Model - For VIP user data
- Helper functions: `getSettingIntegerParam()`, `getSelledActions()`, `actualActionValue()`, etc.

## Performance Considerations
- Uses pagination to limit result sets
- Aggregation queries (SUM) are indexed by beneficiary_id and balance_operation_id
- Search queries use LIKE operator - consider adding database indexes if needed
- Error logging includes context for debugging without performance impact

## Future Enhancements
Consider moving to service layer:
- VIP flash shares calculations
- Action value calculations
- Percentage calculations
- Date/time operations for flash period

## Usage Example

```php
// In any controller, command, or Livewire component:
$sharesService = app(SharesService::class);

// Get paginated shares data
$shares = $sharesService->getSharesData(
    userId: 123,
    search: 'keyword',
    sortField: 'created_at',
    sortDirection: 'desc',
    perPage: 20
);

// Get user statistics
$soldValue = $sharesService->getUserSoldSharesValue(123);
$totalPaid = $sharesService->getUserTotalPaid(123);

echo "User sold shares worth: $" . $soldValue;
echo "User paid total: $" . $totalPaid;
```

