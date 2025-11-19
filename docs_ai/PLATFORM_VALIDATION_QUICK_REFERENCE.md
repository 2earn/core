# Platform Validation Request System - Quick Reference

## Quick Start

### For Developers

#### Create a New Platform (API)
```bash
POST /api/partner/platforms
Content-Type: application/json

{
  "name": "My Platform",
  "description": "Platform description",
  "type": "1",
  "owner_id": 1,
  "created_by": 1,
  "marketing_manager_id": 2,
  "financial_manager_id": 3,
  "business_sector_id": 1
}
```

**Result**: Platform created with `enabled = false` and validation request status = `pending`

#### Check Platform Status
```bash
GET /api/partner/platforms?user_id=1
```

Response includes `validation_request` object and request counts:
```json
{
  "validation_request": {
    "status": "pending|approved|rejected",
    "rejection_reason": "..."
  },
  "type_change_requests_count": 5,
  "validation_requests_count": 2
}
```

#### Show Platform Details
```bash
GET /api/partner/platforms/{id}?user_id=1
```

Response includes the 3 latest type change and validation requests:
```json
{
  "status": true,
  "data": {
    "platform": { ... },
    "type_change_requests": [
      { "id": 3, "status": "pending", ... },
      { "id": 2, "status": "rejected", ... },
      { "id": 1, "status": "approved", ... }
    ],
    "validation_requests": [
      { "id": 1, "status": "approved", ... }
    ]
  }
}
```

### For Admins

#### Access Validation Requests Interface
Navigate to the platform validation requests page in the admin panel or create a route for the Livewire component:

```php
// Add to web.php
Route::get('platform-validation-requests', \App\Livewire\PlatformValidationRequests::class)
    ->name('platform_validation_requests');
```

#### Approve via API
```bash
POST /api/partner/platform/validation/{validationRequestId}/approve
Content-Type: application/json

{
  "user_id": 1
}
```

#### Reject via API
```bash
POST /api/partner/platform/validation/{validationRequestId}/reject
Content-Type: application/json

{
  "user_id": 1,
  "rejection_reason": "Reason for rejection (min 10 chars)"
}
```

## Database

### Check Pending Validations
```sql
SELECT pvr.*, p.name as platform_name, p.type
FROM platform_validation_requests pvr
JOIN platforms p ON pvr.platform_id = p.id
WHERE pvr.status = 'pending'
ORDER BY pvr.created_at DESC;
```

### Check Platform with Validation Status
```sql
SELECT 
    p.id,
    p.name,
    p.enabled,
    pvr.status as validation_status,
    pvr.rejection_reason,
    pvr.created_at as requested_at
FROM platforms p
LEFT JOIN platform_validation_requests pvr ON p.id = pvr.platform_id
ORDER BY p.id DESC;
```

## Status Flow

```
Platform Created
     ↓
enabled = false
status = 'pending'
     ↓
Admin Reviews
     ↓
  ┌──────┴──────┐
  ↓             ↓
Approve       Reject
  ↓             ↓
enabled=true  enabled=false
status=       status=
'approved'    'rejected'
              + reason
```

## Common Tasks

### Manually Approve (Database)
```sql
START TRANSACTION;

UPDATE platform_validation_requests 
SET status = 'approved' 
WHERE id = 1;

UPDATE platforms 
SET enabled = true, updated_by = 1 
WHERE id = (SELECT platform_id FROM platform_validation_requests WHERE id = 1);

COMMIT;
```

### Get Pending Count
```php
$pendingCount = PlatformValidationRequest::where('status', 'pending')->count();
```

### Check Platform Validation Status
```php
$platform = Platform::with('validationRequest')->find($id);
$status = $platform->validationRequest?->status;
$reason = $platform->validationRequest?->rejection_reason;
```

## Error Handling

### Common Errors

1. **"This request has already been processed"**
   - Status is not 'pending'
   - Cannot approve/reject twice

2. **"Validation request not found"**
   - Invalid validation request ID
   - Request might have been deleted

3. **"Rejection reason is required"**
   - Must provide reason when rejecting
   - Minimum 10 characters

## Testing

### Test Platform Creation
```php
$response = $this->postJson('/api/partner/platforms', [
    'name' => 'Test Platform',
    'type' => '1',
    'owner_id' => 1,
    'created_by' => 1,
]);

$response->assertStatus(201);
$response->assertJsonStructure([
    'status',
    'message',
    'data' => [
        'platform',
        'validation_request'
    ]
]);
```

### Test Approval
```php
$validationRequest = PlatformValidationRequest::factory()->create([
    'status' => 'pending'
]);

$response = $this->postJson("/api/partner/platform/validation/{$validationRequest->id}/approve", [
    'user_id' => 1
]);

$response->assertStatus(200);
$this->assertEquals('approved', $validationRequest->fresh()->status);
```

## Troubleshooting

### Platform not appearing in list
- Check if validation request exists
- Verify platform owner/manager IDs match the user

### Cannot approve/reject
- Verify status is 'pending'
- Check user permissions
- Ensure validation request ID is correct

### Rejection reason not saving
- Verify minimum length (10 characters)
- Check maximum length (1000 characters)
- Ensure field is included in request

## Integration with Frontend

### Display Validation Status Badge
```javascript
function getValidationBadge(platform) {
    if (!platform.validation_request) return '';
    
    const status = platform.validation_request.status;
    const badges = {
        pending: '<span class="badge bg-warning">Pending Validation</span>',
        approved: '<span class="badge bg-success">Validated</span>',
        rejected: '<span class="badge bg-danger">Rejected</span>'
    };
    
    return badges[status] || '';
}
```

### Show Rejection Reason
```javascript
function showRejectionReason(platform) {
    const request = platform.validation_request;
    if (request && request.status === 'rejected' && request.rejection_reason) {
        return `
            <div class="alert alert-danger">
                <strong>Rejection Reason:</strong>
                ${request.rejection_reason}
            </div>
        `;
    }
    return '';
}
```

## Migration Rollback

If you need to rollback:
```bash
php artisan migrate:rollback --step=1
```

This will:
- Drop the `platform_validation_requests` table
- Keep existing platforms intact
- Remove the validation workflow

## Performance Considerations

- Indexes are added for `platform_id` and `status`
- Eager loading is used in the index method
- Pagination is implemented (10 items per page)
- Search uses LIKE queries (consider full-text search for large datasets)

## Security Notes

- All actions require user authentication
- User ID is validated against users table
- Status checks prevent duplicate processing
- Database transactions ensure data consistency
- All actions are logged for audit trail

## Related Files

- Model: `app/Models/PlatformValidationRequest.php`
- Controller: `app/Http/Controllers/Api/partner/PlatformPartnerController.php`
- Livewire: `app/Livewire/PlatformValidationRequests.php`
- View: `resources/views/livewire/platform-validation-requests.blade.php`
- Migration: `database/migrations/2025_11_18_152958_create_platform_validation_requests_table.php`
- Routes: `routes/api.php`

## Support

For issues or questions, check:
1. Laravel logs: `storage/logs/laravel.log`
2. Database constraints
3. Validation errors in the response
4. Network requests in browser developer tools

