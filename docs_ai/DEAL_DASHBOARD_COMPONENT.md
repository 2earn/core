# Deal Dashboard Component - Complete Guide

## Overview
A comprehensive Livewire component that provides a visual dashboard for monitoring deal performance using the `getDealPerformanceChart` method from the DealService.

## Files Created

### 1. Component Class
- **Location**: `app/Livewire/DealDashboard.php`
- **Purpose**: Main Livewire component with business logic

### 2. Blade View
- **Location**: `resources/views/livewire/deal-dashboard.blade.php`
- **Purpose**: User interface with Chart.js integration

### 3. JavaScript Service (Optional)
- **Location**: `resources/js/services/dealService.js`
- **Purpose**: API service for future JavaScript-based interactions

## Features

### Dashboard Capabilities
1. **Deal Selection**
   - Dropdown to select from available deals
   - Filtered by user permissions (Super Admin sees all, Partners see managed deals)
   - Platform filtering support

2. **Date Range Filtering**
   - Start date and end date inputs
   - Validation to ensure logical date ranges
   - Default: Last 30 days

3. **View Mode Options**
   - Daily view
   - Weekly view
   - Monthly view

4. **Performance Metrics Display**
   - **Target Amount**: Deal's target turnover
   - **Current Revenue**: Actual revenue generated
   - **Expected Progress**: Time-based expected completion percentage
   - **Actual Progress**: Revenue-based actual completion percentage

5. **Visual Chart**
   - Line chart showing revenue over time
   - Responsive design
   - Interactive tooltips
   - Currency formatting

6. **Real-time Updates**
   - Livewire-powered reactive updates
   - Refresh button for manual data reload
   - Reset filters functionality

## Route

### Web Route Added
```php
Route::get('/deals/dashboard/{dealId?}', \App\Livewire\DealDashboard::class)->name('deals_dashboard');
```

**Access URL**: 
- `/{locale}/deals/dashboard` - Auto-select first available deal
- `/{locale}/deals/dashboard/123` - Load specific deal by ID

## Usage

### Basic Access
```blade
<a href="{{ route('deals_dashboard', ['locale' => app()->getLocale()]) }}">
    View Deal Dashboard
</a>
```

### With Specific Deal
```blade
<a href="{{ route('deals_dashboard', ['locale' => app()->getLocale(), 'dealId' => $deal->id]) }}">
    View Dashboard for {{ $deal->name }}
</a>
```

### From DealsShow Component
Add a button to navigate to the dashboard:
```blade
<a href="{{ route('deals_dashboard', ['locale' => app()->getLocale(), 'dealId' => $deal->id]) }}" 
   class="btn btn-primary">
    <i class="ri-dashboard-line me-1"></i>{{ __('View Dashboard') }}
</a>
```

## Component Properties

### Public Properties
- `$userId` - Current authenticated user ID
- `$dealId` - Selected deal ID
- `$deal` - Deal model instance
- `$startDate` - Filter start date (Y-m-d)
- `$endDate` - Filter end date (Y-m-d)
- `$viewMode` - Chart view mode (daily/weekly/monthly)
- `$targetAmount` - Deal target turnover
- `$currentRevenue` - Actual revenue
- `$expectedProgress` - Expected progress percentage
- `$actualProgress` - Actual progress percentage
- `$chartData` - Revenue data for chart
- `$loading` - Loading state
- `$error` - Error message
- `$availableDeals` - Collection of accessible deals
- `$availablePlatforms` - Collection of platforms
- `$selectedPlatformId` - Selected platform filter

## Methods

### Public Methods
- `mount($dealId = null)` - Initialize component
- `loadAvailableDeals()` - Load deals based on user permissions
- `loadPerformanceData()` - Fetch performance data from DealService
- `refreshData()` - Manually refresh dashboard data
- `resetFilters()` - Reset all filters to defaults

### Lifecycle Hooks
- `updatedDealId()` - Triggered when deal selection changes
- `updatedViewMode()` - Triggered when view mode changes
- `updatedStartDate()` - Triggered when start date changes
- `updatedEndDate()` - Triggered when end date changes
- `updatedSelectedPlatformId()` - Triggered when platform filter changes

## Integration with DealService

### Method Called
```php
$performanceData = $this->dealService->getDealPerformanceChart(
    $this->userId,
    $this->dealId,
    $this->startDate,
    $this->endDate,
    $this->viewMode
);
```

### Expected Response Structure
```php
[
    'deal_id' => 123,
    'target_amount' => 100000.00,
    'current_revenue' => 45000.00,
    'expected_progress' => 50.00,
    'actual_progress' => 45.00,
    'chart_data' => [
        ['date' => '2024-01-01', 'revenue' => 1500.00],
        ['date' => '2024-01-02', 'revenue' => 2300.00],
        // ... more data points
    ]
]
```

## Chart.js Integration

### Chart Configuration
- **Type**: Line chart
- **Data Points**: Date (x-axis) vs Revenue (y-axis)
- **Features**: 
  - Responsive
  - Interactive tooltips with currency formatting
  - Smooth curves (tension: 0.4)
  - Filled area under line
  - Y-axis starts at zero

### Event System
The component uses Livewire events to update the chart:
```javascript
Livewire.on('chartDataUpdated', (event) => {
    updateChart(event.chartData);
});
```

## Permissions

### Access Control
- **Super Admin**: Can view all deals
- **Platform Managers**: Can only view deals from platforms they manage
- Enforced at query level in `loadAvailableDeals()`

## Styling

### UI Framework
- Bootstrap 5 classes
- Custom card components
- Responsive grid system
- Remix Icons for iconography

### Color Scheme
- Primary: Deal selection and branding
- Success: Revenue metrics (when performing well)
- Warning: Revenue metrics (when underperforming)
- Info: Expected progress
- Light/Muted: Background elements

## Error Handling

### Validation
- Date range validation (start < end)
- Deal existence validation
- Permission validation

### Error Display
- User-friendly error messages
- Alert boxes with clear descriptions
- Logged to application log for debugging

## Query String Support

The component maintains state in URL parameters:
- `dealId` - Selected deal
- `viewMode` - Chart view mode
- `startDate` - Filter start date
- `endDate` - Filter end date

This allows:
- Bookmarkable dashboards
- Shareable links
- Browser back/forward navigation

## Extensibility

### Future Enhancements
1. **Export Data**: Add PDF/Excel export functionality
2. **Comparison View**: Compare multiple deals side-by-side
3. **Alerts**: Set up automated alerts for performance thresholds
4. **Additional Metrics**: Add more KPIs (commission breakdown, customer acquisition, etc.)
5. **Forecast**: Implement revenue forecasting
6. **Real-time Updates**: Add polling or websocket support

### Adding Custom Metrics
To add new metrics:
1. Extend the `getDealPerformanceChart` method in DealService
2. Add properties to DealDashboard component
3. Update the blade view with new metric cards
4. Update the chart if needed

## Testing Access

### URL Examples
```
# English, no deal selected
http://localhost/en/deals/dashboard

# English, specific deal
http://localhost/en/deals/dashboard/15

# Arabic, with query parameters
http://localhost/ar/deals/dashboard?dealId=15&viewMode=weekly&startDate=2024-01-01&endDate=2024-12-31
```

## Dependencies

### PHP
- Laravel 10+
- Livewire 3+
- App\Services\Deals\DealService
- App\Services\Platform\PlatformService
- App\Models\Deal
- App\Models\User

### JavaScript
- Chart.js 3.7.0 (from package.json)
- Livewire frontend

### CSS
- Bootstrap 5
- Remix Icons

## Troubleshooting

### Common Issues

1. **Chart not displaying**
   - Ensure Chart.js is loaded
   - Check browser console for JavaScript errors
   - Verify chartData is being passed correctly

2. **No deals available**
   - Check user permissions
   - Verify deals exist in database
   - Check platform assignments for non-admin users

3. **Date filter not working**
   - Verify date format (Y-m-d)
   - Check for date validation errors
   - Ensure dates are within deal's date range

## Quick Reference

### Component Name
`DealDashboard`

### Route Name
`deals_dashboard`

### View Path
`livewire.deal-dashboard`

### Permission Check
User must be authenticated and have access to at least one deal

## Related Documentation
- See `docs_ai/DEAL_DASHBOARD_IMPLEMENTATION.md` for implementation details
- See `app/Services/Deals/DealService.php` for service documentation
- See API documentation for endpoint details

---

**Created**: December 9, 2025
**Version**: 1.0.0
**Author**: AI Assistant

