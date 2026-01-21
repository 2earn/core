# Entity Role Service Enhancement & Partner Payment Controller Update

## Summary
Moved the `verifyUserIsPartner` logic from raw database queries to the `EntityRoleService` using Eloquent models. Updated the `PartnerPaymentController` to use the service instead of inline DB queries.

## Changes Made

### 1. EntityRoleService - New Methods Added

Added four new methods to `EntityRoleService.php`:

#### `userHasPlatformRole(int $userId): bool`
Checks if a user has any role in platforms.

```php
public function userHasPlatformRole(int $userId): bool
{
    return EntityRole::where('user_id', $userId)
        ->where('roleable_type', Platform::class)
        ->exists();
}
```

#### `userHasPartnerRole(int $userId): bool`
Checks if a user has any role in partners.

```php
public function userHasPartnerRole(int $userId): bool
{
    return EntityRole::where('user_id', $userId)
        ->where('roleable_type', Partner::class)
        ->exists();
}
```

#### `getUserPlatformIds(int $userId): array`
Gets all platform IDs where user has a role.

```php
public function getUserPlatformIds(int $userId): array
{
    return EntityRole::where('user_id', $userId)
        ->where('roleable_type', Platform::class)
        ->pluck('roleable_id')
        ->toArray();
}
```

#### `getUserPartnerIds(int $userId): array`
Gets all partner IDs where user has a role.

```php
public function getUserPartnerIds(int $userId): array
{
    return EntityRole::where('user_id', $userId)
        ->where('roleable_type', Partner::class)
        ->pluck('roleable_id')
        ->toArray();
}
```

### 2. PartnerPaymentController - Refactored

**Before:**
```php
use Illuminate\Support\Facades\DB;

class PartnerPaymentController extends Controller
{
    protected $partnerPaymentService;

    public function __construct(PartnerPaymentService $partnerPaymentService)
    {
        $this->middleware('check.url');
        $this->partnerPaymentService = $partnerPaymentService;
    }

    private function verifyUserIsPartner(int $userId): bool
    {
        return DB::table('entity_roles')
            ->where('user_id', $userId)
            ->where('roleable_type', 'App\\Models\\Platform')
            ->exists();
    }
}
```

**After:**
```php
use App\Services\EntityRole\EntityRoleService;

class PartnerPaymentController extends Controller
{
    protected $partnerPaymentService;
    protected $entityRoleService;

    public function __construct(
        PartnerPaymentService $partnerPaymentService, 
        EntityRoleService $entityRoleService
    ) {
        $this->middleware('check.url');
        $this->partnerPaymentService = $partnerPaymentService;
        $this->entityRoleService = $entityRoleService;
    }

    private function verifyUserIsPartner(int $userId): bool
    {
        return $this->entityRoleService->userHasPlatformRole($userId);
    }
}
```

## Benefits

### 1. **Service Layer Pattern**
- Logic is now centralized in the service layer
- Controllers remain thin and focused on HTTP concerns
- Better separation of concerns

### 2. **Eloquent Models**
- Uses Eloquent instead of raw Query Builder
- Better type safety and IDE support
- Leverages Laravel's ORM features

### 3. **Reusability**
- The new methods in `EntityRoleService` can be used across the application
- No need to duplicate the role-checking logic
- Easy to add more role-related methods in the future

### 4. **Testability**
- Service can be mocked in tests
- Easier to unit test controllers
- Clear dependencies via constructor injection

### 5. **Maintainability**
- Single source of truth for role verification logic
- Changes to role-checking logic only need to be made in one place
- Clearer code intent with descriptive method names

## Additional Methods for Future Use

The following helper methods are now available throughout the application:

- `userHasPlatformRole($userId)` - Check if user is a platform partner
- `userHasPartnerRole($userId)` - Check if user is a partner
- `getUserPlatformIds($userId)` - Get all platforms where user has a role
- `getUserPartnerIds($userId)` - Get all partners where user has a role

## Usage Examples

### Check if user is a platform partner:
```php
if ($this->entityRoleService->userHasPlatformRole($userId)) {
    // User has at least one platform role
}
```

### Get all platforms where user has a role:
```php
$platformIds = $this->entityRoleService->getUserPlatformIds($userId);
// Returns [1, 3, 5] - array of platform IDs
```

### Check if user has partner roles:
```php
if ($this->entityRoleService->userHasPartnerRole($userId)) {
    // User has at least one partner role
}
```

## Backward Compatibility

- All existing functionality remains the same
- The API responses are unchanged
- Only the implementation details were refactored

## Testing Recommendations

1. **Unit Tests**
   - Test each new method in `EntityRoleService`
   - Mock `EntityRoleService` in controller tests

2. **Integration Tests**
   - Verify `verifyUserIsPartner` works correctly with real data
   - Test all controller methods still function properly

3. **Edge Cases**
   - User with no roles
   - User with multiple roles
   - Non-existent user IDs

## Files Modified

1. `app/Services/EntityRole/EntityRoleService.php`
   - Added 4 new public methods
   - +60 lines

2. `app/Http/Controllers/Api/partner/PartnerPaymentController.php`
   - Added `EntityRoleService` dependency injection
   - Refactored `verifyUserIsPartner()` method
   - Removed direct DB query usage

## Date
January 16, 2026
