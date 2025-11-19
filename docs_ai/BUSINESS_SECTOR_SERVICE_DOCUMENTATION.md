# Business Sector Service Documentation

## Overview
The `BusinessSectorService` is a service class that encapsulates all business sector-related business logic and database queries. It provides a clean, reusable interface for working with business sectors throughout the application.

## Location
`app/Services/BusinessSector/BusinessSectorService.php`

## Purpose
- Centralize business sector-related queries and operations
- Provide consistent error handling and logging
- Reduce code duplication across components and controllers
- Improve code maintainability and testability
- Work seamlessly with PlatformService

## Available Methods

### 1. `getBusinessSectors(array $filters = []): EloquentCollection`
Get all business sectors with optional filters.

**Parameters:**
- `search`: Search in name/description
- `color`: Filter by color
- `with`: Array of relationships to load
- `order_by`: Column to order by (default: 'created_at')
- `order_direction`: Order direction (default: 'desc')

**Returns:** `Illuminate\Database\Eloquent\Collection` of BusinessSector models

**Example:**
```php
$businessSectors = $businessSectorService->getBusinessSectors([
    'search' => 'retail',
    'with' => ['logoImage', 'platforms']
]);
```

### 2. `getBusinessSectorById(int $id, array $with = []): ?BusinessSector`
Get a specific business sector by ID with optional relationships.

**Example:**
```php
$businessSector = $businessSectorService->getBusinessSectorById($id, ['logoImage', 'platforms']);
```

### 3. `getBusinessSectorWithImages(int $id): ?BusinessSector`
Get business sector with all image relationships (logo, thumbnails, thumbnailsHome).

**Returns:** `BusinessSector|null`

**Example:**
```php
$businessSector = $businessSectorService->getBusinessSectorWithImages($id);
```

### 4. `getBusinessSectorsWithCounts(): EloquentCollection`
Get business sectors with platform counts (total and enabled).

**Returns:** Business sectors with `platforms_count` and `enabled_platforms_count` attributes

**Example:**
```php
$businessSectors = $businessSectorService->getBusinessSectorsWithCounts();
foreach ($businessSectors as $sector) {
    echo $sector->platforms_count;
    echo $sector->enabled_platforms_count;
}
```

### 5. `getBusinessSectorsWithEnabledPlatforms(): EloquentCollection`
Get business sectors that have enabled platforms, with platforms eager loaded.

**Example:**
```php
$businessSectors = $businessSectorService->getBusinessSectorsWithEnabledPlatforms();
foreach ($businessSectors as $sector) {
    foreach ($sector->platforms as $platform) {
        echo $platform->name;
    }
}
```

### 6. `getStatistics(?int $businessSectorId = null): array`
Get business sector statistics.

**Returns:**
```php
[
    'total_sectors' => int,          // Total business sectors
    'sectors_with_platforms' => int, // Sectors that have platforms
    'total_platforms' => int,        // Total platforms across all sectors
    'enabled_platforms' => int,      // Total enabled platforms
]
```

**Example:**
```php
// All sectors
$stats = $businessSectorService->getStatistics();

// Specific sector
$stats = $businessSectorService->getStatistics($businessSectorId);
```

### 7. `createBusinessSector(array $data): ?BusinessSector`
Create a new business sector.

**Example:**
```php
$businessSector = $businessSectorService->createBusinessSector([
    'name' => 'Retail',
    'description' => 'Retail business sector',
    'color' => '#FF5733'
]);
```

### 8. `updateBusinessSector(int $id, array $data): ?BusinessSector`
Update business sector data.

**Example:**
```php
$businessSector = $businessSectorService->updateBusinessSector($id, [
    'name' => 'Updated Name',
    'description' => 'Updated Description'
]);
```

### 9. `deleteBusinessSector(int $id): bool`
Delete a business sector.

**Example:**
```php
$success = $businessSectorService->deleteBusinessSector($id);
```

### 10. `hasPlatforms(int $id): bool`
Check if a business sector has any platforms.

**Example:**
```php
if ($businessSectorService->hasPlatforms($id)) {
    // Sector has platforms
}
```

### 11. `getForSelect(): EloquentCollection`
Get business sectors for dropdown/select options (only id, name, color).

**Example:**
```php
$sectors = $businessSectorService->getForSelect();
// Returns optimized collection for selects
```

### 12. `search(string $searchTerm, int $limit = 10): EloquentCollection`
Search business sectors by name or description.

**Example:**
```php
$sectors = $businessSectorService->search('retail', 5);
```

## Usage in Components

### Livewire Components

Inject the service in the `boot()` method:

```php
use App\Services\BusinessSector\BusinessSectorService;

class YourComponent extends Component
{
    protected $businessSectorService;
    
    public function boot(BusinessSectorService $businessSectorService)
    {
        $this->businessSectorService = $businessSectorService;
    }
    
    public function render()
    {
        $businessSector = $this->businessSectorService->getBusinessSectorWithImages($id);
        return view('your-view', compact('businessSector'));
    }
}
```

### Controllers

Inject via constructor or method:

```php
use App\Services\BusinessSector\BusinessSectorService;

class BusinessSectorController extends Controller
{
    protected $businessSectorService;
    
    public function __construct(BusinessSectorService $businessSectorService)
    {
        $this->businessSectorService = $businessSectorService;
    }
    
    public function index()
    {
        $businessSectors = $this->businessSectorService->getBusinessSectorsWithCounts();
        return view('business-sectors.index', compact('businessSectors'));
    }
    
    public function show($id)
    {
        $businessSector = $this->businessSectorService->getBusinessSectorWithImages($id);
        return view('business-sectors.show', compact('businessSector'));
    }
}
```

## Error Handling

All methods include try-catch blocks with error logging. On error:
- EloquentCollection methods return an empty `Illuminate\Database\Eloquent\Collection`
- Single entity methods return null
- Boolean methods return false
- Statistics return array with zero values

Errors are logged to Laravel's log with descriptive messages.

## Integration with PlatformService

The BusinessSectorService works seamlessly with PlatformService:

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
    // Get business sector with images
    $businessSector = $this->businessSectorService->getBusinessSectorWithImages($id);
    
    // Get platforms with active deals for this sector
    $platforms = $this->platformService->getPlatformsWithActiveDeals($id);
    
    return view('view', compact('businessSector', 'platforms'));
}
```

## Benefits

1. **Consistency**: All business sector queries follow the same pattern
2. **Reusability**: Use the same methods across multiple components/controllers
3. **Maintainability**: Changes to business sector logic only need to be made in one place
4. **Testability**: Easy to mock and test service methods
5. **Performance**: Optimized queries with eager loading
6. **Error Handling**: Consistent error handling and logging
7. **Integration**: Works seamlessly with other services (PlatformService)

## Migration Guide

### Before (without service):
```php
$businessSector = BusinessSector::with(['logoImage', 'thumbnailsImage', 'thumbnailsHomeImage'])
    ->find($id);
```

### After (with service):
```php
$businessSector = $this->businessSectorService->getBusinessSectorWithImages($id);
```

## Common Patterns

### Pattern 1: Display Business Sector with Platforms
```php
$businessSector = $this->businessSectorService->getBusinessSectorWithImages($id);
$platforms = $this->platformService->getPlatformsWithActiveDeals($id);
```

### Pattern 2: List with Counts
```php
$businessSectors = $this->businessSectorService->getBusinessSectorsWithCounts();
```

### Pattern 3: Search and Filter
```php
$results = $this->businessSectorService->search($searchTerm);
```

### Pattern 4: CRUD Operations
```php
// Create
$sector = $this->businessSectorService->createBusinessSector($data);

// Update
$sector = $this->businessSectorService->updateBusinessSector($id, $data);

// Delete
$success = $this->businessSectorService->deleteBusinessSector($id);
```

## Related Files
- `app/Models/BusinessSector.php` - BusinessSector model
- `app/Services/Platform/PlatformService.php` - Platform service (complementary)
- `app/Livewire/BusinessSectorShow.php` - Example usage

## Testing

Example test:
```php
public function test_get_business_sector_with_images()
{
    $businessSector = BusinessSector::factory()->create();
    $service = new BusinessSectorService();
    
    $result = $service->getBusinessSectorWithImages($businessSector->id);
    
    $this->assertNotNull($result);
    $this->assertEquals($businessSector->id, $result->id);
}

public function test_delete_business_sector()
{
    $businessSector = BusinessSector::factory()->create();
    $service = new BusinessSectorService();
    
    $success = $service->deleteBusinessSector($businessSector->id);
    
    $this->assertTrue($success);
    $this->assertDatabaseMissing('business_sectors', ['id' => $businessSector->id]);
}
```

