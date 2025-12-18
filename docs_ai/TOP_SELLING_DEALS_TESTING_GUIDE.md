# Top Selling Deals Chart - Testing Guide

## Prerequisites
- Ensure you have test data with:
  - Deals created in the database
  - Items associated with those deals
  - Orders with OrderDetails containing qty values
  - At least some orders with `status = 6` (Dispatched)

## Manual API Testing

### Using cURL (PowerShell)

#### Test 1: Basic Request (Default Limit = 5)
```powershell
$headers = @{
    "Accept" = "application/json"
    "Content-Type" = "application/json"
}

Invoke-WebRequest -Uri "http://localhost/api/partner/sales/dashboard/top-deals" `
    -Method GET `
    -Headers $headers
```

#### Test 2: With Limit Parameter
```powershell
Invoke-WebRequest -Uri "http://localhost/api/partner/sales/dashboard/top-deals?limit=10" `
    -Method GET `
    -Headers $headers
```

#### Test 3: With Platform Filter
```powershell
Invoke-WebRequest -Uri "http://localhost/api/partner/sales/dashboard/top-deals?platform_id=1&limit=10" `
    -Method GET `
    -Headers $headers
```

#### Test 4: With Date Range
```powershell
Invoke-WebRequest -Uri "http://localhost/api/partner/sales/dashboard/top-deals?start_date=2025-01-01&end_date=2025-12-16&limit=20" `
    -Method GET `
    -Headers $headers
```

#### Test 5: All Parameters Combined
```powershell
Invoke-WebRequest -Uri "http://localhost/api/partner/sales/dashboard/top-deals?start_date=2025-11-01&end_date=2025-12-16&platform_id=1&limit=15" `
    -Method GET `
    -Headers $headers
```

### Using Postman

1. **Method**: GET
2. **URL**: `http://localhost/api/partner/sales/dashboard/top-deals`
3. **Query Parameters**:
   - `start_date` (optional): `2025-01-01`
   - `end_date` (optional): `2025-12-16`
   - `platform_id` (optional): `1`
   - `limit` (optional): `10`
4. **Headers**:
   - `Accept`: `application/json`
   - `Content-Type`: `application/json`
   - Add authentication headers if required

## Validation Tests

### Test Invalid Date Range
```powershell
# Should return 422 error
Invoke-WebRequest -Uri "http://localhost/api/partner/sales/dashboard/top-deals?start_date=2025-12-31&end_date=2025-01-01" `
    -Method GET `
    -Headers $headers
```

Expected Response:
```json
{
    "status": "Failed",
    "message": "Validation failed",
    "errors": {
        "end_date": ["The end date must be a date after or equal to start date."]
    }
}
```

### Test Invalid Limit
```powershell
# Should return 422 error
Invoke-WebRequest -Uri "http://localhost/api/partner/sales/dashboard/top-deals?limit=150" `
    -Method GET `
    -Headers $headers
```

Expected Response:
```json
{
    "status": "Failed",
    "message": "Validation failed",
    "errors": {
        "limit": ["The limit must not be greater than 100."]
    }
}
```

### Test Invalid Platform ID
```powershell
# Should return 422 error
Invoke-WebRequest -Uri "http://localhost/api/partner/sales/dashboard/top-deals?platform_id=99999" `
    -Method GET `
    -Headers $headers
```

Expected Response:
```json
{
    "status": "Failed",
    "message": "Validation failed",
    "errors": {
        "platform_id": ["The selected platform id is invalid."]
    }
}
```

## Database Verification Queries

### Check Available Deals
```sql
SELECT id, name, platform_id 
FROM deals 
ORDER BY name;
```

### Check Orders with Dispatched Status
```sql
SELECT COUNT(*) as dispatched_orders
FROM orders 
WHERE status = 6;
```

### Manual Verification of Top Deals Query
```sql
SELECT 
    d.id as deal_id,
    d.name as deal_name,
    SUM(od.qty) as total_sales,
    COUNT(DISTINCT o.id) as sales_count
FROM orders o
JOIN order_details od ON o.id = od.order_id
JOIN items i ON od.item_id = i.id
JOIN deals d ON i.deal_id = d.id
WHERE o.status = 6
GROUP BY d.id, d.name
ORDER BY total_sales DESC
LIMIT 10;
```

### Verify Data with Filters
```sql
-- With date range
SELECT 
    d.id as deal_id,
    d.name as deal_name,
    SUM(od.qty) as total_sales,
    COUNT(DISTINCT o.id) as sales_count
FROM orders o
JOIN order_details od ON o.id = od.order_id
JOIN items i ON od.item_id = i.id
JOIN deals d ON i.deal_id = d.id
WHERE o.status = 6
  AND o.created_at >= '2025-01-01'
  AND o.created_at <= '2025-12-31'
GROUP BY d.id, d.name
ORDER BY total_sales DESC
LIMIT 10;
```

## Expected Response Format

### Success Response (200)
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
                "deal_id": 5,
                "deal_name": "Black Friday Deal",
                "total_sales": 980,
                "sales_count": 32
            },
            {
                "deal_id": 3,
                "deal_name": "Spring Collection",
                "total_sales": 750,
                "sales_count": 28
            }
        ]
    }
}
```

### Empty Result (200)
```json
{
    "status": true,
    "message": "Top-selling deals retrieved successfully",
    "data": {
        "top_deals": []
    }
}
```

## Log Verification

Check the Laravel log file at `storage/logs/laravel.log`:

### Success Log Entry
```
[YYYY-MM-DD HH:MM:SS] local.INFO: [SalesDashboardService] Top-selling deals retrieved successfully 
{"filters":{"start_date":"2025-01-01","end_date":"2025-12-16","platform_id":1,"limit":10},"count":10}
```

### Error Log Entry
```
[YYYY-MM-DD HH:MM:SS] local.ERROR: [SalesDashboardService] Error in getTopSellingDeals: [error message] 
{"filters":{...},"trace":"..."}
```

## Integration Test Example

Create a test file: `tests/Feature/TopSellingDealsTest.php`

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Order;
use App\Models\Deal;
use App\Models\Item;
use App\Models\OrderDetail;
use Core\Enum\OrderEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TopSellingDealsTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_top_selling_deals_returns_correct_structure()
    {
        $response = $this->getJson('/api/partner/sales/dashboard/top-deals');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         'top_deals' => [
                             '*' => [
                                 'deal_id',
                                 'deal_name',
                                 'total_sales',
                                 'sales_count'
                             ]
                         ]
                     ]
                 ]);
    }

    public function test_get_top_selling_deals_with_limit()
    {
        $response = $this->getJson('/api/partner/sales/dashboard/top-deals?limit=3');

        $response->assertStatus(200);
        $data = $response->json('data.top_deals');
        $this->assertLessThanOrEqual(3, count($data));
    }

    public function test_validation_fails_with_invalid_date_range()
    {
        $response = $this->getJson('/api/partner/sales/dashboard/top-deals?start_date=2025-12-31&end_date=2025-01-01');

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['end_date']);
    }
}
```

## Performance Testing

### Check Query Execution Time
```php
// Add to the service method temporarily
$startTime = microtime(true);

// ... existing query code ...

$executionTime = microtime(true) - $startTime;
Log::info('Query execution time: ' . $executionTime . ' seconds');
```

### Load Testing with Apache Bench
```bash
# Install Apache Bench if not available
# Test with 100 requests, 10 concurrent
ab -n 100 -c 10 "http://localhost/api/partner/sales/dashboard/top-deals?limit=10"
```

## Troubleshooting

### Issue: Empty Results
**Check:**
1. Are there orders with `status = 6` (Dispatched)?
2. Are items linked to deals?
3. Do order_details have qty values?
4. Are date filters too restrictive?

**Solution:**
```sql
-- Check data availability
SELECT 
    COUNT(DISTINCT o.id) as total_dispatched_orders,
    COUNT(DISTINCT d.id) as deals_with_orders,
    SUM(od.qty) as total_quantity
FROM orders o
JOIN order_details od ON o.id = od.order_id
JOIN items i ON od.item_id = i.id
JOIN deals d ON i.deal_id = d.id
WHERE o.status = 6;
```

### Issue: Incorrect Total Sales
**Check:**
- Verify `order_details.qty` values are correct
- Ensure no duplicate records in joins

### Issue: Authentication Errors
**Check:**
- Ensure proper authentication headers are included
- Verify user has necessary permissions

## Quick Verification Checklist

- [ ] Route is registered: `php artisan route:list | grep top-deals`
- [ ] Service method exists and has DB facade imported
- [ ] Controller method exists with proper validation
- [ ] Test with no parameters (default limit = 5)
- [ ] Test with custom limit
- [ ] Test with date range
- [ ] Test with platform_id
- [ ] Test with invalid parameters (should return 422)
- [ ] Verify response structure matches documentation
- [ ] Check logs for proper logging
- [ ] Verify total_sales calculation is correct
- [ ] Verify sales_count is accurate

## Success Criteria

✅ API returns 200 status for valid requests
✅ Returns array of deals sorted by total_sales DESC
✅ total_sales = SUM of order_details.qty
✅ sales_count = COUNT of distinct orders
✅ Only counts Dispatched orders (status = 6)
✅ Filters work correctly (dates, platform_id, limit)
✅ Validation rejects invalid inputs with 422
✅ Proper error handling with 500 for server errors
✅ Logs success and error events
✅ Response format matches documentation

