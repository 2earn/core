# Platform Change Request - Quick Reference

## What Changed?

### The Problem
Previously, when a partner updated a platform via the API, the changes were applied immediately without any approval process.

### The Solution
Now, all platform update requests create a `PlatformChangeRequest` that stores the proposed changes and waits for admin approval.

## Key Points

✅ **Platform `update()` method now creates change requests instead of direct updates**
✅ **All changes are stored in JSON format with old and new values**
✅ **Index and show methods include change request data**
✅ **Approval flow needs to be implemented in admin panel**

## API Changes

### Update Endpoint Behavior
**Before:**
```
PUT /api/partner/platforms/{id} → Platform updated immediately
```

**After:**
```
PUT /api/partner/platforms/{id} → PlatformChangeRequest created (status: pending)
```

### Response Structure Change
**Before:**
```json
{
  "status": true,
  "message": "Platform updated successfully",
  "data": { /* updated platform */ }
}
```

**After:**
```json
{
  "status": true,
  "message": "Platform change request submitted successfully. Awaiting approval.",
  "data": {
    "platform": { /* original platform */ },
    "change_request": {
      "id": 1,
      "changes": {
        "name": {"old": "...", "new": "..."},
        "description": {"old": "...", "new": "..."}
      },
      "status": "pending"
    }
  }
}
```

## Changes Structure Example

```json
{
  "changes": {
    "name": {
      "old": "Old Platform Name",
      "new": "New Platform Name"
    },
    "description": {
      "old": "Old description",
      "new": "New description"
    },
    "link": {
      "old": "https://old-url.com",
      "new": "https://new-url.com"
    },
    "business_sector_id": {
      "old": 1,
      "new": 3
    }
  }
}
```

## Database

### New Table: `platform_change_requests`
- `id`: Primary key
- `platform_id`: Which platform
- `changes`: JSON (old/new values)
- `status`: pending/approved/rejected
- `rejection_reason`: Why rejected (nullable)
- `requested_by`: Who requested
- `reviewed_by`: Who approved/rejected
- `reviewed_at`: When reviewed
- `created_at`, `updated_at`

## Index Response Updates

Each platform now includes:
```json
{
  "id": 1,
  "name": "Platform",
  "type_change_requests_count": 2,
  "validation_requests_count": 1,
  "change_requests_count": 3,  // ← NEW
  "typeChangeRequests": [...],
  "validationRequests": [...],
  "changeRequests": [...]  // ← NEW (last 3)
}
```

## Show Response Updates

Platform detail now includes:
```json
{
  "platform": {...},
  "type_change_requests": [...],
  "validation_requests": [...],
  "change_requests": [...]  // ← NEW (all)
}
```

## Model Relationships

### Platform Model
```php
// Get all change requests
$platform->changeRequests

// Get latest pending change request
$platform->pendingChangeRequest
```

### PlatformChangeRequest Model
```php
// Get related platform
$changeRequest->platform

// Get who requested
$changeRequest->requestedBy

// Get who reviewed
$changeRequest->reviewedBy

// Access changes
$changeRequest->changes // Array of field changes
```

## Status Values

- `pending`: Waiting for approval
- `approved`: Approved and applied
- `rejected`: Rejected with reason

## TODO: Admin Approval Component

You need to create:
1. Admin route to list pending requests
2. Approve endpoint
3. Reject endpoint
4. Frontend UI to review and approve/reject

See `PLATFORM_CHANGE_REQUEST_DOCUMENTATION.md` for implementation details.

## Migration

Already run: ✅ `2025_11_20_123304_create_platform_change_requests_table.php`

## Files Modified

1. ✅ `app/Models/PlatformChangeRequest.php` - NEW MODEL
2. ✅ `database/migrations/2025_11_20_123304_create_platform_change_requests_table.php` - NEW MIGRATION
3. ✅ `app/Http/Controllers/Api/partner/PlatformPartnerController.php` - UPDATED
4. ✅ `Core/Models/Platform.php` - UPDATED (added relationships)

## Testing

```php
// Test creating a change request
$response = $this->putJson('/api/partner/platforms/1', [
    'name' => 'New Name',
    'updated_by' => 1
]);

$response->assertStatus(201);
$response->assertJson([
    'status' => true,
    'message' => 'Platform change request submitted successfully. Awaiting approval.'
]);

// Verify change request was created
$this->assertDatabaseHas('platform_change_requests', [
    'platform_id' => 1,
    'status' => 'pending',
    'requested_by' => 1
]);
```

