# Platform Sales Dashboard - Implementation Guide
**Status:** Complete and Ready for Testing
**Version:** 1.0
**Implementation Date:** December 9, 2025

---

- **Error Logs:** Check Laravel logs for detailed error traces
- **Log Prefix:** `[PlatformSalesDashboard]` and `[PlatformSalesWidget]`
- **Service Used:** `App\Services\Dashboard\SalesDashboardService`

## Support & Maintenance

**Solution:** Check user authentication and platform access rights
### Issue: Permission denied errors

**Solution:** Ensure platform ID is passed correctly and platform exists
### Issue: Widget not displaying

**Solution:** Verify date format is 'Y-m-d' and dates are valid
### Issue: Date filter not working

**Solution:** Check if orders have order_details with items linked to the platform
### Issue: KPIs showing zero despite having orders

## Troubleshooting

   - Filter by product category
   - Filter by customer segment
   - Filter by order status
5. **Advanced Filters:**

   - Live notifications
   - Auto-refresh option
   - WebSocket integration
4. **Real-time Updates:**

   - Benchmark against averages
   - Period-over-period comparison
   - Compare multiple platforms
3. **Comparative Analytics:**

   - Email reports
   - PDF reports
   - Export to CSV/Excel
2. **Export Functionality:**

   - Customer retention rate
   - Order trends (charts)
   - Revenue totals
   - Average order value
1. **Additional Metrics:**

## Future Enhancements

```
</div>
    @livewire('platform-sales-widget', ['platformId' => $platform->id, 'showFilters' => true])
<div class="col-md-6">
```blade
### Scenario 3: Embed Widget in Platform Show Page

4. View filtered results
3. Set end date to today
2. Set start date to beginning of month
1. Open platform sales dashboard
### Scenario 2: Filter by Date Range

4. View full dashboard with all KPIs
3. Click "View Sales" button
2. Find enabled platform
1. Navigate to platform index
### Scenario 1: View Platform Sales from Index

## Example Usage Scenarios

5. `docs_ai/PLATFORM_SALES_DASHBOARD_IMPLEMENTATION.md` (this file)
4. `resources/views/livewire/platform-sales-widget.blade.php`
3. `resources/views/livewire/platform-sales-dashboard.blade.php`
2. `app/Livewire/PlatformSalesWidget.php`
1. `app/Livewire/PlatformSalesDashboard.php`

## Files Created

2. `resources/views/livewire/platform-index.blade.php` - Added "View Sales" button
1. `routes/web.php` - Added sales dashboard route

## Files Modified

- [ ] Back button navigates to platform index
- [ ] Zero values handled gracefully
- [ ] Success rate calculates correctly
- [ ] Loading states display properly
- [ ] Error handling works with invalid dates
- [ ] "View Sales" link appears on platform index
- [ ] Widget displays correctly when embedded
- [ ] Reset filters button works
- [ ] Date filters update KPIs correctly
- [ ] Dashboard loads with valid platform ID

## Testing Checklist

```
{{__('Success Rate')}}
{{__('View Sales')}}
{{__('Total Sales')}}
```blade
All text is wrapped in `__()` helper for translation support:

## Localization

- Using `PlatformService::userHasRoleInPlatform()`
- User roles (marketing manager, financial manager, owner)
- Platform ownership
**Future Enhancement:** Consider adding checks for:

- Platform must exist in database
- Uses existing authentication middleware from route group
- Requires a valid platform ID
Currently, no additional access control is implemented. The component:

## Permissions & Access Control

- Color-coded badges (success, warning, danger, info)
- Custom card animations
- Remixicon icons (ri-*)
- Bootstrap 5 classes
Uses existing CSS classes from the project:

## Styling

- Link to full dashboard
- Summary section at bottom
- 2x2 grid for main metrics
- Compact card design
### Widget Layout

5. **Navigation:** Back button to platform index
4. **Customer Stats:** Additional customer-related metrics
3. **KPI Cards:** 4 main metric cards with icons
2. **Filter Section:** Date range inputs with reset button
1. **Header Section:** Platform info with logo, name, and business sector
### Dashboard Page Layout

## UI Components

- Spinners for visual feedback
- Opacity change on filter updates
- Loading overlay during data fetch
### Loading States

- User-friendly error messages via flash messages
- Error logging for debugging
- Graceful fallback to zero values on service errors
### Error Handling

- **Success Rate:** `(orders_successful / total_sales) * 100`
### Calculated Metrics

- Real-time updates with Livewire
- Start date cannot be after end date
- Max date: Today
- Default: Last 30 days
### Date Range Filtering

## Key Features

```
]
    'total_customers' => int,       // Unique customer count
    'orders_failed' => int,         // Orders with "Failed" status
    'orders_successful' => int,     // Orders with "Dispatched" status
    'orders_in_progress' => int,    // Orders with "Ready" status
    'total_sales' => int,           // Total number of orders
[
```php
**Returned Data Structure:**

```
$kpis = $this->dashboardService->getKpiData($filters);

];
    'end_date' => $endDate,
    'start_date' => $startDate,
    'platform_id' => $platformId,
$filters = [
```php

Both components utilize the existing `SalesDashboardService::getKpiData()` method:

## Service Integration

```
</a>
    <i class="ri-bar-chart-line align-middle me-1"></i>{{__('View Sales')}}
   class="btn btn-soft-success btn-sm">
<a href="{{route('platform_sales_dashboard', ['locale' => app()->getLocale(), 'platformId' => $platform->id])}}"
```blade

The platform index page now includes a "View Sales" button for each enabled platform:

## Platform Index Integration

**Example URL:** `/en/platform/123/sales-dashboard`

**Full Route Name:** `platform_sales_dashboard`

```
    ->name('sales_dashboard');
Route::get('/{platformId}/sales-dashboard', \App\Livewire\PlatformSalesDashboard::class)
```php
**Added Route:**

## Route Configuration

**View:** `resources/views/livewire/platform-sales-widget.blade.php`

```
])
    'endDate' => '2024-12-31'
    'startDate' => '2024-01-01',
    'showFilters' => true,
    'platformId' => $platform->id,
@livewire('platform-sales-widget', [
<!-- With filters enabled -->

@livewire('platform-sales-widget', ['platformId' => $platform->id])
```blade
**Usage in Blade:**

- All core KPIs in a small footprint
- Quick link to full dashboard
- Optional date filters
- Compact card layout
**Features:**

A compact, reusable widget that can be embedded in other views to show quick sales overview.

**Location:** `app/Livewire/PlatformSalesWidget.php`
### 2. **PlatformSalesWidget** (Compact Widget)

**View:** `resources/views/livewire/platform-sales-dashboard.blade.php`

```
route('platform_sales_dashboard', ['locale' => app()->getLocale(), 'platformId' => $platformId])
// Access via route
```php
**Usage:**

- Loading states and error handling
- Success rate calculation
  - Total Unique Customers
  - Failed Orders
  - Successful Orders
  - Orders In Progress
  - Total Sales
- Displays 5 key metrics:
- Real-time data updates when filters change
- Date range filtering (start date and end date)
**Features:**

A complete Livewire component that displays comprehensive sales analytics for a specific platform.

**Location:** `app/Livewire/PlatformSalesDashboard.php`
### 1. **PlatformSalesDashboard** (Full Dashboard Page)

## Created Components

This implementation adds sales analytics capabilities to the platform management system, allowing users to view detailed sales KPIs for each platform.
## Overview


