# Bulk Add Items to Deal Endpoint Implementation

## Overview
Added a new API endpoint to allow bulk addition of multiple products to a deal in a single request.

## Endpoint Details

### URL
```
POST /api/partner/items/deal/add-bulk
```

### Route Name
```
api_partner_items_add_to_deal_bulk
```

### Request Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `deal_id` | integer | Yes | ID of the deal to add items to |
| `product_ids` | array | Yes | Array of product IDs to add to the deal |
| `product_ids.*` | integer | Yes | Each product ID must exist in items table |

### Request Example
```json
{
  "deal_id": 1,
  "product_ids": [101, 102, 103]
}
```

### Response Example

#### Success Response (200 OK)
```json
{
  "status": "success",
  "message": "Products added to deal successfully",
  "deal_id": 1,
  "product_ids": [101, 102, 103]
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

**Deal Not Validated (400 Bad Request)**
```json
{
  "status": "Failed",
  "message": "Deal is not validated"
}
```

## Implementation Details

### Files Modified

1. **ItemsPartnerController.php**
   - Added `addItemsToDeal()` method
   - Location: `app/Http/Controllers/Api/partner/ItemsPartnerController.php`
   - Uses `ItemService::bulkUpdateDeal()` for bulk update operation

2. **ItemService.php**
   - Added `bulkUpdateDeal()` method
   - Location: `app/Services/Items/ItemService.php`
   - Handles bulk update of items to assign them to a deal

3. **api.php**
   - Added route for bulk add items to deal
   - Location: `routes/api.php`
   - Route: `Route::post('/deal/add-bulk', [ItemsPartnerController::class, 'addItemsToDeal'])->name('add_to_deal_bulk');`

### Method Logic

1. **Validation**
   - Validates that `deal_id` is required, integer, and exists in deals table
   - Validates that `product_ids` is required, array
   - Validates that each product ID exists in items table

2. **Business Logic**
   - Retrieves the deal using `DealService::find()`
   - Checks if deal exists
   - Checks if deal is validated (only validated deals can have items added)
   - Updates all specified products to link them to the deal using `ItemService::bulkUpdateDeal()`
   - Returns the number of items updated

3. **Logging**
   - Logs validation failures
   - Logs when deal is not found
   - Logs when deal is not validated
   - Logs successful product additions with updated count

### Security Features
- Route protected by `check.url` middleware
- Validates deal existence before processing
- Validates all product IDs exist
- Only allows adding items to validated deals

## Testing the Endpoint

### Using cURL
```bash
curl -X POST "http://your-domain/api/partner/items/deal/add-bulk" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "deal_id": 1,
    "product_ids": [101, 102, 103]
  }'
```

### Using Postman
1. Method: POST
2. URL: `http://your-domain/api/partner/items/deal/add-bulk`
3. Headers:
   - Content-Type: application/json
   - Authorization: Bearer YOUR_TOKEN
4. Body (raw JSON):
```json
{
  "deal_id": 1,
  "product_ids": [101, 102, 103]
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

## Notes
- Products are linked to deals via the `deal_id` foreign key in the `items` table
- Only validated deals can have items added
- The endpoint performs a bulk update, so all products are updated in a single query
- Products with ref "#0001" are excluded from deal listings (filtering happens in listing, not in bulk add)

## Date Implemented
December 31, 2025

