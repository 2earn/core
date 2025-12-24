# Get Partner Platforms API Endpoint

## Overview
This document describes the new API endpoint that retrieves all platforms where a user has a role (owner, marketing manager, or financial manager).

## Endpoint Details

### URL
```
POST /api/partner/users/platforms
```

### Request Headers
```
Authorization: Bearer {token}
Content-Type: application/json
```

### Request Body
```json
{
  "user_id": 123
}
```

### Request Parameters
| Parameter | Type    | Required | Description                          |
|-----------|---------|----------|--------------------------------------|
| user_id   | integer | Yes      | The ID of the user to query platforms for |

### Validation Rules
- `user_id`: Required, must be an integer and must exist in the users table

## Response

### Success Response (200 OK)
```json
{
  "status": true,
  "message": "Platforms retrieved successfully",
  "data": {
    "platforms": [
      {
        "id": 1,
        "name": "Platform Name",
        "description": "Platform Description",
        "type": "platform_type",
        "link": "https://platform-url.com",
        "enabled": true,
        "show_profile": true,
        "image_link": "https://image-url.com",
        "business_sector": {
          "id": 5,
          "name": "Technology"
        },
        "logo": {
          "id": 10,
          "path": "/images/logo.png"
        },
        "roles": ["owner", "marketing"],
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
      }
    ],
    "total": 1
  }
}
```

### Error Response (422 Unprocessable Entity)
```json
{
  "status": false,
  "message": "Validation failed",
  "errors": {
    "user_id": [
      "User ID is required"
    ]
  }
}
```

### Error Response (500 Internal Server Error)
```json
{
  "status": false,
  "message": "Failed to retrieve platforms: [error message]"
}
```

## Role Types
The `roles` array in the response can contain the following values:
- `owner` - User is the platform owner
- `marketing` - User is the marketing manager
- `financial` - User is the financial manager

A user can have multiple roles on a single platform.

## Example Usage

### Using cURL
```bash
curl -X POST https://your-domain.com/api/partner/users/platforms \
  -H "Authorization: Bearer your_token_here" \
  -H "Content-Type: application/json" \
  -d '{"user_id": 123}'
```

### Using JavaScript (Fetch)
```javascript
fetch('https://your-domain.com/api/partner/users/platforms', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer your_token_here',
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    user_id: 123
  })
})
.then(response => response.json())
.then(data => console.log(data))
.catch(error => console.error('Error:', error));
```

### Using PHP (Laravel HTTP Client)
```php
use Illuminate\Support\Facades\Http;

$response = Http::withToken($token)
    ->post('https://your-domain.com/api/partner/users/platforms', [
        'user_id' => 123
    ]);

$data = $response->json();
```

## Implementation Details

### Files Modified/Created
1. **Created**: `app/Http/Requests/Api/Partner/GetPartnerPlatformsRequest.php` - Request validation class
2. **Modified**: `app/Http/Controllers/Api/partner/UserPartnerController.php` - Added `getPartnerPlatforms()` method
3. **Modified**: `routes/api.php` - Added route for the new endpoint

### Database Query
The endpoint queries the `platforms` table and checks three fields:
- `owner_id`
- `marketing_manager_id`
- `financial_manager_id`

It returns all platforms where any of these fields match the provided `user_id`.

### Eager Loading
The query uses eager loading for:
- `businessSector` - The business sector relationship
- `logoImage` - The logo image relationship

This optimizes database queries and prevents N+1 problems.

### Logging
The endpoint logs:
- Success: User ID and count of platforms found
- Error: Full error message, trace, and user ID

All logs are prefixed with `[UserPartnerController]` for easy identification.

## Notes
- The endpoint requires authentication (uses Laravel's authentication middleware)
- The response includes all platform details including related data (business sector and logo)
- Multiple roles per platform are supported and returned in the `roles` array
- The endpoint uses database transactions for data integrity
- Comprehensive error handling and logging is implemented

