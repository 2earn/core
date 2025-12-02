# Deal Change Request Cancellation Feature

## Summary
Added the ability to cancel pending deal change requests. Only requests with 'pending' status can be cancelled.

## Changes Made

### 1. DealChangeRequest Model (`app/Models/DealChangeRequest.php`)

#### Added Status Constants:
```php
public const STATUS_PENDING = 'pending';
public const STATUS_APPROVED = 'approved';
public const STATUS_REJECTED = 'rejected';
public const STATUS_CANCELLED = 'cancelled';
```

#### New Methods:
- `getStatuses()` - Returns array of all available statuses
- `isPending()` - Check if request is pending
- `isApproved()` - Check if request is approved
- `isRejected()` - Check if request is rejected
- `isCancelled()` - Check if request is cancelled
- `canBeCancelled()` - Check if request can be cancelled (only pending requests)
- `scopePending($query)` - Query scope for pending requests
- `scopeApproved($query)` - Query scope for approved requests
- `scopeRejected($query)` - Query scope for rejected requests
- `scopeCancelled($query)` - Query scope for cancelled requests

### 2. Database Migration

#### Migration File: `2025_12_01_102500_add_cancelled_status_to_deal_change_requests_table.php`

#### Status Field Updated:
- Updated status column comment to include 'cancelled' in the list of valid statuses
- No new columns added - only status values updated

### 3. DealPartnerController (`app/Http/Controllers/Api/partner/DealPartnerController.php`)

#### New Endpoint: `cancelChangeRequest(Request $request)`

**Method**: POST  
**Route**: `/api/partner/deals/change/cancel`  
**Route Name**: `api_partner_deal_change_cancel`

**Request Parameters**:
- `change_request_id` (required, integer, exists in deal_change_requests)

**Response Codes**:
- `200 OK` - Change request cancelled successfully
- `404 NOT_FOUND` - Change request not found
- `403 FORBIDDEN` - Change request cannot be cancelled (not pending)
- `422 UNPROCESSABLE_ENTITY` - Validation failed

**Validation Rules**:
- Only pending change requests can be cancelled
- Change request must exist in the system

**Logging**:
- Logs error when change request not found
- Logs warning when trying to cancel non-pending request
- Logs info when change request is successfully cancelled (tracks change_request_id and deal_id)

### 4. API Routes (`routes/api.php`)

Added new route in the partner API group:
```php
Route::post('deals/change/cancel', [DealPartnerController::class, 'cancelChangeRequest'])
    ->name('deal_change_cancel');
```

## Usage Example

### Cancel a Change Request

```bash
POST /api/partner/deals/change/cancel
Content-Type: application/json

{
    "change_request_id": 123
}
```

**Success Response (200 OK)**:
```json
{
    "status": true,
    "message": "Change request cancelled successfully",
    "data": {
        "id": 123,
        "deal_id": 78,
        "changes": {
            "name": {
                "old": "Old Deal Name",
                "new": "New Deal Name"
            },
            "price": {
                "old": 100,
                "new": 150
            }
        },
        "status": "cancelled",
        "rejection_reason": null,
        "requested_by": 45,
        "reviewed_by": null,
        "reviewed_at": null,
        "created_at": "2025-12-01T10:15:00.000000Z",
        "updated_at": "2025-12-01T10:20:00.000000Z"
    }
}
```

**Error Response - Cannot Cancel (403 FORBIDDEN)**:
```json
{
    "status": "Failed",
    "message": "Only pending change requests can be cancelled. Current status: approved"
}
```

**Error Response - Not Found (404 NOT_FOUND)**:
```json
{
    "status": "Failed",
    "message": "Change request not found"
}
```

## Database Schema Changes

The `deal_change_requests` table now has:
- `status` varchar(255): Values include `['pending', 'approved', 'rejected', 'cancelled']`

## Testing Checklist

- [ ] Test cancelling a pending change request
- [ ] Test attempting to cancel an approved change request (should fail)
- [ ] Test attempting to cancel a rejected change request (should fail)
- [ ] Test attempting to cancel an already cancelled change request (should fail)
- [ ] Test with valid and invalid change_request_id
- [ ] Verify logging is working correctly
- [ ] Test query scopes (scopeCancelled, scopePending, etc.)
- [ ] Verify status constants are used consistently throughout the codebase

## Notes

- Only **pending** change requests can be cancelled
- Cancelled requests cannot be reactivated
- All status checks now use constants instead of hardcoded strings for better maintainability
- The cancellation is tracked by the status change only
- When a change request is cancelled, the deal data remains unchanged (same as if the request was rejected)

## Related Features

This feature mirrors the cancellation functionality implemented for:
- **Platform Change Requests** - Cancel platform change requests
- **Platform Validation Requests** - Cancel platform validation requests
- **Deal Validation Requests** - Cancel deal validation requests

## API Consistency

All cancellation endpoints follow the same pattern:
- POST method
- Single required parameter: `<entity>_request_id`
- Returns the updated request object
- Only allows cancellation of pending requests
- Provides detailed error messages with appropriate HTTP status codes

