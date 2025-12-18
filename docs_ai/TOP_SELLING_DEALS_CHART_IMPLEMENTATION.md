# Top Selling Deals Chart - Implementation Documentation

## Overview
This document describes the implementation of the Top Selling Deals Chart feature, which displays the top-performing deals based on sales volume (quantity sold) and number of successful orders.

## Implementation Date
December 16, 2025

## Feature Purpose
Display the top-performing deals based on sales volume or revenue to help partners analyze which deals are most successful.

## Technical Architecture

### 1. Service Layer
**File:** `app/Services/Dashboard/SalesDashboardService.php`

**Method:** `getTopSellingDeals(array $filters = []): array`

#### Query Logic
- Joins Orders → OrderDetails → Items → Deals
- Filters only successful orders (status = `OrderEnum::Dispatched`)
- Groups results by deal
- Calculates:
  - `total_sales`: Sum of all quantities (qty) sold for successful orders in the deal
  - `sales_count`: Number of successful orders
- Orders by `total_sales` descending
- Limits results based on the `limit` parameter

#### Database Query Structure
```php
Order::query()
    ->select(
        'deals.id as deal_id',
        'deals.name as deal_name',
        DB::raw('SUM(order_details.qty) as total_sales'),
        DB::raw('COUNT(DISTINCT orders.id) as sales_count')
    )
    ->join('order_details', 'orders.id', '=', 'order_details.order_id')
    ->join('items', 'order_details.item_id', '=', 'items.id')
    ->join('deals', 'items.deal_id', '=', 'deals.id')
    ->where('orders.status', OrderEnum::Dispatched->value)
    // ... filters applied here
    ->groupBy('deals.id', 'deals.name')
    ->orderByDesc('total_sales')
    ->limit($limit)
```

### 2. Controller Layer
**File:** `app/Http/Controllers/Api/partner/SalesDashboardController.php`

**Method:** `getTopSellingDeals(Request $request): JsonResponse`

#### Request Validation
- `start_date` (optional): string, date format
- `end_date` (optional): string, date format, must be after or equal to start_date
- `platform_id` (optional): integer, must exist in platforms table
- `limit` (optional): integer, min: 1, max: 100, default: 5

#### Response Structure
```json
{
    "status": true,
    "message": "Top-selling deals retrieved successfully",
    "data": {
        "top_deals": [
            {
                "deal_id": 1,
                "deal_name": "Summer Sale 2025",
                "total_sales": 1250,
                "sales_count": 45
            },
            {
                "deal_id": 2,
                "deal_name": "Black Friday Deal",
                "total_sales": 980,
                "sales_count": 32
            }
        ]
    }
}
```

### 3. Route Configuration
**File:** `routes/api.php`

**Endpoint:** `GET /api/partner/sales/dashboard/top-deals`

**Route Name:** `api_sales_dashboard_top_deals`

**Middleware:** Applied via route group (authentication, partner permissions)

## API Endpoint Details

### Request
```
GET /api/partner/sales/dashboard/top-deals
```

### Query Parameters

| Parameter | Type | Required | Default | Description |
|-----------|------|----------|---------|-------------|
| `start_date` | string | No | null | Filter orders from this date (Y-m-d format) |
| `end_date` | string | No | null | Filter orders until this date (Y-m-d format) |
| `platform_id` | integer | No | null | Filter by specific platform |
| `limit` | integer | No | 5 | Number of top deals to return (1-100) |

### Response Fields

| Field | Type | Description |
|-------|------|-------------|
| `deal_id` | integer | Unique identifier of the deal |
| `deal_name` | string | Name of the deal |
| `total_sales` | integer | Sum of all quantities (qty) sold for successful orders |
| `sales_count` | integer | Number of successful orders (Dispatched status) |

## Success Criteria

### Calculations
✅ **total_sales**: Sum of all quantities (qty) from order_details where order status is Dispatched
✅ **sales_count**: Count of distinct orders with Dispatched status

### Filters Applied
✅ Date range filtering (start_date, end_date)
✅ Platform filtering (platform_id)
✅ Limit on number of results

### Data Integrity
✅ Only includes orders with `Dispatched` status (successful orders)
✅ Groups by deal to aggregate data correctly
✅ Handles NULL values appropriately

## Usage Examples

### Example 1: Get Top 5 Deals (Default)
```bash
GET /api/partner/sales/dashboard/top-deals
```

### Example 2: Get Top 10 Deals for Specific Platform
```bash
GET /api/partner/sales/dashboard/top-deals?platform_id=3&limit=10
```

### Example 3: Get Top Deals for Date Range
```bash
GET /api/partner/sales/dashboard/top-deals?start_date=2025-01-01&end_date=2025-12-31&limit=20
```

### Example 4: Combined Filters
```bash
GET /api/partner/sales/dashboard/top-deals?start_date=2025-11-01&end_date=2025-12-16&platform_id=5&limit=15
```

## Error Handling

### Validation Errors (422)
```json
{
    "status": "Failed",
    "message": "Validation failed",
    "errors": {
        "end_date": ["The end date must be a date after or equal to start date."]
    }
}
```

### Authorization Errors (403)
Returns when user doesn't have role in specified platform.

### Server Errors (500)
```json
{
    "status": "Failed",
    "message": "Error retrieving top-selling deals",
    "error": "Error message details"
}
```

## Database Schema Dependencies

### Tables Used
1. **orders** - Main order information and status
2. **order_details** - Individual items in orders with quantities (qty)
3. **items** - Product/service information
4. **deals** - Deal information and names

### Key Fields
- `orders.status` - Order status (filters for Dispatched = 6)
- `order_details.qty` - Quantity of items ordered
- `items.deal_id` - Links items to deals
- `deals.id`, `deals.name` - Deal identification
- `deals.platform_id` - Platform association

## Order Status Enum Reference
From `Core\Enum\OrderEnum`:
- New = 1
- Ready = 2
- Simulated = 3
- Paid = 4
- Failed = 5
- **Dispatched = 6** ← Used for successful orders

## Logging

### Success Logging
```php
Log::info('[SalesDashboardService] Top-selling deals retrieved successfully', [
    'filters' => $filters,
    'count' => count($topDeals)
]);
```

### Error Logging
```php
Log::error('[SalesDashboardService] Error in getTopSellingDeals: ' . $e->getMessage(), [
    'filters' => $filters,
    'trace' => $e->getTraceAsString()
]);
```

## Testing Recommendations

### Unit Tests
1. Test with no filters (default behavior)
2. Test with start_date only
3. Test with end_date only
4. Test with both start_date and end_date
5. Test with platform_id filter
6. Test with custom limit values (1, 5, 50, 100)
7. Test with invalid date ranges
8. Test with non-existent platform_id

### Integration Tests
1. Verify correct SQL query generation
2. Verify correct aggregation of quantities
3. Verify correct counting of orders
4. Verify proper ordering (descending by total_sales)
5. Verify limit is applied correctly
6. Test with empty result set

### Edge Cases
1. Deals with no orders
2. Deals with only failed orders (should not appear)
3. Deals with same total_sales (ordering behavior)
4. Date range with no orders
5. Platform with no deals

## Performance Considerations

### Optimization
- Query uses proper joins for efficient data retrieval
- Aggregation done at database level (SUM, COUNT)
- LIMIT applied to reduce result set size
- Indexes on foreign keys recommended:
  - `orders.status`
  - `order_details.order_id`
  - `order_details.item_id`
  - `items.deal_id`
  - `deals.platform_id`

### Scalability
- Default limit of 5 prevents large result sets
- Maximum limit of 100 to prevent performance issues
- Date range filtering reduces data scanned

## Security Considerations

1. **Authorization**: User role validation for platform access
2. **Input Validation**: All inputs validated via Laravel validator
3. **SQL Injection**: Protected by Eloquent ORM
4. **Data Exposure**: Only returns authorized data based on user platform roles

## Related Features

- Sales Dashboard KPIs (`/sales/dashboard/kpis`)
- Sales Evolution Chart (`/sales/dashboard/evolution-chart`)
- Top Selling Products (`/sales/dashboard/top-products`)

## Files Modified

1. `app/Services/Dashboard/SalesDashboardService.php` - Added `getTopSellingDeals()` method
2. `app/Http/Controllers/Api/partner/SalesDashboardController.php` - Added `getTopSellingDeals()` controller method
3. `routes/api.php` - Added route for top deals endpoint

## Future Enhancements

### Potential Improvements
1. Add revenue-based sorting option (in addition to volume)
2. Add comparison with previous period
3. Include trend indicators (up/down arrows)
4. Add filtering by deal status
5. Include average order value per deal
6. Add deal performance percentage metrics
7. Export functionality for reports
8. Caching for frequently requested data

## Maintenance Notes

- Monitor query performance with large datasets
- Consider adding database indexes if queries become slow
- Review and optimize if order volume grows significantly
- Keep OrderEnum values synchronized with business logic

## Support and Troubleshooting

### Common Issues

**Issue**: Empty result set
- **Solution**: Check if orders have Dispatched status, verify date range

**Issue**: Missing deals
- **Solution**: Verify deal has items with orders, check platform_id filter

**Issue**: Incorrect total_sales
- **Solution**: Verify order_details.qty values, check order status filter

## Changelog

### Version 1.0 - December 16, 2025
- Initial implementation
- Added service method for top deals calculation
- Added controller endpoint
- Added API route
- Created documentation

