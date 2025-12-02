# Deal Validation Service Refactoring

**Date:** December 1, 2025  
**Status:** ✅ Complete

## Overview

Moved the business logic for validating deals and updating request statuses from the Livewire component to the `PendingDealValidationRequestsInlineService`, following the service layer pattern.

## Changes Made

### 1. Service Layer: `PendingDealValidationRequestsInlineService`

**File:** `app/Services/Deals/PendingDealValidationRequestsInlineService.php`

#### Added Methods:

1. **`approveRequest(int $requestId, int $reviewedBy): DealValidationRequest`**
   - Validates that the request status is 'pending'
   - Sets the deal's `validated` flag to `true`
   - Updates the deal's `updated_by` field
   - Updates request status to 'approved'
   - Sets `reviewed_by` and `reviewed_at` fields
   - Returns the updated request
   - Throws exception if request already processed

2. **`rejectRequest(int $requestId, int $reviewedBy, string $rejectionReason): DealValidationRequest`**
   - Validates that the request status is 'pending'
   - Updates request status to 'rejected'
   - Sets the `rejection_reason`
   - Sets `reviewed_by` and `reviewed_at` fields
   - Returns the updated request
   - Throws exception if request already processed

3. **`findRequest(int $requestId): DealValidationRequest`**
   - Finds a validation request by ID
   - Throws `ModelNotFoundException` if not found

4. **`findRequestWithRelations(int $requestId, array $relations = []): DealValidationRequest`**
   - Finds a validation request by ID with eager loaded relationships
   - Throws `ModelNotFoundException` if not found

5. **`getFilteredQuery(?string $statusFilter, ?string $search, ?int $userId, bool $isSuperAdmin): Builder`**
   - Builds a query with filters for status, search, and user permissions
   - Applies eager loading for `deal.platform` and `requestedBy` relationships
   - Orders by `created_at` descending
   - Filters by status (pending, approved, rejected, cancelled, or all)
   - Searches by deal name or requester name/email
   - Restricts results to platforms the user manages (unless super admin)

6. **`getPaginatedRequests(?string $statusFilter, ?string $search, ?int $userId, bool $isSuperAdmin, int $perPage = 10): LengthAwarePaginator`**
   - Returns paginated validation requests using the filtered query
   - Uses `getFilteredQuery()` internally
   - Returns Laravel paginator instance

#### Added Import:
- `use App\Models\Deal;` - Required for deal validation logic

### 2. Livewire Component: `DealValidationRequests`

**File:** `app/Livewire/DealValidationRequests.php`

#### Updated Methods:

1. **`approveRequest()`**
   - **Before:** Had inline logic for validating deal and updating request
   - **After:** Calls `$this->dealValidationRequestService->approveRequest()`
   - Simplified from ~30 lines to ~15 lines
   - Better error handling through service exceptions

2. **`rejectRequest()`**
   - **Before:** Had inline logic for updating rejection status
   - **After:** Calls `$this->dealValidationRequestService->rejectRequest()`
   - Simplified from ~25 lines to ~15 lines
   - Better error handling through service exceptions

#### Service Injection:
```php
protected PendingDealValidationRequestsInlineService $dealValidationRequestService;

public function boot(PendingDealValidationRequestsInlineService $dealValidationRequestService)
{
    $this->dealValidationRequestService = $dealValidationRequestService;
}
```

## Benefits

### 1. **Separation of Concerns**
- Business logic is now in the service layer
- Livewire component focuses on UI interactions and data flow
- Clear separation between presentation and business logic

### 2. **Reusability**
- `approveRequest()` and `rejectRequest()` can be used from:
  - API controllers
  - Console commands
  - Other Livewire components
  - Background jobs

### 3. **Testability**
- Service methods are easier to unit test
- Can mock the service in component tests
- Business logic can be tested independently

### 4. **Maintainability**
- Changes to approval/rejection logic only need to be made in one place
- Reduced code duplication
- Clearer method signatures and documentation

### 5. **Error Handling**
- Consistent exception throwing from service layer
- Validation logic centralized in service
- Better error messages

## Usage Example

```php
// In a controller or command
use App\Services\Deals\PendingDealValidationRequestsInlineService;

public function approveValidation($requestId)
{
    $service = app(PendingDealValidationRequestsInlineService::class);
    
    try {
        DB::beginTransaction();
        
        $request = $service->approveRequest($requestId, Auth::id());
        
        DB::commit();
        
        return response()->json([
            'message' => 'Request approved',
            'request' => $request
        ]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => $e->getMessage()], 400);
    }
}
```

## Model Fields Required

All these fields already exist in the `DealValidationRequest` model:
- ✅ `requested_by` - User who requested validation
- ✅ `reviewed_by` - User who approved/rejected
- ✅ `reviewed_at` - Timestamp of review

## Related Files

- `app/Services/Deals/PendingDealValidationRequestsInlineService.php`
- `app/Livewire/DealValidationRequests.php`
- `app/Models/DealValidationRequest.php`
- `app/Models/Deal.php`

## Testing Recommendations

1. **Unit Tests for Service:**
   ```php
   test('approveRequest validates the deal and updates status')
   test('approveRequest throws exception if request already processed')
   test('rejectRequest updates rejection reason and status')
   test('rejectRequest throws exception if request already processed')
   ```

2. **Integration Tests for Livewire:**
   ```php
   test('approveRequest calls service and shows success message')
   test('rejectRequest validates rejection reason')
   test('error handling displays appropriate messages')
   ```

## Notes

- The service throws exceptions for invalid states, which are caught by the Livewire component
- Transaction management (DB::beginTransaction/commit/rollBack) remains in the Livewire component
- Logging is kept in the Livewire component for better context
- Flash messages are handled at the component level

