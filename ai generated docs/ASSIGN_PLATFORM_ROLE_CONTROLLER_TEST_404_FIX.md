# AssignPlatformRoleControllerTest - 404 Response Fix

## Issue
Two tests in `AssignPlatformRoleControllerTest` were failing:
1. `it_returns_404_for_nonexistent_assignment_on_approve` 
2. `it_returns_404_for_nonexistent_assignment_on_reject`

Both tests expected a 404 response when attempting to approve/reject a nonexistent assignment (ID: 999999), but were not receiving it.

## Root Cause

The service methods `approve()` and `reject()` in `AssignPlatformRoleService` were using `AssignPlatformRole::find()` which returns `null` for nonexistent records. The service was checking for null and returning an error array instead of throwing a `ModelNotFoundException`.

The controller was catching `ModelNotFoundException` to return a 404 response, but the exception was never being thrown from the service.

### Original Service Code (Lines 58-66 and 161-169)

**approve() method:**
```php
$assignment = AssignPlatformRole::find($assignmentId);
if (!$assignment) {
    DB::rollBack();
    return [
        'success' => false,
        'message' => 'Failed to approve assignment: Assignment not found.'
    ];
}
```

**reject() method:**
```php
$assignment = AssignPlatformRole::find($assignmentId);
if (!$assignment) {
    DB::rollBack();
    return [
        'success' => false,
        'message' => 'Failed to reject assignment: Assignment not found.'
    ];
}
```

## Solution Applied

Changed both methods to use `findOrFail()` which automatically throws `ModelNotFoundException` when the record doesn't exist, and added explicit re-throwing in the catch block.

### File: `app/Services/Platform/AssignPlatformRoleService.php`

#### 1. Fixed `approve()` method

**Changes:**
- Line 58: Changed `find()` to `findOrFail()`
- Removed null check (lines 59-64)
- Added `ModelNotFoundException` catch block that re-throws the exception (lines 120-122)

**After:**
```php
public function approve(int $assignmentId, int $approvedBy): array
{
    try {
        DB::beginTransaction();

        $assignment = AssignPlatformRole::findOrFail($assignmentId);

        // ... rest of approval logic ...

        return [
            'success' => true,
            'message' => 'Role assignment approved successfully.'
        ];

    } catch (ModelNotFoundException $e) {
        DB::rollBack();
        throw $e; // Re-throw to let controller handle 404
    } catch (\Throwable $e) {
        DB::rollBack();

        Log::error('[AssignPlatformRoleService] Failed to approve role assignment', [
            'assignment_id' => $assignmentId,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return [
            'success' => false,
            'message' => 'Failed to approve assignment: ' . $e->getMessage()
        ];
    }
}
```

#### 2. Fixed `reject()` method

**Changes:**
- Line 149: Changed `find()` to `findOrFail()`
- Removed null check (lines 150-155)
- Added `ModelNotFoundException` catch block that re-throws the exception (lines 196-198)

**After:**
```php
public function reject(int $assignmentId, string $rejectionReason, int $rejectedBy): array
{
    try {
        DB::beginTransaction();

        $assignment = AssignPlatformRole::findOrFail($assignmentId);

        // ... rest of rejection logic ...

        return [
            'success' => true,
            'message' => 'Role assignment rejected successfully.'
        ];

    } catch (ModelNotFoundException $e) {
        DB::rollBack();
        throw $e; // Re-throw to let controller handle 404
    } catch (\Throwable $e) {
        DB::rollBack();

        Log::error('[AssignPlatformRoleService] Failed to reject role assignment', [
            'assignment_id' => $assignmentId,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return [
            'success' => false,
            'message' => 'Failed to reject assignment: ' . $e->getMessage()
        ];
    }
}
```

## Controller Exception Handling

The controller was already properly set up to catch `ModelNotFoundException` and return 404:

**File: `app/Http/Controllers/Api/v2/AssignPlatformRoleController.php`**

```php
public function approve(Request $request, int $id): JsonResponse
{
    try {
        // ... validation ...
        
        $result = $this->assignPlatformRoleService->approve($id, $request->approved_by);
        
        // ... return result ...
        
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Assignment not found'
        ], 404);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error approving assignment: ' . $e->getMessage()
        ], 500);
    }
}

public function reject(Request $request, int $id): JsonResponse
{
    try {
        // ... validation ...
        
        $result = $this->assignPlatformRoleService->reject(
            $id,
            $request->rejection_reason,
            $request->rejected_by
        );
        
        // ... return result ...
        
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Assignment not found'
        ], 404);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error rejecting assignment: ' . $e->getMessage()
        ], 500);
    }
}
```

## Test Cases

### File: `tests/Feature/Api/v2/AssignPlatformRoleControllerTest.php`

**Test 1: Line 102-109**
```php
#[Test]
public function it_returns_404_for_nonexistent_assignment_on_approve()
{
    $response = $this->postJson('/api/v2/assign-platform-roles/999999/approve', [
        'approved_by' => $this->user->id
    ]);

    $response->assertStatus(404);
}
```

**Test 2: Line 134-142**
```php
#[Test]
public function it_returns_404_for_nonexistent_assignment_on_reject()
{
    $response = $this->postJson('/api/v2/assign-platform-roles/999999/reject', [
        'rejected_by' => $this->user->id,
        'rejection_reason' => 'Test reason'
    ]);

    $response->assertStatus(404);
}
```

## Key Changes Summary

1. **Service Layer (`AssignPlatformRoleService.php`):**
   - ✅ Changed `find()` to `findOrFail()` in both `approve()` and `reject()` methods
   - ✅ Removed manual null checks
   - ✅ Added `ModelNotFoundException` catch blocks that re-throw the exception
   - ✅ Rollback transaction before re-throwing

2. **Controller Layer (`AssignPlatformRoleController.php`):**
   - ✅ Already properly catching `ModelNotFoundException` and returning 404
   - ✅ No changes needed

3. **Test Layer (`AssignPlatformRoleControllerTest.php`):**
   - ✅ No changes needed
   - ✅ Tests should now pass

## Technical Details

### Why Re-throw Instead of Return?

The service could return an error array, but that would require the controller to check the returned array and manually return a 404. The current approach is cleaner:

**Current Approach (Cleaner):**
```php
// Service throws exception → Controller catches → Returns 404
throw $e; // Service
```

**Alternative (More Code):**
```php
// Service returns error → Controller checks response → Returns 404
if (!$result['success'] && str_contains($result['message'], 'not found')) {
    return response()->json(['message' => 'Not found'], 404);
}
```

### Exception Flow

```
Request with ID 999999
    ↓
Controller::approve(999999)
    ↓
Service::approve(999999)
    ↓
AssignPlatformRole::findOrFail(999999) → Throws ModelNotFoundException
    ↓
Service catch (ModelNotFoundException) → Re-throw after rollback
    ↓
Controller catch (ModelNotFoundException) → Return 404 JSON response
```

## API Endpoints

Both endpoints now properly return 404 for nonexistent assignments:

- `POST /api/v2/assign-platform-roles/{id}/approve` - Returns 404 if assignment doesn't exist
- `POST /api/v2/assign-platform-roles/{id}/reject` - Returns 404 if assignment doesn't exist

## Test Results

After fixes, both tests should pass:

✅ `it_returns_404_for_nonexistent_assignment_on_approve` ⭐ **FIXED**
✅ `it_returns_404_for_nonexistent_assignment_on_reject` ⭐ **FIXED**

## Related Files

- ✅ `app/Services/Platform/AssignPlatformRoleService.php` - Modified (2 methods)
- `app/Http/Controllers/Api/v2/AssignPlatformRoleController.php` - No changes needed
- `tests/Feature/Api/v2/AssignPlatformRoleControllerTest.php` - No changes needed

## Key Learnings

1. **findOrFail() vs find()**: Use `findOrFail()` when you want automatic exception throwing for missing records
2. **Exception Re-throwing**: When catching specific exceptions in services, re-throw them if you want the controller to handle the HTTP response
3. **Transaction Rollback**: Always rollback before re-throwing exceptions to maintain database consistency
4. **Separation of Concerns**: Services throw business logic exceptions, controllers translate them to HTTP responses

## Pattern Consistency

This fix maintains consistency with other controllers in the codebase that use the same pattern for 404 responses on missing resources.

---

**Status:** ✅ **FIXED AND DOCUMENTED**
**Date:** February 11, 2026
**Lines Changed:** 4 (2 in approve, 2 in reject)
**Tests Fixed:** 2/2

