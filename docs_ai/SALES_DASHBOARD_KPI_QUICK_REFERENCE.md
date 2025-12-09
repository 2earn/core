# Sales Dashboard KPI - Quick Reference

## Endpoint
```
GET /api/v1/dashboard/sales/kpis
```

## Authentication
- **Required**: Yes (Bearer Token)

## Query Parameters

| Parameter | Type | Required | Example |
|-----------|------|----------|---------|
| start_date | date | No | 2024-01-01 |
| end_date | date | No | 2024-12-31 |
| platform_id | integer | No | 5 |

## Response Data

```json
{
    "status": true,
    "message": "KPIs retrieved successfully",
    "data": {
        "total_sales": 150,           // All orders
        "orders_in_progress": 25,     // Status = Ready (2)
        "orders_successful": 100,     // Status = Dispatched (6)
        "orders_failed": 10,          // Status = Failed (5)
        "total_customers": 75         // Unique user_id count
    }
}
```

## Status Mapping

| KPI Field | Order Status | Enum Value |
|-----------|--------------|------------|
| total_sales | All statuses | - |
| orders_in_progress | Ready | 2 |
| orders_successful | Dispatched | 6 |
| orders_failed | Failed | 5 |
| total_customers | Distinct user_id | - |

## cURL Examples

### All KPIs (No filters)
```bash
curl -X GET "http://yourdomain.com/api/v1/dashboard/sales/kpis" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

### With Date Range
```bash
curl -X GET "http://yourdomain.com/api/v1/dashboard/sales/kpis?start_date=2024-01-01&end_date=2024-12-31" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

### With Platform Filter
```bash
curl -X GET "http://yourdomain.com/api/v1/dashboard/sales/kpis?platform_id=5" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

### All Filters Combined
```bash
curl -X GET "http://yourdomain.com/api/v1/dashboard/sales/kpis?start_date=2024-01-01&end_date=2024-12-31&platform_id=5" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

## JavaScript/Axios Example

```javascript
// Get KPIs with filters
const getKPIs = async (filters = {}) => {
    try {
        const response = await axios.get('/api/v1/dashboard/sales/kpis', {
            params: {
                start_date: filters.startDate,
                end_date: filters.endDate,
                platform_id: filters.platformId
            },
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        return response.data.data;
    } catch (error) {
        console.error('Error fetching KPIs:', error);
        throw error;
    }
};

// Usage
const kpis = await getKPIs({
    startDate: '2024-01-01',
    endDate: '2024-12-31',
    platformId: 5
});

console.log(`Total Sales: ${kpis.total_sales}`);
console.log(`Orders In Progress: ${kpis.orders_in_progress}`);
console.log(`Successful Orders: ${kpis.orders_successful}`);
console.log(`Failed Orders: ${kpis.orders_failed}`);
console.log(`Total Customers: ${kpis.total_customers}`);
```

## PHP/Laravel Example

```php
use Illuminate\Support\Facades\Http;

$response = Http::withToken($token)
    ->get('http://yourdomain.com/api/v1/dashboard/sales/kpis', [
        'start_date' => '2024-01-01',
        'end_date' => '2024-12-31',
        'platform_id' => 5
    ]);

if ($response->successful()) {
    $kpis = $response->json('data');
    
    echo "Total Sales: " . $kpis['total_sales'];
    echo "Orders In Progress: " . $kpis['orders_in_progress'];
    echo "Successful Orders: " . $kpis['orders_successful'];
    echo "Failed Orders: " . $kpis['orders_failed'];
    echo "Total Customers: " . $kpis['total_customers'];
}
```

## Files Created

1. **Service**: `app/Services/Dashboard/SalesDashboardService.php`
2. **Controller**: `app/Http/Controllers/Api/Admin/SalesDashboardController.php`
3. **Route**: `routes/api.php` (line ~75)

## Implementation Details

- **Service Method**: `SalesDashboardService::getKpiData()`
- **Controller Method**: `SalesDashboardController::getKpis()`
- **Route Name**: `api_sales_dashboard_kpis`
- **Middleware**: `auth:sanctum`

## Notes

- All filters are optional
- `end_date` must be >= `start_date`
- `platform_id` must exist in the platforms table
- Total customers count is based on distinct `user_id` in filtered orders
- Platform filtering works through: Order → OrderDetail → Item → Platform relationship

