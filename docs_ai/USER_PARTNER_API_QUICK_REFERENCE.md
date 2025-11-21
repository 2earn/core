# User Partner API - Add Role Quick Reference

## Endpoint
```
POST /api/partner/users/add-role
```

## Request Format
```json
{
  "platform_id": 1,
  "user_id": 123,
  "role": "manager"
}
```

## Response Format

### Success (200)
```json
{
  "status": true,
  "message": "Role assigned successfully",
  "data": {
    "user_id": 123,
    "platform_id": 1,
    "role": "manager"
  }
}
```

### Validation Error (422)
```json
{
  "status": false,
  "message": "Validation failed",
  "errors": {
    "platform_id": ["The specified platform does not exist"]
  }
}
```

### Server Error (500)
```json
{
  "status": false,
  "message": "Failed to assign role: {error message}"
}
```

## Validation Rules

| Field | Required | Type | Validation |
|-------|----------|------|------------|
| `platform_id` | Yes | integer | Must exist in platforms table |
| `user_id` | Yes | integer | Must exist in users table |
| `role` | Yes | string | Max 255 characters |

## Quick Test

### Using cURL
```bash
curl -X POST http://localhost/api/partner/users/add-role \
  -H "Content-Type: application/json" \
  -d '{"platform_id":1,"user_id":123,"role":"manager"}'
```

### Using Postman
1. Method: `POST`
2. URL: `http://your-domain.com/api/partner/users/add-role`
3. Headers: `Content-Type: application/json`
4. Body (raw JSON):
```json
{
  "platform_id": 1,
  "user_id": 123,
  "role": "manager"
}
```

## Files Created
- Controller: `app/Http/Controllers/Api/partner/UserPartnerController.php`
- Request: `app/Http/Requests/Api/Partner/AddRoleRequest.php`
- Route: Added to `routes/api.php`
- Documentation: `docs_ai/USER_PARTNER_CONTROLLER_ADD_ROLE.md`

## Common Roles
Ensure these roles exist in your database before using:
- `platform-manager`
- `platform-admin`
- `sales-representative`
- `marketing-manager`
- `financial-manager`

## Create Roles (Tinker)
```php
php artisan tinker

use Spatie\Permission\Models\Role;

Role::create(['name' => 'platform-manager']);
Role::create(['name' => 'platform-admin']);
Role::create(['name' => 'sales-representative']);
```

## Logs
Check logs at: `storage/logs/laravel.log`

Search for: `[UserPartnerController]`

