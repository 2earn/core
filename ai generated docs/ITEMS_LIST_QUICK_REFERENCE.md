# Items List API - Quick Reference Card

## 📍 Endpoint
```
GET /api/partner/items
```

## 📥 Request Parameters

| Parameter | Type | Required | Default | Description |
|-----------|------|----------|---------|-------------|
| `platform_id` | integer | ✅ Yes | - | Platform ID to filter items |
| `page` | integer | ❌ No | 1 | Page number |
| `per_page` | integer | ❌ No | 15 | Items per page (max: 100) |

## 📤 Response Fields

### Main Response
```json
{
  "status": "Success",
  "message": "Items retrieved successfully",
  "data": { ... }
}
```

### Item Object
| Field | Type | Description |
|-------|------|-------------|
| `id` | integer | Item ID |
| `name` | string | Item name |
| `ref` | string | Reference/SKU |
| `price` | decimal | Price |
| `discount` | decimal | Discount |
| `discount_2earn` | decimal | 2earn discount |
| `stock` | integer | Stock quantity |
| `platform_id` | integer | Platform ID |
| `platform_name` | string | Platform name |
| **`is_assigned_to_deal`** | **boolean** | **Deal assignment status** |
| **`deal`** | **object\|null** | **Deal info or null** |

### Deal Object (when assigned)
| Field | Type | Description |
|-------|------|-------------|
| `id` | integer | Deal ID |
| `name` | string | Deal name |
| `validated` | boolean | Is validated |

## 🎯 Quick Examples

### Basic Request
```bash
curl "https://api.example.com/api/partner/items?platform_id=1"
```

### With Pagination
```bash
curl "https://api.example.com/api/partner/items?platform_id=1&page=2&per_page=20"
```

### JavaScript/Fetch
```javascript
const response = await fetch('/api/partner/items?platform_id=1');
const data = await response.json();
const items = data.data.items;
```

### Check Deal Assignment
```javascript
items.forEach(item => {
    if (item.is_assigned_to_deal) {
        console.log(`${item.name} -> Deal: ${item.deal.name}`);
    } else {
        console.log(`${item.name} -> Not in deal`);
    }
});
```

### Filter Unassigned Items
```javascript
const unassigned = items.filter(item => !item.is_assigned_to_deal);
```

### Filter Items in Deals
```javascript
const inDeals = items.filter(item => item.is_assigned_to_deal);
```

### Search Items
```javascript
// Search by name, ref, or description
const searchResponse = await fetch('/api/partner/items?platform_id=1&search=Red');
const searchData = await searchResponse.json();
console.log(`Found ${searchData.data.items.length} items`);
```

### Find Item by SKU
```javascript
const skuResponse = await fetch('/api/partner/items?platform_id=1&search=SKU-123');
const skuData = await skuResponse.json();
if (skuData.data.items.length > 0) {
    console.log('Item found:', skuData.data.items[0].name);
}
```

## ⚠️ Common Errors

### Missing platform_id
```json
{
  "status": "Failed",
  "message": "Validation failed",
  "errors": {
    "platform_id": ["The platform id field is required."]
  }
}
```

### Invalid platform_id
```json
{
  "status": "Failed",
  "message": "Validation failed",
  "errors": {
    "platform_id": ["The selected platform id is invalid."]
  }
}
```

## ✅ Response Status Codes

| Code | Meaning |
|------|---------|
| 200 | Success |
| 422 | Validation Error |
| 500 | Server Error |

## 📊 Example Response
```json
{
  "status": "Success",
  "message": "Items retrieved successfully",
  "data": {
    "platform_id": 1,
    "items": [
      {
        "id": 123,
        "name": "Product A",
        "ref": "PROD-A",
        "price": 29.99,
        "stock": 50,
        "is_assigned_to_deal": true,
        "deal": {
          "id": 5,
          "name": "Flash Sale",
          "validated": true
        }
      },
      {
        "id": 124,
        "name": "Product B",
        "ref": "PROD-B",
        "price": 39.99,
        "stock": 30,
        "is_assigned_to_deal": false,
        "deal": null
      }
    ],
    "pagination": {
      "current_page": 1,
      "per_page": 15,
      "total": 45,
      "last_page": 3
    }
  }
}
```

## 🔗 Related Endpoints
- `GET /api/partner/items/deal/{dealId}` - List items for specific deal
- `POST /api/partner/items/deal/add-bulk` - Add items to deal
- `POST /api/partner/items/deal/remove-bulk` - Remove items from deal
