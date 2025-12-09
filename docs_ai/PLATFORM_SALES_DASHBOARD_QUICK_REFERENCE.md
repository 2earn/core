# Platform Sales Dashboard - Quick Reference

## Quick Access

### View Sales Dashboard
```
URL: /{locale}/platform/{platformId}/sales-dashboard
Route: platform_sales_dashboard
```

### Add Link to Any View
```blade
<a href="{{route('platform_sales_dashboard', ['locale' => app()->getLocale(), 'platformId' => $platform->id])}}">
    View Sales
</a>
```

### Embed Widget
```blade
@livewire('platform-sales-widget', ['platformId' => $platform->id])
```

## Components

| Component | Purpose | Location |
|-----------|---------|----------|
| `PlatformSalesDashboard` | Full page dashboard | `app/Livewire/PlatformSalesDashboard.php` |
| `PlatformSalesWidget` | Compact widget | `app/Livewire/PlatformSalesWidget.php` |

## KPI Metrics

| Metric | Description | Source |
|--------|-------------|--------|
| Total Sales | All orders count | `OrderEnum::*` |
| Orders In Progress | Orders with "Ready" status | `OrderEnum::Ready` |
| Successful Orders | Orders with "Dispatched" status | `OrderEnum::Dispatched` |
| Failed Orders | Orders with "Failed" status | `OrderEnum::Failed` |
| Total Customers | Unique customer count | `distinct user_id` |
| Success Rate | % of successful orders | Calculated |

## Widget Options

```blade
@livewire('platform-sales-widget', [
    'platformId' => 123,           // Required
    'startDate' => '2024-01-01',   // Optional
    'endDate' => '2024-12-31',     // Optional
    'showFilters' => true          // Optional, default: false
])
```

## Service Method

```php
use App\Services\Dashboard\SalesDashboardService;

$service = app(SalesDashboardService::class);

$kpis = $service->getKpiData([
    'platform_id' => $platformId,
    'start_date' => '2024-01-01',
    'end_date' => '2024-12-31',
]);
```

## Common Filters

```php
$filters = [
    'platform_id' => int,      // Required for platform-specific data
    'start_date' => 'Y-m-d',   // Optional
    'end_date' => 'Y-m-d',     // Optional
    'user_id' => int,          // For user verification (optional)
];
```

## Translation Keys

```php
__('Total Sales')
__('Orders In Progress')
__('Successful Orders')
__('Failed Orders')
__('Total Customers')
__('Success Rate')
__('View Sales')
__('Sales Dashboard')
__('Filters')
__('Start Date')
__('End Date')
__('Reset Filters')
__('Loading...')
__('Sales Overview')
```

## Icon Classes (Remixicon)

```
ri-bar-chart-line      // Sales dashboard icon
ri-shopping-cart-line  // Total sales
ri-time-line          // In progress
ri-checkbox-circle-line // Success
ri-close-circle-line   // Failed
ri-user-line          // Customers
ri-percent-line       // Success rate
ri-filter-3-line      // Filters
ri-refresh-line       // Reset
ri-arrow-left-line    // Back
ri-external-link-line // External link
```

## Troubleshooting

| Issue | Solution |
|-------|----------|
| No data showing | Check if platform has orders with order_details |
| Wrong metrics | Verify order statuses match `OrderEnum` values |
| Widget not loading | Ensure platform ID is valid |
| Date filter broken | Check date format is 'Y-m-d' |

## Files Reference

```
app/Livewire/
├── PlatformSalesDashboard.php
└── PlatformSalesWidget.php

resources/views/livewire/
├── platform-sales-dashboard.blade.php
└── platform-sales-widget.blade.php

routes/web.php (line ~271)

docs_ai/
├── PLATFORM_SALES_DASHBOARD_IMPLEMENTATION.md
└── PLATFORM_SALES_DASHBOARD_QUICK_REFERENCE.md
```

## Related Services

- `SalesDashboardService` - Main data service
- `PlatformService` - Platform management
- `PlatformPartnerController` - API endpoints

---
**Last Updated:** December 9, 2025

