# Platform Validation Request Service Implementation

**Date:** December 1, 2025  
**Status:** ✅ Complete

## Overview

Created the `PlatformValidationRequestService` and refactored the `PlatformValidationRequests` Livewire component to use service layer architecture, following the same pattern established with Deal services.

## Changes Made

### 1. Created Service: `PlatformValidationRequestService`

**File:** `app/Services/Platform/PlatformValidationRequestService.php`

#### Implemented Methods:

1. **`getPendingRequests(?int $limit = null): Collection`**
   - Returns pending validation requests with optional limit
   - Eager loads `platform` and `requestedBy` relationships
   - Orders by `created_at` descending

2. **`getTotalPending(): int`**
   - Returns count of all pending validation requests

3. **`getPendingRequestsWithTotal(?int $limit = null): array`**
   - Returns both pending requests and total count
   - Useful for dashboard widgets

4. **`findRequest(int $requestId): PlatformValidationRequest`**
   - Finds a validation request by ID
   - Throws `ModelNotFoundException` if not found

5. **`findRequestWithRelations(int $requestId, array $relations = []): PlatformValidationRequest`**
   - Finds a validation request by ID with eager loaded relationships
   - Throws `ModelNotFoundException` if not found

6. **`approveRequest(int $requestId, int $reviewedBy): PlatformValidationRequest`**
   - Validates that the request status is 'pending'
   - Enables the platform (`enabled = true`)
   - Updates the platform's `updated_by` field
   - Updates request status to 'approved'
   - Sets `reviewed_by` and `reviewed_at` fields
   - Returns the updated request
   - Throws exception if request already processed

7. **`rejectRequest(int $requestId, int $reviewedBy, string $rejectionReason): PlatformValidationRequest`**
   - Validates that the request status is 'pending'
   - Updates request status to 'rejected'
   - Sets the `rejection_reason`
   - Sets `reviewed_by`, `updated_by`, and `reviewed_at` fields
   - Returns the updated request
   - Throws exception if request already processed

8. **`getFilteredQuery(?string $statusFilter, ?string $search): Builder`**
   - Builds a query with filters for status and search
   - Applies eager loading for `platform`, `requestedBy`, and `reviewedBy` relationships
   - Orders by `created_at` descending
   - Filters by status (pending, approved, rejected, cancelled, or all)
   - Searches by platform name or ID

9. **`getPaginatedRequests(?string $statusFilter, ?string $search, int $perPage = 10): LengthAwarePaginator`**
   - Returns paginated validation requests using the filtered query
   - Uses `getFilteredQuery()` internally
   - Returns Laravel paginator instance

### 2. Updated Livewire Component: `PlatformValidationRequests`

**File:** `app/Livewire/PlatformValidationRequests.php`

#### Changes:

1. **Service Injection:**
   ```php
   protected PlatformValidationRequestService $platformValidationRequestService;

   public function boot(PlatformValidationRequestService $platformValidationRequestService)
   {
       $this->platformValidationRequestService = $platformValidationRequestService;
   }
   ```

2. **`approveRequest()` Method:**
   - **Before:** Had inline logic for enabling platform and updating request (22 lines)
   - **After:** Calls `$this->platformValidationRequestService->approveRequest()` (13 lines)
   - Removed duplicate status check (now in service)
   - Simplified transaction management

3. **`rejectRequest()` Method:**
   - **Before:** Had inline logic for updating rejection status (23 lines)
   - **After:** Calls `$this->platformValidationRequestService->rejectRequest()` (13 lines)
   - Removed duplicate status check (now in service)
   - Cleaner error handling

4. **`render()` Method:**
   - **Before:** Built query with whereHas and when clauses inline (15 lines)
   - **After:** Calls `$this->platformValidationRequestService->getPaginatedRequests()` (8 lines)
   - All query logic moved to service

## Benefits

### 1. **Separation of Concerns**
- Business logic is now in the service layer
- Livewire component focuses on UI interactions
- Clear separation between presentation and business logic

### 2. **Reusability**
- Service methods can be used from:
  - API controllers
  - Console commands
  - Other Livewire components
  - Background jobs
  - Webhooks

### 3. **Testability**
- Service methods are easier to unit test
- Can mock the service in component tests
- Business logic can be tested independently

### 4. **Maintainability**
- Changes to approval/rejection logic only need to be made in one place
- Reduced code duplication
- Clearer method signatures and documentation
- Consistent with other services (Deal, Platform Change)

### 5. **Consistency**
- Follows the same pattern as `PendingDealValidationRequestsInlineService`
- Follows the same pattern as `PlatformChangeRequestService`
- Consistent error handling across all validation services

## Model Fields

All these fields already exist in the `PlatformValidationRequest` model:
- ✅ `requested_by` - User who requested validation
- ✅ `reviewed_by` - User who approved/rejected
- ✅ `reviewed_at` - Timestamp of review
- ✅ `rejection_reason` - Reason for rejection
- ✅ `updated_by` - User who last updated the record

## Usage Example

### From a Controller:
```php
use App\Services\Platform\PlatformValidationRequestService;

public function approveValidation($requestId)
{
    $service = app(PlatformValidationRequestService::class);
    
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

### From a Console Command:
```php
use App\Services\Platform\PlatformValidationRequestService;

public function handle(PlatformValidationRequestService $service)
{
    $pendingRequests = $service->getPendingRequests(10);
    
    foreach ($pendingRequests as $request) {
        $this->info("Pending: {$request->platform->name}");
    }
    
    $this->info("Total pending: " . $service->getTotalPending());
}
```

## Related Files

- ✅ `app/Services/Platform/PlatformValidationRequestService.php` (NEW)
- ✅ `app/Livewire/PlatformValidationRequests.php` (UPDATED)
- `app/Models/PlatformValidationRequest.php`
- `Core/Models/Platform.php`

## Pattern Consistency

This implementation follows the same architecture as:
1. `PendingDealValidationRequestsInlineService` + `DealValidationRequests` component
2. `PendingDealChangeRequestsInlineService` + `DealChangeRequests` component
3. `PlatformChangeRequestService` + `PlatformChangeRequests` component

All validation/change request services now follow the same pattern with:
- `findRequest()` and `findRequestWithRelations()` methods
- `approveRequest()` and `rejectRequest()` methods
- `getPaginatedRequests()` and `getFilteredQuery()` methods
- Consistent error handling with exceptions
- Consistent field updates (`reviewed_by`, `reviewed_at`, `updated_by`)

## Testing Recommendations

### Unit Tests for Service:
```php
test('approveRequest enables platform and updates status')
test('approveRequest throws exception if request already processed')
test('rejectRequest updates rejection reason and status')
test('rejectRequest throws exception if request already processed')
test('getFilteredQuery applies status filter correctly')
test('getFilteredQuery applies search filter correctly')
test('getPaginatedRequests returns paginated results')
```

### Integration Tests for Livewire:
```php
test('approveRequest calls service and shows success message')
test('rejectRequest validates rejection reason')
test('render displays filtered requests')
test('error handling displays appropriate messages')
```

## Notes

- The service throws exceptions for invalid states, which are caught by the Livewire component
- Transaction management (DB::beginTransaction/commit/rollBack) remains in the Livewire component
- Logging is kept in the Livewire component for better context with request IDs
- Flash messages are handled at the component level for better UX
- All query building logic is now centralized in the service layer

