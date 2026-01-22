# Partner Role Request System

## Overview
This feature allows partners to create role assignment requests for users, which can then be approved or rejected by administrators. Once approved, an `EntityRole` is automatically created for the user in the partner context.

## Components Created

### 1. Database Migration
**File:** `database/migrations/2026_01_22_093629_create_partner_role_requests_table.php`

Creates the `partner_role_requests` table with the following fields:
- `id` - Primary key
- `partner_id` - Foreign key to partners table
- `user_id` - Foreign key to users table (the user to be assigned the role)
- `role_name` - The role being requested (e.g., 'manager', 'admin')
- `status` - Request status (pending, approved, rejected, cancelled)
- `reason` - Reason for the request
- `rejection_reason` - Reason for rejection (if applicable)
- `requested_by` - User who created the request
- `reviewed_by` - User who approved/rejected
- `reviewed_at` - Timestamp of review
- `cancelled_by` - User who cancelled the request
- `cancelled_reason` - Reason for cancellation
- `cancelled_at` - Timestamp of cancellation
- `timestamps` - Created and updated timestamps

### 2. Model
**File:** `app/Models/PartnerRoleRequest.php`

Features:
- Status constants (PENDING, APPROVED, REJECTED, CANCELLED)
- Relationships to Partner, User, and various action users
- Helper methods: `isPending()`, `isApproved()`, `isRejected()`, `isCancelled()`
- Scopes: `pending()`, `approved()`, `rejected()`
- Permission checks: `canBeReviewed()`, `canBeCancelled()`

### 3. API Controller
**File:** `app/Http/Controllers/Api/partner/PartnerRolePartnerController.php`

Endpoints:
- `GET /api/partner/role-requests` - List all requests with filtering
- `GET /api/partner/role-requests/{id}` - Get a specific request
- `POST /api/partner/role-requests` - Create a new request
- `POST /api/partner/role-requests/{id}/cancel` - Cancel a request

**Note:** Approve and reject actions are only available from the admin back office, not through the partner API.

### 4. Livewire Component
**File:** `app/Livewire/PartnerRoleRequestManage.php`

Features:
- Real-time filtering by status
- User search functionality
- Approve/Reject modals
- Statistics dashboard
- Pagination support

**View File:** `resources/views/livewire/partner-role-request-manage.blade.php`

## API Usage

### 1. List All Partner Role Requests

```http
GET /api/partner/role-requests
```

**Query Parameters:**
- `partner_id` (required) - Partner ID
- `status` (optional) - Filter by status: all, pending, approved, rejected, cancelled
- `user_id` (optional) - Filter by user ID
- `page` (optional) - Page number (default: 1)
- `limit` (optional) - Items per page (default: 15, max: 100)

**Example Request:**
```bash
curl -X GET "https://your-domain.com/api/partner/role-requests?partner_id=1&status=pending" \
  -H "Content-Type: application/json"
```

**Example Response:**
```json
{
  "status": "Success",
  "message": "Partner role requests retrieved successfully",
  "data": {
    "requests": [
      {
        "id": 1,
        "partner_id": 1,
        "user_id": 5,
        "role_name": "manager",
        "status": "pending",
        "reason": "Need manager for sales department",
        "requested_by": 2,
        "created_at": "2026-01-22T10:30:00.000000Z",
        "partner": {
          "id": 1,
          "company_name": "Acme Corp"
        },
        "user": {
          "id": 5,
          "name": "John Doe",
          "email": "john@example.com"
        }
      }
    ],
    "statistics": {
      "total_requests": 10,
      "pending_requests": 3,
      "approved_requests": 5,
      "rejected_requests": 2
    },
    "pagination": {
      "current_page": 1,
      "per_page": 15,
      "total": 10,
      "total_pages": 1
    }
  }
}
```

### 2. Get a Specific Request

```http
GET /api/partner/role-requests/{id}
```

**Query Parameters:**
- `partner_id` (required) - Partner ID

**Example Request:**
```bash
curl -X GET "https://your-domain.com/api/partner/role-requests/1?partner_id=1" \
  -H "Content-Type: application/json"
```

### 3. Create a New Request

```http
POST /api/partner/role-requests
```

**Request Body:**
```json
{
  "partner_id": 1,
  "user_id": 5,
  "role_name": "manager",
  "reason": "Need manager for sales department",
  "requested_by": 2
}
```

**Example Request:**
```bash
curl -X POST "https://your-domain.com/api/partner/role-requests" \
  -H "Content-Type: application/json" \
  -d '{
    "partner_id": 1,
    "user_id": 5,
    "role_name": "manager",
    "reason": "Need manager for sales department",
    "requested_by": 2
  }'
```

**Example Response:**
```json
{
  "status": "Success",
  "message": "Partner role request created successfully",
  "data": {
    "id": 1,
    "partner_id": 1,
    "user_id": 5,
    "role_name": "manager",
    "status": "pending",
    "reason": "Need manager for sales department",
    "requested_by": 2,
    "created_at": "2026-01-22T10:30:00.000000Z"
  }
}
```

### 4. Cancel a Request

```http
POST /api/partner/role-requests/{id}/cancel
```

**Request Body:**
```json
{
  "cancelled_by": 2,
  "cancelled_reason": "Request no longer needed"
}
```

## Livewire Component Usage (Admin Back Office)

The Livewire component `PartnerRoleRequestManage` provides the admin interface for approving and rejecting partner role requests.

To use the Livewire component in your application, add it to a route in `routes/web.php`:

```php
Route::get('/partner-role-requests', function () {
    return view('layouts.app', [
        'slot' => '<livewire:partner-role-request-manage />'
    ]);
})->name('partner_role_requests');
```

Or in a Blade template:

```blade
<livewire:partner-role-request-manage />
```

## Database Schema

```sql
CREATE TABLE `partner_role_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `partner_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `role_name` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `reason` text,
  `rejection_reason` text,
  `requested_by` bigint unsigned NOT NULL,
  `reviewed_by` bigint unsigned DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `cancelled_by` bigint unsigned DEFAULT NULL,
  `cancelled_reason` text,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `partner_role_requests_partner_id_status_index` (`partner_id`,`status`),
  KEY `partner_role_requests_user_id_index` (`user_id`),
  CONSTRAINT `partner_role_requests_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE,
  CONSTRAINT `partner_role_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `partner_role_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `partner_role_requests_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `partner_role_requests_cancelled_by_foreign` FOREIGN KEY (`cancelled_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
);
```

## Workflow

1. **Request Creation**: A user creates a partner role request through the API endpoint
2. **Pending State**: The request is created with status "pending"
3. **Review**: An administrator reviews the request via the Livewire component or API
4. **Approval**: 
   - If approved, an `EntityRole` is automatically created
   - Request status changes to "approved"
   - `reviewed_by` and `reviewed_at` are recorded
5. **Rejection**:
   - If rejected, a rejection reason must be provided
   - Request status changes to "rejected"
   - No `EntityRole` is created
6. **Cancellation**: Pending requests can be cancelled by authorized users

## Error Handling

The API returns appropriate HTTP status codes:
- `200 OK` - Successful operation
- `201 Created` - Resource created successfully
- `404 Not Found` - Resource not found
- `409 Conflict` - Request cannot be processed (e.g., duplicate pending request)
- `422 Unprocessable Entity` - Validation error
- `500 Internal Server Error` - Server error

## Notes

- Only pending requests can be approved or rejected
- Only pending requests can be cancelled
- Duplicate pending requests for the same user/partner/role combination are prevented
- All actions are logged for audit purposes
- The system uses the existing `EntityRoleService` to create roles upon approval
