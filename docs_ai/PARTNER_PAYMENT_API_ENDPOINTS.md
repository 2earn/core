# Partner Payment API Endpoints Documentation

## Overview
Complete REST API for partner payments management including listing payments, viewing details, creating financial demands, and retrieving statistics.

**Base URL:** `/api/partner/payment`

**Authentication:** Requires `check.url` middleware (API key/token validation)

---

## Endpoints

### 1. Get Partner Payments List

**Endpoint:** `GET /api/partner/payment/`

**Route Name:** `api_partner_payments_index`

**Description:** Retrieve a paginated list of partner payments with optional filtering.

#### Request Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| user_id | integer | ✅ Yes | User ID of the partner (must be platform partner) |
| partner_id | integer | ❌ No | Filter by specific partner ID |
| status | string | ❌ No | Filter by status: `all`, `pending`, `validated` |
| method | string | ❌ No | Filter by payment method |
| from_date | date | ❌ No | Filter from date (YYYY-MM-DD) |
| to_date | date | ❌ No | Filter to date (YYYY-MM-DD) |
| page | integer | ❌ No | Page number (default: 1) |
| limit | integer | ❌ No | Items per page (default: 15, max: 100) |

#### Example Request

```bash
GET /api/partner/payment/?user_id=123&status=pending&page=1&limit=10
```

#### Success Response (200 OK)

```json
{
  "status": "Success",
  "message": "Partner payments retrieved successfully",
  "data": {
    "payments": [
      {
        "id": 1,
        "amount": "1500.50",
        "method": "bank_transfer",
        "payment_date": "2024-12-15 10:30:00",
        "user_id": 45,
        "partner_id": 123,
        "demand_id": "REQ123456",
        "validated_by": null,
        "validated_at": null,
        "created_at": "2024-12-15 09:00:00",
        "updated_at": "2024-12-15 09:00:00",
        "user": {
          "id": 45,
          "name": "John Doe",
          "email": "john@example.com"
        },
        "partner": {
          "id": 123,
          "name": "Jane Smith",
          "email": "jane@example.com"
        },
        "demand": null,
        "validator": null
      }
    ],
    "statistics": {
      "total_payments": 150,
      "pending_payments": 25,
      "validated_payments": 125,
      "total_amount": 245000.50,
      "pending_amount": 12500.00
    },
    "pagination": {
      "current_page": 1,
      "per_page": 10,
      "total": 150,
      "total_pages": 15
    }
  }
}
```

#### Error Responses

**Validation Error (422)**
```json
{
  "status": "Failed",
  "message": "Validation failed",
  "errors": {
    "user_id": ["The user_id field is required."]
  }
}
```

**Unauthorized (403)**
```json
{
  "status": "Failed",
  "message": "User is not a platform partner"
}
```

---

### 2. Get Single Partner Payment

**Endpoint:** `GET /api/partner/payment/{id}`

**Route Name:** `api_partner_payments_show`

**Description:** Retrieve detailed information about a specific partner payment.

#### Request Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| user_id | integer | ✅ Yes | User ID of the partner |
| {id} | integer | ✅ Yes | Payment ID (in URL) |

#### Example Request

```bash
GET /api/partner/payment/1?user_id=123
```

#### Success Response (200 OK)

```json
{
  "status": "Success",
  "message": "Partner payment retrieved successfully",
  "data": {
    "id": 1,
    "amount": "1500.50",
    "method": "bank_transfer",
    "payment_date": "2024-12-15 10:30:00",
    "user_id": 45,
    "partner_id": 123,
    "demand_id": "REQ123456",
    "validated_by": 999,
    "validated_at": "2024-12-16 14:20:00",
    "created_by": 45,
    "updated_by": null,
    "created_at": "2024-12-15 09:00:00",
    "updated_at": "2024-12-16 14:20:00",
    "user": {
      "id": 45,
      "name": "John Doe",
      "email": "john@example.com"
    },
    "partner": {
      "id": 123,
      "name": "Jane Smith",
      "email": "jane@example.com"
    },
    "demand": {
      "numeroReq": "REQ123456",
      "amount": "1500.50",
      "status": 1
    },
    "validator": {
      "id": 999,
      "name": "Admin User",
      "email": "admin@example.com"
    }
  }
}
```

#### Error Responses

**Not Found (404)**
```json
{
  "status": "Failed",
  "message": "Partner payment not found",
  "error": "No query results for model [PartnerPayment] 1"
}
```

**Unauthorized (403)**
```json
{
  "status": "Failed",
  "message": "Unauthorized access to this payment"
}
```

---

### 3. Create Demand for Partner Payment

**Endpoint:** `POST /api/partner/payment/demand`

**Route Name:** `api_partner_payments_create_demand`

**Description:** Create a financial request (demand) for partner payments.

#### Request Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| user_id | integer | ✅ Yes | User ID of the partner creating the demand |
| amount | numeric | ✅ Yes | Total amount to request |
| payment_ids | array | ❌ No | Array of payment IDs to link to this demand |
| note | string | ❌ No | Additional note/description (max 500 chars) |

#### Example Request

```bash
POST /api/partner/payment/demand
Content-Type: application/json

{
  "user_id": 123,
  "amount": 5000.00,
  "payment_ids": [1, 2, 3, 4],
  "note": "Request for monthly payments settlement"
}
```

#### Success Response (201 Created)

```json
{
  "status": "Success",
  "message": "Demand created successfully",
  "data": {
    "demand_id": "REQ789012",
    "amount": "5000.00",
    "status": 0,
    "date": "2024-12-18 10:30:00",
    "security_code": "A1B2C3D4",
    "linked_payments": 4
  }
}
```

#### Business Rules

1. **Partner Verification:** User must be a platform partner (financial manager, marketing manager, or owner)
2. **Payment Ownership:** All `payment_ids` must belong to the requesting partner
3. **Amount Validation:** If `payment_ids` provided, total validated payments must match requested amount
4. **Demand Linking:** Payments will be linked to the demand via `demand_id` field
5. **Security Code:** Auto-generated unique 8-character code for the demand

#### Error Responses

**Amount Mismatch (400)**
```json
{
  "status": "Failed",
  "message": "Amount does not match total of selected payments",
  "details": {
    "requested_amount": 5000.00,
    "payments_total": 4500.00
  }
}
```

**Unauthorized Payments (403)**
```json
{
  "status": "Failed",
  "message": "Some payment IDs do not belong to this partner"
}
```

---

### 4. Get Payment Statistics

**Endpoint:** `GET /api/partner/payment/statistics/summary`

**Route Name:** `api_partner_payments_statistics`

**Description:** Retrieve comprehensive statistics about partner payments.

#### Request Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| user_id | integer | ✅ Yes | User ID of the partner |
| from_date | date | ❌ No | Start date for statistics (YYYY-MM-DD) |
| to_date | date | ❌ No | End date for statistics (YYYY-MM-DD) |

#### Example Request

```bash
GET /api/partner/payment/statistics/summary?user_id=123&from_date=2024-01-01&to_date=2024-12-31
```

#### Success Response (200 OK)

```json
{
  "status": "Success",
  "message": "Statistics retrieved successfully",
  "data": {
    "total_payments": 150,
    "pending_payments": 25,
    "validated_payments": 125,
    "total_amount": 245000.50,
    "pending_amount": 12500.00,
    "payment_methods": [
      {
        "method": "bank_transfer",
        "count": 100
      },
      {
        "method": "cash",
        "count": 30
      },
      {
        "method": "check",
        "count": 20
      }
    ],
    "monthly_totals": [
      {
        "month": "2024-12",
        "total": "45000.00",
        "count": 15
      },
      {
        "month": "2024-11",
        "total": "38000.00",
        "count": 12
      }
    ]
  }
}
```

---

## Authorization

### Partner Verification

All endpoints verify that the `user_id` belongs to a platform partner by checking if the user exists in the `platforms` table as:
- `financial_manager_id`
- `marketing_manager_id`
- `owner_id`

If the user is not a partner, the API returns:
```json
{
  "status": "Failed",
  "message": "User is not a platform partner"
}
```
**HTTP Status:** 403 Forbidden

---

## Common Error Responses

### 422 Unprocessable Entity (Validation Error)
```json
{
  "status": "Failed",
  "message": "Validation failed",
  "errors": {
    "field_name": ["Error message"]
  }
}
```

### 403 Forbidden
```json
{
  "status": "Failed",
  "message": "User is not a platform partner"
}
```

### 404 Not Found
```json
{
  "status": "Failed",
  "message": "Partner payment not found",
  "error": "Error details"
}
```

### 500 Internal Server Error
```json
{
  "status": "Failed",
  "message": "Failed to process request",
  "error": "Error details"
}
```

---

## Usage Examples

### Example 1: Get All Pending Payments

```bash
curl -X GET "https://api.example.com/api/partner/payment/?user_id=123&status=pending" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

### Example 2: Get Payment Details

```bash
curl -X GET "https://api.example.com/api/partner/payment/1?user_id=123" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

### Example 3: Create Demand with Multiple Payments

```bash
curl -X POST "https://api.example.com/api/partner/payment/demand" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "user_id": 123,
    "amount": 10000.00,
    "payment_ids": [1, 2, 3, 4, 5],
    "note": "Monthly settlement for December 2024"
  }'
```

### Example 4: Get Statistics for Current Year

```bash
curl -X GET "https://api.example.com/api/partner/payment/statistics/summary?user_id=123&from_date=2024-01-01&to_date=2024-12-31" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

---

## Payment Methods

Common payment methods used in the system:
- `bank_transfer`
- `cash`
- `check`
- `online_payment`
- `mobile_payment`
- `wire_transfer`
- `other`

---

## Payment Status

- **Pending:** `validated_at` is `null`
- **Validated:** `validated_at` is not `null`

---

## Demand (Financial Request) Status

| Code | Status | Description |
|------|--------|-------------|
| 0 | Pending | Awaiting approval |
| 1 | Accepted | Approved and processed |
| 5 | Refused | Rejected |

---

## Testing with Postman

### Collection Setup

1. **Base URL:** `https://your-domain.com/api`
2. **Authorization:** Configure based on your auth method
3. **Headers:**
   - `Accept: application/json`
   - `Content-Type: application/json`

### Test Sequence

1. ✅ Get payments list
2. ✅ Get single payment details
3. ✅ Get statistics
4. ✅ Create demand
5. ✅ Verify demand was created
6. ✅ Check payments linked to demand

---

## Database Tables Used

### partner_payments
- Stores all partner payment records
- Links to users (payer and partner)
- Links to financial_request (demand)

### platforms
- Validates partner role
- Checks financial_manager_id, marketing_manager_id, owner_id

### financial_request
- Stores demand/financial request records
- Auto-generated numeroReq (primary key)

### detail_financial_request
- Stores additional details/notes for demands

---

## Security Considerations

1. **Partner Verification:** All endpoints verify user is a legitimate platform partner
2. **Payment Ownership:** Users can only access payments where they are the partner
3. **Demand Validation:** Amount must match linked payments total
4. **Security Code:** Each demand gets a unique security code for verification
5. **Logging:** All operations are logged for audit trail

---

## Rate Limiting

Consider implementing rate limiting for production:
- Recommended: 60 requests per minute per user
- Headers: `X-RateLimit-Limit`, `X-RateLimit-Remaining`

---

## Changelog

### Version 1.0.0 (December 18, 2024)
- ✅ Initial release
- ✅ GET payments list endpoint
- ✅ GET payment details endpoint
- ✅ POST create demand endpoint
- ✅ GET statistics endpoint
- ✅ Partner verification
- ✅ Comprehensive validation
- ✅ Error handling and logging

---

## Support & Documentation

**Controller:** `App\Http\Controllers\Api\partner\PartnerPaymentController`  
**Service:** `App\Services\PartnerPayment\PartnerPaymentService`  
**Model:** `App\Models\PartnerPayment`  
**Routes:** `routes/api.php`

**Related Documentation:**
- `PARTNER_PAYMENT_IMPLEMENTATION.md` - System implementation
- `PARTNER_PAYMENT_VALIDATION_UPDATE.md` - Validation rules
- `PARTNER_PAYMENT_COMPLETE_SETUP.md` - Complete setup guide

---

**Status:** ✅ Production Ready  
**Version:** 1.0.0  
**Last Updated:** December 18, 2024

