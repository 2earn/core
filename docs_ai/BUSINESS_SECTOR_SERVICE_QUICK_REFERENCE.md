# Business Sector Service Quick Reference

## Import
```php
use App\Services\BusinessSector\BusinessSectorService;
```

## Inject in Livewire
```php
protected $businessSectorService;

public function boot(BusinessSectorService $businessSectorService)
{
    $this->businessSectorService = $businessSectorService;
}
```

## Common Methods

### Get All Business Sectors
```php
// All business sectors
$sectors = $this->businessSectorService->getBusinessSectors();

// With filters
$sectors = $this->businessSectorService->getBusinessSectors([
    'search' => 'keyword',
    'color' => '#FF5733',
    'with' => ['logoImage', 'platforms']
]);
```

### Get Single Business Sector
```php
// Basic
$sector = $this->businessSectorService->getBusinessSectorById($id);

// With relationships
$sector = $this->businessSectorService->getBusinessSectorById($id, ['logoImage', 'platforms']);

// With all images (logo, thumbnails, thumbnailsHome)
$sector = $this->businessSectorService->getBusinessSectorWithImages($id);
```

### Get Business Sectors with Counts
```php
$sectors = $this->businessSectorService->getBusinessSectorsWithCounts();
// Access: $sector->platforms_count, $sector->enabled_platforms_count
```

### Get Business Sectors with Enabled Platforms
```php
$sectors = $this->businessSectorService->getBusinessSectorsWithEnabledPlatforms();
foreach ($sectors as $sector) {
    foreach ($sector->platforms as $platform) {
        // Only enabled platforms
    }
}
```

### Get Statistics
```php
// All sectors statistics
$stats = $this->businessSectorService->getStatistics();

// Specific sector statistics
$stats = $this->businessSectorService->getStatistics($businessSectorId);

// Returns: ['total_sectors', 'sectors_with_platforms', 'total_platforms', 'enabled_platforms']
```

### Check if Has Platforms
```php
if ($this->businessSectorService->hasPlatforms($id)) {
    // Sector has platforms
}
```

### Get for Select/Dropdown
```php
$sectors = $this->businessSectorService->getForSelect();
// Returns optimized collection: id, name, color
```

### Search
```php
$sectors = $this->businessSectorService->search('retail', 10);
```

### CRUD Operations
```php
// Create
$sector = $this->businessSectorService->createBusinessSector([
    'name' => 'Retail',
    'description' => 'Retail sector',
    'color' => '#FF5733'
]);

// Update
$sector = $this->businessSectorService->updateBusinessSector($id, $data);

// Delete
$success = $this->businessSectorService->deleteBusinessSector($id);
```

## Integration with PlatformService

```php
public function boot(
    BusinessSectorService $businessSectorService,
    PlatformService $platformService
) {
    $this->businessSectorService = $businessSectorService;
    $this->platformService = $platformService;
}

public function render()
{
    // Get business sector
    $businessSector = $this->businessSectorService->getBusinessSectorWithImages($id);
    
    // Get platforms for this sector
    $platforms = $this->platformService->getPlatformsWithActiveDeals($id);
    
    return view('view', compact('businessSector', 'platforms'));
}
```

## Return Types
- **EloquentCollection methods**: Return `Illuminate\Database\Eloquent\Collection` (empty on error)
  - `getBusinessSectors()`
  - `getBusinessSectorsWithCounts()`
  - `getBusinessSectorsWithEnabledPlatforms()`
  - `getForSelect()`
  - `search()`
- **Single entity**: Return `BusinessSector|null`
  - `getBusinessSectorById()`
  - `getBusinessSectorWithImages()`
  - `createBusinessSector()`
  - `updateBusinessSector()`
- **Boolean operations**: Return `bool`
  - `deleteBusinessSector()`
  - `hasPlatforms()`
- **Statistics**: Return `array`
  - `getStatistics()`

## Key Features
✅ Consistent error handling with logging
✅ Eager loads relationships to prevent N+1 queries
✅ Returns safe defaults on errors
✅ Optimized queries for performance
✅ Works seamlessly with PlatformService
✅ Supports filtering and searching

## Common Use Cases

### 1. Show Business Sector Page
```php
$businessSector = $this->businessSectorService->getBusinessSectorWithImages($id);
$platforms = $this->platformService->getPlatformsWithActiveDeals($id);
```

### 2. List Business Sectors with Stats
```php
$businessSectors = $this->businessSectorService->getBusinessSectorsWithCounts();
```

### 3. Delete with Validation
```php
if ($this->businessSectorService->hasPlatforms($id)) {
    // Cannot delete - has platforms
} else {
    $this->businessSectorService->deleteBusinessSector($id);
}
```

### 4. Search and Display
```php
$results = $this->businessSectorService->search($searchTerm);
```

