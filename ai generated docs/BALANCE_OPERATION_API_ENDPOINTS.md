# Balance Operation Service API Endpoints

## Overview
This document describes the API endpoints that expose all methods from the `BalanceOperationService` class.

All endpoints are protected by the `auth:sanctum` middleware and are prefixed with `/api/v1`.

## Base URL
```
/api/v1/balance/operations
```

## Endpoints

### 1. Get Operations (DataTables)
Returns balance operations formatted for DataTables.

**Endpoint:** `GET /api/v1/balance/operations`  
**Route Name:** `api_balance_operations`  
**Method:** `index()`

**Response:**
```json
{
  "data": [...],
  "draw": 1,
  "recordsTotal": 100,
  "recordsFiltered": 100
}
```

---

### 2. Get Filtered Operations
Get filtered and paginated balance operations with search functionality.

**Endpoint:** `GET /api/v1/balance/operations/filtered`  
**Route Name:** `api_balance_operations_filtered`  
**Method:** `getFilteredOperations()`

**Query Parameters:**
- `search` (string, optional) - Search term to filter operations
- `per_page` (integer, optional, default: 10) - Number of results per page

**Example Request:**
```
GET /api/v1/balance/operations/filtered?search=transfer&per_page=20
```

**Response:**
```json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "operation": "Transfer",
      "io": "I",
      "source": "system",
      "mode": "automatic",
      "balance_id": 123,
      "parent_operation_id": null,
      "parent": null,
      "opeartionCategory": {
        "id": 1,
        "name": "Transfer"
      },
      "created_at": "2026-02-09T10:00:00.000000Z",
      "updated_at": "2026-02-09T10:00:00.000000Z"
    }
  ],
  "first_page_url": "...",
  "from": 1,
  "last_page": 5,
  "last_page_url": "...",
  "links": [...],
  "next_page_url": "...",
  "path": "...",
  "per_page": 20,
  "prev_page_url": null,
  "to": 20,
  "total": 100
}
```

---

### 3. Get All Operations
Retrieve all balance operations without pagination.

**Endpoint:** `GET /api/v1/balance/operations/all`  
**Route Name:** `api_balance_operations_all`  
**Method:** `getAllOperations()`

**Response:**
```json
[
  {
    "id": 1,
    "operation": "Transfer",
    "io": "I",
    "source": "system",
    "mode": "automatic",
    "balance_id": 123,
    "parent_operation_id": null,
    "parent": null,
    "opeartionCategory": {
      "id": 1,
      "name": "Transfer"
    },
    "created_at": "2026-02-09T10:00:00.000000Z",
    "updated_at": "2026-02-09T10:00:00.000000Z"
  }
]
```

---

### 4. Get Operation by ID
Retrieve a specific balance operation by its ID.

**Endpoint:** `GET /api/v1/balance/operations/{id}`  
**Route Name:** `api_balance_operations_show`  
**Method:** `show()`

**URL Parameters:**
- `id` (integer, required) - The operation ID

**Example Request:**
```
GET /api/v1/balance/operations/1
```

**Success Response (200):**
```json
{
  "id": 1,
  "operation": "Transfer",
  "io": "I",
  "source": "system",
  "mode": "automatic",
  "amounts_id": 1,
  "note": "Monthly transfer",
  "modify_amount": true,
  "parent_id": null,
  "operation_category_id": 1,
  "ref": "REF001",
  "direction": "IN",
  "balance_id": 123,
  "parent_operation_id": null,
  "parent": null,
  "opeartionCategory": {
    "id": 1,
    "name": "Transfer"
  },
  "created_at": "2026-02-09T10:00:00.000000Z",
  "updated_at": "2026-02-09T10:00:00.000000Z"
}
```

**Error Response (404):**
```json
{
  "message": "Operation not found"
}
```

---

### 5. Create Operation
Create a new balance operation.

**Endpoint:** `POST /api/v1/balance/operations`  
**Route Name:** `api_balance_operations_store`  
**Method:** `store()`

**Request Body:**
```json
{
  "operation": "Transfer",
  "io": "I",
  "source": "system",
  "mode": "automatic",
  "amounts_id": 1,
  "note": "Monthly transfer",
  "modify_amount": true,
  "parent_id": null,
  "operation_category_id": 1,
  "ref": "REF001",
  "direction": "IN",
  "balance_id": 123,
  "parent_operation_id": null,
  "relateble": 456,
  "relateble_model": "App\\Models\\User",
  "relateble_types": "user"
}
```

**Validation Rules:**
- `operation` - required, string, max 255 characters
- `io` - optional, string
- `source` - optional, string
- `mode` - optional, string
- `amounts_id` - optional, integer
- `note` - optional, string
- `modify_amount` - optional, boolean
- `parent_id` - optional, integer, must exist in balance_operations table
- `operation_category_id` - optional, integer, must exist in operation_categories table
- `ref` - optional, string
- `direction` - optional, string
- `balance_id` - optional, integer
- `parent_operation_id` - optional, integer
- `relateble` - optional, integer
- `relateble_model` - optional, string
- `relateble_types` - optional, string

**Success Response (201):**
```json
{
  "id": 10,
  "operation": "Transfer",
  "io": "I",
  "source": "system",
  "mode": "automatic",
  "amounts_id": 1,
  "note": "Monthly transfer",
  "modify_amount": true,
  "parent_id": null,
  "operation_category_id": 1,
  "ref": "REF001",
  "direction": "IN",
  "balance_id": 123,
  "parent_operation_id": null,
  "created_at": "2026-02-09T10:00:00.000000Z",
  "updated_at": "2026-02-09T10:00:00.000000Z"
}
```

**Error Response (422):**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "operation": [
      "The operation field is required."
    ]
  }
}
```

---

### 6. Update Operation
Update an existing balance operation.

**Endpoint:** `PUT /api/v1/balance/operations/{id}`  
**Route Name:** `api_balance_operations_update`  
**Method:** `update()`

**URL Parameters:**
- `id` (integer, required) - The operation ID

**Request Body:**
```json
{
  "operation": "Updated Transfer",
  "note": "Updated note",
  "modify_amount": false
}
```

**Validation Rules:**
Same as create, but all fields are optional (use `sometimes` instead of `required`).

**Success Response (200):**
```json
{
  "message": "Operation updated successfully"
}
```

**Error Response (404):**
```json
{
  "message": "Operation not found"
}
```

---

### 7. Delete Operation
Delete a balance operation.

**Endpoint:** `DELETE /api/v1/balance/operations/{id}`  
**Route Name:** `api_balance_operations_destroy`  
**Method:** `destroy()`

**URL Parameters:**
- `id` (integer, required) - The operation ID

**Example Request:**
```
DELETE /api/v1/balance/operations/1
```

**Success Response (200):**
```json
{
  "message": "Operation deleted successfully"
}
```

**Error Response (404):**
```json
{
  "message": "Operation not found"
}
```

---

### 8. Get Category Name
Get the name of an operation category by its ID.

**Endpoint:** `GET /api/v1/balance/operations/category/{categoryId}/name`  
**Route Name:** `api_balance_operations_category_name`  
**Method:** `getCategoryName()`

**URL Parameters:**
- `categoryId` (integer, required) - The category ID

**Example Request:**
```
GET /api/v1/balance/operations/category/1/name
```

**Response:**
```json
{
  "category_name": "Transfer"
}
```

If category not found, returns:
```json
{
  "category_name": "-"
}
```

---

### 9. Get Categories (DataTables)
Returns operation categories formatted for DataTables.

**Endpoint:** `GET /api/v1/balance/operations/categories`  
**Route Name:** `api_operations_categories`  
**Method:** `getCategories()`

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Transfer",
      "description": "Transfer operations",
      "action": "<html content>"
    }
  ],
  "draw": 1,
  "recordsTotal": 10,
  "recordsFiltered": 10
}
```

---

## Authentication

All endpoints require authentication using Laravel Sanctum. Include the Bearer token in the Authorization header:

```
Authorization: Bearer {your-token}
```

---

## Common Error Responses

### 401 Unauthorized
```json
{
  "message": "Unauthenticated."
}
```

### 422 Validation Error
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "field_name": [
      "Error message"
    ]
  }
}
```

### 500 Server Error
```json
{
  "message": "Server Error"
}
```

---

## Usage Examples

### cURL Examples

#### Get filtered operations
```bash
curl -X GET "https://your-domain.com/api/v1/balance/operations/filtered?search=transfer&per_page=20" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

#### Create a new operation
```bash
curl -X POST "https://your-domain.com/api/v1/balance/operations" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "operation": "Transfer",
    "io": "I",
    "source": "system",
    "note": "Test transfer"
  }'
```

#### Update an operation
```bash
curl -X PUT "https://your-domain.com/api/v1/balance/operations/1" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "note": "Updated note"
  }'
```

#### Delete an operation
```bash
curl -X DELETE "https://your-domain.com/api/v1/balance/operations/1" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

### JavaScript/Axios Examples

```javascript
// Get filtered operations
const getOperations = async () => {
  const response = await axios.get('/api/v1/balance/operations/filtered', {
    params: {
      search: 'transfer',
      per_page: 20
    },
    headers: {
      'Authorization': `Bearer ${token}`
    }
  });
  return response.data;
};

// Create operation
const createOperation = async (data) => {
  const response = await axios.post('/api/v1/balance/operations', data, {
    headers: {
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json'
    }
  });
  return response.data;
};

// Update operation
const updateOperation = async (id, data) => {
  const response = await axios.put(`/api/v1/balance/operations/${id}`, data, {
    headers: {
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json'
    }
  });
  return response.data;
};

// Delete operation
const deleteOperation = async (id) => {
  const response = await axios.delete(`/api/v1/balance/operations/${id}`, {
    headers: {
      'Authorization': `Bearer ${token}`
    }
  });
  return response.data;
};
```

---

## Notes

- All timestamps are returned in ISO 8601 format
- The `parent` and `opeartionCategory` relationships are eager loaded when available
- Pagination uses Laravel's standard pagination format
- The `io` field typically contains 'I' for input or 'O' for output
- The `direction` field typically contains 'IN' or 'OUT'
- Search functionality in `getFilteredOperations` searches across: id, operation, balance_id, and parent_operation_id fields

---

## Related Models

### BalanceOperation Model
Located at: `app/Models/BalanceOperation.php`

### OperationCategory Model
Located at: `app/Models/OperationCategory.php`

### BalanceOperationService
Located at: `app/Services/Balances/BalanceOperationService.php`

---

## Implementation Date
February 9, 2026

