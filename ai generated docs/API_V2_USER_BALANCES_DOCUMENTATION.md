# User Balances API v2 Documentation

## Overview
This document describes the API v2 endpoints for managing user balances through the exposed services:
- `UserBalancesHelper`
- `UserCurrentBalanceVerticalService`
- `UserCurrentBalanceHorisontalService`

All endpoints are prefixed with `/api/v2/user-balances/`.

## Authentication
These endpoints are currently configured without authentication middleware for testing purposes. In production, ensure proper authentication is enabled.

## Base URL
```
http://localhost/api/v2/user-balances
```

---

## Horizontal Balance Endpoints

Horizontal balances track user balance information in a single row per user with multiple balance type columns.

### 1. Get User Horizontal Balance
Retrieve the complete horizontal balance record for a user.

**Endpoint:** `GET /horizontal/{userId}`

**Response:**
```json
{
  "status": true,
  "data": {
    "id": 1,
    "user_id": 1,
    "user_id_auto": 1,
    "cash_balance": 1500.50,
    "bfss_balance": [...],
    "discount_balance": 200.00,
    "tree_balance": 50.00,
    "sms_balance": 25,
    "share_balance": 100.00,
    "chances_balance": [...],
    "created_at": "2026-01-01T00:00:00.000000Z",
    "updated_at": "2026-02-09T00:00:00.000000Z"
  }
}
```

---

### 2. Get Horizontal Balance Field
Retrieve a specific balance field value.

**Endpoint:** `GET /horizontal/{userId}/field/{field}`

**Path Parameters:**
- `field`: The field name (e.g., `cash_balance`, `discount_balance`, `tree_balance`, `sms_balance`)

**Example:** `GET /horizontal/1/field/cash_balance`

**Response:**
```json
{
  "status": true,
  "data": {
    "field": "cash_balance",
    "value": 1500.50
  }
}
```

---

### 3. Get Cash Balance
Retrieve user's cash balance.

**Endpoint:** `GET /horizontal/{userId}/cash`

**Response:**
```json
{
  "status": true,
  "data": {
    "cash_balance": 1500.50
  }
}
```

---

### 4. Get BFSS Balance by Type
Retrieve user's BFSS balance for a specific type.

**Endpoint:** `GET /horizontal/{userId}/bfss/{type}`

**Path Parameters:**
- `type`: BFSS type identifier (e.g., `type1`, `100`)

**Response:**
```json
{
  "status": true,
  "data": {
    "bfss_balance": 500.00,
    "type": "type1"
  }
}
```

---

### 5. Get Discount Balance
Retrieve user's discount balance.

**Endpoint:** `GET /horizontal/{userId}/discount`

**Response:**
```json
{
  "status": true,
  "data": {
    "discount_balance": 200.00
  }
}
```

---

### 6. Get Tree Balance
Retrieve user's tree balance.

**Endpoint:** `GET /horizontal/{userId}/tree`

**Response:**
```json
{
  "status": true,
  "data": {
    "tree_balance": 50.00
  }
}
```

---

### 7. Get SMS Balance
Retrieve user's SMS balance.

**Endpoint:** `GET /horizontal/{userId}/sms`

**Response:**
```json
{
  "status": true,
  "data": {
    "sms_balance": 25
  }
}
```

---

### 8. Update Calculated Horizontal Balance
Update a calculated horizontal balance field.

**Endpoint:** `PUT /horizontal/{userId}/calculated`

**Request Body:**
```json
{
  "type": "cash_balance",
  "value": 1500.50
}
```

**Response:**
```json
{
  "status": true,
  "message": "Balance updated successfully",
  "data": {
    "rows_updated": 1
  }
}
```

---

### 9. Update Balance Field
Update a specific balance field with a new value.

**Endpoint:** `PUT /horizontal/{userId}/field`

**Request Body:**
```json
{
  "balance_field": "share_balance",
  "new_balance": 250.75
}
```

**Response:**
```json
{
  "status": true,
  "message": "Balance field updated successfully"
}
```

**Error Response:**
```json
{
  "status": false,
  "message": "Failed to update balance"
}
```

---

### 10. Calculate New Balance
Calculate the new balance after applying a change amount (used for simulation/preview).

**Endpoint:** `POST /horizontal/{userId}/calculate`

**Request Body:**
```json
{
  "balance_field": "cash_balance",
  "change_amount": 100.00
}
```

**Note:** `change_amount` can be positive (addition) or negative (subtraction).

**Response:**
```json
{
  "status": true,
  "data": {
    "record": {
      "id": 1,
      "user_id": 1,
      "cash_balance": 1500.50,
      ...
    },
    "currentBalance": 1500.50,
    "newBalance": 1600.50
  }
}
```

---

## Vertical Balance Endpoints

Vertical balances track user balance information with separate rows for each balance type, providing detailed operation history.

### 1. Get Vertical Balance by Type
Retrieve user's vertical balance record for a specific balance type.

**Endpoint:** `GET /vertical/{userId}/{balanceId}`

**Path Parameters:**
- `balanceId`: Balance type ID or BalanceEnum value
  - `1` = CASH
  - `2` = BFSS
  - `3` = DISCOUNT
  - `4` = TREE
  - `5` = SMS

**Response:**
```json
{
  "status": true,
  "data": {
    "id": 1,
    "user_id": 1,
    "balance_id": 1,
    "current_balance": 1500.50,
    "previous_balance": 1400.50,
    "last_operation_id": 123,
    "last_operation_value": 100.00,
    "last_operation_date": "2026-02-09 10:30:00",
    "created_at": "2026-01-01T00:00:00.000000Z",
    "updated_at": "2026-02-09T10:30:00.000000Z"
  }
}
```

**Error Response:**
```json
{
  "status": false,
  "message": "Vertical balance not found"
}
```

---

### 2. Get All Vertical Balances
Retrieve all vertical balance records for a user.

**Endpoint:** `GET /vertical/{userId}/all`

**Response:**
```json
{
  "status": true,
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "balance_id": 1,
      "current_balance": 1500.50,
      "previous_balance": 1400.50,
      "last_operation_id": 123,
      "last_operation_value": 100.00,
      "last_operation_date": "2026-02-09 10:30:00"
    },
    {
      "id": 2,
      "user_id": 1,
      "balance_id": 2,
      "current_balance": 500.00,
      "previous_balance": 450.00,
      "last_operation_id": 124,
      "last_operation_value": 50.00,
      "last_operation_date": "2026-02-09 11:00:00"
    }
  ]
}
```

---

### 3. Update Vertical Balance After Operation
Update vertical balance after a balance operation, tracking operation history.

**Endpoint:** `PUT /vertical/{userId}/update-after-operation`

**Request Body:**
```json
{
  "balance_id": 1,
  "balance_change": 50.00,
  "last_operation_id": 123,
  "last_operation_value": 50.00,
  "last_operation_date": "2026-02-09 10:30:00"
}
```

**Field Descriptions:**
- `balance_id`: Balance type ID (1=CASH, 2=BFSS, 3=DISCOUNT, 4=TREE, 5=SMS)
- `balance_change`: Amount to add/subtract from current balance (positive or negative)
- `last_operation_id`: ID of the balance operation being applied
- `last_operation_value`: Value of the operation
- `last_operation_date`: Date/time of the operation (format: Y-m-d H:i:s)

**Response:**
```json
{
  "status": true,
  "message": "Vertical balance updated successfully"
}
```

**Error Response:**
```json
{
  "status": false,
  "message": "Failed to update vertical balance"
}
```

---

### 4. Update Calculated Vertical Balance
Update a calculated vertical balance by balance type.

**Endpoint:** `PUT /vertical/{userId}/calculated`

**Request Body:**
```json
{
  "type": 1,
  "value": 500.00
}
```

**Note:** `type` can be a balance ID (integer) or BalanceEnum value.

**Response:**
```json
{
  "status": true,
  "message": "Vertical balance updated successfully",
  "data": {
    "rows_updated": 1
  }
}
```

---

## Error Handling

All endpoints follow a consistent error response format:

### Validation Error (422)
```json
{
  "status": false,
  "errors": {
    "field_name": [
      "Error message"
    ]
  }
}
```

### Not Found Error (404)
```json
{
  "status": false,
  "message": "Resource not found"
}
```

### Bad Request Error (400)
```json
{
  "status": false,
  "message": "Error description"
}
```

---

## Balance Type Reference

### BalanceEnum Values
- `1` - CASH: User's cash balance
- `2` - BFSS: Business First Sharing System balance
- `3` - DISCOUNT: Discount balance
- `4` - TREE: Tree/referral balance
- `5` - SMS: SMS credit balance
- `6` - SHARE: Share balance
- `7` - CHANCE: Chance/lottery balance

---

## Postman Collection

A complete Postman collection is available at:
```
/postman/User_Balances_API_v2_Collection.json
```

Import this collection into Postman to test all endpoints with pre-configured requests.

### Collection Variables
- `base_url`: Default is `http://localhost/api/v2`
- `user_id`: Default test user ID is `1`

You can modify these variables in Postman to match your environment.

---

## Implementation Details

### Services Used

1. **UserCurrentBalanceHorisontalService**
   - Manages horizontal balance records (one row per user)
   - Methods: `getStoredUserBalances()`, `getStoredCash()`, `getStoredBfss()`, `getStoredDiscount()`, `getStoredTree()`, `getStoredSms()`, `updateCalculatedHorisental()`, `updateBalanceField()`, `calculateNewBalance()`

2. **UserCurrentBalanceVerticalService**
   - Manages vertical balance records (multiple rows per user, one per balance type)
   - Methods: `getUserBalanceVertical()`, `getAllUserBalances()`, `updateBalanceAfterOperation()`, `updateCalculatedVertical()`

3. **UserBalancesHelper**
   - Currently injected for future use
   - Contains helper methods for balance operations

---

## Testing

### Manual Testing
1. Import the Postman collection
2. Set the `user_id` variable to a valid user ID in your database
3. Test each endpoint sequentially

### Sample Test Flow
1. Get horizontal balance: `GET /horizontal/1`
2. Get cash balance: `GET /horizontal/1/cash`
3. Calculate new balance: `POST /horizontal/1/calculate` with `{"balance_field": "cash_balance", "change_amount": 100}`
4. Update balance field: `PUT /horizontal/1/field` with `{"balance_field": "cash_balance", "new_balance": 1600.50}`
5. Get all vertical balances: `GET /vertical/1/all`
6. Get specific vertical balance: `GET /vertical/1/1`

---

## Notes

- All monetary values should be provided as numbers (integer or float)
- Dates should be in the format: `Y-m-d H:i:s` (e.g., `2026-02-09 10:30:00`)
- The `balance_change` parameter in vertical updates can be positive (credit) or negative (debit)
- Currently, no authentication middleware is applied - add appropriate middleware in production

---

## Route Names

All routes are named with the `api_v2_user_balances_` prefix:

**Horizontal:**
- `api_v2_user_balances_horizontal_get`
- `api_v2_user_balances_horizontal_get_field`
- `api_v2_user_balances_horizontal_cash`
- `api_v2_user_balances_horizontal_bfss`
- `api_v2_user_balances_horizontal_discount`
- `api_v2_user_balances_horizontal_tree`
- `api_v2_user_balances_horizontal_sms`
- `api_v2_user_balances_horizontal_update_calculated`
- `api_v2_user_balances_horizontal_update_field`
- `api_v2_user_balances_horizontal_calculate`

**Vertical:**
- `api_v2_user_balances_vertical_all`
- `api_v2_user_balances_vertical_get`
- `api_v2_user_balances_vertical_update_after_operation`
- `api_v2_user_balances_vertical_update_calculated`

---

## Support

For issues or questions regarding these endpoints, refer to:
- Service implementations in `app/Services/UserBalances/`
- Controller implementation in `app/Http/Controllers/Api/v2/UserBalancesController.php`
- Route definitions in `routes/api.php`

