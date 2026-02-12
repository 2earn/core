# Partner Items API - Bulk Operations Fix

**Date:** February 12, 2026  
**Status:** ✅ FIXED

---

## Issue

The bulk operations endpoints for adding and removing items from deals were failing with validation errors:

**Error:**
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

**Previous Request Body:**
```json
{
  "user_id": {{user_id}},
  "deal_id": {{deal_id}},
  "item_ids": [1, 2, 3]
}
```

---

## Root Cause

The Postman collection was using incorrect field names:
- ❌ Using `item_ids` instead of `product_ids`
- ❌ Including unnecessary `user_id` field

The controller expects:
- ✅ `product_ids` (array of item IDs)
- ✅ `deal_id` (integer)
- ❌ Does NOT require `user_id` in the request body

---

## Solution

### **Files Modified:**

1. ✅ `postman/collections/Partner/Partner Items API.postman_collection.json`
2. ✅ `ai generated docs/PARTNER_ITEMS_API_POSTMAN_UPDATE.md`

### **Changes Made:**

#### **1. Add Items to Deal (Bulk) - `/api/partner/items/deal/add-bulk`**

**Before:**
```json
{
  "user_id": {{user_id}},
  "deal_id": {{deal_id}},
  "item_ids": [1, 2, 3]
}
```

**After:**
```json
{
  "deal_id": {{deal_id}},
  "product_ids": [1, 2, 3]
}
```

#### **2. Remove Items from Deal (Bulk) - `/api/partner/items/deal/remove-bulk`**

**Before:**
```json
{
  "user_id": {{user_id}},
  "deal_id": {{deal_id}},
  "item_ids": [1, 2, 3]
}
```

**After:**
```json
{
  "deal_id": {{deal_id}},
  "product_ids": [1, 2, 3]
}
```

---

## Validation Rules

### **Add Items to Deal Bulk (POST /api/partner/items/deal/add-bulk)**

| Field | Type | Validation | Description |
|-------|------|------------|-------------|
| `deal_id` | integer | required, exists:deals,id | Deal ID to add items to |
| `product_ids` | array | required, array | Array of product IDs to add |
| `product_ids.*` | integer | integer, exists:items,id | Each product ID must exist |

### **Remove Items from Deal Bulk (POST /api/partner/items/deal/remove-bulk)**

| Field | Type | Validation | Description |
|-------|------|------------|-------------|
| `deal_id` | integer | required, exists:deals,id | Deal ID to remove items from |
| `product_ids` | array | required, array | Array of product IDs to remove |
| `product_ids.*` | integer | integer, exists:items,id | Each product ID must exist |

---

## Example Requests & Responses

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

**Error Response - Missing product_ids (422):**
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

**Error Response - Invalid product IDs (422):**
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

**Error Response - Deal not found (404):**
```json
{
  "status": "Failed",
  "message": "Deal not found"
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

## Testing with cURL

### **Add Items to Deal:**
```bash
curl -X POST http://localhost/api/partner/items/deal/add-bulk \
  -H "Content-Type: application/json" \
  -d '{
    "deal_id": 123,
    "product_ids": [1, 2, 3, 4, 5]
  }'
```

### **Remove Items from Deal:**
```bash
curl -X POST http://localhost/api/partner/items/deal/remove-bulk \
  -H "Content-Type: application/json" \
  -d '{
    "deal_id": 123,
    "product_ids": [1, 2, 3]
  }'
```

---

## Testing with Postman

### **Steps:**

1. **Import/Refresh** the updated Postman collection
2. **Set environment variables:**
   - `base_url` = `http://localhost`
   - `deal_id` = `123` (use an existing deal ID from your database)
3. **Get existing item IDs** from your database or create items first
4. **Test Add Items to Deal:**
   - Open "Add Items to Deal (Bulk)" request
   - Update `product_ids` array with valid item IDs: `[1, 2, 3]`
   - Click "Send"
   - Verify 200 OK response with `updated_count`
5. **Test Remove Items from Deal:**
   - Open "Remove Items from Deal (Bulk)" request
   - Update `product_ids` array with valid item IDs: `[1, 2, 3]`
   - Click "Send"
   - Verify 200 OK response with `removed_count`

---

## Important Notes

### **Field Name Consistency**

⚠️ **Always use `product_ids` (not `item_ids`)** for bulk operations:
- The controller validation expects `product_ids`
- Even though the database table is called `items`, the API uses `product_ids` for consistency with the deal-product relationship

### **User Authentication**

The `user_id` is not required in the request body because:
- The authenticated user is obtained from `auth()->id()` in the controller
- The system automatically tracks who performed the bulk operation
- This is logged in the deal product changes history

### **Deal Product Changes Tracking**

When items are added or removed in bulk, the system:
- Creates change records in `deal_product_changes` table
- Logs the action (`added` or `removed`)
- Records the authenticated user who performed the action
- Optionally sends notifications to platform users

---

## Related Controller Methods

**File:** `app/Http/Controllers/Api/partner/ItemsPartnerController.php`

### **addItemsToDeal() Method:**
```php
public function addItemsToDeal(Request $request)
{
    $validator = Validator::make($request->all(), [
        'deal_id' => 'required|integer|exists:deals,id',
        'product_ids' => 'required|array',
        'product_ids.*' => 'integer|exists:items,id',
    ]);
    
    // ... validation and processing ...
    
    $updatedCount = $this->itemService->bulkUpdateDeal($productIds, $dealId);
    
    $this->changeService->createBulkChanges(
        $dealId,
        $productIds,
        'added',
        auth()->id(),
        'Products added to deal via API'
    );
}
```

### **removeItemsFromDeal() Method:**
```php
public function removeItemsFromDeal(Request $request)
{
    $validator = Validator::make($request->all(), [
        'deal_id' => 'required|integer|exists:deals,id',
        'product_ids' => 'required|array',
        'product_ids.*' => 'integer|exists:items,id',
    ]);
    
    // ... validation and processing ...
    
    $removedCount = $this->itemService->bulkRemoveFromDeal($productIds, $dealId);
    
    $this->changeService->createBulkChanges(
        $dealId,
        $productIds,
        'removed',
        auth()->id(),
        'Product removed from deal via API'
    );
}
```

---

## Service Layer

**File:** `app/Services/Items/ItemService.php`

### **bulkUpdateDeal() Method:**
```php
public function bulkUpdateDeal(array $itemIds, int $dealId): int
{
    return Item::whereIn('id', $itemIds)->update(['deal_id' => $dealId]);
}
```

### **bulkRemoveFromDeal() Method:**
```php
public function bulkRemoveFromDeal(array $itemIds, int $dealId): int
{
    return Item::where('deal_id', $dealId)
        ->whereIn('id', $itemIds)
        ->update(['deal_id' => null]);
}
```

---

## Summary

✅ **Fixed field name** - Changed `item_ids` to `product_ids`  
✅ **Removed unnecessary field** - Removed `user_id` from request body  
✅ **Updated Postman collection** - Both bulk endpoints now use correct fields  
✅ **Updated documentation** - Added comprehensive validation rules and examples  
✅ **Added error examples** - Documented all possible validation errors  
✅ **Added cURL examples** - Easy testing from command line  

**The bulk operations now work correctly!** 🚀

---

## Quick Reference

| Endpoint | Field Name | Required | Type |
|----------|------------|----------|------|
| `/api/partner/items/deal/add-bulk` | `product_ids` | Yes | array of integers |
| `/api/partner/items/deal/add-bulk` | `deal_id` | Yes | integer |
| `/api/partner/items/deal/remove-bulk` | `product_ids` | Yes | array of integers |
| `/api/partner/items/deal/remove-bulk` | `deal_id` | Yes | integer |

**Common Mistake:** ❌ Using `item_ids` instead of `product_ids`  
**Correct Usage:** ✅ Always use `product_ids`

