# Bulk Remove Items from Deal Endpoint Implementation

## Overview
Added a new API endpoint to allow bulk removal of multiple products from a deal in a single request.

## Endpoint Details

### URL
```
POST /api/partner/items/deal/remove-bulk
```

### Route Name
```
api_partner_items_remove_from_deal_bulk
```

### Request Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `deal_id` | integer | Yes | ID of the deal to remove items from |
| `product_ids` | array | Yes | Array of product IDs to remove from the deal |
| `product_ids.*` | integer | Yes | Each product ID must exist in items table |

### Request Example
```json
{
  "deal_id": 1,
  "product_ids": [101, 103]
}
```

### Response Example

#### Success Response (200 OK)
```json
{
  "status": "success",
  "message": "Products removed from deal successfully",
  "deal_id": 1,
  "removed_product_ids": [101, 103]
}
```

#### Error Responses

**Validation Failed (422 Unprocessable Entity)**
```json
{
  "status": "Failed",
  "message": "Validation failed",
  "errors": {
    "deal_id": ["The deal id field is required."],
    "product_ids": ["The product ids field is required."]
  }
}
```

**Deal Not Found (404 Not Found)**
```json
{
  "status": "Failed",
  "message": "Deal not found"
}
```

**Failed to Remove Products (500 Internal Server Error)**
```json
{
  "status": "Failed",
  "message": "Failed to remove products from deal",
  "error": "Database error message..."
}
```

## Implementation Details

### Files Modified

1. **ItemsPartnerController.php**
   - Added `removeItemsFromDeal()` method
   - Location: `app/Http/Controllers/Api/partner/ItemsPartnerController.php`
   - Uses `ItemService::bulkRemoveFromDeal()` for bulk removal operation

2. **ItemService.php**
   - Added `bulkRemoveFromDeal()` method
   - Location: `app/Services/Items/ItemService.php`
   - Handles bulk removal of items from a deal by setting deal_id to null

3. **api.php**
   - Added route for bulk remove items from deal
   - Location: `routes/api.php`
   - Route: `Route::post('/deal/remove-bulk', [ItemsPartnerController::class, 'removeItemsFromDeal'])->name('remove_from_deal_bulk');`

### Method Logic

1. **Validation**
   - Validates that `deal_id` is required, integer, and exists in deals table
   - Validates that `product_ids` is required, array
   - Validates that each product ID exists in items table

2. **Business Logic**
   - Retrieves the deal using `DealService::find()`
   - Checks if deal exists
   - Removes all specified products from the deal using `ItemService::bulkRemoveFromDeal()`
   - Only removes items that actually belong to the specified deal (safety check)
   - Returns the number of items removed

3. **Error Handling**
   - Try-catch block wraps the bulk removal operation
   - Catches any exceptions and returns detailed error response
   - Logs all errors with full stack trace for debugging

4. **Logging**
   - Logs validation failures
   - Logs when deal is not found
   - Logs successful product removal with removed count
   - Logs any exceptions with full context

### Security Features
- Route protected by `check.url` middleware
- Validates deal existence before processing
- Validates all product IDs exist
- Only removes items that actually belong to the specified deal
- Comprehensive error handling with try-catch

## Service Layer Implementation

### ItemService::bulkRemoveFromDeal()
```php
public function bulkRemoveFromDeal(array $itemIds, int $dealId): int
{
    return Item::whereIn('id', $itemIds)
        ->where('deal_id', $dealId)
        ->update(['deal_id' => null]);
}
```

**Key Features:**
- Only removes items that belong to the specified deal (double check with `where('deal_id', $dealId)`)
- Sets `deal_id` to `null` to unlink items from the deal
- Returns the count of items actually removed
- Performs bulk update in a single query for efficiency

## Testing the Endpoint

### Using cURL
```bash
curl -X POST "http://your-domain/api/partner/items/deal/remove-bulk" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "deal_id": 1,
    "product_ids": [101, 103]
  }'
```

### Using Postman
1. Method: POST
2. URL: `http://your-domain/api/partner/items/deal/remove-bulk`
3. Headers:
   - Content-Type: application/json
   - Authorization: Bearer YOUR_TOKEN
4. Body (raw JSON):
```json
{
  "deal_id": 1,
  "product_ids": [101, 103]
}
```

## Related Endpoints

### List Items for a Deal
```
GET /api/partner/items/deal/{dealId}
```
- Retrieves all products linked to a specific deal
- Returns products excluding ref "#0001"
- Returns product count and full product details

### Add Items to Deal (Bulk)
```
POST /api/partner/items/deal/add-bulk
```
- Adds multiple products to a deal in a single request
- Requires deal to be validated
- Returns success with product IDs added

## Key Differences from Add Items

| Feature | Add Items | Remove Items |
|---------|-----------|--------------|
| Deal Validation Check | Required (only validated deals) | Not required (can remove from any deal) |
| Operation | Sets deal_id to specific value | Sets deal_id to null |
| Safety Check | None (overwrites existing deal_id) | Verifies items belong to specified deal |

## Notes
- Products are unlinked from deals by setting the `deal_id` foreign key to `null`
- The service method includes a safety check to only remove items that actually belong to the specified deal
- If an item in the array doesn't belong to the deal, it won't be affected
- The endpoint performs a bulk update, so all products are updated in a single query
- Unlike adding items, removing items doesn't require the deal to be validated
- The removal count returned may be less than the number of IDs provided if some items weren't actually linked to that deal

## Date Implemented
December 31, 2025

