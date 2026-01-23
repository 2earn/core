# Platform Roles API Endpoint

## Overview
This document describes the new API endpoint to retrieve the list of roles associated with a platform.

## Endpoint Details

### Get Platform Roles

**Endpoint:** `GET /api/partner/platforms/platforms/{platformId}/roles`

**Route Name:** `api_partner_platform_roles`

**Description:** Retrieves all entity roles associated with a specific platform. The user must have authorized access to the platform.

### Request Parameters

#### Path Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| platformId | integer | Yes | The ID of the platform to retrieve roles for |

#### Query Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| user_id | integer | Yes | The ID of the authenticated user making the request |

### Request Example
```http
GET /api/partner/platforms/platforms/1/roles?user_id=5 HTTP/1.1
Host: your-domain.com
```

### Response Format

#### Success Response (200 OK)
```json
{
    "status": true,
    "message": "Platform roles retrieved successfully",
    "data": {
        "platform_id": 1,
        "platform_name": "Example Platform",
        "roles": [
            {
                "id": 1,
                "name": "owner",
                "user_id": 5,
                "created_at": "2026-01-15T10:30:00.000000Z"
            },
            {
                "id": 2,
                "name": "manager",
                "user_id": 8,
                "created_at": "2026-01-18T14:20:00.000000Z"
            }
        ]
    }
}
```

#### Error Responses

**Validation Error (422 Unprocessable Entity)**
```json
{
    "status": "Failed",
    "message": "Validation failed",
    "errors": {
        "user_id": [
            "The user id field is required."
        ],
        "platform_id": [
            "The selected platform id is invalid."
        ]
    }
}
```

**Platform Not Found (404 Not Found)**
```json
{
    "status": "Failed",
    "message": "Platform not found or unauthorized access"
}
```

**Server Error (500 Internal Server Error)**
```json
{
    "status": "Failed",
    "message": "Error retrieving platform roles",
    "error": "Detailed error message"
}
```

### Response Fields

#### Data Object
| Field | Type | Description |
|-------|------|-------------|
| platform_id | integer | The ID of the platform |
| platform_name | string | The name of the platform |
| roles | array | Array of entity role objects |

#### Role Object
| Field | Type | Description |
|-------|------|-------------|
| id | integer | The entity role ID |
| name | string | The role name (e.g., 'owner', 'manager', 'staff') |
| user_id | integer | The ID of the user assigned to this role |
| created_at | datetime | When this role assignment was created |

### Implementation Details

- **Controller:** `App\Http\Controllers\Api\partner\PlatformPartnerController`
- **Method:** `getRoles(Request $request, $platformId)`
- **Service:** Uses `PlatformService::getPlatformForPartner()` to verify platform access
- **Relationship:** Uses the polymorphic `roles()` relationship on the Platform model

### Security & Authorization

1. The endpoint validates that the `user_id` exists in the users table
2. The endpoint validates that the `platform_id` exists in the platforms table
3. The `PlatformService::getPlatformForPartner()` method ensures the user has authorized access to the platform
4. Unauthorized access attempts return a 404 error

### Logging

The endpoint logs the following events:

- **Error:** Validation failures with error details
- **Error:** Platform not found or unauthorized access attempts
- **Info:** Successful retrieval with platform ID, user ID, and roles count
- **Error:** Exceptions during processing with full stack trace

### Usage Example (cURL)

```bash
curl -X GET "http://your-domain.com/api/partner/platforms/platforms/1/roles?user_id=5" \
  -H "Accept: application/json"
```

### Usage Example (JavaScript/Axios)

```javascript
axios.get('/api/partner/platforms/platforms/1/roles', {
    params: {
        user_id: 5
    }
})
.then(response => {
    console.log('Platform roles:', response.data.data.roles);
})
.catch(error => {
    console.error('Error:', error.response.data);
});
```

### Usage Example (PHP/Guzzle)

```php
$client = new \GuzzleHttp\Client();
$response = $client->get('http://your-domain.com/api/partner/platforms/platforms/1/roles', [
    'query' => [
        'user_id' => 5
    ]
]);

$data = json_decode($response->getBody(), true);
$roles = $data['data']['roles'];
```

### Testing

A Postman collection has been created at `platform-roles-api.postman_collection.json` with example requests and responses.

### Notes

- The roles returned are entity roles from the polymorphic relationship
- Only platforms accessible to the specified user will be returned
- The endpoint is part of the partner API group and uses the `check.url` middleware
- Authentication middleware is bypassed as configured in the partner route group

