# Platform Type Change API Documentation

## Overview
This document describes the Platform Type Change API endpoint that allows partners to request changes to their platform type with proper validation.

## Endpoint
**POST** `/api/partner/platform/change`

## Authentication
- No authentication required (uses `check.url` middleware)
- Part of the partner API group

## Request Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| platform_id | integer | Yes | ID of the platform to change |
| type_id | integer | Yes | New type ID (1, 2, or 3) |

## Platform Types

| Type ID | Name | Description |
|---------|------|-------------|
| 1 | Full | Full platform - Cannot be changed |
| 2 | Hybrid | Hybrid platform - Can only change to Full (1) |
| 3 | Paiement | Payment platform - Can change to Full (1) or Hybrid (2) |

## Type Transition Rules

### Allowed Transitions
- **Type 3 (Paiement)** → Can change to:
  - Type 1 (Full)
  - Type 2 (Hybrid)

- **Type 2 (Hybrid)** → Can change to:
  - Type 1 (Full)

- **Type 1 (Full)** → Cannot change

## Database Schema

### Table: `platform_type_change_requests`

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| platform_id | bigint | Foreign key to platforms table |
| old_type | integer | Previous platform type |
| new_type | integer | Requested new type |
| status | string | Request status (default: 'pending') |
| created_at | timestamp | Request creation time |
| updated_at | timestamp | Last update time |

### Indexes
- Foreign key: `platform_id` references `platforms(id)` with cascade delete
- Composite index: `(platform_id, status)`

## Response Formats

### Success Response (201 Created)
```json
{
  "status": true,
  "message": "Platform type change request created successfully",
  "data": {
    "id": 1,
    "platform_id": 123,
    "old_type": 3,
    "new_type": 2,
    "status": "pending",
    "created_at": "2025-11-18T14:06:38.000000Z",
    "updated_at": "2025-11-18T14:06:38.000000Z"
  }
}
```

### Error Responses

#### Validation Failed (422)
```json
{
  "status": "Failed",
  "message": "Validation failed",
  "errors": {
    "platform_id": ["The platform id field is required."],
    "type_id": ["The type id must be 1, 2, or 3."]
  }
}
```

#### Platform Not Found (404)
```json
{
  "status": "Failed",
  "message": "Platform not found"
}
```

#### Type 1 Cannot Change (403)
```json
{
  "status": "Failed",
  "message": "Type 1 (Full) platforms cannot change their type"
}
```

#### Invalid Transition (403)
```json
{
  "status": "Failed",
  "message": "Type 2 platforms can only change to types: 1"
}
```

#### Same Type Error (422)
```json
{
  "status": "Failed",
  "message": "New type cannot be the same as current type"
}
```

## Example Requests

### Request to change Type 3 to Type 2
```bash
curl -X POST "https://yourdomain.com/api/partner/platform/change" \
  -H "Content-Type: application/json" \
  -d '{
    "platform_id": 123,
    "type_id": 2
  }'
```

### Request to change Type 2 to Type 1
```bash
curl -X POST "https://yourdomain.com/api/partner/platform/change" \
  -H "Content-Type: application/json" \
  -d '{
    "platform_id": 456,
    "type_id": 1
  }'
```

## Business Logic

1. **Validation**: First validates that both `platform_id` and `type_id` are provided and valid
2. **Platform Check**: Verifies the platform exists
3. **Type 1 Check**: Rejects requests if current type is 1 (Full)
4. **Transition Validation**: Ensures the requested transition is allowed based on current type
5. **Same Type Check**: Prevents changing to the same type
6. **Request Creation**: Creates a pending change request in the database
7. **Logging**: Logs all requests and errors for audit purposes

## Model Relationship

### PlatformTypeChangeRequest Model
```php
namespace App\Models;

use Core\Models\Platform;
use Illuminate\Database\Eloquent\Model;

class PlatformTypeChangeRequest extends Model
{
    protected $fillable = [
        'platform_id',
        'old_type',
        'new_type',
        'status',
    ];

    public function platform(): BelongsTo
    {
        return $this->belongsTo(Platform::class);
    }
}
```

## Implementation Details

### Controller Method Location
`App\Http\Controllers\Api\partner\PlatformPartnerController::changePlatformType()`

### Route Registration
```php
Route::post('platform/change', [PlatformPartnerController::class, 'changePlatformType'])
    ->name('platform.change_type');
```

### Middleware Stack
- `withoutMiddleware([\App\Http\Middleware\Authenticate::class])`
- `middleware(['check.url'])`

## Status Management
The `status` field in the change request table allows for workflow management:
- **pending**: Initial state when request is created
- **approved**: (Future) When request is approved
- **rejected**: (Future) When request is rejected
- **completed**: (Future) When type change is applied

## Security Considerations
- Uses URL checking middleware (`check.url`)
- Validates platform existence before processing
- Enforces strict type transition rules
- Prevents unauthorized type changes
- All actions are logged for audit trail

## Future Enhancements
- Add approval workflow
- Add user permissions checking
- Add notifications for request status changes
- Add bulk type change requests
- Add request cancellation feature

## Related Files
- Migration: `database/migrations/2025_11_18_140638_create_platform_type_change_requests_table.php`
- Model: `app/Models/PlatformTypeChangeRequest.php`
- Controller: `app/Http/Controllers/Api/partner/PlatformPartnerController.php`
- Routes: `routes/api.php`
- Platform Enum: `Core/Enum/PlatformType.php`

