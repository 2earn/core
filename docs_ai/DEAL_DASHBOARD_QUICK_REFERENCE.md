# Deal Dashboard - Quick Reference

## Quick Start

### Access the Dashboard
```blade
{{-- In any blade file --}}
<a href="{{ route('deals_dashboard', ['locale' => app()->getLocale()]) }}">
    Dashboard
</a>

{{-- With specific deal --}}
<a href="{{ route('deals_dashboard', ['locale' => app()->getLocale(), 'dealId' => $dealId]) }}">
    View Deal Dashboard
</a>
```

## Files Overview

```
app/Livewire/DealDashboard.php                          # Main component
resources/views/livewire/deal-dashboard.blade.php       # View template
resources/js/services/dealService.js                     # API service (optional)
routes/web.php                                          # Route definition
docs_ai/DEAL_DASHBOARD_COMPONENT.md                     # Full documentation
```

## Key Features

✅ Deal selection with platform filtering
✅ Date range filtering (start/end date)
✅ Multiple view modes (daily/weekly/monthly)
✅ 4 key performance metrics cards
✅ Interactive Chart.js revenue chart
✅ Real-time Livewire updates
✅ Permission-based access control
✅ Responsive design

## Component Usage

### In Routes
```php
// Route already added in routes/web.php
Route::get('/deals/dashboard/{dealId?}', \App\Livewire\DealDashboard::class)->name('deals_dashboard');
```

### In Navigation Menu
```blade
<li>
    <a href="{{ route('deals_dashboard', ['locale' => app()->getLocale()]) }}">
        <i class="ri-dashboard-line"></i>
        <span>{{ __('Deal Dashboard') }}</span>
    </a>
</li>
```

### Direct Link from Deal Show Page
```blade
{{-- Add to resources/views/livewire/deals-show.blade.php --}}
<a href="{{ route('deals_dashboard', ['locale' => app()->getLocale(), 'dealId' => $deal->id]) }}" 
   class="btn btn-primary">
    <i class="ri-dashboard-line me-1"></i>{{ __('Performance Dashboard') }}
</a>
```

## Service Method Used

```php
// From DealService
public function getDealPerformanceChart(
    int $userId,
    int $dealId,
    ?string $startDate = null,
    ?string $endDate = null,
    string $viewMode = 'daily'
): array
```

## API Response Structure

```php
[
    'deal_id' => int,
    'target_amount' => float,
    'current_revenue' => float,
    'expected_progress' => float,    // Percentage
    'actual_progress' => float,      // Percentage
    'chart_data' => [
        ['date' => 'Y-m-d', 'revenue' => float],
        // ... more data points
    ]
]
```

## Component Properties

### Key Public Properties
```php
$userId               // Current user ID
$dealId              // Selected deal ID
$startDate           // Filter: start date (Y-m-d)
$endDate             // Filter: end date (Y-m-d)
$viewMode            // 'daily', 'weekly', 'monthly'
$targetAmount        // Deal target
$currentRevenue      // Actual revenue
$expectedProgress    // Expected % based on time
$actualProgress      // Actual % based on revenue
$chartData           // Array for Chart.js
```

## Important Methods

```php
// Refresh dashboard data
$this->loadPerformanceData()

// Reset all filters
$this->resetFilters()

// Manually refresh
$this->refreshData()
```

## Livewire Events

```php
// Dispatched from component
$this->dispatch('chartDataUpdated', [
    'chartData' => $this->chartData,
    'viewMode' => $this->viewMode
]);

// Listener for external refresh
public $listeners = ['refreshDashboard' => 'loadPerformanceData'];
```

## JavaScript Functions

```javascript
// Initialize chart
initChart()

// Update chart data
updateChart(chartData)
```

## Common Customizations

### Add to Sidebar Menu
```blade
{{-- In sidebar navigation --}}
<li class="nav-item">
    <a class="nav-link" href="{{ route('deals_dashboard', ['locale' => app()->getLocale()]) }}">
        <i class="ri-dashboard-line"></i>
        <span>{{ __('Deal Performance') }}</span>
    </a>
</li>
```

### Set Default Deal in Mount
```php
// In DealDashboard.php mount() method
if ($dealId) {
    $this->dealId = $dealId;
} else {
    // Custom logic for default deal
    $this->dealId = Deal::where('status', 2)->latest()->first()?->id;
}
```

### Change Default Date Range
```php
// In mount() method
$this->startDate = Carbon::now()->subMonths(3)->format('Y-m-d'); // Last 3 months
$this->endDate = Carbon::now()->format('Y-m-d');
```

### Add Export Button
```blade
<button wire:click="exportData" class="btn btn-success">
    <i class="ri-download-line me-1"></i>{{ __('Export Data') }}
</button>
```

Then add method:
```php
public function exportData()
{
    // Export logic here
    return Excel::download(new DealPerformanceExport($this->chartData), 'deal-performance.xlsx');
}
```

## Permissions

- **Super Admin**: Access all deals
- **Platform Manager**: Access only managed platform deals
- Checked in `loadAvailableDeals()` method

## Testing

### Manual Test Steps
1. Navigate to `/en/deals/dashboard`
2. Select a deal from dropdown
3. Change date range
4. Switch view modes (daily/weekly/monthly)
5. Verify chart updates
6. Check all 4 metric cards display correctly
7. Test refresh button
8. Test reset filters

### URLs to Test
```
http://localhost/en/deals/dashboard
http://localhost/en/deals/dashboard/15
http://localhost/ar/deals/dashboard?dealId=15&viewMode=weekly
```

## Troubleshooting

| Issue | Solution |
|-------|----------|
| Chart not showing | Check Chart.js is loaded, verify console for errors |
| No deals in dropdown | Check user permissions and deal status |
| Dates not filtering | Verify date format is Y-m-d |
| Livewire errors | Clear cache: `php artisan livewire:discover` |
| Styles broken | Rebuild assets: `npm run build` |

## Console Commands

```bash
# Clear all caches
php artisan optimize:clear

# Discover Livewire components
php artisan livewire:discover

# Rebuild frontend assets
npm run build

# Run in dev mode with hot reload
npm run dev
```

## Browser Console Debug

```javascript
// Check if Livewire is loaded
window.Livewire

// Check if Chart.js is loaded
window.Chart

// Manually trigger chart update (in browser console)
updateChart([
    {date: '2024-01-01', revenue: 1000},
    {date: '2024-01-02', revenue: 1500}
])
```

## Key Dependencies

- Laravel 10+
- Livewire 3+
- Chart.js 3.7.0
- Bootstrap 5
- Remix Icons

## Next Steps

1. Add the dashboard link to your sidebar/navigation
2. Test with different user roles
3. Customize colors/styling to match your theme
4. Add export functionality if needed
5. Consider adding more metrics

## Support

For full documentation, see: `docs_ai/DEAL_DASHBOARD_COMPONENT.md`

---

**Last Updated**: December 9, 2025

