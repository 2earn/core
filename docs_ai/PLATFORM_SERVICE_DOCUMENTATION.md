# Platform Service Documentation

## Overview
The `PlatformService` is a service class that encapsulates all platform-related business logic and database queries. It provides a clean, reusable interface for working with platforms throughout the application.

## Location
`app/Services/PlatformService.php`

## Purpose
- Centralize platform-related queries and operations
- Ensure only enabled platforms are fetched by default
- Provide consistent error handling and logging
- Reduce code duplication across components and controllers
- Improve code maintainability and testability

## Available Methods

### 1. `getEnabledPlatforms(array $filters = []): EloquentCollection`
Get enabled platforms with optional filters.

**Parameters:**
- `business_sector_id`: Filter by business sector
- `type`: Filter by platform type
- `owner_id`: Filter by owner
- `search`: Search in name/description
- `with`: Array of relationships to load
- `order_by`: Column to order by (default: 'created_at')
- `order_direction`: Order direction (default: 'asc')

**Returns:** `Illuminate\Database\Eloquent\Collection` of Platform models

**Example:**
```php
$platforms = $platformService->getEnabledPlatforms([
    'business_sector_id' => 1,
    'with' => ['logoImage', 'deals']
]);
```

### 2. `getPlatformsWithActiveDeals(int $businessSectorId): EloquentCollection`
Get platforms with active deals for a specific business sector.

**Features:**
- Filters only enabled platforms
- Loads active deals (start_date <= now && end_date >= now)
- Includes items (excluding #0001 reference)
- Eager loads thumbnails

**Returns:** `Illuminate\Database\Eloquent\Collection` of Platform models

**Example:**
```php
$platforms = $platformService->getPlatformsWithActiveDeals($businessSectorId);
```

### 3. `getItemsFromEnabledPlatforms(int $businessSectorId): Collection`
Get all items from enabled platforms in a business sector.

**Returns:** `Illuminate\Support\Collection` of Item models (flattened)

**Example:**
```php
$items = $platformService->getItemsFromEnabledPlatforms($businessSectorId);
```

### 4. `getStatistics(?int $businessSectorId = null): array`
Get platform statistics.

**Returns:**
```php
[
    'total' => int,           // Total platforms
    'enabled' => int,         // Enabled platforms
    'disabled' => int,        // Disabled platforms
    'with_deals' => int,      // Platforms with any deals
    'with_active_deals' => int // Platforms with active deals
]
```

**Example:**
```php
$stats = $platformService->getStatistics($businessSectorId);
```

### 5. `getPlatformById(int $id, array $with = []): ?Platform`
Get a specific platform by ID with optional relationships.

**Example:**
```php
$platform = $platformService->getPlatformById($id, ['logoImage', 'deals', 'businessSector']);
```

### 6. `toggleEnabled(int $id): bool`
Toggle the enabled status of a platform.

**Example:**
```php
$success = $platformService->toggleEnabled($platformId);
```

### 7. `getPlatformsManagedByUser(int $userId, bool $onlyEnabled = true): EloquentCollection`
Get platforms managed by a specific user (owner, marketing manager, or financial manager).

**Returns:** `Illuminate\Database\Eloquent\Collection` of Platform models

**Example:**
```php
$platforms = $platformService->getPlatformsManagedByUser($userId);
```

### 8. `getPlatformsWithCounts(int $businessSectorId): EloquentCollection`
Get platforms with counts of related entities (deals, active deals, items, coupons).

**Returns:** `Illuminate\Database\Eloquent\Collection` of Platform models with count attributes

**Example:**
```php
$platforms = $platformService->getPlatformsWithCounts($businessSectorId);
foreach ($platforms as $platform) {
    echo $platform->deals_count;
    echo $platform->active_deals_count;
}
```

### 9. `canUserAccessPlatform(int $platformId, int $userId): bool`
Check if a user can access a platform (is owner, marketing manager, or financial manager).

**Example:**
```php
if ($platformService->canUserAccessPlatform($platformId, auth()->id())) {
    // Allow access
}
```

### 10. `updatePlatform(int $id, array $data): ?Platform`
Update platform data.

**Example:**
```php
$platform = $platformService->updatePlatform($platformId, [
    'name' => 'New Name',
    'enabled' => true
]);
```

### 11. `createPlatform(array $data): ?Platform`
Create a new platform.

**Example:**
```php
$platform = $platformService->createPlatform([
    'name' => 'Platform Name',
    'business_sector_id' => 1,
    'enabled' => true
]);
```

### 12. `deletePlatform(int $id): bool`
Delete a platform.

**Example:**
```php
$success = $platformService->deletePlatform($platformId);
```

## Usage in Components

### Livewire Components

Inject the service in the `boot()` method:

```php
use App\Services\Platform\PlatformService;

class YourComponent extends Component
{
    protected $platformService;
    
    public function boot(PlatformService $platformService)
    {
        $this->platformService = $platformService;
    }
    
    public function render()
    {
        $platforms = $this->platformService->getPlatformsWithActiveDeals($businessSectorId);
        return view('your-view', compact('platforms'));
    }
}
```

### Controllers

Inject via constructor or method:

```php
use App\Services\Platform\PlatformService;

class PlatformController extends Controller
{
    protected $platformService;
    
    public function __construct(PlatformService $platformService)
    {
        $this->platformService = $platformService;
    }
    
    public function index()
    {
        $platforms = $this->platformService->getEnabledPlatforms([
            'with' => ['logoImage', 'businessSector']
        ]);
        
        return view('platforms.index', compact('platforms'));
    }
}
```

## Error Handling

All methods include try-catch blocks with error logging. On error:
- EloquentCollection methods return an empty `Illuminate\Database\Eloquent\Collection`
- Support Collection methods return an empty `Illuminate\Support\Collection`
- Single entity methods return null
- Boolean methods return false

Errors are logged to Laravel's log with descriptive messages.

## Return Types

- **Platform Collection methods** (getEnabledPlatforms, getPlatformsWithActiveDeals, etc.): Return `Illuminate\Database\Eloquent\Collection`
- **Flattened item methods** (getItemsFromEnabledPlatforms): Return `Illuminate\Support\Collection`
- **Single platform methods** (getPlatformById): Return `Platform|null`
- **Boolean operations** (toggleEnabled, deletePlatform, etc.): Return `bool`
- **Statistics**: Return `array`

## Benefits

1. **Consistency**: All platform queries follow the same pattern
2. **Reusability**: Use the same methods across multiple components/controllers
3. **Maintainability**: Changes to platform logic only need to be made in one place
4. **Testability**: Easy to mock and test service methods
5. **Performance**: Optimized queries with eager loading
6. **Error Handling**: Consistent error handling and logging

## Migration Guide

### Before (without service):
```php
$platforms = Platform::with([
    'logoImage',
    'deals' => function($query) {
        $query->where('start_date', '<=', now())
              ->where('end_date', '>=', now());
    }
])
->where('enabled', true)
->where('business_sector_id', $businessSectorId)
->get();
```

### After (with service):
```php
$platforms = $this->platformService->getPlatformsWithActiveDeals($businessSectorId);
```

## Future Enhancements

Consider adding:
- Caching for frequently accessed data
- Pagination support
- Bulk operations
- Search and filtering improvements
- Platform analytics methods
- Export functionality

## Related Files
- `Core/Models/Platform.php` - Platform model
- `app/Livewire/BusinessSectorShow.php` - Example usage
- `app/Services/` - Other service classes

## Testing

Example test:
```php
public function test_get_enabled_platforms()
{
    $platform = Platform::factory()->create(['enabled' => true]);
    $service = new PlatformService();
    
    $platforms = $service->getEnabledPlatforms();
    
    $this->assertTrue($platforms->contains($platform));
}
```

