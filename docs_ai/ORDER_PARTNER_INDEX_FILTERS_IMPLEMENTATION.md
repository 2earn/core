# Order Partner Controller - Index Filters Implementation

## Summary
Added comprehensive filtering capabilities to the `OrderPartnerController::index()` method to allow partners to filter orders by multiple criteria.

## Implementation Date
December 2, 2025

## Changes Made

### 1. OrderPartnerController::index() Method
**File:** `app/Http/Controllers/Api/partner/OrderPartnerController.php`

#### Added Validation Rules:
- `note` - nullable|string|max:255 - Filter by order notes (partial match)
- `status` - nullable|integer - Filter by order status value
- `created_by` - nullable|integer|exists:users,id - Filter by user who created the order
- `user_search` - nullable|string|max:255 - Search users by email or name

#### Updated Filters Array:
```php
$filters = [
    'platform_id' => $request->input('platform_id'),
    'note' => $request->input('note'),
    'status' => $request->input('status'),
    'created_by' => $request->input('created_by'),
    'user_search' => $request->input('user_search')
];
```

### 2. OrderService::getOrdersQuery() Method
**File:** `app/Services/Orders/OrderService.php`

#### Added Filter Logic:

1. **Note Filter (Partial Match):**
   ```php
   if (!empty($filters['note'])) {
       $query->where('note', 'LIKE', '%' . $filters['note'] . '%');
   }
   ```

2. **Created By Filter (Exact Match):**
   ```php
   if (!empty($filters['created_by'])) {
       $query->where('created_by', $filters['created_by']);
   }
   ```

3. **User Search Filter (Searches User Email and Name):**
   ```php
   if (!empty($filters['user_search'])) {
       $query->whereHas('user', function ($q) use ($filters) {
           $q->where('email', 'LIKE', '%' . $filters['user_search'] . '%')
             ->orWhere('name', 'LIKE', '%' . $filters['user_search'] . '%');
       });
   }
   ```

## API Usage

### Endpoint
`GET /api/partner/orders`

### Request Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| user_id | integer | Yes | ID of the user whose orders to retrieve |
| platform_id | integer | No | Filter by platform ID |
| note | string | No | Search orders by note content (partial match) |
| status | integer | No | Filter by order status value |
| created_by | integer | No | Filter by user ID who created the order |
| user_search | string | No | Search by user email or name |
| limit | integer | No | Number of results per page (default: 8) |
| page | integer | No | Page number for pagination |

### Example Requests

#### 1. Filter by Note
```json
GET /api/partner/orders?user_id=123&note=urgent
```

#### 2. Filter by Status
```json
GET /api/partner/orders?user_id=123&status=1
```

#### 3. Filter by Creator
```json
GET /api/partner/orders?user_id=123&created_by=456
```

#### 4. Search by User Email or Name
```json
GET /api/partner/orders?user_id=123&user_search=john@example.com
```
or
```json
GET /api/partner/orders?user_id=123&user_search=john
```

#### 5. Combined Filters
```json
GET /api/partner/orders?user_id=123&status=1&note=urgent&created_by=456&user_search=john
```

### Response Format
```json
{
    "status": true,
    "data": [
        {
            "id": 1,
            "user_id": 123,
            "note": "Urgent order",
            "status": 1,
            "created_by": 456,
            "user": {
                "id": 123,
                "name": "John Doe",
                "email": "john@example.com"
            },
            "OrderDetails": [...],
            // ... other order fields
        }
    ],
    "total": 50
}
```

## Technical Details

### Filter Types

1. **Exact Match Filters:**
   - `status` - Exact integer match on order status
   - `created_by` - Exact match on user ID who created the order
   - `platform_id` - Exact match on platform ID (existing)

2. **Partial Match Filters:**
   - `note` - Case-insensitive partial match using SQL LIKE
   - `user_search` - Searches across user's email and name fields

### Database Relationships
- The `user_search` filter uses Laravel's `whereHas()` to query the related User model through the `Order::user()` relationship
- This ensures efficient querying with proper joins

### Validation
All filter parameters are validated:
- Type checking (integer, string)
- Existence checking for foreign keys (users, platforms)
- Maximum length constraints for string fields
- All filters are optional (nullable)

## Testing Recommendations

1. Test each filter individually
2. Test combined filters
3. Test with empty/null filter values
4. Test with invalid user IDs (should return validation error)
5. Test pagination with filters applied
6. Test partial matches for note and user_search
7. Test case-insensitivity of search filters

## Notes

- The `user_search` parameter searches both email AND name fields (OR condition)
- The `note` filter performs partial matching, so "urg" will match "urgent"
- All filters work in combination - results must match ALL provided filters
- Filters are applied before pagination
- The total count reflects filtered results

## Related Models

- **Order Model:** `app/Models/Order.php`
  - Has `user()` relationship to User model
  - Has `created_by` field for tracking creator

- **User Model:** `app/Models/User.php`
  - Has `email` and `name` fields searchable via `user_search`

## Status Values Reference

Refer to `Core\Enum\OrderEnum` for valid status values:
- New
- Ready
- (other status values as defined in the enum)

