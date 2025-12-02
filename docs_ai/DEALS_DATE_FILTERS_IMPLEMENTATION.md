# Deals Date Filters Implementation

## Overview
Added comprehensive date filtering functionality to the Deals Index page, allowing users to filter deals by both start date and end date ranges.

## Implementation Date
December 2, 2025

## Changes Made

### 1. DealsIndex.php (Livewire Component)
**File:** `app/Livewire/DealsIndex.php`

#### Added Properties:
```php
public $startDateFrom = null;
public $startDateTo = null;
public $endDateFrom = null;
public $endDateTo = null;
```

#### Added Lifecycle Methods:
- `updatingStartDateFrom()` - Resets pagination when start date from filter changes
- `updatingStartDateTo()` - Resets pagination when start date to filter changes
- `updatingEndDateFrom()` - Resets pagination when end date from filter changes
- `updatingEndDateTo()` - Resets pagination when end date to filter changes

#### Updated render() Method:
Now passes date filter parameters to the service:
```php
$choosenDeals = $this->dealService->getFilteredDeals(
    User::isSuperAdmin(),
    auth()->user()->id,
    $this->keyword,
    $this->selectedStatuses,
    $this->selectedTypes,
    $this->selectedPlatforms,
    $this->startDateFrom,
    $this->startDateTo,
    $this->endDateFrom,
    $this->endDateTo,
    $this->perPage
);
```

### 2. DealService.php
**File:** `app/Services/Deals/DealService.php`

#### Updated Method Signature:
```php
public function getFilteredDeals(
    bool    $isSuperAdmin,
    ?int    $userId = null,
    ?string $keyword = null,
    array   $selectedStatuses = [],
    array   $selectedTypes = [],
    array   $selectedPlatforms = [],
    ?string $startDateFrom = null,
    ?string $startDateTo = null,
    ?string $endDateFrom = null,
    ?string $endDateTo = null,
    ?int    $perPage = null
)
```

#### Added Filter Logic:
```php
// Apply start date filters
if ($startDateFrom) {
    $query->where('start_date', '>=', $startDateFrom);
}

if ($startDateTo) {
    $query->where('start_date', '<=', $startDateTo);
}

// Apply end date filters
if ($endDateFrom) {
    $query->where('end_date', '>=', $endDateFrom);
}

if ($endDateTo) {
    $query->where('end_date', '<=', $endDateTo);
}
```

### 3. deals-index.blade.php (View)
**File:** `resources/views/livewire/deals-index.blade.php`

#### Added Date Filter Inputs:
Four new datetime-local input fields in a new row within the filters section:

1. **Start Date From** - Filter deals that start on or after this date/time
2. **Start Date To** - Filter deals that start on or before this date/time
3. **End Date From** - Filter deals that end on or after this date/time
4. **End Date To** - Filter deals that end on or before this date/time

Each input:
- Uses `type="datetime-local"` for full date and time selection
- Has Livewire wire:model binding for real-time filtering
- Styled consistently with existing filters
- Includes icons and proper labels

## Features

### Date Range Filtering
- **Start Date Range:** Filter deals by when they start
  - `Start Date From`: Show deals starting on or after this date/time
  - `Start Date To`: Show deals starting on or before this date/time
  
- **End Date Range:** Filter deals by when they end
  - `End Date From`: Show deals ending on or after this date/time
  - `End Date To`: Show deals ending on or before this date/time

### Behavior
- All date filters are optional
- Filters can be used independently or in combination
- Real-time filtering with Livewire
- Pagination resets when any date filter changes
- Compatible with existing filters (status, type, platform, keyword)

## Usage Examples

### Example 1: Find Ongoing Deals
- Set `Start Date From` to a past date
- Set `End Date To` to a future date
- This shows deals that have started and haven't ended yet

### Example 2: Find Deals Starting Soon
- Set `Start Date From` to today's date
- Set `Start Date To` to a week from now
- This shows deals starting within the next week

### Example 3: Find Expired Deals
- Set `End Date To` to today's date
- This shows all deals that have already ended

### Example 4: Find Deals in a Specific Period
- Set `Start Date From` and `Start Date To` to define a start range
- Set `End Date From` and `End Date To` to define an end range
- This shows deals that both start and end within specific timeframes

## Technical Notes

### Date Format
- Input fields use `datetime-local` type (HTML5)
- Format expected by inputs: `YYYY-MM-DDTHH:mm` (e.g., `2025-12-02T14:30`)
- Database queries use standard SQL comparison operators

### Database Queries
The filters generate SQL WHERE clauses:
```sql
WHERE start_date >= '2025-12-01 00:00:00'
  AND start_date <= '2025-12-31 23:59:59'
  AND end_date >= '2025-12-01 00:00:00'
  AND end_date <= '2025-12-31 23:59:59'
```

### Performance
- Indexes on `start_date` and `end_date` columns recommended for optimal performance
- Queries are optimized to only apply filters when values are provided

## Integration with Existing Features

### Compatible with:
- ✅ Platform filtering
- ✅ Status filtering
- ✅ Type filtering
- ✅ Keyword search
- ✅ Pagination
- ✅ Permission-based filtering (Super Admin vs Partner)

### Livewire Integration:
- Uses `wire:model` for two-way data binding
- Automatic pagination reset on filter change
- No page refresh required

## Future Enhancements

Potential improvements for future iterations:
1. Add preset date ranges (Today, This Week, This Month, etc.)
2. Add a "Clear Filters" button to reset all date filters
3. Add date validation (ensure "From" dates are before "To" dates)
4. Add visual feedback for active filters
5. Save filter preferences per user
6. Export filtered results

## Testing Recommendations

### Test Cases:
1. Filter by start date from only
2. Filter by start date to only
3. Filter by end date from only
4. Filter by end date to only
5. Filter by both start date from and to
6. Filter by both end date from and to
7. Combine all date filters
8. Combine date filters with other filters (status, type, platform)
9. Test pagination with date filters applied
10. Test as Super Admin and Partner user

## Files Modified

1. `app/Livewire/DealsIndex.php` - Added date filter properties and methods
2. `app/Services/Deals/DealService.php` - Updated service to handle date filtering
3. `resources/views/livewire/deals-index.blade.php` - Added date filter UI components

## Summary

Successfully implemented comprehensive date filtering for the Deals Index page. Users can now filter deals by start and end date ranges using intuitive datetime inputs. The implementation follows Laravel and Livewire best practices and integrates seamlessly with existing filtering functionality.

