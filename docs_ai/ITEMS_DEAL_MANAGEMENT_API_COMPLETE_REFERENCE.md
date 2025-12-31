# Items-Deal Management API - Complete Reference

## Overview
This document provides a comprehensive overview of all endpoints for managing the relationship between items (products) and deals.

## Endpoints Summary

| Endpoint | Method | Purpose | Validation Required |
|----------|--------|---------|-------------------|
| `/api/partner/items/deal/{dealId}` | GET | List all items for a deal | Deal must be validated |
| `/api/partner/items/deal/add-bulk` | POST | Add multiple items to a deal | Deal must be validated |
| `/api/partner/items/deal/remove-bulk` | POST | Remove multiple items from a deal | No validation required |

---

## 1. List Items for a Deal

### Endpoint
```
GET /api/partner/items/deal/{dealId}
```

### Parameters
| Parameter | Type | Location | Description |
|-----------|------|----------|-------------|
| `dealId` | integer | URL path | ID of the deal |

### Response
```json
{
  "deal_id": 1,
  "deal_name": "Summer Sale",
  "products_count": 12,
  "products": [
    {
      "id": 101,
      "name": "Product A",
      "ref": "PROD-A-001",
      "price": 120,
      "discount": 10,
      "discount_2earn": 5,
      "photo_link": "https://example.com/photo.jpg",
      "description": "Product description",
      "stock": 50,
      "platform_id": 1
    }
  ]
}
```

### Features
- Filters out products with ref "#0001"
- Only returns items for validated deals
- Returns complete product details

---

## 2. Add Items to Deal (Bulk)

### Endpoint
```
POST /api/partner/items/deal/add-bulk
```

### Request Body
```json
{
  "deal_id": 1,
  "product_ids": [101, 102, 103]
}
```

### Request Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `deal_id` | integer | Yes | ID of the deal |
| `product_ids` | array | Yes | Array of product IDs to add |

### Response
```json
{
  "status": "success",
  "message": "Products added to deal successfully",
  "deal_id": 1,
  "product_ids": [101, 102, 103]
}
```

### Features
- Validates deal exists and is validated
- Bulk updates all items in single query
- Comprehensive error handling with try-catch
- Returns success with deal_id and product_ids

### Validation Rules
- Deal must exist
- Deal must be validated (`validated = true`)
- All product IDs must exist in items table

---

## 3. Remove Items from Deal (Bulk)

### Endpoint
```
POST /api/partner/items/deal/remove-bulk
```

### Request Body
```json
{
  "deal_id": 1,
  "product_ids": [101, 103]
}
```

### Request Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `deal_id` | integer | Yes | ID of the deal |
| `product_ids` | array | Yes | Array of product IDs to remove |

### Response
```json
{
  "status": "success",
  "message": "Products removed from deal successfully",
  "deal_id": 1,
  "removed_product_ids": [101, 103]
}
```

### Features
- Sets deal_id to null for specified products
- Safety check: only removes items that belong to the specified deal
- Bulk updates all items in single query
- Comprehensive error handling with try-catch

### Validation Rules
- Deal must exist
- All product IDs must exist in items table
- Deal validation status is NOT checked (can remove from any deal)

---

## Service Layer Methods

### ItemService

#### getItemsForDeal(int $dealId)
```php
/**
 * Get all items for a specific deal with complete details
 *
 * @param int $dealId
 * @return \Illuminate\Database\Eloquent\Collection
 */
public function getItemsForDeal(int $dealId)
{
    return Item::where('deal_id', $dealId)
        ->where('ref', '!=', '#0001')
        ->get();
}
```

#### bulkUpdateDeal(array $itemIds, int $dealId)
```php
/**
 * Bulk update items to assign them to a deal
 *
 * @param array $itemIds Array of item IDs to update
 * @param int $dealId Deal ID to assign to the items
 * @return int Number of items updated
 */
public function bulkUpdateDeal(array $itemIds, int $dealId): int
{
    return Item::whereIn('id', $itemIds)->update(['deal_id' => $dealId]);
}
```

#### bulkRemoveFromDeal(array $itemIds, int $dealId)
```php
/**
 * Bulk remove items from a deal
 *
 * @param array $itemIds Array of item IDs to remove from the deal
 * @param int $dealId Deal ID to verify items belong to
 * @return int Number of items removed
 */
public function bulkRemoveFromDeal(array $itemIds, int $dealId): int
{
    return Item::whereIn('id', $itemIds)
        ->where('deal_id', $dealId)
        ->update(['deal_id' => null]);
}
```

---

## Error Responses

All endpoints follow consistent error response format:

### Validation Failed (422)
```json
{
  "status": "Failed",
  "message": "Validation failed",
  "errors": {
    "field_name": ["Error message"]
  }
}
```

### Deal Not Found (404)
```json
{
  "status": "Failed",
  "message": "Deal not found"
}
```

### Deal Not Validated (400) - Add Items Only
```json
{
  "status": "Failed",
  "message": "Deal is not validated"
}
```

### Internal Server Error (500)
```json
{
  "status": "Failed",
  "message": "Failed to [operation description]",
  "error": "Detailed error message"
}
```

---

## Security & Middleware

All endpoints are protected by:
- `check.url` middleware
- Parameter validation
- Deal existence verification
- Error handling with try-catch blocks

---

## Logging

All operations are logged with:
- **INFO**: Successful operations with context
- **WARNING**: Business rule violations (e.g., deal not validated)
- **ERROR**: Validation failures and exceptions with stack traces

Example log entries:
```
[ItemsPartnerController] Products added to deal {"deal_id":1,"product_ids":[101,102,103],"updated_count":3}
[ItemsPartnerController] Products removed from deal {"deal_id":1,"product_ids":[101,103],"removed_count":2}
```

---

## Database Schema

### items table
```
deal_id (nullable, foreign key to deals.id)
```

### Relationship
- An item can belong to zero or one deal
- A deal can have many items
- When deal_id is null, the item is not associated with any deal

---

## Use Cases

### 1. Creating a New Deal with Items
```
1. Create deal
2. Validate deal
3. POST /api/partner/items/deal/add-bulk with deal_id and product_ids
4. GET /api/partner/items/deal/{dealId} to verify items were added
```

### 2. Updating Deal Items
```
1. POST /api/partner/items/deal/remove-bulk to remove unwanted items
2. POST /api/partner/items/deal/add-bulk to add new items
3. GET /api/partner/items/deal/{dealId} to verify changes
```

### 3. Removing All Items from a Deal
```
1. GET /api/partner/items/deal/{dealId} to get all current items
2. POST /api/partner/items/deal/remove-bulk with all product_ids
```

---

## Performance Considerations

- **Bulk Operations**: All add/remove operations use single SQL UPDATE queries with `whereIn()`
- **Filtering**: The `ref != '#0001'` filter is applied at query level
- **Indexing**: Ensure `deal_id` column is indexed for optimal performance
- **Pagination**: List endpoint currently returns all items (consider pagination for large deals)

---

## Best Practices

1. **Always verify deal is validated before adding items** (enforced by API)
2. **Use bulk operations instead of multiple single-item updates** for better performance
3. **Check the returned count** to verify how many items were actually updated
4. **Handle errors gracefully** in your client application
5. **Log operations** for audit trail
6. **Use transactions** if combining with other operations in your application

---

## Testing Checklist

### List Items
- [ ] List items for valid deal
- [ ] List items for non-existent deal (should return 404)
- [ ] List items for unvalidated deal (should return 400)
- [ ] Verify ref "#0001" is filtered out

### Add Items
- [ ] Add items to validated deal
- [ ] Add items to unvalidated deal (should return 400)
- [ ] Add items to non-existent deal (should return 404)
- [ ] Add non-existent items (should return 422)
- [ ] Add duplicate items (should update successfully)

### Remove Items
- [ ] Remove items from deal
- [ ] Remove items from non-existent deal (should return 404)
- [ ] Remove non-existent items (should return 422)
- [ ] Remove items not in deal (should return success with 0 removed)
- [ ] Remove items from any deal (validated or not)

---

## Date Implemented
December 31, 2025

## Related Documentation
- [BULK_ADD_ITEMS_TO_DEAL_ENDPOINT.md](./BULK_ADD_ITEMS_TO_DEAL_ENDPOINT.md)
- [BULK_REMOVE_ITEMS_FROM_DEAL_ENDPOINT.md](./BULK_REMOVE_ITEMS_FROM_DEAL_ENDPOINT.md)

