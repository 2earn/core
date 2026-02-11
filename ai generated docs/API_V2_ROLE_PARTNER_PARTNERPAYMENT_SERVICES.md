# 2Earn API v2 - RoleService, PartnerService & PartnerPaymentService Documentation

## Overview

This document describes the REST API endpoints for **RoleService**, **PartnerService**, and **PartnerPaymentService** exposed under the `/api/v2/` prefix.

## Table of Contents

- [Base URL](#base-url)
- [Authentication](#authentication)
- [RoleService Endpoints](#roleservice-endpoints)
- [PartnerService Endpoints](#partnerservice-endpoints)
- [PartnerPaymentService Endpoints](#partnerpaymentservice-endpoints)
- [Postman Collection](#postman-collection)
- [Response Format](#response-format)
- [Error Handling](#error-handling)

---

## Base URL

```
http://your-domain.com/api/v2/
```

## Authentication

These endpoints are configured without the default Laravel authentication middleware. However, you may need to implement your own authentication mechanism based on your requirements.

---

## RoleService Endpoints

### 1. Get All Roles (Paginated)

**Endpoint:** `GET /api/v2/roles`

**Description:** Retrieve all roles with pagination and optional search.

**Query Parameters:**
- `search` (optional, string): Search by role name or guard name
- `per_page` (optional, integer): Number of items per page (default: 10, max: 100)

**Response:**
```json
{
  "status": true,
  "data": [...],
  "pagination": {
    "current_page": 1,
    "per_page": 10,
    "total": 8,
    "last_page": 1
  }
}
```

---

### 2. Get All Roles (Non-Paginated)

**Endpoint:** `GET /api/v2/roles/all`

**Description:** Get all roles without pagination.

**Response:**
```json
{
  "status": true,
  "data": [...]
}
```

---

### 3. Get Role By ID

**Endpoint:** `GET /api/v2/roles/{id}`

**Description:** Get a specific role by ID.

**Path Parameters:**
- `id` (required, integer): Role ID

**Response:**
```json
{
  "status": true,
  "data": {
    "id": 1,
    "name": "admin",
    "guard_name": "web",
    ...
  }
}
```

---

### 4. Check If Role Can Be Deleted

**Endpoint:** `GET /api/v2/roles/{id}/can-delete`

**Description:** Check if a role can be deleted (roles with ID 1-4 are protected).

**Path Parameters:**
- `id` (required, integer): Role ID

**Response:**
```json
{
  "status": true,
  "can_delete": false
}
```

---

### 5. Get User Roles

**Endpoint:** `GET /api/v2/roles/user-roles`

**Description:** Get paginated user roles with user information.

**Query Parameters:**
- `search` (optional, string): Search by username, mobile, or country
- `per_page` (optional, integer): Number of items per page (default: 10, max: 100)

**Response:**
```json
{
  "status": true,
  "data": [...],
  "pagination": {
    "current_page": 1,
    "per_page": 10,
    "total": 100,
    "last_page": 10
  }
}
```

---

### 6. Create Role

**Endpoint:** `POST /api/v2/roles`

**Description:** Create a new role.

**Request Body:**
```json
{
  "name": "Manager",
  "guard_name": "web"
}
```

**Response:**
```json
{
  "status": true,
  "data": {
    "id": 5,
    "name": "Manager",
    "guard_name": "web",
    ...
  }
}
```

---

### 7. Update Role

**Endpoint:** `PUT /api/v2/roles/{id}`

**Description:** Update an existing role.

**Path Parameters:**
- `id` (required, integer): Role ID

**Request Body:**
```json
{
  "name": "Updated Manager",
  "guard_name": "web"
}
```

**Response:**
```json
{
  "status": true,
  "message": "Role updated successfully"
}
```

---

### 8. Delete Role

**Endpoint:** `DELETE /api/v2/roles/{id}`

**Description:** Delete a role (protected roles with ID 1-4 cannot be deleted).

**Path Parameters:**
- `id` (required, integer): Role ID

**Response:**
```json
{
  "status": true,
  "message": "Role deleted successfully"
}
```

---

## PartnerService Endpoints

### 1. Get All Partners

**Endpoint:** `GET /api/v2/partners`

**Description:** Get all partners (non-paginated).

**Response:**
```json
{
  "status": true,
  "data": [...]
}
```

---

### 2. Get Filtered Partners (Paginated)

**Endpoint:** `GET /api/v2/partners/filtered`

**Description:** Get filtered partners with pagination.

**Query Parameters:**
- `search` (optional, string): Search by company name, URL, or business sector
- `per_page` (optional, integer): Number of items per page (default: 15, max: 100)

**Response:**
```json
{
  "status": true,
  "data": [...],
  "pagination": {
    "current_page": 1,
    "per_page": 15,
    "total": 45,
    "last_page": 3
  }
}
```

---

### 3. Get Partner By ID

**Endpoint:** `GET /api/v2/partners/{id}`

**Description:** Get a specific partner by ID.

**Path Parameters:**
- `id` (required, integer): Partner ID

**Response:**
```json
{
  "status": true,
  "data": {
    "id": 1,
    "company_name": "Tech Solutions Inc",
    "platform_url": "https://techsolutions.com",
    ...
  }
}
```

---

### 4. Get Partners By Business Sector

**Endpoint:** `GET /api/v2/partners/business-sectors/{businessSectorId}`

**Description:** Get all partners in a specific business sector.

**Path Parameters:**
- `businessSectorId` (required, integer): Business Sector ID

**Response:**
```json
{
  "status": true,
  "data": [...]
}
```

---

### 5. Search Partners By Company Name

**Endpoint:** `GET /api/v2/partners/search`

**Description:** Search partners by company name.

**Query Parameters:**
- `company_name` (required, string): Company name to search for

**Response:**
```json
{
  "status": true,
  "data": [...]
}
```

---

### 6. Create Partner

**Endpoint:** `POST /api/v2/partners`

**Description:** Create a new partner.

**Request Body:**
```json
{
  "company_name": "Tech Solutions Inc",
  "business_sector_id": 1,
  "platform_url": "https://techsolutions.com",
  "platform_description": "Leading technology solutions provider",
  "partnership_reason": "Strategic partnership for growth",
  "created_by": 1
}
```

**Response:**
```json
{
  "status": true,
  "data": {
    "id": 1,
    "company_name": "Tech Solutions Inc",
    ...
  }
}
```

---

### 7. Update Partner

**Endpoint:** `PUT /api/v2/partners/{id}`

**Description:** Update an existing partner.

**Path Parameters:**
- `id` (required, integer): Partner ID

**Request Body:**
```json
{
  "company_name": "Tech Solutions International",
  "platform_url": "https://techsolutions-intl.com"
}
```

**Response:**
```json
{
  "status": true,
  "data": {...},
  "message": "Partner updated successfully"
}
```

---

### 8. Delete Partner

**Endpoint:** `DELETE /api/v2/partners/{id}`

**Description:** Delete a partner.

**Path Parameters:**
- `id` (required, integer): Partner ID

**Response:**
```json
{
  "status": true,
  "message": "Partner deleted successfully"
}
```

---

## PartnerPaymentService Endpoints

### 1. Get All Payments (Paginated)

**Endpoint:** `GET /api/v2/partner-payments`

**Description:** Get all payments with filters and pagination.

**Query Parameters:**
- `search` (optional, string): Search term
- `status_filter` (optional, string): Filter by status (all, pending, validated, rejected)
- `method_filter` (optional, string): Filter by payment method
- `partner_filter` (optional, integer): Filter by partner ID
- `from_date` (optional, date): Start date
- `to_date` (optional, date): End date
- `per_page` (optional, integer): Number of items per page (default: 10, max: 100)

**Response:**
```json
{
  "status": true,
  "data": [...],
  "pagination": {
    "current_page": 1,
    "per_page": 10,
    "total": 50,
    "last_page": 5
  }
}
```

---

### 2. Get Payment By ID

**Endpoint:** `GET /api/v2/partner-payments/{id}`

**Description:** Get a specific payment by ID.

**Path Parameters:**
- `id` (required, integer): Payment ID

**Response:**
```json
{
  "status": true,
  "data": {
    "id": 1,
    "amount": 1500.50,
    "method": "bank_transfer",
    ...
  }
}
```

---

### 3. Get Payments By Partner ID

**Endpoint:** `GET /api/v2/partner-payments/partners/{partnerId}`

**Description:** Get all payments for a specific partner.

**Path Parameters:**
- `partnerId` (required, integer): Partner ID

**Query Parameters:**
- `validated` (optional, boolean): Filter by validation status
- `method` (optional, string): Filter by payment method
- `from_date` (optional, date): Start date
- `to_date` (optional, date): End date

**Response:**
```json
{
  "status": true,
  "data": [...]
}
```

---

### 4. Get Total Payments By Partner

**Endpoint:** `GET /api/v2/partner-payments/partners/{partnerId}/total`

**Description:** Get total payment amount for a specific partner.

**Path Parameters:**
- `partnerId` (required, integer): Partner ID

**Query Parameters:**
- `validated_only` (optional, boolean): Count only validated payments

**Response:**
```json
{
  "status": true,
  "total": 15000.50
}
```

---

### 5. Get Pending Payments

**Endpoint:** `GET /api/v2/partner-payments/pending`

**Description:** Get all pending payments (not validated).

**Response:**
```json
{
  "status": true,
  "data": [...]
}
```

---

### 6. Get Validated Payments

**Endpoint:** `GET /api/v2/partner-payments/validated`

**Description:** Get all validated payments.

**Query Parameters:**
- `from_date` (optional, date): Start date
- `to_date` (optional, date): End date

**Response:**
```json
{
  "status": true,
  "data": [...]
}
```

---

### 7. Get Payment Statistics

**Endpoint:** `GET /api/v2/partner-payments/stats`

**Description:** Get payment statistics.

**Response:**
```json
{
  "status": true,
  "data": {
    "total_payments": 100,
    "pending_payments": 10,
    "validated_payments": 85,
    "rejected_payments": 5,
    "total_amount": 150000.00,
    "pending_amount": 5000.00
  }
}
```

---

### 8. Get Payment Methods

**Endpoint:** `GET /api/v2/partner-payments/payment-methods`

**Description:** Get list of all payment methods used.

**Response:**
```json
{
  "status": true,
  "data": ["bank_transfer", "paypal", "stripe", "wire_transfer"]
}
```

---

### 9. Create Payment

**Endpoint:** `POST /api/v2/partner-payments`

**Description:** Create a new partner payment.

**Request Body:**
```json
{
  "amount": 1500.50,
  "method": "bank_transfer",
  "payment_date": "2024-02-09",
  "partner_id": 1
}
```

**Response:**
```json
{
  "status": true,
  "data": {
    "id": 1,
    "amount": 1500.50,
    ...
  }
}
```

---

### 10. Update Payment

**Endpoint:** `PUT /api/v2/partner-payments/{id}`

**Description:** Update an existing payment.

**Path Parameters:**
- `id` (required, integer): Payment ID

**Request Body:**
```json
{
  "amount": 2000.00,
  "method": "paypal"
}
```

**Response:**
```json
{
  "status": true,
  "data": {...},
  "message": "Payment updated successfully"
}
```

---

### 11. Validate Payment

**Endpoint:** `POST /api/v2/partner-payments/{id}/validate`

**Description:** Validate a payment.

**Path Parameters:**
- `id` (required, integer): Payment ID

**Request Body:**
```json
{
  "validator_id": 1
}
```

**Response:**
```json
{
  "status": true,
  "data": {...},
  "message": "Payment validated successfully"
}
```

---

### 12. Reject Payment

**Endpoint:** `POST /api/v2/partner-payments/{id}/reject`

**Description:** Reject a payment with optional reason.

**Path Parameters:**
- `id` (required, integer): Payment ID

**Request Body:**
```json
{
  "rejector_id": 1,
  "reason": "Insufficient documentation"
}
```

**Response:**
```json
{
  "status": true,
  "data": {...},
  "message": "Payment rejected successfully"
}
```

---

### 13. Delete Payment

**Endpoint:** `DELETE /api/v2/partner-payments/{id}`

**Description:** Delete a payment (cannot delete validated payments).

**Path Parameters:**
- `id` (required, integer): Payment ID

**Response:**
```json
{
  "status": true,
  "message": "Payment deleted successfully"
}
```

---

## Postman Collection

A complete Postman collection has been generated at:
```
/postman/2Earn_API_v2_RoleService_PartnerService_PartnerPaymentService_Collection.json
```

**To import:**
1. Open Postman
2. Click "Import" button
3. Select the JSON file
4. The collection will be imported with all endpoints pre-configured

**Environment Variable:**
- `base_url`: Set this to your application URL (e.g., `http://localhost` or `https://your-domain.com`)

---

## Response Format

All endpoints follow a consistent response format:

### Success Response
```json
{
  "status": true,
  "data": {...},
  "message": "Optional success message"
}
```

### Error Response
```json
{
  "status": false,
  "message": "Error description",
  "errors": {
    "field_name": ["Validation error message"]
  }
}
```

---

## Error Handling

### HTTP Status Codes

- **200 OK**: Request successful
- **201 Created**: Resource created successfully
- **400 Bad Request**: Invalid request or business logic error
- **404 Not Found**: Resource not found
- **422 Unprocessable Entity**: Validation error
- **500 Internal Server Error**: Server error

### Common Error Scenarios

1. **Validation Errors (422)**
   ```json
   {
     "status": false,
     "errors": {
       "name": ["The name field is required."]
     }
   }
   ```

2. **Not Found (404)**
   ```json
   {
     "status": false,
     "message": "Role not found"
   }
   ```

3. **Business Logic Error (400/500)**
   ```json
   {
     "status": false,
     "message": "This Role cannot be deleted!"
   }
   ```

---

## Service Methods Exposed

### RoleService

The following methods from `RoleService` are exposed:

- `getById()` - Get role by ID
- `getByIdOrFail()` - Get role or throw exception (used internally)
- `getPaginated()` - Get paginated roles
- `getAll()` - Get all roles
- `create()` - Create role
- `update()` - Update role
- `delete()` - Delete role
- `canDelete()` - Check if role can be deleted
- `getUserRoles()` - Get user roles with pagination

**Note:** Roles with ID 1-4 are protected system roles and cannot be deleted.

---

### PartnerService

The following methods from `PartnerService` are exposed:

- `getAllPartners()` - Get all partners
- `getPartnerById()` - Get partner by ID
- `createPartner()` - Create partner
- `updatePartner()` - Update partner
- `deletePartner()` - Delete partner
- `getFilteredPartners()` - Get filtered partners with pagination
- `getPartnersByBusinessSector()` - Get partners by business sector
- `searchPartnersByCompanyName()` - Search partners by company name

---

### PartnerPaymentService

The following methods from `PartnerPaymentService` are exposed:

- `create()` - Create payment
- `update()` - Update payment
- `validatePayment()` - Validate payment
- `rejectPayment()` - Reject payment
- `getByPartnerId()` - Get payments by partner ID
- `getById()` - Get payment by ID
- `getPayments()` - Get payments with filters and pagination
- `delete()` - Delete payment
- `getTotalPaymentsByPartner()` - Get total payments by partner
- `getPendingPayments()` - Get pending payments
- `getValidatedPayments()` - Get validated payments
- `getStats()` - Get payment statistics
- `getPaymentMethods()` - Get payment methods

**Note:** Validated payments cannot be deleted. Payments trigger notifications to partners upon validation/rejection.

---

## Implementation Files

### Controllers
- `/app/Http/Controllers/Api/v2/RoleController.php` - Role endpoints
- `/app/Http/Controllers/Api/v2/PartnerController.php` - Partner endpoints
- `/app/Http/Controllers/Api/v2/PartnerPaymentController.php` - Partner payment endpoints

### Routes
- `/routes/api.php` - All route definitions under `api/v2/` prefix

### Services
- `/app/Services/Role/RoleService.php` - Role business logic
- `/app/Services/Partner/PartnerService.php` - Partner business logic
- `/app/Services/PartnerPayment/PartnerPaymentService.php` - Partner payment business logic

---

## Notes

1. **Route Naming Convention**: All routes are named with `api_v2_` prefix followed by the resource name
   - Example: `api_v2_roles_index`, `api_v2_partners_show`, `api_v2_partner_payments_validate`

2. **Middleware**: Routes are configured with `withoutMiddleware([\App\Http\Middleware\Authenticate::class])`
   - Add your own authentication middleware as needed

3. **Validation**: All inputs are validated using Laravel's Validator facade

4. **Error Logging**: Errors are logged in the service layer for debugging

5. **Database Transactions**: Partner payment operations use transactions for data integrity

6. **Notifications**: Partner payments trigger email notifications on validation/rejection

7. **Protected Resources**: 
   - Roles with ID 1-4 cannot be deleted (system roles)
   - Validated payments cannot be deleted

---

## Support

For questions or issues, please contact the development team or refer to the main application documentation.

---

**Generated:** February 9, 2026  
**Version:** 1.0  
**API Version:** v2

