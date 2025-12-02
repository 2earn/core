# Deal Validation Request Cancellation Feature

## Summary
Added the ability to cancel pending deal validation requests with complete tracking of who cancelled the request and why.

## Changes Made

### 1. DealValidationRequest Model (`app/Models/DealValidationRequest.php`)

#### Added Status Constants:
```php
public const STATUS_PENDING = 'pending';
public const STATUS_APPROVED = 'approved';
public const STATUS_REJECTED = 'rejected';
public const STATUS_CANCELLED = 'cancelled';
```

#### New Fillable Fields:
- `cancelled_reason` - Text field for cancellation reason
- `cancelled_by` - Foreign key to users table

#### New Methods:
- `getStatuses()` - Returns array of all available statuses
- `cancelledBy()` - Relationship to User who cancelled the request
- `scopeCancelled($query)` - Query scope for cancelled requests
- `isCancelled()` - Check if request is cancelled
- `canBeCancelled()` - Check if request can be cancelled (only pending requests)

#### Updated Methods:
- All scope methods now use status constants instead of hardcoded strings

### 2. Database Migration (`database/migrations/2025_12_01_101500_add_cancelled_fields_to_deal_validation_requests_table.php`)

#### New Columns:
- `cancelled_by` (nullable, foreign key to users)
- `cancelled_reason` (nullable text)

#### Status Enum Updated:
- Added 'cancelled' to the status enum field

### 3. DealPartnerController (`app/Http/Controllers/Api/partner/DealPartnerController.php`)

#### New Endpoint: `cancelValidationRequest(Request $request)`

**Method**: POST  
**Route**: `/api/partner/deals/validation/cancel`  
**Route Name**: `api_partner_deal_validation_cancel`

**Request Parameters**:
- `validation_request_id` (required, integer, exists in deal_validation_requests)
- `cancelled_by` (required, integer, exists in users)
- `cancelled_reason` (optional, string, max 500 characters)

**Response Codes**:
- `200 OK` - Validation request cancelled successfully
- `404 NOT_FOUND` - Validation request not found
- `403 FORBIDDEN` - Validation request cannot be cancelled (not pending)
- `422 UNPROCESSABLE_ENTITY` - Validation failed

**Validation Rules**:
- Only pending validation requests can be cancelled
- User must exist in the system
- Cancellation reason is optional but limited to 500 characters

**Logging**:
- Logs error when validation request not found
- Logs warning when trying to cancel non-pending request
- Logs info when validation request is successfully cancelled

### 4. API Routes (`routes/api.php`)

Added new route in the partner API group:
```php
Route::post('deals/validation/cancel', [DealPartnerController::class, 'cancelValidationRequest'])
    ->name('deal_validation_cancel');
```

## Usage Example

### Cancel a Validation Request

```bash
POST /api/partner/deals/validation/cancel
Content-Type: application/json

{
    "validation_request_id": 123,
    "cancelled_by": 45,
    "cancelled_reason": "Deal terms changed, resubmitting with new information"
}
```

**Success Response (200 OK)**:
```json
{
    "status": true,
    "message": "Validation request cancelled successfully",
    "data": {
        "id": 123,
        "deal_id": 78,
        "requested_by_id": 45,
        "status": "cancelled",
        "rejection_reason": null,
        "notes": "Original request notes",
        "cancelled_by": 45,
        "cancelled_reason": "Deal terms changed, resubmitting with new information",
        "created_at": "2025-12-01T10:15:00.000000Z",
        "updated_at": "2025-12-01T10:20:00.000000Z"
    }
}
```

**Error Response - Cannot Cancel (403 FORBIDDEN)**:
```json
{
    "status": "Failed",
    "message": "Only pending validation requests can be cancelled. Current status: approved"
}
```

**Error Response - Not Found (404 NOT_FOUND)**:
```json
{
    "status": "Failed",
    "message": "Validation request not found"
}
```

## Database Schema Changes

The `deal_validation_requests` table now has:
- `status` enum: `['pending', 'approved', 'rejected', 'cancelled']`
- `cancelled_by` (nullable): Foreign key to `users.id` with `onDelete('set null')`
- `cancelled_reason` (nullable): Text field for cancellation reason

## Testing Checklist

- [ ] Test cancelling a pending validation request
- [ ] Test attempting to cancel an approved validation request (should fail)
- [ ] Test attempting to cancel a rejected validation request (should fail)
- [ ] Test attempting to cancel an already cancelled validation request (should fail)
- [ ] Test with valid and invalid validation_request_id
- [ ] Verify logging is working correctly
- [ ] Test query scopes (scopeCancelled)

## Notes

- Only **pending** validation requests can be cancelled
- Cancelled requests cannot be reactivated
- The user who cancelled the request is tracked via `cancelled_by` foreign key
- If the user who cancelled is deleted, `cancelled_by` is set to null
- Cancellation reason is optional but recommended for audit trail
- All status checks now use constants instead of hardcoded strings for better maintainability

