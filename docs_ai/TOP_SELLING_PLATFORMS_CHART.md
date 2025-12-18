# Top Selling Platforms Chart Implementation

## Overview
This document describes the implementation of the Platforms Top Selling Chart feature that ranks platforms by their sales based on the `purchase_value` field in the `commission_break_downs` table.

## Implementation Date
December 18, 2025

## API Endpoint

### GET `/api/partner/sales/dashboard/top-platforms`

#### Description
Retrieves platforms ranked by total sales calculated from the `purchase_value` column in the `commission_break_downs` table.

#### Query Parameters
| Parameter | Type | Required | Default | Description |
|-----------|------|----------|---------|-------------|
| start_date | date | No | - | Filter records from this date onwards (format: Y-m-d) |
| end_date | date | No | - | Filter records up to this date (format: Y-m-d) |
| limit | integer | No | 10 | Maximum number of platforms to return (1-100) |

#### Response Format
```json
{
    "status": true,
    "message": "Top-selling platforms retrieved successfully",
    "data": {
        "top_platforms": [
            {
                "platform_id": 1,
                "platform_name": "Platform Name",
                "total_sales": 15000.50
            },
            {
                "platform_id": 2,
                "platform_name": "Another Platform",
                "total_sales": 12500.75
            }
        ]
    }
}
```

#### Example Requests

**Get top 10 platforms (default):**
```bash
GET /api/partner/sales/dashboard/top-platforms
```

**Get top 5 platforms:**
```bash
GET /api/partner/sales/dashboard/top-platforms?limit=5
```

**Get top platforms for a date range:**
```bash
GET /api/partner/sales/dashboard/top-platforms?start_date=2025-01-01&end_date=2025-12-31&limit=10
```

## Implementation Details

### Files Modified/Created

1. **SalesDashboardService.php** - Added `getTopSellingPlatforms()` method
   - Location: `app/Services/Dashboard/SalesDashboardService.php`
   - Method: `public function getTopSellingPlatforms(array $filters = []): array`

2. **SalesDashboardController.php** - Added `getTopSellingPlatforms()` controller method
   - Location: `app/Http/Controllers/Api/partner/SalesDashboardController.php`
   - Method: `public function getTopSellingPlatforms(Request $request): JsonResponse`

3. **api.php** - Added route
   - Location: `routes/api.php`
   - Route: `Route::get('/sales/dashboard/top-platforms', [SalesDashboardController::class, 'getTopSellingPlatforms'])->name('api_sales_dashboard_top_platforms');`

### Database Query Logic

The implementation queries the `commission_break_downs` table and:
1. Joins with the `platforms` table to get platform names
2. Filters out records where `platform_id` or `purchase_value` is NULL
3. Optionally filters by date range (`start_date` and `end_date`)
4. Groups by platform
5. Sums the `purchase_value` column to calculate `total_sales`
6. Orders by `total_sales` descending
7. Limits results based on the `limit` parameter

### SQL Query (Conceptual)
```sql
SELECT 
    platforms.id as platform_id,
    platforms.name as platform_name,
    SUM(commission_break_downs.purchase_value) as total_sales
FROM commission_break_downs
INNER JOIN platforms ON commission_break_downs.platform_id = platforms.id
WHERE commission_break_downs.platform_id IS NOT NULL
  AND commission_break_downs.purchase_value IS NOT NULL
  AND commission_break_downs.created_at >= ? -- if start_date provided
  AND commission_break_downs.created_at <= ? -- if end_date provided
GROUP BY platforms.id, platforms.name
ORDER BY total_sales DESC
LIMIT ?
```

## Validation Rules

The endpoint validates the following:
- `start_date`: Optional, must be a valid date
- `end_date`: Optional, must be a valid date and after or equal to start_date
- `limit`: Optional, must be an integer between 1 and 100

## Error Handling

The implementation includes comprehensive error handling:
- **Validation Errors**: Returns 422 Unprocessable Entity with validation error details
- **Server Errors**: Returns 500 Internal Server Error with error message
- **Logging**: All errors and successful requests are logged with context

## Testing

### Manual Testing Examples

**Using cURL:**
```bash
# Get top 10 platforms
curl -X GET "http://localhost/api/partner/sales/dashboard/top-platforms" \
  -H "Authorization: Bearer YOUR_TOKEN"

# Get top 5 platforms with date range
curl -X GET "http://localhost/api/partner/sales/dashboard/top-platforms?start_date=2025-01-01&end_date=2025-12-31&limit=5" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

**Using Postman:**
1. Method: GET
2. URL: `http://your-domain/api/partner/sales/dashboard/top-platforms`
3. Headers: Authorization: Bearer YOUR_TOKEN
4. Query Params: Add optional parameters (start_date, end_date, limit)

## Dependencies

This feature depends on:
- `commission_break_downs` table having the `platform_id` column (added in migration `2025_12_18_085043_add_platform_id_to_commission_break_downs_table.php`)
- `platforms` table
- Laravel Query Builder (DB facade)

## Notes

1. The `purchase_value` field in `commission_break_downs` represents the actual purchase value for each commission record
2. Platforms without any commission breakdowns will not appear in the results
3. The query only includes records where both `platform_id` and `purchase_value` are not NULL
4. Results are ordered by total sales in descending order (highest first)
5. The `total_sales` value is returned as a float with full precision

## Future Enhancements

Potential improvements:
1. Add filtering by specific platform owner or manager
2. Add additional metrics like order count per platform
3. Include percentage of total sales
4. Add caching for better performance on large datasets
5. Add export functionality (CSV, Excel)
6. Add comparison with previous periods

## Related Documentation

- [Commission Breakdown Model](../app/Models/CommissionBreakDown.php)
- [Platform Model](../Core/Models/Platform.php)
- [Sales Dashboard Service](../app/Services/Dashboard/SalesDashboardService.php)
- [Platform Partner Controller](../app/Http/Controllers/Api/partner/PlatformPartnerController.php)

