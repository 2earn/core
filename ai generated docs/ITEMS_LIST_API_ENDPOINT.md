# Items List API Endpoint - Documentation

## Overview
This document describes the Items List API endpoint that allows filtering items by platform and shows whether each item is assigned to a deal.

## Endpoint Details

**Route:** `GET /api/partner/items`  
**Route Name:** `api_partner_items_list`  
**Controller Method:** `ItemsPartnerController@listItems`

## Request Parameters

### Required Parameters
- `platform_id` (required, integer, exists in platforms table)
  - The ID of the platform to filter items by

### Optional Parameters
- `page` (nullable, integer, min: 1)
  - The page number for pagination
  - Default: 1

- `per_page` (nullable, integer, min: 1, max: 100)
  - Number of items per page
  - Default: 15

## Request Example

```bash
# Basic request
GET /api/partner/items?platform_id=1

# With pagination
GET /api/partner/items?platform_id=1&page=2&per_page=20
```

## Response Format

### Success Response (200 - OK)

```json
{
    "status": "Success",
    "message": "Items retrieved successfully",
    "data": {
        "platform_id": 1,
        "items": [
            {
                "id": 1,
                "name": "Product Name",
                "ref": "PROD-001",
                "price": 99.99,
                "discount": 10.00,
                "discount_2earn": 5.00,
                "photo_link": "https://example.com/image.jpg",
                "description": "Product description",
                "stock": 50,
                "platform_id": 1,
                "platform_name": "Platform Name",
                "is_assigned_to_deal": true,
                "deal": {
                    "id": 5,
                    "name": "Summer Sale Deal",
                    "validated": true
                },
                "created_at": "2024-01-01T00:00:00.000000Z",
                "updated_at": "2024-01-15T10:30:00.000000Z"
            },
            {
                "id": 2,
                "name": "Another Product",
                "ref": "PROD-002",
                "price": 49.99,
                "discount": 0,
                "discount_2earn": 2.50,
                "photo_link": null,
                "description": "Another product description",
                "stock": 100,
                "platform_id": 1,
                "platform_name": "Platform Name",
                "is_assigned_to_deal": false,
                "deal": null,
                "created_at": "2024-01-02T00:00:00.000000Z",
                "updated_at": "2024-01-02T00:00:00.000000Z"
            }
        ],
        "pagination": {
            "current_page": 1,
            "per_page": 15,
            "total": 45,
            "last_page": 3,
            "from": 1,
            "to": 15
        }
    }
}
```

## Response Fields

### Item Object Fields

| Field | Type | Description |
|-------|------|-------------|
| `id` | integer | Unique item identifier |
| `name` | string | Item name |
| `ref` | string | Item reference/SKU |
| `price` | decimal | Item price |
| `discount` | decimal | Discount amount |
| `discount_2earn` | decimal | 2earn platform discount |
| `photo_link` | string\|null | URL to item photo |
| `description` | string\|null | Item description |
| `stock` | integer | Available stock quantity |
| `platform_id` | integer | Platform ID |
| `platform_name` | string | Platform name |
| `is_assigned_to_deal` | boolean | **Key Field**: Indicates if item is assigned to a deal |
| `deal` | object\|null | Deal information if assigned, null otherwise |
| `created_at` | timestamp | Item creation timestamp |
| `updated_at` | timestamp | Last update timestamp |

### Deal Object Fields (when item is assigned)

| Field | Type | Description |
|-------|------|-------------|
| `id` | integer | Deal identifier |
| `name` | string | Deal name |
| `validated` | boolean | Deal validation status |

### Pagination Object Fields

| Field | Type | Description |
|-------|------|-------------|
| `current_page` | integer | Current page number |
| `per_page` | integer | Items per page |
| `total` | integer | Total number of items |
| `last_page` | integer | Last page number |
| `from` | integer | First item number on current page |
| `to` | integer | Last item number on current page |

## Error Responses

### 422 - Validation Error (Missing platform_id)

```json
{
    "status": "Failed",
    "message": "Validation failed",
    "errors": {
        "platform_id": [
            "The platform id field is required."
        ]
    }
}
```

### 422 - Validation Error (Invalid platform_id)

```json
{
    "status": "Failed",
    "message": "Validation failed",
    "errors": {
        "platform_id": [
            "The selected platform id is invalid."
        ]
    }
}
```

### 500 - Internal Server Error

```json
{
    "status": "Failed",
    "message": "Failed to retrieve items",
    "error": "Error details here"
}
```

## Features

### ✅ Platform Filtering
- Returns only items belonging to the specified platform
- Validates that the platform exists

### ✅ Deal Assignment Indication
- Each item includes `is_assigned_to_deal` boolean field
- If `true`, the `deal` object contains deal information
- If `false`, the `deal` field is `null`

### ✅ Pagination Support
- Configurable items per page (1-100)
- Complete pagination metadata included
- Default: 15 items per page

### ✅ Eager Loading
- Optimized database queries with eager loading of:
  - Deal information
  - Platform information

### ✅ Sorted Results
- Items ordered by creation date (newest first)

## Use Cases

### 1. Display All Platform Items
```javascript
// Fetch all items for platform 1
fetch('/api/partner/items?platform_id=1')
  .then(response => response.json())
  .then(data => {
    const items = data.data.items;
    items.forEach(item => {
      console.log(`${item.name}: ${item.is_assigned_to_deal ? 'In Deal' : 'Available'}`);
    });
  });
```

### 2. Filter Unassigned Items
```javascript
// Get items not assigned to any deal
fetch('/api/partner/items?platform_id=1')
  .then(response => response.json())
  .then(data => {
    const unassignedItems = data.data.items.filter(
      item => !item.is_assigned_to_deal
    );
    console.log(`Unassigned items: ${unassignedItems.length}`);
  });
```

### 3. Display Items with Deal Info
```javascript
// Show items with their deal status
fetch('/api/partner/items?platform_id=1')
  .then(response => response.json())
  .then(data => {
    data.data.items.forEach(item => {
      if (item.is_assigned_to_deal) {
        console.log(`${item.name} - Deal: ${item.deal.name} (${item.deal.validated ? 'Active' : 'Inactive'})`);
      } else {
        console.log(`${item.name} - Not in any deal`);
      }
    });
  });
```

## Testing

The endpoint has comprehensive test coverage including:

✅ **test_can_list_items_with_platform_filter**
- Verifies correct filtering by platform
- Ensures items from other platforms are excluded
- Validates response structure

✅ **test_can_list_items_with_pagination**
- Tests pagination functionality
- Verifies page parameters work correctly
- Checks pagination metadata accuracy

✅ **test_list_items_shows_deal_assignment_status**
- Confirms `is_assigned_to_deal` field accuracy
- Validates deal object presence/absence
- Tests both assigned and unassigned scenarios

✅ **test_list_items_fails_without_platform_id**
- Ensures validation works for missing parameters

✅ **test_list_items_fails_with_invalid_platform_id**
- Validates platform existence checking

## Performance Considerations

1. **Eager Loading**: Uses `with()` to prevent N+1 query problems
2. **Pagination**: Limits query size for better performance
3. **Indexing**: Queries use indexed `platform_id` column
4. **Selective Fields**: Only loads necessary relationship fields

## Security

- Protected by `check.url` middleware (IP validation)
- Part of `/api/partner/` route group
- Platform ID validation prevents unauthorized access
- SQL injection protection through Laravel's query builder

## Logging

All operations are logged with the prefix `[ItemsPartnerController]`:

- **Info**: Successful item retrieval with counts
- **Error**: Failed retrievals with error details and stack traces
- **Error**: Validation failures with error details

## Related Endpoints

- `GET /api/partner/items/deal/{dealId}` - List items for a specific deal
- `POST /api/partner/items/deal/add-bulk` - Add items to a deal in bulk
- `POST /api/partner/items/deal/remove-bulk` - Remove items from a deal in bulk
- `POST /api/partner/items` - Create a new item
- `PUT /api/partner/items/{id}` - Update an item

## Implementation Date
January 23, 2026

## Version
1.0.0
