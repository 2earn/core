# Partner Orders API - Validation Errors Fix

**Date:** February 12, 2026  
**Status:** ✅ FIXED

---

## Issue

The Partner Orders API endpoints were failing with validation errors when creating or updating orders and order details:

**Error Response:**
```json
{
    "errors": {
        "platform_id": [
            "The Platform ID field is required."
        ],
        "created_by": [
            "The created by field is required."
        ]
    }
}
```

**Previous Request Body (Create Order):**
```json
{
  "user_id": {{user_id}},
  "deal_id": {{deal_id}},
  "quantity": 1,
  "total_amount": 80.00
}
```

---

## Root Cause

The Postman collection was missing required fields and using incorrect field names:

### **Create Order Issues:**
- ❌ Missing `platform_id` (required)
- ❌ Missing `created_by` (required)
- ❌ Using invalid fields like `deal_id` and `quantity`
- ❌ Using `total_amount` instead of `total_order`

### **Update Order Issues:**
- ❌ Missing `updated_by` (required)
- ❌ Including `user_id` (not needed in update)

### **Order Details Issues:**
- ❌ Using `quantity` instead of `qty`
- ❌ Using `price` instead of `unit_price`
- ❌ Missing `created_by` (required for create)
- ❌ Missing `updated_by` (required for update)

---

## Solution

### **Files Modified:**

1. ✅ `postman/collections/Partner/Partner Orders API.postman_collection.json`

### **Changes Made:**

#### **1. Create Order - POST `/api/partner/orders/orders`**

**Before:**
```json
{
  "user_id": {{user_id}},
  "deal_id": {{deal_id}},
  "quantity": 1,
  "total_amount": 80.00
}
```

**After:**
```json
{
  "user_id": {{user_id}},
  "platform_id": {{platform_id}},
  "total_order": 80.00,
  "note": "New order",
  "created_by": {{user_id}}
}
```

---

#### **2. Update Order - PUT `/api/partner/orders/orders/{order_id}`**

**Before:**
```json
{
  "user_id": {{user_id}},
  "quantity": 2,
  "total_amount": 160.00
}
```

**After:**
```json
{
  "total_order": 160.00,
  "note": "Updated order",
  "updated_by": {{user_id}}
}
```

---

#### **3. Change Order Status - PATCH `/api/partner/orders/{order_id}/status`**

**Before:**
```json
{
  "user_id": {{user_id}},
  "status": "completed"
}
```

**After:**
```json
{
  "user_id": {{user_id}},
  "status": 3
}
```

**Note:** Status must be integer value `3` (Ready status only)

---

#### **4. Create Order Detail - POST `/api/partner/orders/details`**

**Before:**
```json
{
  "user_id": {{user_id}},
  "order_id": {{order_id}},
  "item_id": {{item_id}},
  "quantity": 1,
  "price": 80.00
}
```

**After:**
```json
{
  "order_id": {{order_id}},
  "item_id": {{item_id}},
  "qty": 1,
  "unit_price": 80.00,
  "shipping": 0,
  "created_by": {{user_id}}
}
```

---

#### **5. Update Order Detail - PUT `/api/partner/orders/details/{detail_id}`**

**Before:**
```json
{
  "user_id": {{user_id}},
  "quantity": 2,
  "price": 160.00
}
```

**After:**
```json
{
  "qty": 2,
  "unit_price": 80.00,
  "shipping": 5.00,
  "updated_by": {{user_id}}
}
```

---

## Validation Rules

### **Create Order (POST /api/partner/orders/orders)**

| Field | Type | Validation | Description |
|-------|------|------------|-------------|
| `user_id` | integer | required, exists:users,id | User who places the order |
| `platform_id` | integer | required, exists:platforms,id | Platform ID |
| `created_by` | integer | required, exists:users,id | User who creates the order |
| `out_of_deal_amount` | numeric | nullable | Amount outside deals |
| `deal_amount_before_discount` | numeric | nullable | Deal amount before discount |
| `total_order` | numeric | nullable | Total order amount |
| `total_order_quantity` | integer | nullable | Total quantity |
| `deal_amount_after_discounts` | numeric | nullable | Deal amount after discounts |
| `amount_after_discount` | numeric | nullable | Amount after discount |
| `paid_cash` | numeric | nullable | Cash paid |
| `commission_2_earn` | numeric | nullable | 2earn commission |
| `deal_amount_for_partner` | numeric | nullable | Partner deal amount |
| `commission_for_camembert` | numeric | nullable | Camembert commission |
| `total_final_discount` | numeric | nullable | Total final discount |
| `total_final_discount_percentage` | numeric | nullable | Final discount % |
| `total_lost_discount` | numeric | nullable | Total lost discount |
| `total_lost_discount_percentage` | numeric | nullable | Lost discount % |
| `note` | string | nullable | Order notes |
| `status` | string | nullable | Order status (auto-set to "New") |

---

### **Update Order (PUT /api/partner/orders/orders/{order_id})**

| Field | Type | Validation | Description |
|-------|------|------------|-------------|
| `updated_by` | integer | required, exists:users,id | User who updates the order |
| `out_of_deal_amount` | numeric | nullable | Amount outside deals |
| `deal_amount_before_discount` | numeric | nullable | Deal amount before discount |
| `total_order` | numeric | nullable | Total order amount |
| `total_order_quantity` | integer | nullable | Total quantity |
| `deal_amount_after_discounts` | numeric | nullable | Deal amount after discounts |
| `amount_after_discount` | numeric | nullable | Amount after discount |
| `paid_cash` | numeric | nullable | Cash paid |
| `commission_2_earn` | numeric | nullable | 2earn commission |
| `deal_amount_for_partner` | numeric | nullable | Partner deal amount |
| `commission_for_camembert` | numeric | nullable | Camembert commission |
| `total_final_discount` | numeric | nullable | Total final discount |
| `total_final_discount_percentage` | numeric | nullable | Final discount % |
| `total_lost_discount` | numeric | nullable | Total lost discount |
| `total_lost_discount_percentage` | numeric | nullable | Lost discount % |
| `note` | string | nullable | Order notes |

**Note:** `user_id` and `status` are NOT allowed in update requests.

---

### **Change Order Status (PATCH /api/partner/orders/{order_id}/status)**

| Field | Type | Validation | Description |
|-------|------|------------|-------------|
| `user_id` | integer | required, exists:users,id | User requesting the change |
| `status` | integer | required | Order status (must be 3 for Ready) |

**Important:** 
- Only the order owner can change the status
- Only status value `3` (Ready) is allowed
- Status is an **integer**, not a string

---

### **Create Order Detail (POST /api/partner/orders/details)**

| Field | Type | Validation | Description |
|-------|------|------------|-------------|
| `order_id` | integer | required, exists:orders,id | Order ID |
| `item_id` | integer | required, exists:items,id | Item ID |
| `qty` | numeric | required, min:1 | Quantity |
| `unit_price` | numeric | required, min:0 | Unit price |
| `shipping` | numeric | nullable, min:0 | Shipping cost |
| `created_by` | integer | required, exists:users,id | User who creates the detail |

**Auto-calculated:** `total_amount = (qty * unit_price) + shipping`

---

### **Update Order Detail (PUT /api/partner/orders/details/{detail_id})**

| Field | Type | Validation | Description |
|-------|------|------------|-------------|
| `qty` | numeric | sometimes, min:1 | Quantity |
| `unit_price` | numeric | sometimes, min:0 | Unit price |
| `shipping` | numeric | sometimes, min:0 | Shipping cost |
| `note` | string | nullable | Detail notes |
| `updated_by` | integer | required, exists:users,id | User who updates the detail |

**Auto-calculated:** `total_amount` is recalculated if any of qty, unit_price, or shipping changes.

---

## Example Requests & Responses

### **Create Order**

**Request:**
```bash
POST {{base_url}}/api/partner/orders/orders
Content-Type: application/json

{
  "user_id": 1,
  "platform_id": 5,
  "total_order": 150.00,
  "note": "New order for platform 5",
  "created_by": 1
}
```

**Success Response (201 Created):**
```json
{
  "id": 123,
  "user_id": 1,
  "platform_id": 5,
  "total_order": 150.00,
  "note": "New order for platform 5",
  "status": "New",
  "created_by": 1,
  "created_at": "2026-02-12T10:00:00.000000Z",
  "updated_at": "2026-02-12T10:00:00.000000Z"
}
```

**Error Response - Missing Required Fields (422):**
```json
{
  "errors": {
    "platform_id": [
      "The Platform ID field is required."
    ],
    "created_by": [
      "The created by field is required."
    ]
  }
}
```

---

### **Update Order**

**Request:**
```bash
PUT {{base_url}}/api/partner/orders/orders/123
Content-Type: application/json

{
  "total_order": 200.00,
  "note": "Updated order amount",
  "updated_by": 1
}
```

**Success Response (200 OK):**
```json
{
  "status": true,
  "message": "Order updated successfully",
  "data": {
    "id": 123,
    "user_id": 1,
    "platform_id": 5,
    "total_order": 200.00,
    "note": "Updated order amount",
    "status": "New",
    "updated_by": 1,
    "updated_at": "2026-02-12T11:00:00.000000Z"
  }
}
```

**Error Response - Missing updated_by (422):**
```json
{
  "status": "Failed",
  "message": "Validation failed",
  "errors": {
    "updated_by": [
      "The updated by field is required."
    ]
  }
}
```

---

### **Change Order Status**

**Request:**
```bash
PATCH {{base_url}}/api/partner/orders/123/status
Content-Type: application/json

{
  "user_id": 1,
  "status": 3
}
```

**Success Response (200 OK):**
```json
{
  "status": true,
  "message": "Order status updated successfully",
  "data": {
    "id": 123,
    "status": "Ready",
    "updated_by": 1
  }
}
```

**Error Response - Invalid Status (422):**
```json
{
  "status": "Failed",
  "message": "Invalid order status value"
}
```

**Error Response - Not Order Owner (403):**
```json
{
  "status": "Failed",
  "message": "You do not have permission to change this order status"
}
```

---

### **Create Order Detail**

**Request:**
```bash
POST {{base_url}}/api/partner/orders/details
Content-Type: application/json

{
  "order_id": 123,
  "item_id": 45,
  "qty": 2,
  "unit_price": 50.00,
  "shipping": 10.00,
  "created_by": 1
}
```

**Success Response (201 Created):**
```json
{
  "status": "Success",
  "message": "Order detail created successfully",
  "data": {
    "id": 456,
    "order_id": 123,
    "item_id": 45,
    "qty": 2,
    "unit_price": 50.00,
    "shipping": 10.00,
    "total_amount": 110.00,
    "created_by": 1,
    "created_at": "2026-02-12T10:30:00.000000Z"
  }
}
```

**Note:** `total_amount` is auto-calculated: (2 × 50.00) + 10.00 = 110.00

---

### **Update Order Detail**

**Request:**
```bash
PUT {{base_url}}/api/partner/orders/details/456
Content-Type: application/json

{
  "qty": 3,
  "unit_price": 50.00,
  "shipping": 15.00,
  "updated_by": 1
}
```

**Success Response (200 OK):**
```json
{
  "status": "Success",
  "message": "Order detail updated successfully",
  "data": {
    "id": 456,
    "order_id": 123,
    "item_id": 45,
    "qty": 3,
    "unit_price": 50.00,
    "shipping": 15.00,
    "total_amount": 165.00,
    "updated_by": 1,
    "updated_at": "2026-02-12T11:30:00.000000Z"
  }
}
```

**Note:** `total_amount` is auto-recalculated: (3 × 50.00) + 15.00 = 165.00

---

## Testing with Postman

### **Environment Variables**

Set these variables in your Postman environment:

```
base_url = http://localhost
user_id = 1
platform_id = 5
order_id = 123
item_id = 45
detail_id = 456
```

### **Steps to Test:**

1. **Import/Refresh** the updated Postman collection
2. **Set environment variables** as shown above
3. **Create Order:**
   - Select "Create Order" request
   - Make sure `platform_id` exists in your database
   - Click "Send"
   - Verify 201 Created response
   - Copy the returned `order_id`
4. **Create Order Detail:**
   - Update `order_id` with the ID from step 3
   - Make sure `item_id` exists in your database
   - Click "Send"
   - Verify 201 Created response
5. **Update Order:**
   - Select "Update Order" request
   - Modify the `total_order` value
   - Click "Send"
   - Verify 200 OK response
6. **Change Order Status:**
   - Make sure you're the order owner (user_id matches)
   - Click "Send"
   - Verify 200 OK response

---

## Testing with cURL

### **Create Order:**
```bash
curl -X POST http://localhost/api/partner/orders/orders \
  -H "Content-Type: application/json" \
  -d '{
    "user_id": 1,
    "platform_id": 5,
    "total_order": 150.00,
    "note": "New order",
    "created_by": 1
  }'
```

### **Update Order:**
```bash
curl -X PUT http://localhost/api/partner/orders/orders/123 \
  -H "Content-Type: application/json" \
  -d '{
    "total_order": 200.00,
    "note": "Updated order",
    "updated_by": 1
  }'
```

### **Change Order Status:**
```bash
curl -X PATCH http://localhost/api/partner/orders/123/status \
  -H "Content-Type: application/json" \
  -d '{
    "user_id": 1,
    "status": 3
  }'
```

### **Create Order Detail:**
```bash
curl -X POST http://localhost/api/partner/orders/details \
  -H "Content-Type: application/json" \
  -d '{
    "order_id": 123,
    "item_id": 45,
    "qty": 2,
    "unit_price": 50.00,
    "shipping": 10.00,
    "created_by": 1
  }'
```

### **Update Order Detail:**
```bash
curl -X PUT http://localhost/api/partner/orders/details/456 \
  -H "Content-Type: application/json" \
  -d '{
    "qty": 3,
    "unit_price": 50.00,
    "shipping": 15.00,
    "updated_by": 1
  }'
```

---

## Important Notes

### **Order Status Enum Values**

The `OrderEnum` defines the following status values:

```php
enum OrderEnum: int
{
    case New = 0;
    case InProgress = 1;
    case Cancelled = 2;
    case Ready = 3;
    case Delivered = 4;
}
```

**For the Change Status endpoint, only `Ready` (value 3) is allowed.**

---

### **Auto-calculated Fields**

#### **Orders:**
- `status` is automatically set to `"New"` when creating an order

#### **Order Details:**
- `total_amount` is automatically calculated as: `(qty * unit_price) + shipping`
- If `shipping` is not provided, it defaults to `0`
- When updating, `total_amount` is recalculated if qty, unit_price, or shipping changes

---

### **Field Name Differences**

⚠️ **Important:** The order details use different field names than you might expect:

| Common Name | API Field Name | Type |
|-------------|----------------|------|
| Quantity | `qty` | numeric |
| Price | `unit_price` | numeric |
| Shipping | `shipping` | numeric |
| Total | `total_amount` | numeric (auto-calculated) |

---

### **Permissions**

- **Change Order Status:** Only the user who owns the order (order.user_id) can change its status
- **Create/Update:** The `created_by` and `updated_by` fields track who performed the actions

---

## Related Files

### **Controllers:**
- `app/Http/Controllers/Api/partner/OrderPartnerController.php`
- `app/Http/Controllers/Api/partner/OrderDetailsPartnerController.php`

### **Services:**
- `app/Services/Orders/OrderService.php`

### **Models:**
- `app/Models/Order.php`
- `app/Models/OrderDetail.php`

### **Enums:**
- `app/Enums/OrderEnum.php`

---

## Summary

✅ **Fixed Create Order** - Added required `platform_id` and `created_by` fields  
✅ **Fixed Update Order** - Added required `updated_by`, removed `user_id`  
✅ **Fixed Change Status** - Changed status to integer value  
✅ **Fixed Create Order Detail** - Changed field names to `qty`, `unit_price`, added `created_by`  
✅ **Fixed Update Order Detail** - Changed field names, added `updated_by`  
✅ **All requests** - Added proper JSON formatting options  
✅ **Descriptions** - Updated to clarify all parameters are in request body  

**The validation errors are now resolved!** 🚀

---

## Quick Reference

### **Create Order - Required Fields:**
- `user_id` (integer)
- `platform_id` (integer)
- `created_by` (integer)

### **Update Order - Required Fields:**
- `updated_by` (integer)

### **Create Order Detail - Required Fields:**
- `order_id` (integer)
- `item_id` (integer)
- `qty` (numeric, min:1)
- `unit_price` (numeric, min:0)
- `created_by` (integer)

### **Update Order Detail - Required Fields:**
- `updated_by` (integer)

### **Change Order Status - Required Fields:**
- `user_id` (integer)
- `status` (integer, must be 3)

