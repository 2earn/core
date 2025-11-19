# Platform Service Quick Reference

## Import

```php

```

## Inject in Livewire
```php
protected $platformService;

public function boot(PlatformService $platformService)
{
    $this->platformService = $platformService;
}
```

## Common Methods

### Get Enabled Platforms
```php
// All enabled platforms
$platforms = $this->platformService->getEnabledPlatforms();

// With filters
$platforms = $this->platformService->getEnabledPlatforms([
    'business_sector_id' => 1,
    'type' => 'ecommerce',
    'search' => 'keyword',
    'with' => ['logoImage', 'deals']
]);
```

### Get Platforms with Active Deals
```php
$platforms = $this->platformService->getPlatformsWithActiveDeals($businessSectorId);
```

### Get Items from Enabled Platforms
```php
$items = $this->platformService->getItemsFromEnabledPlatforms($businessSectorId);
```

### Get Statistics
```php
$stats = $this->platformService->getStatistics($businessSectorId);
// Returns: ['total', 'enabled', 'disabled', 'with_deals', 'with_active_deals']
```

### Get Single Platform
```php
$platform = $this->platformService->getPlatformById($id, ['logoImage', 'deals']);
```

### Toggle Enabled Status
```php
$success = $this->platformService->toggleEnabled($platformId);
```

### User's Platforms
```php
$platforms = $this->platformService->getPlatformsManagedByUser($userId);
```

### Platforms with Counts
```php
$platforms = $this->platformService->getPlatformsWithCounts($businessSectorId);
// Access: $platform->deals_count, $platform->active_deals_count
```

### Check Access
```php
if ($this->platformService->canUserAccessPlatform($platformId, auth()->id())) {
    // User has access
}
```

### CRUD Operations
```php
// Create
$platform = $this->platformService->createPlatform($data);

// Update
$platform = $this->platformService->updatePlatform($id, $data);

// Delete
$success = $this->platformService->deletePlatform($id);
```

## Return Types
- **EloquentCollection methods**: Return `Illuminate\Database\Eloquent\Collection` (empty on error)
  - `getEnabledPlatforms()`
  - `getPlatformsWithActiveDeals()`
  - `getPlatformsManagedByUser()`
  - `getPlatformsWithCounts()`
- **Support Collection methods**: Return `Illuminate\Support\Collection` (empty on error)
  - `getItemsFromEnabledPlatforms()` (flattened items)
- **Single entity**: Return `Platform|null`
- **Boolean operations**: Return `bool`
- **Statistics**: Return `array`

## Key Features
✅ Automatically filters for enabled platforms (where applicable)
✅ Eager loads relationships to prevent N+1 queries
✅ Includes error handling and logging
✅ Returns safe defaults on errors
✅ Consistent API across all methods

