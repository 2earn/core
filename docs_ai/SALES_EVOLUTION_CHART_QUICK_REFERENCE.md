# Sales Evolution Chart - Quick Reference

## API Endpoint

```
GET /api/partner/sales/dashboard/evolution-chart
```

## Parameters

```php
[
    'start_date' => '2024-11-01',    // Optional
    'end_date' => '2024-12-09',      // Optional
    'platform_id' => 123,            // Optional
    'user_id' => 456,                // Required
    'view_mode' => 'daily'           // Optional: daily, weekly, monthly
]
```

## Response

```json
{
    "status": true,
    "message": "Sales evolution chart retrieved successfully",
    "data": {
        "chart_data": [
            {"date": "2024-12-01", "revenue": 1250.50},
            {"date": "2024-12-02", "revenue": 980.25}
        ],
        "view_mode": "daily",
        "start_date": "2024-12-01",
        "end_date": "2024-12-09",
        "total_revenue": 15430.75
    }
}
```

## Service Method

```php
use App\Services\Dashboard\SalesDashboardService;

$service = app(SalesDashboardService::class);

$result = $service->getSalesEvolutionChart([
    'platform_id' => 123,
    'start_date' => '2024-11-01',
    'end_date' => '2024-12-09',
    'view_mode' => 'monthly'
]);
```

## View Modes

| Mode | Format | Example | Best For |
|------|--------|---------|----------|
| daily | `Y-m-d` | `2024-12-09` | 1-30 days |
| weekly | `Week YYYY-WW` | `Week 2024-49` | 1-6 months |
| monthly | `Y-m` | `2024-12` | 6+ months |

## Revenue Calculation

```sql
SUM(order_details.amount_after_deal_discount)
WHERE orders.payment_result = true
```

## Livewire Dashboard

**URL:** `/{locale}/platform/{platformId}/sales-dashboard`

**Features:**
- Date range selector
- View mode dropdown
- Interactive chart with Chart.js
- Auto-refresh on filter change

## Chart.js CDN

```html
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
```

## JavaScript Event

```javascript
// Listen for chart updates
Livewire.on('chartDataUpdated', (chartData) => {
    console.log('New chart data:', chartData);
    // Re-render chart
});
```

## Translation Keys

```php
__('View Mode')
__('Daily')
__('Weekly')
__('Monthly')
__('Sales Evolution')
__('Revenue')
__('No Chart Data Available')
```

## Common Errors

### Weekly Parse Error
**Error:** `Could not parse '2025-49'`  
**Fixed:** Weekly dates use special format `Week YYYY-WW`, no parsing needed

### No Data
**Check:**
- Orders exist in date range
- `payment_result = true` on orders
- Platform filter is correct

### Chart Not Rendering
**Check:**
- Chart.js CDN loaded
- Canvas element exists
- `chartData` array not empty
- No JavaScript console errors

## File Locations

```
app/Services/Dashboard/SalesDashboardService.php
app/Http/Controllers/Api/partner/SalesDashboardController.php
app/Livewire/PlatformSalesDashboard.php
resources/views/livewire/platform-sales-dashboard.blade.php
routes/api.php (line ~143)
```

## Related Documentation

- `SALES_EVOLUTION_CHART_IMPLEMENTATION.md` - Full implementation guide
- `PLATFORM_SALES_DASHBOARD_IMPLEMENTATION.md` - Dashboard docs
- `SALES_DASHBOARD_KPI_IMPLEMENTATION.md` - KPI metrics

---

**Version:** 1.0  
**Last Updated:** December 9, 2025
