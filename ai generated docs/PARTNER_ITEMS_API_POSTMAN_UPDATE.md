# Partner Items API - Postman Collection Update

**Date:** February 12, 2026  
**Status:** ✅ COMPLETED

---

## Overview

Updated the **Partner Items API** Postman collection to include all required fields based on the controller validation rules. The validation errors have been resolved by adding the missing required fields `ref` and `created_by`.

---

## Error Fixed

### **Previous Error Response:**
```json
{
    "status": "Failed",
    "message": "Validation failed",
    "errors": {
        "ref": [
            "The Reference field is required."
        ],
        "created_by": [
            "The created by field is required."
        ]
    }
}
```

### **Root Cause:**
The Postman collection's "Create Item" request was missing required fields:
- ❌ `ref` - Item reference/SKU (required, unique)
- ❌ `created_by` - User ID who created the item (required)

---

## Changes Made

### 1. **Create Item Endpoint** ✅

**Updated Request Body:**

**Before:**
```json
{
  "user_id": {{user_id}},
  "name": "Item Name",
  "description": "Item description",
  "price": 50.00,
  "platform_id": {{platform_id}}
}
```

**After:**
```json
{
  "name": "New Product 2026",
  "ref": "SKU-2026-001",
  "price": 50.00,
  "stock": 100,
  "description": "High quality product description",
  "photo_link": "https://example.com/photo.jpg",
  "discount": 10,
  "discount_2earn": 5,
  "platform_id": {{platform_id}},
  "deal_id": {{deal_id}},
  "created_by": {{user_id}}
}
```

### 2. **Update Item Endpoint** ✅

**Updated Request Body:**

**Before:**
```json
{
  "user_id": {{user_id}},
  "name": "Updated Item Name",
  "description": "Updated description",
  "price": 55.00
}
```

**After:**
```json
{
  "name": "Updated Item Name",
  "description": "Updated description",
  "price": 55.00,
  "stock": 150,
  "discount": 15,
  "discount_2earn": 7.5,
  "updated_by": {{user_id}}
}
```

**Changes:**
- ❌ Removed `user_id` (not used in validation)
- ✅ Added `updated_by` (required field)
- ✅ Added optional fields: `stock`, `discount`, `discount_2earn`
- ✅ Changed URL variable from `{{id}}` to `{{item_id}}` for consistency

### 3. **Remove Platform from Item Endpoint** ✅

**Updated from query parameters to request body:**

**Before:**
```
DELETE {{base_url}}/api/partner/items/:id/platform?user_id=384&platform_id=1&updated_by=384
```

**After:**
```
DELETE {{base_url}}/api/partner/items/{{item_id}}/platform

Body:
{
  "updated_by": {{user_id}}
}
```

---

## API Validation Rules

### **Create Item (POST /api/partner/items)**

#### **Required Fields:**
| Field | Type | Validation | Description |
|-------|------|------------|-------------|
| `name` | string | required, max:255 | Item name |
| `ref` | string | required, unique:items,ref | Item reference/SKU (must be unique) |
| `price` | numeric | required, min:0 | Item price |
| `created_by` | integer | required, exists:users,id | User ID who creates the item |

#### **Optional Fields:**
| Field | Type | Validation | Description |
|-------|------|------------|-------------|
| `stock` | integer | nullable, min:0 | Stock quantity (defaults to 0 if not provided) |
| `description` | string | nullable | Item description |
| `photo_link` | string | nullable | Photo URL |
| `discount` | numeric | nullable, min:0 | Discount percentage or amount |
| `discount_2earn` | numeric | nullable, min:0 | 2earn discount |
| `platform_id` | integer | nullable, exists:platforms,id | Platform ID |
| `deal_id` | integer | nullable, exists:deals,id | Deal ID |

---

### **Update Item (PUT /api/partner/items/{id})**

#### **Required Fields:**
| Field | Type | Validation | Description |
|-------|------|------------|-------------|
| `updated_by` | integer | required, exists:users,id | User ID who updates the item |

#### **Optional Fields (any you want to update):**
| Field | Type | Validation | Description |
|-------|------|------------|-------------|
| `name` | string | sometimes, max:255 | Item name |
| `ref` | string | sometimes, unique:items,ref,{id} | Item reference/SKU |
| `price` | numeric | sometimes, min:0 | Item price |
| `stock` | integer | sometimes, min:0 | Stock quantity |
| `description` | string | nullable | Item description |
| `photo_link` | string | nullable | Photo URL |
| `discount` | numeric | nullable, min:0 | Discount |
| `discount_2earn` | numeric | nullable, min:0 | 2earn discount |
| `platform_id` | integer | sometimes, exists:platforms,id | Platform ID |
| `deal_id` | integer | nullable, exists:deals,id | Deal ID |

---

### **Remove Platform from Item (DELETE /api/partner/items/{id}/platform)**

#### **Required Fields:**
| Field | Type | Validation | Description |
|-------|------|------------|-------------|
| `updated_by` | integer | required, exists:users,id | User ID who performs the action |

---

### **Add Items to Deal Bulk (POST /api/partner/items/deal/add-bulk)**

#### **Required Fields:**
| Field | Type | Validation | Description |
|-------|------|------------|-------------|
| `deal_id` | integer | required, exists:deals,id | Deal ID to add items to |
| `product_ids` | array | required, array | Array of product IDs to add |
| `product_ids.*` | integer | integer, exists:items,id | Each product ID must exist |

---

### **Remove Items from Deal Bulk (POST /api/partner/items/deal/remove-bulk)**

#### **Required Fields:**
| Field | Type | Validation | Description |
|-------|------|------------|-------------|
| `deal_id` | integer | required, exists:deals,id | Deal ID to remove items from |
| `product_ids` | array | required, array | Array of product IDs to remove |
| `product_ids.*` | integer | integer, exists:items,id | Each product ID must exist |

---

## API Endpoints Summary

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/partner/items` | GET | List items (with optional filters) |
| `/api/partner/items/{id}` | GET | Get item by ID |
| `/api/partner/items` | POST | Create new item |
| `/api/partner/items/{id}` | PUT | Update item |
| `/api/partner/items/{id}/platform` | DELETE | Remove platform from item |
| `/api/partner/items/deal/{dealId}` | GET | List items for a deal |
| `/api/partner/items/deal/add-bulk` | POST | Add items to deal (bulk) |
| `/api/partner/items/deal/remove-bulk` | POST | Remove items from deal (bulk) |

---

## Example Requests

### **Create Item**

**Request:**
```bash
POST {{base_url}}/api/partner/items
Content-Type: application/json

{
  "name": "Premium Laptop 2026",
  "ref": "SKU-LAPTOP-2026-001",
  "price": 1299.99,
  "stock": 50,
  "description": "High-performance laptop with latest specs",
  "photo_link": "https://example.com/images/laptop.jpg",
  "discount": 100,
  "discount_2earn": 50,
  "platform_id": 5,
  "deal_id": 123,
  "created_by": 1
}
```

**Success Response (201 Created):**
```json
{
  "status": "Success",
  "message": "Item created successfully",
  "data": {
    "id": 456,
    "name": "Premium Laptop 2026",
    "ref": "SKU-LAPTOP-2026-001",
    "price": 1299.99,
    "stock": 50,
    "description": "High-performance laptop with latest specs",
    "photo_link": "https://example.com/images/laptop.jpg",
    "discount": 100,
    "discount_2earn": 50,
    "platform_id": 5,
    "deal_id": 123,
    "created_at": "2026-02-12T10:30:00.000000Z",
    "updated_at": "2026-02-12T10:30:00.000000Z"
  }
}
```

---

### **Update Item**

**Request:**
```bash
PUT {{base_url}}/api/partner/items/456
Content-Type: application/json

{
  "name": "Premium Laptop 2026 - Special Edition",
  "price": 1199.99,
  "stock": 45,
  "discount": 150,
  "updated_by": 1
}
```

**Success Response (200 OK):**
```json
{
  "status": "Success",
  "message": "Item updated successfully",
  "data": {
    "id": 456,
    "name": "Premium Laptop 2026 - Special Edition",
    "ref": "SKU-LAPTOP-2026-001",
    "price": 1199.99,
    "stock": 45,
    "description": "High-performance laptop with latest specs",
    "discount": 150,
    "discount_2earn": 50,
    "platform_id": 5,
    "deal_id": 123,
    "updated_at": "2026-02-12T11:00:00.000000Z"
  }
}
```

---

### **Remove Platform from Item**

**Request:**
```bash
DELETE {{base_url}}/api/partner/items/456/platform
Content-Type: application/json

{
  "updated_by": 1
}
```

**Success Response (200 OK):**
```json
{
  "status": "Success",
  "message": "Platform removed from item successfully",
  "data": {
    "id": 456,
    "name": "Premium Laptop 2026 - Special Edition",
    "ref": "SKU-LAPTOP-2026-001",
    "platform_id": null,
    "old_platform_id": 5
  }
}
```

---

### **Add Items to Deal (Bulk)**

**Request:**
```bash
POST {{base_url}}/api/partner/items/deal/add-bulk
Content-Type: application/json

{
  "deal_id": 123,
  "product_ids": [1, 2, 3, 4, 5]
}
```

**Success Response (200 OK):**
```json
{
  "status": "success",
  "message": "Products added to deal successfully",
  "deal_id": 123,
  "added_product_ids": [1, 2, 3, 4, 5],
  "updated_count": 5
}
```

---

### **Remove Items from Deal (Bulk)**

**Request:**
```bash
POST {{base_url}}/api/partner/items/deal/remove-bulk
Content-Type: application/json

{
  "deal_id": 123,
  "product_ids": [1, 2, 3]
}
```

**Success Response (200 OK):**
```json
{
  "status": "success",
  "message": "Products removed from deal successfully",
  "deal_id": 123,
  "removed_product_ids": [1, 2, 3],
  "removed_count": 3
}
```

---

## Error Responses

### **422 Unprocessable Entity - Validation Error**

**Missing required fields:**
```json
{
  "status": "Failed",
  "message": "Validation failed",
  "errors": {
    "ref": [
      "The Reference field is required."
    ],
    "created_by": [
      "The created by field is required."
    ]
  }
}
```

**Bulk operations - Missing product_ids:**
```json
{
  "status": "Failed",
  "message": "Validation failed",
  "errors": {
    "product_ids": [
      "The product ids field is required."
    ]
  }
}
```

**Bulk operations - Invalid product IDs:**
```json
{
  "status": "Failed",
  "message": "Validation failed",
  "errors": {
    "product_ids.0": [
      "The selected product_ids.0 is invalid."
    ],
    "product_ids.2": [
      "The selected product_ids.2 is invalid."
    ]
  }
}
```

**Duplicate ref:**
```json
{
  "status": "Failed",
  "message": "Validation failed",
  "errors": {
    "ref": [
      "The ref has already been taken."
    ]
  }
}
```

**Invalid price:**
```json
{
  "status": "Failed",
  "message": "Validation failed",
  "errors": {
    "price": [
      "The price must be at least 0."
    ]
  }
}
```

---

### **404 Not Found**

```json
{
  "status": "Failed",
  "message": "Item not found"
}
```

---

### **500 Internal Server Error**

```json
{
  "status": "Failed",
  "message": "Failed to remove platform from item",
  "error": "[error details]"
}
```

---

## Using Postman

### **Environment Variables**

Set these variables in your Postman environment:

```
base_url = http://localhost
user_id = 1
platform_id = 5
deal_id = 123
item_id = 456
```

### **Steps to Test:**

1. **Import/Refresh** the updated collection
2. **Set environment variables** as shown above
3. **Create Item:**
   - Select "Create Item" request
   - Modify the `ref` field to ensure it's unique (e.g., `SKU-2026-002`)
   - Click "Send"
   - Verify 201 Created response
4. **Update Item:**
   - Copy the `id` from the create response
   - Set `item_id` environment variable
   - Select "Update Item" request
   - Click "Send"
   - Verify 200 OK response
5. **Remove Platform:**
   - Select "Remove Platform from Item" request
   - Click "Send"
   - Verify platform_id is now null

---

## Important Notes

### **Unique Reference Constraint**

⚠️ The `ref` field must be unique across all items. If you try to create an item with an existing ref, you'll get a validation error:

```json
{
  "errors": {
    "ref": ["The ref has already been taken."]
  }
}
```

**Solution:** Always use unique SKU/reference numbers when creating items.

---

### **Stock Default Value**

If you don't provide a `stock` value when creating an item, it will default to `0`:

```php
if (!isset($data['stock'])) {
    $data['stock'] = 0;
}
```

---

### **Optional Platform & Deal**

Both `platform_id` and `deal_id` are optional. You can:
- Create an item without associating it to a platform
- Create an item without associating it to a deal
- Associate an item to both platform and deal
- Remove platform association later using the DELETE endpoint

---

## Testing with cURL

### **Create Item:**
```bash
curl -X POST http://localhost/api/partner/items \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test Product",
    "ref": "SKU-TEST-001",
    "price": 29.99,
    "stock": 100,
    "description": "Test product description",
    "discount": 5,
    "discount_2earn": 2.5,
    "platform_id": 5,
    "deal_id": 123,
    "created_by": 1
  }'
```

### **Update Item:**
```bash
curl -X PUT http://localhost/api/partner/items/456 \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Updated Test Product",
    "price": 24.99,
    "stock": 90,
    "updated_by": 1
  }'
```

### **Remove Platform:**
```bash
curl -X DELETE http://localhost/api/partner/items/456/platform \
  -H "Content-Type: application/json" \
  -d '{
    "updated_by": 1
  }'
```

### **Add Items to Deal (Bulk):**
```bash
curl -X POST http://localhost/api/partner/items/deal/add-bulk \
  -H "Content-Type: application/json" \
  -d '{
    "deal_id": 123,
    "product_ids": [1, 2, 3, 4, 5]
  }'
```

### **Remove Items from Deal (Bulk):**
```bash
curl -X POST http://localhost/api/partner/items/deal/remove-bulk \
  -H "Content-Type: application/json" \
  -d '{
    "deal_id": 123,
    "product_ids": [1, 2, 3]
  }'
```

---

## Files Modified

1. ✅ `postman/collections/Partner/Partner Items API.postman_collection.json`

---

## Summary

The Partner Items API Postman collection has been updated to include all required fields:

✅ **Create Item** - Added `ref` and `created_by` required fields  
✅ **Update Item** - Added `updated_by` required field and changed variable to `{{item_id}}`  
✅ **Remove Platform** - Changed from query params to request body  
✅ **Add Items to Deal (Bulk)** - Changed `item_ids` to `product_ids`, removed `user_id`  
✅ **Remove Items from Deal (Bulk)** - Changed `item_ids` to `product_ids`, removed `user_id`  
✅ **All requests** - Now use request body for data (RESTful best practice)  
✅ **Descriptions** - Updated to clarify all parameters are in request body  

The validation errors are now resolved, and all endpoints follow consistent patterns! 🚀

