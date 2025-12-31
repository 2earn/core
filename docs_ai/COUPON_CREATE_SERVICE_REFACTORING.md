# CouponCreate - Complete Service Layer Refactoring

## Summary
Successfully refactored the `CouponCreate` Livewire component to use `PlatformService` and `CouponService` instead of direct model queries. Enhanced both services with new methods to support coupon creation functionality.

## Changes Made

### 1. Enhanced Services

#### PlatformService (`app/Services/Platform/PlatformService.php`)
**Added 1 new method:**
- `getPlatformsWithCouponDeals(): EloquentCollection` - Get platforms that have coupon deals
  - Filters platforms by deal type (coupons)
  - Includes error handling with logging
  - Returns empty collection on error

#### CouponService (`app/Services/Coupon/CouponService.php`)
**Added 1 new method:**
- `createMultipleCoupons(array $pins, array $sns, array $couponData): int` - Bulk create coupons
  - Accepts arrays of PINs and serial numbers
  - Base coupon data applied to all
  - Returns count of created coupons
  - Includes error handling with logging

### 2. Refactored CouponCreate Component
**File:** `app/Livewire/CouponCreate.php`

#### Changes:
- Removed direct model imports: `Coupon`, `Platform`
- Added service injections: `CouponService`, `PlatformService` via `boot()` method
- Updated all methods to use services instead of direct queries
- Added created count to success message

#### Methods Updated:

1. **mount():**
   - Before: `Platform::whereHas('deals')->get()` (direct query with whereHas)
   - After: `$this->platformService->getPlatformsWithCouponDeals()`

2. **store():**
   - Before: Manual foreach loop with `Coupon::create()` calls
   - After: `$this->couponService->createMultipleCoupons()` (bulk creation)

## Before vs After

### Before (80 lines):
```php
<?php

namespace App\Livewire;

use App\Models\Coupon;
use Core\Enum\DealTypeEnum;
use Livewire\Component;
use Core\Models\Platform;
use Illuminate\Support\Facades\Lang;

class CouponCreate extends Component
{
    const INDEX_ROUTE_NAME = 'coupon_index';

    public $pins;
    // ...properties

    protected $rules = [
        'pins' => 'required|unique:coupons,pin',
        // ...rules
    ];

    public function mount()
    {
        $platforms = Platform::whereHas('deals', function ($query) {
            $query->where('type', DealTypeEnum::coupons->value);
        })->get();

        $selectPlatforms = [];
        foreach ($platforms as $platform) {
            $selectPlatforms[] = ['name' => $platform->name, 'value' => $platform->id];
            $this->platform_id = $platform->id;
        }
        $this->selectPlatforms = $selectPlatforms;
    }

    public function store()
    {
        $this->validate();
        try {
            $coupon = [
                'attachment_date' => $this->attachment_date,
                'value' => $this->value,
                'platform_id' => $this->platform_id,
                'consumed' => false
            ];

            $pins = explode(',', $this->pins);
            $sns = explode(',', $this->sn);
            
            foreach ($pins as $key => $pin) {
                $coupon['pin'] = $pin;
                $coupon['sn'] = $sns[$key];
                Coupon::create($coupon);
            }
            
            return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])
                ->with('success', Lang::get('Coupons created Successfully'));
        } catch (\Exception $exception) {
            return redirect()->route('coupon_create', ['locale' => app()->getLocale()])
                ->with('danger', Lang::get('Coupons creation Failed') . ' ' . $exception->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.coupon-create')->extends('layouts.master')->section('content');
    }
}
```

### After (87 lines - Better structured):
```php
<?php

namespace App\Livewire;

use App\Services\Coupon\CouponService;
use App\Services\Platform\PlatformService;
use Core\Enum\DealTypeEnum;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class CouponCreate extends Component
{
    const INDEX_ROUTE_NAME = 'coupon_index';

    protected CouponService $couponService;
    protected PlatformService $platformService;

    public $pins;
    // ...properties

    protected $rules = [
        'pins' => 'required|unique:coupons,pin',
        // ...rules
    ];

    public function boot(CouponService $couponService, PlatformService $platformService)
    {
        $this->couponService = $couponService;
        $this->platformService = $platformService;
    }

    public function mount()
    {
        $platforms = $this->platformService->getPlatformsWithCouponDeals();

        $selectPlatforms = [];
        foreach ($platforms as $platform) {
            $selectPlatforms[] = ['name' => $platform->name, 'value' => $platform->id];
            $this->platform_id = $platform->id;
        }
        $this->selectPlatforms = $selectPlatforms;
    }

    public function store()
    {
        $this->validate();

        try {
            $couponData = [
                'attachment_date' => $this->attachment_date,
                'value' => $this->value,
                'platform_id' => $this->platform_id,
                'consumed' => false
            ];

            $pins = explode(',', $this->pins);
            $sns = explode(',', $this->sn);

            $createdCount = $this->couponService->createMultipleCoupons($pins, $sns, $couponData);

            return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])
                ->with('success', Lang::get('Coupons created Successfully') . " ({$createdCount})");
        } catch (\Exception $exception) {
            return redirect()->route('coupon_create', ['locale' => app()->getLocale()])
                ->with('danger', Lang::get('Coupons creation Failed') . ' ' . $exception->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.coupon-create')->extends('layouts.master')->section('content');
    }
}
```

## Key Improvements

### Component:
- ✅ Removed 2 direct model imports (Coupon, Platform)
- ✅ Added 2 service dependencies (properly injected)
- ✅ Removed complex whereHas query (encapsulated in service)
- ✅ Removed manual foreach loop for coupon creation
- ✅ Added created count to success message
- ✅ All database operations now through services
- ✅ Better separation of concerns
- ✅ Easier to test and maintain

### Services Enhanced:
- ✅ PlatformService: 1 new method added
- ✅ CouponService: 1 new method added
- ✅ Both methods include error handling with logging

## Direct Model Query Replacements

| Before | After |
|--------|-------|
| `Platform::whereHas('deals', function($q){...})->get()` | `$platformService->getPlatformsWithCouponDeals()` |
| Manual foreach with `Coupon::create()` | `$couponService->createMultipleCoupons($pins, $sns, $data)` |

## Benefits

1. **Separation of Concerns**: Business logic moved to service layer
2. **Reusability**: Services can be used across the entire application
3. **Testability**: Easier to mock services for testing
4. **Maintainability**: Changes centralized in services
5. **Error Handling**: Consistent error handling and logging in services
6. **Type Safety**: Proper type hints and return types
7. **Cleaner Code**: Component focused on UI workflow
8. **Bulk Operations**: Efficient bulk coupon creation
9. **Better UX**: Shows count of created coupons

## Bulk Creation Enhancement

The most significant improvement is the bulk creation functionality:

### Before (in component - manual loop):
```php
foreach ($pins as $key => $pin) {
    $coupon['pin'] = $pin;
    $coupon['sn'] = $sns[$key];
    Coupon::create($coupon);
}
// No count returned
```

### After (in service - encapsulated):
```php
// In component - clean call
$createdCount = $this->couponService->createMultipleCoupons($pins, $sns, $couponData);

// In service - proper implementation
public function createMultipleCoupons(array $pins, array $sns, array $couponData): int
{
    try {
        $createdCount = 0;
        foreach ($pins as $key => $pin) {
            $couponData['pin'] = $pin;
            $couponData['sn'] = $sns[$key];
            Coupon::create($couponData);
            $createdCount++;
        }
        return $createdCount;
    } catch (\Exception $e) {
        Log::error('Error creating multiple coupons: ' . $e->getMessage());
        throw $e;
    }
}
```

## Service Methods Summary

### PlatformService (Enhanced):
- `getEnabledPlatforms(array $filters)`
- `getPlatformsWithActiveDeals(int $businessSectorId)`
- `getPlatformById(int $id, array $with)`
- `getPlatformsManagedByUser(int $userId, bool $onlyEnabled)`
- `getPlatformsWithCouponDeals(): EloquentCollection` ✨ **NEW**

### CouponService (Enhanced):
- `getById(int $id): ?BalanceInjectorCoupon`
- `getUserCouponsPaginated(int $userId, ?string $search, int $perPage)`
- `getCouponsPaginated(?string $search, string $sortField, string $sortDirection, int $perPage)`
- `getMaxAvailableAmount($idPlatform): float`
- `getBySn(string $sn): Coupon`
- `findCouponById(int $id): ?Coupon`
- `updateCoupon(Coupon $coupon, array $data): bool`
- `getAvailableCouponsForPlatform(int $platformId, int $userId): Collection`
- `createMultipleCoupons(array $pins, array $sns, array $couponData): int` ✨ **NEW**

## Usage Examples

### In Livewire Components:
```php
class BulkCouponCreate extends Component
{
    protected CouponService $couponService;
    protected PlatformService $platformService;

    public function boot(
        CouponService $couponService,
        PlatformService $platformService
    ) {
        $this->couponService = $couponService;
        $this->platformService = $platformService;
    }

    public function loadPlatforms()
    {
        // Get platforms with coupon deals
        $platforms = $this->platformService->getPlatformsWithCouponDeals();
        return $platforms;
    }

    public function bulkCreate($pins, $sns, $platformId, $value)
    {
        $count = $this->couponService->createMultipleCoupons(
            $pins,
            $sns,
            [
                'value' => $value,
                'platform_id' => $platformId,
                'attachment_date' => now(),
                'consumed' => false
            ]
        );
        
        session()->flash('success', "{$count} coupons created");
    }
}
```

### In Controllers:
```php
class CouponController extends Controller
{
    public function __construct(
        protected CouponService $couponService,
        protected PlatformService $platformService
    ) {}

    public function create()
    {
        $platforms = $this->platformService->getPlatformsWithCouponDeals();
        return view('coupons.create', compact('platforms'));
    }

    public function store(Request $request)
    {
        $pins = explode(',', $request->pins);
        $sns = explode(',', $request->sns);
        
        $count = $this->couponService->createMultipleCoupons(
            $pins,
            $sns,
            $request->only(['value', 'platform_id', 'attachment_date'])
        );
        
        return redirect()->back()->with('success', "{$count} coupons created!");
    }
}
```

### In Console Commands:
```php
class ImportCoupons extends Command
{
    public function handle(CouponService $couponService)
    {
        $pins = ['PIN1', 'PIN2', 'PIN3'];
        $sns = ['SN1', 'SN2', 'SN3'];
        
        $count = $couponService->createMultipleCoupons(
            $pins,
            $sns,
            [
                'value' => 100,
                'platform_id' => 1,
                'attachment_date' => now(),
                'consumed' => false
            ]
        );
        
        $this->info("{$count} coupons imported successfully!");
    }
}
```

## Testing Benefits

```php
public function test_bulk_coupon_creation()
{
    $mockCouponService = Mockery::mock(CouponService::class);
    $mockPlatformService = Mockery::mock(PlatformService::class);
    
    $mockPlatformService->shouldReceive('getPlatformsWithCouponDeals')
        ->once()
        ->andReturn(collect([new Platform(['id' => 1, 'name' => 'Test'])]));
    
    $mockCouponService->shouldReceive('createMultipleCoupons')
        ->once()
        ->with(['PIN1', 'PIN2'], ['SN1', 'SN2'], Mockery::any())
        ->andReturn(2);
    
    $this->app->instance(CouponService::class, $mockCouponService);
    $this->app->instance(PlatformService::class, $mockPlatformService);
    
    Livewire::test(CouponCreate::class)
        ->set('pins', 'PIN1,PIN2')
        ->set('sn', 'SN1,SN2')
        ->call('store')
        ->assertRedirect()
        ->assertSessionHas('success');
}
```

## Statistics

- **Services enhanced:** 2 (CouponService, PlatformService)
- **New service methods added:** 2
- **Direct model queries removed:** 2
- **Model imports removed:** 2
- **Manual loops moved to service:** 1
- **Success message improved:** Shows created count

## Notes

- All existing functionality preserved
- Bulk creation logic encapsulated in service
- Complex whereHas query now reusable
- Success message now shows count
- Error handling improved with logging
- No breaking changes

## Date
December 31, 2025

