# Deal Validation Requests - Reviewed By Field Fix

**Date**: December 1, 2025

## Problem

The application was throwing an error:
```
Unknown column 'reviewed_by' in deal_validation_requests
```

This occurred because the migration file `2025_12_01_133819_add_review_fields_to_deal_validation_requests_table.php` was empty when it was initially run, so the required columns were never added to the database.

## Solution

### 1. Updated Migration File

**File**: `database/migrations/2025_12_01_133819_add_review_fields_to_deal_validation_requests_table.php`

Added the following columns to the `deal_validation_requests` table:
- `requested_by` (bigint, nullable) - Foreign key to users table
- `reviewed_by` (bigint, nullable) - User who approved/rejected the request
- `reviewed_at` (timestamp, nullable) - When the request was reviewed
- `cancelled_by` (bigint, nullable) - User who cancelled the request
- `cancelled_reason` (text, nullable) - Reason for cancellation

Also updated the `status` enum to include 'cancelled' status.

### 2. Migration Process

1. Rolled back the empty migration
2. Updated the migration with proper fields
3. Re-ran the migration successfully

### 3. Database Schema (Final)

```sql
CREATE TABLE deal_validation_requests (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    deal_id BIGINT UNSIGNED NOT NULL,
    requested_by_id BIGINT UNSIGNED NOT NULL,
    requested_by BIGINT UNSIGNED NULL,
    reviewed_by BIGINT UNSIGNED NULL,
    reviewed_at TIMESTAMP NULL,
    status ENUM('pending','approved','rejected','cancelled') DEFAULT 'pending',
    rejection_reason TEXT NULL,
    cancelled_by BIGINT UNSIGNED NULL,
    cancelled_reason TEXT NULL,
    notes TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (deal_id) REFERENCES deals(id) ON DELETE CASCADE,
    FOREIGN KEY (requested_by_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (requested_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (cancelled_by) REFERENCES users(id) ON DELETE SET NULL,
    
    INDEX (status),
    INDEX (deal_id)
);
```

## Verification

✅ Migration ran successfully  
✅ All columns created with proper foreign keys  
✅ Service methods (`approveRequest`, `rejectRequest`) already use the fields correctly  
✅ Model has fields in `$fillable` array  
✅ No code errors detected  

## Related Files

- **Migration**: `database/migrations/2025_12_01_133819_add_review_fields_to_deal_validation_requests_table.php`
- **Model**: `app/Models/DealValidationRequest.php`
- **Service**: `app/Services/Deals/PendingDealValidationRequestsInlineService.php`
- **Livewire Component**: `app/Livewire/DealValidationRequests.php`
- **View**: `resources/views/livewire/deal-validation-requests.blade.php`

## Service Usage

The `PendingDealValidationRequestsInlineService` properly handles the reviewed_by field:

```php
// Approve request
public function approveRequest(int $requestId, int $reviewedBy): DealValidationRequest
{
    $request->status = 'approved';
    $request->reviewed_by = $reviewedBy;
    $request->reviewed_at = now();
    $request->save();
    return $request;
}

// Reject request
public function rejectRequest(int $requestId, int $reviewedBy, string $rejectionReason): DealValidationRequest
{
    $request->status = 'rejected';
    $request->rejection_reason = $rejectionReason;
    $request->reviewed_by = $reviewedBy;
    $request->reviewed_at = now();
    $request->save();
    return $request;
}
```

## Additional Changes

### Platform Type Change Request Service

As part of this update, the `PlatformTypeChangeRequest::create()` logic was moved to the service layer:

**File**: `app/Services/Platform/PlatformTypeChangeRequestService.php`

Added method:
```php
public function createRequest(int $platformId, int $oldType, int $newType, int $requestedBy): PlatformTypeChangeRequest
```

**Updated**: `app/Http/Controllers/Api/partner/PlatformPartnerController.php`

Changed from:
```php
$changeRequest = PlatformTypeChangeRequest::create([...]);
```

To:
```php
$changeRequest = $this->platformTypeChangeRequestService->createRequest(
    $platformId,
    $oldType,
    $newType,
    $updatedBy
);
```

This follows the service layer architecture pattern used throughout the application.

## Status

✅ **COMPLETE** - All issues resolved and database schema updated successfully.

