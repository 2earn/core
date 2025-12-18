# Top Selling Platforms Chart - Quick Reference

## Quick Start

### Endpoint
```
GET /api/partner/sales/dashboard/top-platforms
```

### Parameters
- `start_date` (optional): Filter from date (Y-m-d format)
- `end_date` (optional): Filter to date (Y-m-d format)
- `limit` (optional): Number of results (default: 10, max: 100)

### Response
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
            }
        ]
    }
}
```

## How It Works

**Data Source:** `commission_break_downs.purchase_value`
- Joins with `platforms` table for platform names
- Sums `purchase_value` per platform
- Orders by total sales (descending)

## Usage Examples

### Basic Request
```bash
GET /api/partner/sales/dashboard/top-platforms
```
Returns top 10 platforms by sales

### With Date Filter
```bash
GET /api/partner/sales/dashboard/top-platforms?start_date=2025-01-01&end_date=2025-12-31
```
Returns top 10 platforms for 2025

### Custom Limit
```bash
GET /api/partner/sales/dashboard/top-platforms?limit=5
```
Returns top 5 platforms

### Combined Filters
```bash
GET /api/partner/sales/dashboard/top-platforms?start_date=2025-01-01&end_date=2025-12-31&limit=20
```
Returns top 20 platforms for 2025

## Code Locations

### Service Layer
**File:** `app/Services/Dashboard/SalesDashboardService.php`
**Method:** `getTopSellingPlatforms(array $filters = [])`

### Controller Layer
**File:** `app/Http/Controllers/Api/partner/SalesDashboardController.php`
**Method:** `getTopSellingPlatforms(Request $request)`

### Route
**File:** `routes/api.php`
```php
Route::get('/sales/dashboard/top-platforms', [SalesDashboardController::class, 'getTopSellingPlatforms'])
    ->name('api_sales_dashboard_top_platforms');
```

## Key Points

✅ **Calculates from:** `commission_break_downs.purchase_value`  
✅ **Returns:** `platform_id`, `platform_name`, `total_sales`  
✅ **Ordered by:** Total sales (highest first)  
✅ **Filters:** Date range and limit supported  
✅ **Validation:** All parameters validated  
✅ **Error Handling:** Comprehensive logging and error responses  

## Testing

### Quick Test with cURL
```bash
curl -X GET "http://localhost/api/partner/sales/dashboard/top-platforms" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

### Expected Success Response
- Status Code: 200
- Contains `top_platforms` array
- Each platform has `platform_id`, `platform_name`, `total_sales`

### Error Scenarios
- **422**: Validation failed (invalid date format, limit out of range)
- **500**: Server error (database issues, unexpected errors)

## Integration Tips

### Frontend Integration
```javascript
// Fetch top platforms
async function fetchTopPlatforms(startDate, endDate, limit = 10) {
    const params = new URLSearchParams({
        ...(startDate && { start_date: startDate }),
        ...(endDate && { end_date: endDate }),
        limit: limit
    });
    
    const response = await fetch(`/api/partner/platforms/top-selling?${params}`, {
        headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
        }
    });
    
    return await response.json();
}
```

### Chart.js Example
```javascript
const data = await fetchTopPlatforms('2025-01-01', '2025-12-31', 10);
const chartData = {
    labels: data.data.top_platforms.map(p => p.platform_name),
    datasets: [{
        label: 'Total Sales',
        data: data.data.top_platforms.map(p => p.total_sales),
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1
    }]
};
```

## Related Endpoints

- `/api/partner/sales/dashboard/kpis` - Dashboard KPIs
- `/api/partner/sales/dashboard/evolution-chart` - Sales evolution over time
- `/api/partner/sales/dashboard/top-products` - Top selling products
- `/api/partner/sales/dashboard/top-deals` - Top selling deals

## Documentation

📄 Full documentation: `docs_ai/TOP_SELLING_PLATFORMS_CHART.md`

