# Platform Change Request System Documentation

## Overview
The Platform Change Request system allows platform updates to be submitted as change requests that require approval before being applied. This ensures all platform modifications go through a review process.

## Architecture

### Models

#### PlatformChangeRequest
**Location:** `app/Models/PlatformChangeRequest.php`

**Purpose:** Stores all change requests for platform updates before they are approved and applied.

**Fields:**
- `id`: Primary key
- `platform_id`: Foreign key to platforms table
- `changes`: JSON field storing the changes (old value vs new value for each field)
- `status`: Status of the request (pending, approved, rejected)
- `rejection_reason`: Text explaining why a request was rejected (nullable)
- `requested_by`: User ID who requested the change (nullable)
- `reviewed_by`: User ID who approved/rejected the change (nullable)
- `reviewed_at`: Timestamp when the request was reviewed (nullable)
- `created_at`: When the request was created
- `updated_at`: When the request was last updated

**Relationships:**
- `platform()`: BelongsTo Platform
- `requestedBy()`: BelongsTo User
- `reviewedBy()`: BelongsTo User

**Casts:**
- `changes`: array
- `reviewed_at`: datetime

### Database Migration
**File:** `database/migrations/2025_11_20_123304_create_platform_change_requests_table.php`

## Controller: PlatformPartnerController

### Updated Methods

#### 1. index() - List Platforms
**Endpoint:** `GET /api/partner/platforms`

**Changes:**
- Added `change_requests_count` to each platform
- Added `changeRequests` array with the 3 most recent change requests

**Response Structure:**
```json
{
  "status": true,
  "data": [
    {
      "id": 1,
      "name": "Platform Name",
      "type_change_requests_count": 2,
      "validation_requests_count": 1,
      "change_requests_count": 3,
      "typeChangeRequests": [...],
      "validationRequests": [...],
      "changeRequests": [
        {
          "id": 1,
          "platform_id": 1,
          "changes": {
            "name": {
              "old": "Old Name",
              "new": "New Name"
            },
            "description": {
              "old": "Old Description",
              "new": "New Description"
            }
          },
          "status": "pending",
          "requested_by": 5,
          "created_at": "2025-11-20T12:00:00"
        }
      ]
    }
  ],
  "total_platforms": 10
}
```

#### 2. show() - Get Single Platform
**Endpoint:** `GET /api/partner/platforms/{id}`

**Changes:**
- Added `change_requests` array with all change requests for the platform

**Response Structure:**
```json
{
  "status": true,
  "data": {
    "platform": {...},
    "type_change_requests": [...],
    "validation_requests": [...],
    "change_requests": [...]
  }
}
```

#### 3. update() - Update Platform
**Endpoint:** `PUT/PATCH /api/partner/platforms/{id}`

**Behavior Changed:**
- No longer directly updates the platform
- Creates a PlatformChangeRequest with status "pending"
- Stores all field changes in JSON format with old and new values

**Request Parameters:**
```json
{
  "name": "Updated Name",
  "description": "Updated Description",
  "link": "https://example.com",
  "enabled": true,
  "type": "1",
  "show_profile": true,
  "image_link": "https://example.com/image.jpg",
  "owner_id": 1,
  "marketing_manager_id": 2,
  "financial_manager_id": 3,
  "business_sector_id": 1,
  "updated_by": 5
}
```

**Response:**
```json
{
  "status": true,
  "message": "Platform change request submitted successfully. Awaiting approval.",
  "data": {
    "platform": {...},
    "change_request": {
      "id": 1,
      "platform_id": 1,
      "changes": {
        "name": {
          "old": "Old Name",
          "new": "Updated Name"
        },
        "description": {
          "old": "Old Description",
          "new": "Updated Description"
        }
      },
      "status": "pending",
      "requested_by": 5,
      "created_at": "2025-11-20T12:00:00"
    }
  }
}
```

**Validation:**
- Only fields that actually changed are included in the changes JSON
- If no changes detected, returns 422 error

## Platform Model Updates

### New Relationships Added
**Location:** `Core/Models/Platform.php`

```php
// Get all change requests for this platform
public function changeRequests(): HasMany

// Get the most recent pending change request
public function pendingChangeRequest()
```

## Approval Flow

### To Implement (Separate Component)

You will need to create an approval component/controller that:

1. **Lists Pending Change Requests**
   - Query: `PlatformChangeRequest::where('status', 'pending')->get()`
   - Display the platform details and the requested changes
   - Show who requested the change and when

2. **Approve Change Request**
   - Update the platform with the new values from `changes` JSON
   - Update the change request:
     ```php
     $changeRequest->update([
         'status' => 'approved',
         'reviewed_by' => auth()->id(),
         'reviewed_at' => now()
     ]);
     
     // Apply changes to platform
     foreach ($changeRequest->changes as $field => $change) {
         $platform->{$field} = $change['new'];
     }
     $platform->save();
     ```

3. **Reject Change Request**
   ```php
   $changeRequest->update([
       'status' => 'rejected',
       'rejection_reason' => $reason,
       'reviewed_by' => auth()->id(),
       'reviewed_at' => now()
   ]);
   ```

## Example Usage

### Frontend Integration

```javascript
// When updating a platform
const response = await axios.put('/api/partner/platforms/1', {
  name: "New Platform Name",
  description: "New Description",
  updated_by: currentUserId
});

// Response will indicate a change request was created
if (response.data.status === true) {
  console.log(response.data.message); 
  // "Platform change request submitted successfully. Awaiting approval."
}
```

### Admin Approval Component (To Be Created)

```javascript
// Fetch pending change requests
const requests = await axios.get('/api/admin/platform-change-requests/pending');

// Approve a request
await axios.post('/api/admin/platform-change-requests/1/approve', {
  reviewed_by: adminUserId
});

// Reject a request
await axios.post('/api/admin/platform-change-requests/1/reject', {
  rejection_reason: "Invalid changes",
  reviewed_by: adminUserId
});
```

## Benefits

1. **Audit Trail**: Complete history of all change requests
2. **Approval Workflow**: Changes require approval before being applied
3. **Change Tracking**: See exactly what fields changed and their old/new values
4. **User Attribution**: Track who requested and who approved changes
5. **Rejection Handling**: Ability to reject changes with reasons

## Next Steps

To complete the system, you need to:

1. Create an admin controller for reviewing change requests
2. Create routes for approve/reject actions
3. Create frontend components to display and manage change requests
4. Add notifications for request creators when requests are approved/rejected
5. Consider adding role-based permissions for who can approve changes

## Related Models

- `PlatformTypeChangeRequest`: Handles platform type changes
- `PlatformValidationRequest`: Handles new platform validation
- `PlatformChangeRequest`: Handles all other platform field updates (NEW)

## Database Schema

```sql
CREATE TABLE platform_change_requests (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    platform_id BIGINT UNSIGNED NOT NULL,
    changes JSON NOT NULL,
    status VARCHAR(255) DEFAULT 'pending',
    rejection_reason TEXT NULL,
    requested_by BIGINT UNSIGNED NULL,
    reviewed_by BIGINT UNSIGNED NULL,
    reviewed_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (platform_id) REFERENCES platforms(id) ON DELETE CASCADE,
    FOREIGN KEY (requested_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX (platform_id, status)
);
```

