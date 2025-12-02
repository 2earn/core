# Platform Type Change Request Service Implementation

**Date:** December 1, 2025  
**Status:** ✅ Complete

## Overview

Created the `PlatformTypeChangeRequestService` and refactored the `PlatformTypeChangeRequests` Livewire component to use service layer architecture, completing the service layer pattern across all validation and change request modules.

## Changes Made

### 1. Created Service: `PlatformTypeChangeRequestService`

**File:** `app/Services/Platform/PlatformTypeChangeRequestService.php`

#### Implemented Methods:

1. **`getPendingRequests(?int $limit = null): Collection`**
   - Returns pending type change requests with optional limit
   - Eager loads `platform` and `requestedBy` relationships
   - Orders by `created_at` descending

2. **`getTotalPending(): int`**
   - Returns count of all pending type change requests

3. **`getPendingRequestsWithTotal(?int $limit = null): array`**
   - Returns both pending requests and total count
   - Useful for dashboard widgets

4. **`findRequest(int $requestId): PlatformTypeChangeRequest`**
   - Finds a type change request by ID
   - Throws `ModelNotFoundException` if not found

5. **`findRequestWithRelations(int $requestId, array $relations = []): PlatformTypeChangeRequest`**
   - Finds a type change request by ID with eager loaded relationships
   - Throws `ModelNotFoundException` if not found

6. **`approveRequest(int $requestId, int $reviewedBy): PlatformTypeChangeRequest`**
   - Validates that the request status is 'pending'
   - Updates the platform's `type` to the `new_type` value
   - Updates the platform's `updated_by` field
   - Updates request status to 'approved'
   - Sets `reviewed_by` and `reviewed_at` fields
   - Returns the updated request
   - Throws exception if request already processed

7. **`rejectRequest(int $requestId, int $reviewedBy, string $rejectionReason): PlatformTypeChangeRequest`**
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
   - Returns paginated type change requests using the filtered query
   - Uses `getFilteredQuery()` internally
   - Returns Laravel paginator instance

### 2. Updated Livewire Component: `PlatformTypeChangeRequests`

**File:** `app/Livewire/PlatformTypeChangeRequests.php`

#### Changes:

1. **Service Injection:**
   ```php
   protected PlatformTypeChangeRequestService $platformTypeChangeRequestService;

   public function boot(PlatformTypeChangeRequestService $platformTypeChangeRequestService)
   {
       $this->platformTypeChangeRequestService = $platformTypeChangeRequestService;
   }
   ```

2. **`approveRequest()` Method:**
   - **Before:** Had inline logic for updating platform type and request status (28 lines)
   - **After:** Calls `$this->platformTypeChangeRequestService->approveRequest()` (13 lines)
   - Removed duplicate status check (now in service)
   - Simplified transaction management
   - **Reduced by 15 lines**

3. **`rejectRequest()` Method:**
   - **Before:** Had inline logic for updating rejection status (20 lines)
   - **After:** Calls `$this->platformTypeChangeRequestService->rejectRequest()` (13 lines)
   - Removed duplicate status check (now in service)
   - Added proper transaction management
   - **Reduced by 7 lines**

4. **`render()` Method:**
   - **Before:** Built query with whereHas and when clauses inline (15 lines)
   - **After:** Calls `$this->platformTypeChangeRequestService->getPaginatedRequests()` (8 lines)
   - All query logic moved to service
   - **Reduced by 7 lines**

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
  - Event listeners

### 3. **Testability**
- Service methods are easier to unit test
- Can mock the service in component tests
- Business logic can be tested independently

### 4. **Maintainability**
- Changes to approval/rejection logic only need to be made in one place
- Reduced code duplication
- Clearer method signatures and documentation
- Consistent with other services

### 5. **Consistency**
- Follows the same pattern as:
  - `PendingDealValidationRequestsInlineService`
  - `PendingDealChangeRequestsInlineService`
  - `PlatformChangeRequestService`
  - `PlatformValidationRequestService`
- Consistent error handling across all services

## Code Metrics

**Component Code Reduction:**
- `approveRequest()`: 28 lines → 13 lines (15 lines reduced)
- `rejectRequest()`: 20 lines → 13 lines (7 lines reduced)
- `render()`: 15 lines → 8 lines (7 lines reduced)
- **Total: 29 lines of complex business logic moved to service**

## Model Fields

All these fields already exist in the `PlatformTypeChangeRequest` model:
- ✅ `platform_id` - Associated platform
- ✅ `old_type` - Previous platform type
- ✅ `new_type` - Requested new platform type
- ✅ `requested_by` - User who requested the change
- ✅ `reviewed_by` - User who approved/rejected
- ✅ `reviewed_at` - Timestamp of review
- ✅ `rejection_reason` - Reason for rejection
- ✅ `status` - Request status (pending, approved, rejected, cancelled)

## Usage Examples

### From a Controller:
```php
use App\Services\Platform\PlatformTypeChangeRequestService;

public function approveTypeChange($requestId)
{
    $service = app(PlatformTypeChangeRequestService::class);
    
    try {
        DB::beginTransaction();
        
        $request = $service->approveRequest($requestId, Auth::id());
        
        DB::commit();
        
        return response()->json([
            'message' => 'Platform type changed successfully',
            'request' => $request,
            'platform' => $request->platform
        ]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => $e->getMessage()], 400);
    }
}
```

### From a Console Command:
```php
use App\Services\Platform\PlatformTypeChangeRequestService;

public function handle(PlatformTypeChangeRequestService $service)
{
    $pendingRequests = $service->getPendingRequests(10);
    
    $this->table(
        ['ID', 'Platform', 'Old Type', 'New Type', 'Requested At'],
        $pendingRequests->map(function ($request) {
            return [
                $request->id,
                $request->platform->name,
                $request->old_type,
                $request->new_type,
                $request->created_at->format(config('app.date_format'))
            ];
        })
    );
    
    $this->info("Total pending: " . $service->getTotalPending());
}
```

### From a Background Job:
```php
use App\Services\Platform\PlatformTypeChangeRequestService;

class ProcessTypeChangeRequestsJob
{
    public function handle(PlatformTypeChangeRequestService $service)
    {
        $pendingRequests = $service->getPendingRequests(50);
        
        foreach ($pendingRequests as $request) {
            if ($this->shouldAutoApprove($request)) {
                try {
                    $service->approveRequest($request->id, $this->systemUserId);
                    Log::info("Auto-approved type change request {$request->id}");
                } catch (\Exception $e) {
                    Log::error("Failed to auto-approve: {$e->getMessage()}");
                }
            }
        }
    }
}
```

## Related Files

- ✅ `app/Services/Platform/PlatformTypeChangeRequestService.php` (NEW)
- ✅ `app/Livewire/PlatformTypeChangeRequests.php` (UPDATED)
- `app/Models/PlatformTypeChangeRequest.php`
- `Core/Models/Platform.php`
- `Core/Enum/PlatformType.php`

## Pattern Consistency

This implementation completes the service layer refactoring across all request types:

### Deal Services:
1. ✅ `PendingDealValidationRequestsInlineService` + `DealValidationRequests`
2. ✅ `PendingDealChangeRequestsInlineService` + `DealChangeRequests`

### Platform Services:
3. ✅ `PlatformChangeRequestService` + `PlatformChangeRequests`
4. ✅ `PlatformValidationRequestService` + `PlatformValidationRequests`
5. ✅ `PlatformTypeChangeRequestService` + `PlatformTypeChangeRequests` (THIS)

All services now follow the same pattern with:
- `findRequest()` and `findRequestWithRelations()` methods
- `approveRequest()` and `rejectRequest()` methods
- `getPaginatedRequests()` and `getFilteredQuery()` methods
- `getPendingRequests()` and `getTotalPending()` methods
- Consistent error handling with exceptions
- Consistent field updates (`reviewed_by`, `reviewed_at`, `updated_by`)

## Testing Recommendations

### Unit Tests for Service:
```php
test('approveRequest updates platform type and request status')
test('approveRequest throws exception if request already processed')
test('rejectRequest updates rejection reason and status')
test('rejectRequest throws exception if request already processed')
test('getFilteredQuery applies status filter correctly')
test('getFilteredQuery applies search filter correctly')
test('getPaginatedRequests returns paginated results')
test('getPendingRequests limits results when specified')
```

### Integration Tests for Livewire:
```php
test('approveRequest calls service and shows success message')
test('rejectRequest validates rejection reason')
test('render displays filtered requests')
test('error handling displays appropriate messages')
test('search filters requests by platform name')
test('status filter shows correct requests')
```

## Notes

- The service throws exceptions for invalid states, which are caught by the Livewire component
- Transaction management (DB::beginTransaction/commit/rollBack) remains in the Livewire component
- Logging is kept in the Livewire component for better context with request IDs
- Flash messages are handled at the component level for better UX
- All query building logic is now centralized in the service layer
- The service uses status constants from the model (`STATUS_PENDING`, `STATUS_APPROVED`, `STATUS_REJECTED`)

## Completion Status

This completes the **Service Layer Refactoring Project**. All validation and change request modules now use consistent service layer architecture:

- ✅ Deal Validation Requests
- ✅ Deal Change Requests  
- ✅ Platform Validation Requests
- ✅ Platform Change Requests
- ✅ Platform Type Change Requests

**Total Services Created/Updated:** 5  
**Total Components Refactored:** 5  
**Total Lines of Business Logic Moved to Services:** ~160 lines  
**Total Documentation Created:** 4 comprehensive guides

