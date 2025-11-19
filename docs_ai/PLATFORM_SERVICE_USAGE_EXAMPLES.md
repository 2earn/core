# Platform Service Usage Examples

## Livewire Component Example

```php
<?php

namespace App\Livewire;

use App\Services\Platform\PlatformService;use Livewire\Component;

class PlatformList extends Component
{
    protected $platformService;
    
    public function boot(PlatformService $platformService)
    {
        $this->platformService = $platformService;
    }
    
    public function render()
    {
        // Get all enabled platforms
        $platforms = $this->platformService->getEnabledPlatforms([
            'with' => ['logoImage', 'businessSector']
        ]);
        
        // Get statistics
        $stats = $this->platformService->getStatistics();
        
        return view('livewire.platform-list', compact('platforms', 'stats'));
    }
}
```

## Controller Example

```php
<?php

namespace App\Http\Controllers;

use App\Services\Platform\PlatformService;use Illuminate\Http\Request;

class PlatformController extends Controller
{
    protected $platformService;
    
    public function __construct(PlatformService $platformService)
    {
        $this->platformService = $platformService;
    }
    
    public function index(Request $request)
    {
        $platforms = $this->platformService->getEnabledPlatforms([
            'business_sector_id' => $request->business_sector_id,
            'search' => $request->search,
            'with' => ['logoImage', 'businessSector'],
            'order_by' => 'name',
            'order_direction' => 'asc'
        ]);
        
        return view('platforms.index', compact('platforms'));
    }
    
    public function show($id)
    {
        $platform = $this->platformService->getPlatformById($id, [
            'logoImage',
            'deals',
            'items',
            'businessSector'
        ]);
        
        if (!$platform) {
            return redirect()->route('platforms.index')
                ->with('error', 'Platform not found');
        }
        
        return view('platforms.show', compact('platform'));
    }
    
    public function toggleStatus($id)
    {
        $success = $this->platformService->toggleEnabled($id);
        
        if ($success) {
            return back()->with('success', 'Platform status updated');
        }
        
        return back()->with('error', 'Failed to update platform status');
    }
    
    public function update(Request $request, $id)
    {
        $platform = $this->platformService->updatePlatform($id, $request->validated());
        
        if ($platform) {
            return redirect()->route('platforms.show', $id)
                ->with('success', 'Platform updated successfully');
        }
        
        return back()->with('error', 'Failed to update platform');
    }
}
```

## Business Sector Component Example

```php
<?php

namespace App\Livewire;

use App\Models\BusinessSector;use App\Services\Platform\PlatformService;use Livewire\Component;

class BusinessSectorShow extends Component
{
    public $items = [];
    protected $platformService;
    public $businessSectorId;

    protected $listeners = ['deletebusinessSector' => 'deletebusinessSector'];

    public function boot(PlatformService $platformService)
    {
        $this->platformService = $platformService;
    }

    public function mount($id)
    {
        $this->businessSectorId = $id;
    }

    public function loadItems()
    {
        if (is_null($this->businessSectorId)) {
            return [];
        }
        return $this->platformService->getItemsFromEnabledPlatforms($this->businessSectorId);
    }

    public function render()
    {
        $businessSector = BusinessSector::with(['logoImage', 'thumbnailsImage', 'thumbnailsHomeImage'])
            ->find($this->businessSectorId);

        if (is_null($businessSector)) {
            redirect()->route('business_sector_index', ['locale' => app()->getLocale()]);
        }

        // Use PlatformService to fetch platforms with active deals
        $platforms = $this->platformService->getPlatformsWithActiveDeals($this->businessSectorId);
        
        // Get statistics
        $stats = $this->platformService->getStatistics($this->businessSectorId);

        $params = [
            'businessSector' => $businessSector,
            'platforms' => $platforms,
            'stats' => $stats,
        ];
        
        $this->items = $this->loadItems();
        
        return view('livewire.business-sector-show', $params)
            ->extends('layouts.master')
            ->section('content');
    }
}
```

## API Controller Example

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;use App\Services\Platform\PlatformService;use Illuminate\Http\JsonResponse;use Illuminate\Http\Request;

class PlatformApiController extends Controller
{
    protected $platformService;
    
    public function __construct(PlatformService $platformService)
    {
        $this->platformService = $platformService;
    }
    
    public function index(Request $request): JsonResponse
    {
        $platforms = $this->platformService->getEnabledPlatforms([
            'business_sector_id' => $request->business_sector_id,
            'with' => ['logoImage']
        ]);
        
        return response()->json([
            'success' => true,
            'data' => $platforms
        ]);
    }
    
    public function statistics($businessSectorId = null): JsonResponse
    {
        $stats = $this->platformService->getStatistics($businessSectorId);
        
        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
    
    public function userPlatforms($userId): JsonResponse
    {
        $platforms = $this->platformService->getPlatformsManagedByUser($userId);
        
        return response()->json([
            'success' => true,
            'data' => $platforms
        ]);
    }
}
```

## Command Example

```php
<?php

namespace App\Console\Commands;

use App\Services\Platform\PlatformService;use Illuminate\Console\Command;

class DisableInactivePlatforms extends Command
{
    protected $signature = 'platforms:disable-inactive';
    protected $description = 'Disable platforms with no active deals';
    
    protected $platformService;
    
    public function __construct(PlatformService $platformService)
    {
        parent::__construct();
        $this->platformService = $platformService;
    }
    
    public function handle()
    {
        $platforms = $this->platformService->getEnabledPlatforms();
        $disabled = 0;
        
        foreach ($platforms as $platform) {
            // Get platform with counts
            $platformWithCounts = $this->platformService->getPlatformsWithCounts($platform->business_sector_id)
                ->firstWhere('id', $platform->id);
            
            if ($platformWithCounts && $platformWithCounts->active_deals_count === 0) {
                $this->platformService->toggleEnabled($platform->id);
                $disabled++;
                $this->info("Disabled platform: {$platform->name}");
            }
        }
        
        $this->info("Disabled {$disabled} inactive platforms");
    }
}
```

## Job Example

```php
<?php

namespace App\Jobs;

use App\Services\Platform\PlatformService;use Illuminate\Bus\Queueable;use Illuminate\Contracts\Queue\ShouldQueue;use Illuminate\Foundation\Bus\Dispatchable;use Illuminate\Queue\InteractsWithQueue;use Illuminate\Queue\SerializesModels;

class UpdatePlatformStatistics implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $businessSectorId;
    
    public function __construct($businessSectorId)
    {
        $this->businessSectorId = $businessSectorId;
    }
    
    public function handle(PlatformService $platformService)
    {
        $stats = $platformService->getStatistics($this->businessSectorId);
        
        // Store or process statistics
        cache()->put(
            "business_sector_{$this->businessSectorId}_stats",
            $stats,
            now()->addHours(6)
        );
    }
}
```

## Middleware Example

```php
<?php

namespace App\Http\Middleware;

use App\Services\Platform\PlatformService;use Closure;use Illuminate\Http\Request;

class CheckPlatformAccess
{
    protected $platformService;
    
    public function __construct(PlatformService $platformService)
    {
        $this->platformService = $platformService;
    }
    
    public function handle(Request $request, Closure $next)
    {
        $platformId = $request->route('platform');
        $userId = auth()->id();
        
        if (!$this->platformService->canUserAccessPlatform($platformId, $userId)) {
            abort(403, 'You do not have access to this platform');
        }
        
        return $next($request);
    }
}
```

## Blade View Example

```blade
{{-- In your controller --}}
$platforms = $this->platformService->getPlatformsWithCounts($businessSectorId);

{{-- In your view --}}
<div class="platforms-grid">
    @foreach($platforms as $platform)
        <div class="platform-card">
            <h3>{{ $platform->name }}</h3>
            <div class="platform-stats">
                <span>Deals: {{ $platform->deals_count }}</span>
                <span>Active: {{ $platform->active_deals_count }}</span>
                <span>Items: {{ $platform->items_count }}</span>
                <span>Coupons: {{ $platform->coupons_count }}</span>
            </div>
        </div>
    @endforeach
</div>
```

## Testing Example

```php
<?php

namespace Tests\Feature;

use App\Models\BusinessSector;use App\Services\Platform\PlatformService;use Core\Models\Platform;use Illuminate\Foundation\Testing\RefreshDatabase;use Tests\TestCase;

class PlatformServiceTest extends TestCase
{
    use RefreshDatabase;
    
    protected $platformService;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->platformService = app(PlatformService::class);
    }
    
    public function test_get_enabled_platforms_only()
    {
        $enabledPlatform = Platform::factory()->create(['enabled' => true]);
        $disabledPlatform = Platform::factory()->create(['enabled' => false]);
        
        $platforms = $this->platformService->getEnabledPlatforms();
        
        $this->assertTrue($platforms->contains($enabledPlatform));
        $this->assertFalse($platforms->contains($disabledPlatform));
    }
    
    public function test_get_platforms_with_active_deals()
    {
        $businessSector = BusinessSector::factory()->create();
        $platform = Platform::factory()->create([
            'business_sector_id' => $businessSector->id,
            'enabled' => true
        ]);
        
        $platforms = $this->platformService->getPlatformsWithActiveDeals($businessSector->id);
        
        $this->assertTrue($platforms->contains($platform));
    }
    
    public function test_toggle_enabled_status()
    {
        $platform = Platform::factory()->create(['enabled' => true]);
        
        $this->platformService->toggleEnabled($platform->id);
        
        $this->assertFalse($platform->fresh()->enabled);
    }
    
    public function test_can_user_access_platform()
    {
        $user = User::factory()->create();
        $platform = Platform::factory()->create(['owner_id' => $user->id]);
        
        $canAccess = $this->platformService->canUserAccessPlatform($platform->id, $user->id);
        
        $this->assertTrue($canAccess);
    }
}
```

## Cache Integration Example

```php
use App\Services\Platform\PlatformService;

class PlatformCacheService
{
    protected $platformService;
    
    public function __construct(PlatformService $platformService)
    {
        $this->platformService = $platformService;
    }
    
    public function getCachedPlatforms($businessSectorId)
    {
        return cache()->remember(
            "platforms_business_sector_{$businessSectorId}",
            now()->addHours(1),
            fn() => $this->platformService->getPlatformsWithActiveDeals($businessSectorId)
        );
    }
    
    public function getCachedStatistics($businessSectorId = null)
    {
        $key = $businessSectorId ? "stats_sector_{$businessSectorId}" : "stats_all";
        
        return cache()->remember(
            $key,
            now()->addMinutes(30),
            fn() => $this->platformService->getStatistics($businessSectorId)
        );
    }
}
```

## Tips & Best Practices

1. **Always inject the service** - Don't instantiate it directly
2. **Use eager loading** - Pass relationships in the `with` parameter
3. **Handle null returns** - Check for null when using single-entity methods
4. **Log errors** - Service already logs, but you can add additional logging
5. **Cache when appropriate** - Cache frequently accessed data
6. **Use type hints** - Makes code more maintainable
7. **Test thoroughly** - Write tests for your service usage

## Common Patterns

### Pattern 1: Load with Statistics
```php
$platforms = $this->platformService->getPlatformsWithCounts($businessSectorId);
$stats = $this->platformService->getStatistics($businessSectorId);
```

### Pattern 2: Filter and Display
```php
$platforms = $this->platformService->getEnabledPlatforms([
    'business_sector_id' => $sectorId,
    'search' => $searchTerm,
    'with' => ['logoImage']
]);
```

### Pattern 3: Access Control
```php
if (!$this->platformService->canUserAccessPlatform($platformId, auth()->id())) {
    abort(403);
}
```

### Pattern 4: CRUD Operations
```php
// Create
$platform = $this->platformService->createPlatform($validated);

// Update
$platform = $this->platformService->updatePlatform($id, $validated);

// Delete
$this->platformService->deletePlatform($id);
```

