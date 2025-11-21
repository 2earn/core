# User Partner Controller - Add Role API Implementation

## Overview
Implementation of an API endpoint for the partner API that allows assigning roles to users on specific platforms. This follows the existing partner API patterns and integrates with Spatie's Laravel Permission package.

## Created Files

### 1. Controller: `app/Http/Controllers/Api/partner/UserPartnerController.php`
- **Namespace**: `App\Http\Controllers\Api\partner`
- **Extends**: `Controller`
- **Middleware**: `check.url`

#### Methods

##### `addRole(AddRoleRequest $request)`
Assigns a role to a user on a specific platform.

**Request Body**:
```json
{
  "platform_id": 1,
  "user_id": 123,
  "role": "manager"
}
```

**Success Response** (200 OK):
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

**Error Response** (500 Internal Server Error):
```json
{
  "status": false,
  "message": "Failed to assign role: {error message}"
}
```

**Features**:
- Database transaction support (rollback on failure)
- Comprehensive logging of success and failures
- Uses Spatie's `assignRole()` method
- Validates user and platform existence via `findOrFail()`
- Logs the ID of the user performing the assignment

---

### 2. Request Validation: `app/Http/Requests/Api/Partner/AddRoleRequest.php`
- **Namespace**: `App\Http\Requests\Api\Partner`
- **Extends**: `FormRequest`

#### Validation Rules

| Field | Rules | Description |
|-------|-------|-------------|
| `platform_id` | required, integer, exists:platforms,id | Must be a valid platform ID |
| `user_id` | required, integer, exists:users,id | Must be a valid user ID |
| `role` | required, string, max:255 | Role name to assign |

#### Custom Error Messages
- Provides user-friendly validation error messages
- Returns JSON response on validation failure (422 Unprocessable Entity)

**Validation Error Response**:
```json
{
  "status": false,
  "message": "Validation failed",
  "errors": {
    "platform_id": ["The specified platform does not exist"],
    "user_id": ["User ID is required"],
    "role": ["Role is required"]
  }
}
```

---

### 3. Route: `routes/api.php`
Added to the partner API group:

```php
Route::post('users/add-role', [UserPartnerController::class, 'addRole'])
    ->name('api_partner_users_add_role');
```

**Full URL**: `POST /api/partner/users/add-role`

**Middleware**:
- No authentication required (follows partner API pattern)
- `check.url` middleware applied

---

## Usage Example

### Using cURL
```bash
curl -X POST http://your-domain.com/api/partner/users/add-role \
  -H "Content-Type: application/json" \
  -d '{
    "platform_id": 1,
    "user_id": 123,
    "role": "platform-manager"
  }'
```

### Using JavaScript (Axios)
```javascript
const response = await axios.post('/api/partner/users/add-role', {
  platform_id: 1,
  user_id: 123,
  role: 'platform-manager'
});

console.log(response.data);
```

### Using PHP (Guzzle)
```php
$client = new \GuzzleHttp\Client();
$response = $client->post('http://your-domain.com/api/partner/users/add-role', [
    'json' => [
        'platform_id' => 1,
        'user_id' => 123,
        'role' => 'platform-manager'
    ]
]);

$data = json_decode($response->getBody(), true);
```

---

## Integration Notes

### Spatie Laravel Permission
The implementation uses Spatie's `HasRoles` trait which is already integrated in the `User` model:

```php
$user->assignRole($validated['role']);
```

**Important**: Ensure the role exists in your `roles` table before attempting to assign it. You can create roles using:

```php
use Spatie\Permission\Models\Role;

Role::create(['name' => 'platform-manager']);
Role::create(['name' => 'platform-admin']);
Role::create(['name' => 'sales-representative']);
```

### Platform-Scoped Roles (Optional Enhancement)
If you need roles to be scoped to specific platforms, you can extend this implementation by:

1. Using Spatie's guard/team concept
2. Creating a pivot table `platform_user_role` to track platform-specific roles
3. Modifying the controller to use: `$user->assignRole($role, $platform)`

---

## Logging

All role assignments are logged with the following information:
- User ID
- Platform ID
- Role assigned
- ID of the user performing the assignment (if authenticated)

**Log Location**: Check Laravel logs at `storage/logs/laravel.log`

**Example Log Entry**:
```
[2025-11-21 08:58:00] local.INFO: [UserPartnerController] Role assigned successfully 
{"user_id":123,"platform_id":1,"role":"manager","assigned_by":5}
```

**Error Log Entry**:
```
[2025-11-21 08:58:00] local.ERROR: [UserPartnerController] Failed to assign role 
{"error":"Role does not exist","trace":"...","payload":{"user_id":123,"platform_id":1,"role":"invalid-role"}}
```

---

## Security Considerations

### Current Implementation
- Uses `check.url` middleware for basic security
- No authentication required (follows partner API pattern)

### Recommended Enhancements
Consider adding:

1. **Authentication**: Require API token/key
   ```php
   $this->middleware('auth:sanctum');
   ```

2. **Authorization**: Check if requester has permission to assign roles
   ```php
   public function authorize()
   {
       return auth()->user()->can('assign-roles');
   }
   ```

3. **Rate Limiting**: Prevent abuse
   ```php
   Route::middleware('throttle:60,1')->post('users/add-role', ...);
   ```

4. **Role Validation**: Ensure role exists before attempting assignment
   ```php
   'role' => 'required|string|exists:roles,name'
   ```

---

## Testing

### Manual Testing

1. **Test Valid Request**:
```bash
POST /api/partner/users/add-role
{
  "platform_id": 1,
  "user_id": 123,
  "role": "manager"
}
# Expected: 200 OK with success message
```

2. **Test Invalid Platform**:
```bash
POST /api/partner/users/add-role
{
  "platform_id": 99999,
  "user_id": 123,
  "role": "manager"
}
# Expected: 422 Unprocessable Entity
```

3. **Test Invalid User**:
```bash
POST /api/partner/users/add-role
{
  "platform_id": 1,
  "user_id": 99999,
  "role": "manager"
}
# Expected: 422 Unprocessable Entity
```

4. **Test Missing Fields**:
```bash
POST /api/partner/users/add-role
{
  "platform_id": 1
}
# Expected: 422 Unprocessable Entity with validation errors
```

5. **Test Non-Existent Role**:
```bash
POST /api/partner/users/add-role
{
  "platform_id": 1,
  "user_id": 123,
  "role": "non-existent-role"
}
# Expected: 500 Internal Server Error (role doesn't exist)
```

### Unit Test Example
Create `tests/Feature/Api/Partner/UserPartnerControllerTest.php`:

```php
<?php

namespace Tests\Feature\Api\Partner;

use Tests\TestCase;
use App\Models\User;
use Core\Models\Platform;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserPartnerControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_assign_role_to_user()
    {
        $user = User::factory()->create();
        $platform = Platform::factory()->create();
        $role = Role::create(['name' => 'manager']);

        $response = $this->postJson('/api/partner/users/add-role', [
            'platform_id' => $platform->id,
            'user_id' => $user->id,
            'role' => 'manager'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'Role assigned successfully'
                 ]);

        $this->assertTrue($user->hasRole('manager'));
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $response = $this->postJson('/api/partner/users/add-role', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['platform_id', 'user_id', 'role']);
    }
}
```

---

## Future Enhancements

1. **Remove Role Endpoint**: Create a complementary endpoint to remove roles
   ```php
   Route::delete('users/remove-role', [UserPartnerController::class, 'removeRole']);
   ```

2. **List User Roles**: Get all roles for a specific user on a platform
   ```php
   Route::get('users/{user}/roles', [UserPartnerController::class, 'getRoles']);
   ```

3. **Bulk Role Assignment**: Assign roles to multiple users at once
   ```php
   Route::post('users/bulk-add-roles', [UserPartnerController::class, 'bulkAddRoles']);
   ```

4. **Platform-Scoped Roles**: Implement team/platform-specific role scoping
5. **Role Validation**: Add validation to ensure role exists before assignment
6. **Audit Trail**: Enhanced auditing of role changes with before/after states

---

## Quick Reference

| Item | Value |
|------|-------|
| **Endpoint** | `POST /api/partner/users/add-role` |
| **Route Name** | `api_partner_users_add_role` |
| **Controller** | `UserPartnerController@addRole` |
| **Request Class** | `AddRoleRequest` |
| **Middleware** | `check.url` |
| **Authentication** | Not required |
| **Success Status** | 200 OK |
| **Validation Error Status** | 422 Unprocessable Entity |
| **Server Error Status** | 500 Internal Server Error |

---

## Related Documentation
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission/v5/introduction)
- [Laravel Form Request Validation](https://laravel.com/docs/validation#form-request-validation)
- [Laravel Database Transactions](https://laravel.com/docs/database#database-transactions)

---

**Created**: November 21, 2025  
**Version**: 1.0

