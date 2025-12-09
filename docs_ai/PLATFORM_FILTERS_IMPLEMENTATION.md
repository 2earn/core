# Platform Index Filters Implementation

## Overview
Added comprehensive filtering functionality to the Platform Index page, allowing users to filter platforms by Business Sector, Type, and Enabled status.

## Changes Made

### 1. PlatformIndex Livewire Component (`app/Livewire/PlatformIndex.php`)

#### Added Properties
```php
public $selectedBusinessSectors = [];
public $selectedTypes = [];
public $selectedEnabled = [];
```

#### Added Imports
```php
use App\Models\BusinessSector;
use Core\Enum\PlatformType;
```

#### Updated Query String
```php
protected $queryString = ['search', 'selectedBusinessSectors', 'selectedTypes', 'selectedEnabled'];
```

#### Added Filter Update Methods
```php
public function updatingSelectedBusinessSectors()
{
    $this->resetPage();
}

public function updatingSelectedTypes()
{
    $this->resetPage();
}

public function updatingSelectedEnabled()
{
    $this->resetPage();
}
```

#### Updated Render Method
- Pass filter parameters to `getPaginatedPlatforms()`
- Fetch all business sectors and platform types
- Pass data to the view

### 2. PlatformService (`app/Services/Platform/PlatformService.php`)

#### Updated `getPaginatedPlatforms()` Method Signature
```php
public function getPaginatedPlatforms(
    ?string $search = null, 
    int $perPage = 10,
    array $businessSectors = [],
    array $types = [],
    array $enabled = []
)
```

#### Added Filter Logic
- **Business Sector Filter**: `whereIn('business_sector_id', $businessSectors)`
- **Type Filter**: `whereIn('type', $types)`
- **Enabled Filter**: `where('enabled', $enabled[0])` (only when one value is selected)

### 3. Platform Index View (`resources/views/livewire/platform-index.blade.php`)

#### Added Filters Section
Created a new filters card with three filter groups:

1. **Business Sector Filter**
   - Displays all business sectors as checkboxes
   - Multiple selection allowed
   - Icon: `fa-building`
   - Wire model: `wire:model.live="selectedBusinessSectors"`

2. **Type Filter**
   - Displays all platform types (Full, Hybrid, Paiement)
   - Multiple selection allowed
   - Icon: `fa-tag`
   - Wire model: `wire:model.live="selectedTypes"`

3. **Status Filter (Enabled/Disabled)**
   - Two checkboxes: Enabled and Disabled
   - Icon: `fa-power-off`
   - Wire model: `wire:model.live="selectedEnabled"`

#### UI Design
- Filters section placed between flash messages and search bar
- Clean card layout with header
- Three-column grid layout (col-md-4 each)
- Form switches with cursor pointer for better UX
- Light background with borders for visual grouping

## Features

### Real-time Filtering
- Uses Livewire's `wire:model.live` for instant filtering
- Automatically resets pagination when filters change
- Filters are preserved in URL query string for sharing/bookmarking

### Multiple Selection
- Business Sector: Select multiple sectors simultaneously
- Type: Select multiple platform types
- Status: Select both enabled and disabled, or just one

### Filter Combinations
- All filters work together (AND logic)
- Can combine search with filters for precise results
- Empty filters show all platforms

## Usage

1. **Filter by Business Sector**: Check one or more business sector checkboxes
2. **Filter by Type**: Check one or more platform type checkboxes (Full, Hybrid, Paiement)
3. **Filter by Status**: Check Enabled, Disabled, or both
4. **Combine Filters**: Use multiple filters together for refined results
5. **Search with Filters**: Use the search bar alongside filters for precise matching

## Technical Notes

- Filters use Livewire reactive properties
- Query string preserves filter state in URL
- Pagination automatically resets when filters change
- Service layer handles all query logic
- Empty filter arrays are ignored (no filtering applied)
- Enabled filter only applies when exactly one status is selected

## Date
December 9, 2025

