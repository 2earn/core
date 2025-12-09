# Top-Selling Products/Services Histogram Implementation

## Overview
This document describes the implementation of the Top-Selling Products/Services histogram endpoint for the Sales Dashboard.

## Implementation Date
December 9, 2025

## Endpoint Details

### URL
```
GET /api/partner/sales/dashboard/top-products
```

### Route Name
```
api_partner_api_sales_dashboard_top_products
```

### Request Parameters

| Parameter | Type | Required | Validation | Description |
|-----------|------|----------|------------|-------------|
| `start_date` | date | No | Valid date format | Start date for filtering orders |
| `end_date` | date | No | Valid date, after_or_equal:start_date | End date for filtering orders |
| `platform_id` | integer | No | exists:platforms,id | Filter by specific platform |
| `deal_id` | integer | No | exists:deals,id | Filter by specific deal |
| `user_id` | integer | Yes | exists:users,id | User ID for authorization check |
| `limit` | integer | No | min:1, max:100 | Number of top products to return (default: 10) |

### Response Format

#### Success Response (200 OK)
```json
{
    "status": true,
    "message": "Top-selling products retrieved successfully",
    "data": [
        {
            "product_name": "Product A",
            "sale_count": 150
        },
        {
            "product_name": "Product B",
            "sale_count": 120
        },
        {
            "product_name": "Product C",
            "sale_count": 95
        }
    ]
}
```

#### Error Response (422 Unprocessable Entity)
```json
{
    "status": "Failed",
    "message": "Validation failed",
    "errors": {
        "user_id": ["The user id field is required."]
    }
}
```

#### Error Response (500 Internal Server Error)
```json
{
    "status": "Failed",
    "message": "Error retrieving top-selling products",
    "error": "Detailed error message"
}
```

## Implementation Details

### Files Modified

1. **app/Services/Dashboard/SalesDashboardService.php**
   - Added `getTopSellingProducts()` method
   - Queries `order_details` joined with `orders` and `items`
   - Filters by successful orders (Dispatched status - OrderEnum::Dispatched)
   - Groups by product/item and calculates total quantity sold
   - Orders by sale count descending
   - Applies user role authorization check

2. **app/Http/Controllers/Api/partner/SalesDashboardController.php**
   - Added `getTopSellingProducts()` endpoint method
   - Validates incoming request parameters
   - Calls the service layer
   - Returns formatted JSON response

3. **routes/api.php**
   - Added route for `/sales/dashboard/top-products`

### Business Logic

#### Sale Calculation
- **Sale Count**: Sum of `order_details.qty` for each product
- **Order Status Filter**: Only includes orders with status = `Dispatched` (successful payments)
- **Grouping**: Products are grouped by `items.id` and `items.name`
- **Ordering**: Results ordered by sale_count descending (highest first)

#### Authorization
- If both `user_id` and `platform_id` are provided, the system checks if the user has a role in that platform
- Uses `PlatformService::userHasRoleInPlatform()` for authorization
- Returns error if user doesn't have access to the platform

#### Date Filtering
- `start_date`: Filters orders created on or after this date
- `end_date`: Filters orders created on or before this date
- Filters applied on `orders.created_at` column

#### Platform Filtering
- When `platform_id` is provided, only products from that platform are included
- Filters on `items.platform_id`

#### Deal Filtering
- When `deal_id` is provided, only products from that deal are included
- Filters on `items.deal_id`
- Useful for tracking performance of specific deals

## Database Schema

### Tables Used
- `orders`: Main order table
- `order_details`: Order line items with quantities
- `items`: Product/service information

### Key Relationships
```
orders (1) ---> (Many) order_details
order_details (Many) ---> (1) items
items (Many) ---> (1) platforms
```

### Order Status Enum
```php
OrderEnum::Dispatched = 6  // Successfully completed orders
```

## Usage Examples

### Example 1: Get Top 10 Products for All Platforms
```bash
GET /api/partner/sales/dashboard/top-products?user_id=123
```

### Example 2: Get Top 20 Products for Specific Platform
```bash
GET /api/partner/sales/dashboard/top-products?user_id=123&platform_id=5&limit=20
```

### Example 3: Get Top Products for Date Range
```bash
GET /api/partner/sales/dashboard/top-products?user_id=123&start_date=2025-01-01&end_date=2025-12-31&limit=15
```

### Example 4: Get Top Products for Specific Platform and Date Range
```bash
GET /api/partner/sales/dashboard/top-products?user_id=123&platform_id=5&start_date=2025-01-01&end_date=2025-03-31&limit=10
```

### Example 5: Get Top Products for Specific Deal
```bash
GET /api/partner/sales/dashboard/top-products?user_id=123&deal_id=10&start_date=2025-01-01&end_date=2025-12-31&limit=15
```

## Frontend Integration

### Histogram Chart Data Structure
The response data is optimized for histogram/bar chart visualization:

```javascript
// Example data mapping for Chart.js
const chartData = {
    labels: response.data.map(item => item.product_name),
    datasets: [{
        label: 'Sales Count',
        data: response.data.map(item => item.sale_count),
        backgroundColor: 'rgba(54, 162, 235, 0.6)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1
    }]
};
```

## Testing Recommendations

### Test Cases
1. ✅ Valid request with all parameters
2. ✅ Valid request with minimum parameters (only user_id)
3. ✅ Invalid user_id (non-existent user)
4. ✅ Invalid platform_id (non-existent platform)
5. ✅ User without access to specified platform
6. ✅ Date range validation (end_date before start_date)
7. ✅ Limit boundary testing (0, 1, 100, 101)
8. ✅ Empty result set (no orders in date range)
9. ✅ Platform with no products sold

## Performance Considerations

### Query Optimization
- Uses joins instead of nested queries
- Applies filters before aggregation
- Indexed columns: `orders.status`, `orders.created_at`, `items.platform_id`
- Limited result set with configurable limit

### Logging
- All requests are logged with filters
- Errors are logged with full trace
- Success responses log result count

## Security

### Authorization
- User ID is required and validated
- Platform access is verified before returning data
- Only dispatched (successful) orders are included

### Input Validation
- All dates validated for format
- Platform ID checked against database
- User ID checked against database
- Limit bounded between 1-100

## Future Enhancements

### Potential Improvements
1. Add revenue amount alongside sale count
2. Include product category filtering
3. Add comparison with previous period
4. Support for multiple platform filtering
5. Export functionality (CSV, PDF)
6. Caching for frequently requested data
7. Add trending indicator (up/down arrow)

## Related Endpoints

- `GET /api/partner/sales/dashboard/kpis` - Sales KPI metrics
- (Future) `GET /api/partner/sales/dashboard/chart` - Sales evolution chart

## Dependencies

### Services
- `SalesDashboardService`: Business logic layer
- `PlatformService`: User authorization

### Models
- `Order`: Order management
- `OrderDetail`: Order line items
- `Item`: Product/service information

### Enums
- `OrderEnum`: Order status definitions

## Maintenance Notes

### Code Location
- Controller: `app/Http/Controllers/Api/partner/SalesDashboardController.php`
- Service: `app/Services/Dashboard/SalesDashboardService.php`
- Route: `routes/api.php` (line ~142)

### Contact
For questions or issues, refer to the development team or check the related documentation in `docs_ai/`.

