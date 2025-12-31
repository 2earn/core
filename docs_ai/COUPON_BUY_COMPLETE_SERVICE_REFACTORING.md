# CouponBuy - Complete Service Layer Refactoring

## Summary
Successfully refactored the `CouponBuy` Livewire component to use `PlatformService`, `OrderService`, `ItemService`, and `CouponService` instead of direct model queries. Enhanced multiple services with new methods to support coupon purchasing functionality.

## Changes Made

### 1. Enhanced Services

#### CouponService (`app/Services/Coupon/CouponService.php`)
**Added 3 new methods:**
- `findCouponById(int $id): ?Coupon` - Find a coupon by ID
- `updateCoupon(Coupon $coupon, array $data): bool` - Update a coupon with array data
- `getAvailableCouponsForPlatform(int $platformId, int $userId): Collection` - Get available coupons for platform with complex query logic

#### ItemService (`app/Services/Items/ItemService.php`)
**Added 1 new method:**
- `findByRefAndPlatform(string $ref, int $platformId): ?Item` - Find item by ref and platform_id

#### OrderService (`app/Services/Orders/OrderService.php`)
**Added 1 new method:**
- `createOrder(array $data): Order` - Create a new order

### 2. Refactored CouponBuy Component
**File:** `app/Livewire/CouponBuy.php`

#### Changes:
- Removed direct model imports: `Coupon`, `Item`, `Order`, `Platform`
- Added 4 service injections via `boot()` method
- Removed duplicate `CouponService` instantiation in `mount()`
- Updated all methods to use services instead of direct queries

#### Methods Updated:

1. **mount():**
   - Before: `$couponService = app(CouponService::class);` (duplicate instantiation)
   - After: Uses injected `$this->couponService`

2. **consumeCoupon():**
   - Before: `Coupon::find()` and `$coupon->update()`
   - After: `$this->couponService->findCouponById()` and `$this->couponService->updateCoupon()`

3. **CancelPurchase():**
   - Before: Direct `$coupon->update()` calls
   - After: `$this->couponService->updateCoupon()` calls

4. **BuyCoupon():**
   - Before: `Platform::find()`, `Order::create()`, `Item::where()`, `Coupon::where()`, `$coupon->update()`
   - After: `$this->platformService->getPlatformById()`, `$this->orderService->createOrder()`, `$this->itemService->findByRefAndPlatform()`, `$this->couponService->getBySn()`, `$this->couponService->updateCoupon()`

5. **getCouponsForAmount():**
   - Before: 15+ lines of complex Coupon query with subqueries
   - After: `$this->couponService->getAvailableCouponsForPlatform()` (encapsulated logic)

6. **render():**
   - Before: `Platform::find()`
   - After: `$this->platformService->getPlatformById()`

## Before vs After

### Before (264 lines):
```php
<?php

namespace App\Livewire;

use App\Models\Coupon;
use App\Models\Item;
use App\Models\Order;
use App\Services\Coupon\CouponService;
use App\Services\Orders\Ordering;
use Core\Enum\CouponStatusEnum;
use Core\Enum\OrderEnum;
use Core\Models\Platform;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class CouponBuy extends Component
{
    const DELAY_FOR_COUPONS_SIMULATION = 5;
    public $amount = 0;
    // ...properties

    public function mount()
    {
        $this->idPlatform = Route::current()->parameter('id');
        $this->amount = 0;

        $couponService = app(CouponService::class);
        $this->maxAmount = $couponService->getMaxAvailableAmount($this->idPlatform);
        // ...
    }

    public function consumeCoupon($id)
    {
        $couponToUpdate = Coupon::find($id);
        if (!$couponToUpdate->consumed) {
            $couponToUpdate->update([...]);
        }
        // ...
    }

    public function BuyCoupon($cpns)
    {
        $platform = Platform::find($this->idPlatform);
        $order = Order::create([...]);
        $coupon = Item::where('ref', '#0001')
            ->where('platform_id', $this->idPlatform)
            ->first();
        
        // ...transaction logic
        
        foreach ($note as $sn) {
            $coupon = Coupon::where('sn', $sn)->first();
            if (!$coupon->consumed) {
                $coupon->update([...]);
            }
        }
        // ...
    }

    public function getCouponsForAmount($amount): array
    {
        $availableCoupons = Coupon::where(function ($query) {
            $query
                ->orWhere('status', CouponStatusEnum::available->value)
                ->orWhere(function ($subQueryReservedForOther) {
                    // ...complex subquery
                })
                ->orWhere(function ($subQueryReservedForUser) {
                    // ...complex subquery
                });
        })
        ->where('platform_id', $this->idPlatform)
        ->orderBy('value', 'desc')
        ->get();

        foreach ($availableCoupons as $coupon) {
            if ($total + $coupon->value <= $amount) {
                $coupon->update([...]);
                // ...
            }
        }
        // ...
    }

    public function render()
    {
        $params = ['platform' => Platform::find($this->idPlatform)];
        return view('livewire.coupon-buy', $params)->extends('layouts.master')->section('content');
    }
}
```

### After (264 lines - Same length but much cleaner):
```php
<?php

namespace App\Livewire;

use App\Services\Coupon\CouponService;
use App\Services\Items\ItemService;
use App\Services\Orders\OrderService;
use App\Services\Orders\Ordering;
use App\Services\Platform\PlatformService;
use Core\Enum\CouponStatusEnum;
use Core\Enum\OrderEnum;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class CouponBuy extends Component
{
    const DELAY_FOR_COUPONS_SIMULATION = 5;

    protected CouponService $couponService;
    protected PlatformService $platformService;
    protected OrderService $orderService;
    protected ItemService $itemService;

    public $amount = 0;
    // ...properties

    public function boot(
        CouponService $couponService,
        PlatformService $platformService,
        OrderService $orderService,
        ItemService $itemService
    ) {
        $this->couponService = $couponService;
        $this->platformService = $platformService;
        $this->orderService = $orderService;
        $this->itemService = $itemService;
    }

    public function mount()
    {
        $this->idPlatform = Route::current()->parameter('id');
        $this->amount = 0;
        $this->maxAmount = $this->couponService->getMaxAvailableAmount($this->idPlatform);
        // ...
    }

    public function consumeCoupon($id)
    {
        $couponToUpdate = $this->couponService->findCouponById($id);
        if ($couponToUpdate && !$couponToUpdate->consumed) {
            $this->couponService->updateCoupon($couponToUpdate, [...]);
        }
        // ...
    }

    public function BuyCoupon($cpns)
    {
        $platform = $this->platformService->getPlatformById($this->idPlatform);
        $order = $this->orderService->createOrder([...]);
        $coupon = $this->itemService->findByRefAndPlatform('#0001', $this->idPlatform);
        
        // ...transaction logic
        
        foreach ($note as $sn) {
            $coupon = $this->couponService->getBySn($sn);
            if (!$coupon->consumed) {
                $this->couponService->updateCoupon($coupon, [...]);
            }
        }
        // ...
    }

    public function getCouponsForAmount($amount): array
    {
        $availableCoupons = $this->couponService->getAvailableCouponsForPlatform(
            $this->idPlatform,
            auth()->user()->id
        );

        foreach ($availableCoupons as $coupon) {
            if ($total + $coupon->value <= $amount) {
                $this->couponService->updateCoupon($coupon, [...]);
                // ...
            }
        }
        // ...
    }

    public function render()
    {
        $params = ['platform' => $this->platformService->getPlatformById($this->idPlatform)];
        return view('livewire.coupon-buy', $params)->extends('layouts.master')->section('content');
    }
}
```

## Key Improvements

### Component:
- ✅ Removed 4 direct model imports (Coupon, Item, Order, Platform)
- ✅ Added 4 service dependencies (properly injected)
- ✅ Removed duplicate service instantiation
- ✅ Removed 15+ lines of complex query logic
- ✅ All database operations now through services
- ✅ Better separation of concerns
- ✅ Easier to test and maintain

### Services Enhanced:
- ✅ CouponService: 3 new methods added
- ✅ ItemService: 1 new method added
- ✅ OrderService: 1 new method added
- ✅ All methods include error handling with logging
- ✅ Complex query logic encapsulated in service

## Direct Model Query Replacements

| Before | After |
|--------|-------|
| `Coupon::find($id)` | `$couponService->findCouponById($id)` |
| `$coupon->update([...])` | `$couponService->updateCoupon($coupon, [...])` |
| `Platform::find($id)` | `$platformService->getPlatformById($id)` |
| `Order::create([...])` | `$orderService->createOrder([...])` |
| `Item::where('ref')->where('platform_id')->first()` | `$itemService->findByRefAndPlatform($ref, $platformId)` |
| `Coupon::where('sn', $sn)->first()` | `$couponService->getBySn($sn)` |
| Complex Coupon query (15+ lines) | `$couponService->getAvailableCouponsForPlatform()` |

## Benefits

1. **Separation of Concerns**: Business logic moved to service layer
2. **Reusability**: Services can be used across the entire application
3. **Testability**: Easier to mock services for testing
4. **Maintainability**: Changes centralized in services
5. **Error Handling**: Consistent error handling and logging in services
6. **Type Safety**: Proper type hints and return types
7. **Cleaner Code**: Component focused on UI and workflow
8. **Query Encapsulation**: Complex coupon queries now in service
9. **No Duplicate Instantiation**: Services properly injected once

## Complex Query Encapsulation

The most significant improvement is encapsulating the complex coupon availability query:

### Before (in component - 15+ lines):
```php
$availableCoupons = Coupon::where(function ($query) {
    $query
        ->orWhere('status', CouponStatusEnum::available->value)
        ->orWhere(function ($subQueryReservedForOther) {
            $subQueryReservedForOther->where('status', CouponStatusEnum::reserved->value)
                ->where('reserved_until', '<', now());
        })
        ->orWhere(function ($subQueryReservedForUser) {
            $subQueryReservedForUser->where('status', CouponStatusEnum::reserved->value)
                ->where('reserved_until', '>=', now())
                ->where('user_id', auth()->user()->id);
        });
})
->where('platform_id', $this->idPlatform)
->orderBy('value', 'desc')
->get();
```

### After (in component - 4 lines):
```php
$availableCoupons = $this->couponService->getAvailableCouponsForPlatform(
    $this->idPlatform,
    auth()->user()->id
);
```

## Service Methods Summary

### CouponService (Enhanced):
- `getById(int $id): ?BalanceInjectorCoupon`
- `getUserCouponsPaginated(int $userId, ?string $search, int $perPage)`
- `getCouponsPaginated(?string $search, string $sortField, string $sortDirection, int $perPage)`
- `getMaxAvailableAmount($idPlatform): float`
- `getBySn(string $sn): Coupon`
- `findCouponById(int $id): ?Coupon` ✨ **NEW**
- `updateCoupon(Coupon $coupon, array $data): bool` ✨ **NEW**
- `getAvailableCouponsForPlatform(int $platformId, int $userId): Collection` ✨ **NEW**

### ItemService (Enhanced):
- `getItems(?string $search, int $perPage)`
- `findItem(int $id): ?Item`
- `findItemOrFail(int $id): Item`
- `createItem(array $data): Item`
- `updateItem(int $id, array $data): bool`
- `findByRefAndPlatform(string $ref, int $platformId): ?Item` ✨ **NEW**

### OrderService (Enhanced):
- `getOrdersQuery(int $userId, array $filters): Builder`
- `createOrder(array $data): Order` ✨ **NEW**

### PlatformService (Already Complete):
- `getPlatformById(int $id, array $with): ?Platform`

## Usage Examples

### In Livewire Components:
```php
class MyCouponComponent extends Component
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

    public function purchaseCoupon($platformId, $amount)
    {
        // Get available coupons
        $coupons = $this->couponService->getAvailableCouponsForPlatform(
            $platformId,
            auth()->id()
        );
        
        // Get platform info
        $platform = $this->platformService->getPlatformById($platformId);
        
        // Process purchase...
    }
}
```

### In Controllers:
```php
class CouponController extends Controller
{
    public function __construct(
        protected CouponService $couponService,
        protected OrderService $orderService
    ) {}

    public function purchase(Request $request, $platformId)
    {
        $coupons = $this->couponService->getAvailableCouponsForPlatform(
            $platformId,
            auth()->id()
        );
        
        $order = $this->orderService->createOrder([
            'user_id' => auth()->id(),
            'platform_id' => $platformId,
            'note' => 'Coupon purchase'
        ]);
        
        return redirect()->back()->with('success', 'Coupon purchased!');
    }
}
```

## Testing Benefits

```php
public function test_coupon_purchase()
{
    $mockCouponService = Mockery::mock(CouponService::class);
    $mockOrderService = Mockery::mock(OrderService::class);
    $mockPlatformService = Mockery::mock(PlatformService::class);
    
    $mockCouponService->shouldReceive('getAvailableCouponsForPlatform')
        ->once()
        ->andReturn(collect([new Coupon(['value' => 100])]));
    
    $mockOrderService->shouldReceive('createOrder')
        ->once()
        ->andReturn(new Order(['id' => 1]));
    
    // Test component with mocked services...
}
```

## Statistics

- **Services enhanced:** 3 (CouponService, ItemService, OrderService)
- **Services used:** 4 (+ PlatformService)
- **New service methods added:** 5
- **Direct model queries removed:** 7
- **Model imports removed:** 4
- **Complex query logic encapsulated:** 1 (15+ lines → 4 lines)
- **Duplicate instantiation removed:** 1

## Notes

- All existing functionality preserved
- Transaction logic maintained (DB::beginTransaction/commit/rollback)
- Ordering service integration preserved
- Complex coupon availability logic now reusable
- No breaking changes

## Date
December 31, 2025

