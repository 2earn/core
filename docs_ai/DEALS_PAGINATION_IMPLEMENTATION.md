# Deals Index Pagination Implementation

## Overview
Added pagination support to the Deals Index page with automatic filter-based page reset functionality.

## Date
November 24, 2025

## Changes Summary

### 1. DealService Enhancement
**File:** `app/Services/Deals/DealService.php`

**Method Updated:** `getFilteredDeals()`

**New Parameters:**
```php
public function getFilteredDeals(
    bool $isSuperAdmin,
    ?int $userId = null,
    ?string $keyword = null,
    array $selectedStatuses = [],
    array $selectedTypes = [],
    array $selectedPlatforms = [],
    ?int $perPage = null  // NEW: Optional pagination parameter
)
```

**Return Type:** `Collection|LengthAwarePaginator`
- Returns `Collection` when `$perPage` is `null`
- Returns `LengthAwarePaginator` when `$perPage` is provided

**Implementation:**
```php
// Return paginated or all results
return $perPage ? $query->paginate($perPage) : $query->get();
```

### 2. DealsIndex Component Updates
**File:** `app/Livewire/DealsIndex.php`

#### Added Property:
```php
public $perPage = 10;
```

#### Added Methods to Reset Pagination on Filter Change:
```php
public function updatingKeyword()
{
    $this->resetPage();
}

public function updatingSelectedStatuses()
{
    $this->resetPage();
}

public function updatingSelectedTypes()
{
    $this->resetPage();
}

public function updatingSelectedPlatforms()
{
    $this->resetPage();
}
```

#### Updated filterDeals Method:
```php
public function filterDeals()
{
    $this->choosenDeals = $this->dealService->getFilteredDeals(
        User::isSuperAdmin(),
        auth()->user()->id,
        $this->keyword,
        $this->selectedStatuses,
        $this->selectedTypes,
        $this->selectedPlatforms,
        $this->perPage  // NEW: Pass pagination parameter
    );
}
```

### 3. View Updates
**File:** `resources/views/livewire/deals-index.blade.php`

#### Updated Results Count Header:
```blade
@if($choosenDeals instanceof \Illuminate\Pagination\LengthAwarePaginator && $choosenDeals->total() > 0)
    <span class="badge bg-white text-dark fs-6">
        {{$choosenDeals->total()}} {{__('Deal(s)')}}
    </span>
@elseif($choosenDeals instanceof \Illuminate\Support\Collection && $choosenDeals->count() > 0)
    <span class="badge bg-white text-dark fs-6">
        {{$choosenDeals->count()}} {{__('Deal(s)')}}
    </span>
@endif
```

#### Added Pagination Controls:
```blade
<!-- Pagination -->
@if($choosenDeals instanceof \Illuminate\Pagination\LengthAwarePaginator && $choosenDeals->hasPages())
    <div class="row mt-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    {{__('Showing')}} {{ $choosenDeals->firstItem() ?? 0 }} {{__('to')}} {{ $choosenDeals->lastItem() ?? 0 }} 
                    {{__('of')}} {{ $choosenDeals->total() }} {{__('deals')}}
                </div>
                <div>
                    {{ $choosenDeals->links() }}
                </div>
            </div>
        </div>
    </div>
@endif
```

## Key Features

### 1. **Flexible Pagination**
- Optional parameter allows same method to be used with or without pagination
- Maintains backward compatibility

### 2. **Automatic Page Reset**
- When any filter changes (keyword, status, type, platform), pagination resets to page 1
- Uses Livewire's `updatingPropertyName()` lifecycle hooks

### 3. **Smart Count Display**
- Handles both paginated and non-paginated results
- Shows total count for paginated results
- Shows collection count for non-paginated results

### 4. **User-Friendly Pagination Info**
- Displays "Showing X to Y of Z deals"
- Shows pagination controls only when there are multiple pages
- Bootstrap-styled pagination links

## Usage Examples

### In Component (With Pagination):
```php
$deals = $this->dealService->getFilteredDeals(
    User::isSuperAdmin(),
    auth()->user()->id,
    $this->keyword,
    $this->selectedStatuses,
    $this->selectedTypes,
    $this->selectedPlatforms,
    15  // Items per page
);

// $deals is LengthAwarePaginator
echo $deals->total();       // Total number of items
echo $deals->count();       // Items on current page
echo $deals->currentPage(); // Current page number
```

### In Component (Without Pagination):
```php
$deals = $this->dealService->getFilteredDeals(
    User::isSuperAdmin(),
    auth()->user()->id,
    $this->keyword,
    $this->selectedStatuses,
    $this->selectedTypes,
    $this->selectedPlatforms,
    null  // No pagination
);

// $deals is Collection
echo $deals->count();  // Total number of items
```

### In View:
```blade
@forelse($choosenDeals as $deal)
    <!-- Deal card -->
@empty
    <!-- No deals message -->
@endforelse

<!-- Pagination automatically handled by Livewire -->
@if($choosenDeals instanceof \Illuminate\Pagination\LengthAwarePaginator && $choosenDeals->hasPages())
    {{ $choosenDeals->links() }}
@endif
```

## Configuration

### Changing Items Per Page:
Update the `$perPage` property in `DealsIndex.php`:
```php
public $perPage = 20; // Show 20 items per page
```

### Disabling Pagination:
Set `$perPage` to `null`:
```php
public $perPage = null; // Show all items
```

### Customizing Pagination View:
Laravel allows custom pagination views. To use a custom view:
```blade
{{ $choosenDeals->links('custom.pagination.view') }}
```

## Benefits

### 1. **Performance**
- Reduces database query results
- Faster page load times with large datasets
- Lower memory usage

### 2. **User Experience**
- Easier navigation through large result sets
- Clear indication of current position
- Fast filter updates with automatic page reset

### 3. **Scalability**
- Handles thousands of deals efficiently
- Consistent performance regardless of total deal count

### 4. **Maintainability**
- Single source of truth for pagination logic
- Easy to modify items per page
- Can be toggled on/off without code changes

## Testing Scenarios

1. **Basic Pagination:**
   - [ ] Verify deals are paginated at 10 items per page
   - [ ] Verify pagination controls appear when needed
   - [ ] Verify correct total count is displayed

2. **Filter Changes:**
   - [ ] Change keyword filter → verify page resets to 1
   - [ ] Toggle status filter → verify page resets to 1
   - [ ] Toggle type filter → verify page resets to 1
   - [ ] Toggle platform filter → verify page resets to 1

3. **Page Navigation:**
   - [ ] Click "Next" → verify correct deals on page 2
   - [ ] Click "Previous" → verify back to page 1
   - [ ] Click specific page number → verify correct deals displayed
   - [ ] Verify URL updates with page parameter

4. **Edge Cases:**
   - [ ] Test with 0 deals (no pagination shown)
   - [ ] Test with exactly 10 deals (no pagination shown)
   - [ ] Test with 11 deals (pagination shown)
   - [ ] Test with 100+ deals (multiple pages)

5. **Filter + Pagination:**
   - [ ] Apply filters → navigate to page 2 → verify correct filtered results
   - [ ] Navigate to page 3 → change filter → verify back to page 1

## Performance Metrics

**Before Pagination:**
- Query: Returns ALL deals matching filters
- Memory: Loads all records into memory
- Response time: Increases with total deal count

**After Pagination:**
- Query: Returns only 10 deals per request
- Memory: Only 10 records in memory at a time
- Response time: Consistent regardless of total count

## Future Enhancements

1. **Per Page Selector:**
   - Add dropdown to change items per page dynamically
   - Store preference in user settings

2. **Infinite Scroll:**
   - Alternative to traditional pagination
   - Load more deals as user scrolls down

3. **Jump to Page:**
   - Input field to jump directly to specific page
   - Quick navigation for large datasets

4. **URL State:**
   - Persist current page in URL
   - Allow bookmarking specific pages

5. **Cache Pagination:**
   - Cache filtered results for faster navigation
   - Invalidate on data changes

## Notes

- Livewire automatically handles AJAX pagination without page reloads
- The `WithPagination` trait is already included in the component
- Pagination state is automatically maintained by Livewire
- No JavaScript required for basic pagination functionality

