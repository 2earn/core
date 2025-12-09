# ItemService Aggregation Refactoring - Top-Selling Products

## Overview
Moved the aggregation and formatting logic (`$topProducts = $query...`) from `OrderDetailService` to `ItemService` for better separation of concerns.

## Date
December 9, 2025

## Changes Made

### 1. Updated ItemService ✅
**File**: `app/Services/Items/ItemService.php`

**Added Method**: `aggregateTopSellingItems(Builder $query, int $limit): array`

**Responsibilities**:
- Select item name and sum of quantities
- Group by item ID and name
- Order by sale count descending
- Limit results
- Format output (product_name, sale_count)
- Return array

**Method Signature**:
```php
public function aggregateTopSellingItems(Builder $query, int $limit = 10): array
```

**Logic**:
```php
$topProducts = $query
    ->select('items.name as product_name', DB::raw('SUM(order_details.qty) as sale_count'))
    ->groupBy('items.id', 'items.name')
    ->orderByDesc('sale_count')
    ->limit($limit)
    ->get()
    ->map(function ($item) {
        return [
            'product_name' => $item->product_name,
            'sale_count' => (int) $item->sale_count,
        ];
    });

return $topProducts->toArray();
```

**Added Import**:
```php
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
```

---

### 2. Updated OrderDetailService ✅
**File**: `app/Services/OrderDetailService.php`

**Changes**:
- ✅ Added `ItemService` dependency injection
- ✅ Removed aggregation logic (select, groupBy, orderBy, map)
- ✅ Removed `DB` import (no longer needed)
- ✅ Delegates aggregation to `ItemService`

**Updated Constructor**:
```php
private ItemService $itemService;

public function __construct(ItemService $itemService)
{
    $this->itemService = $itemService;
}
```

**Simplified Logic**:
```php
// Build query with filters
$query = OrderDetail::query()
    ->join('orders', ...)
    ->join('items', ...)
    ->where('orders.status', OrderEnum::Dispatched->value);

// Apply filters (date, platform, deal)
if (!empty($filters['start_date'])) { ... }
if (!empty($filters['end_date'])) { ... }
if (!empty($filters['platform_id'])) { ... }
if (!empty($filters['deal_id'])) { ... }

// Delegate aggregation to ItemService
$topProducts = $this->itemService->aggregateTopSellingItems($query, $limit);

return $topProducts;
```

---

## Architecture Improvements

### ✅ Separation of Concerns

**Before**:
- OrderDetailService: Query building + Aggregation + Formatting

**After**:
- OrderDetailService: Query building + Filter application
- ItemService: Aggregation + Formatting

### ✅ Single Responsibility Principle

Each service now has a clear, focused responsibility:
- **OrderDetailService**: Build queries for order details with filters
- **ItemService**: Aggregate and format item-related data

### ✅ Reusability

The `ItemService::aggregateTopSellingItems()` method can now be used with ANY query builder that has items joined:

```php
// Example: Use in different context
$query = OrderDetail::query()
    ->join('orders', ...)
    ->join('items', ...)
    ->where('some_condition');

$topItems = $itemService->aggregateTopSellingItems($query, 20);
```

### ✅ Testability

- Mock `ItemService` when testing `OrderDetailService`
- Unit test aggregation logic independently
- Test query building separately from aggregation

---

## Responsibility Distribution

### OrderDetailService Responsibilities
1. ✅ Build OrderDetail base query
2. ✅ Join with orders and items tables
3. ✅ Filter by order status (Dispatched)
4. ✅ Apply date range filters
5. ✅ Apply platform filter
6. ✅ Apply deal filter
7. ✅ Delegate aggregation to ItemService
8. ✅ Log operations

### ItemService Responsibilities
1. ✅ Aggregate item quantities
2. ✅ Group by item
3. ✅ Order by sale count
4. ✅ Limit results
5. ✅ Format output structure
6. ✅ Return formatted array

---

## Method Flow

### Call Chain
```
Controller
    ↓
SalesDashboardService (authorization)
    ↓
OrderDetailService (query building + filtering)
    ↓
ItemService (aggregation + formatting)
    ↓
Return: array of products with sale counts
```

### Data Flow
```
1. OrderDetailService builds filtered query
   └─> Query Builder with joins and WHERE clauses
   
2. ItemService receives query builder
   └─> Adds SELECT, GROUP BY, ORDER BY, LIMIT
   └─> Executes query
   └─> Maps results to array format
   └─> Returns formatted array
```

---

## Code Example

### Usage in OrderDetailService
```php
// Build and filter query
$query = OrderDetail::query()
    ->join('orders', 'order_details.order_id', '=', 'orders.id')
    ->join('items', 'order_details.item_id', '=', 'items.id')
    ->where('orders.status', OrderEnum::Dispatched->value)
    ->where('orders.created_at', '>=', $startDate)
    ->where('items.platform_id', $platformId);

// Delegate aggregation to ItemService
$topProducts = $this->itemService->aggregateTopSellingItems($query, 10);

// Returns:
// [
//     ['product_name' => 'Product A', 'sale_count' => 150],
//     ['product_name' => 'Product B', 'sale_count' => 120],
// ]
```

### Direct Usage (Future Use Case)
```php
class AnalyticsService
{
    public function __construct(private ItemService $itemService) {}
    
    public function getCategoryTopSellers($categoryId)
    {
        $query = OrderDetail::query()
            ->join('orders', ...)
            ->join('items', ...)
            ->where('items.category_id', $categoryId)
            ->where('orders.status', OrderEnum::Dispatched->value);
        
        return $this->itemService->aggregateTopSellingItems($query, 5);
    }
}
```

---

## Testing Strategy

### Unit Test: ItemService
```php
public function test_aggregate_top_selling_items()
{
    // Arrange
    $itemService = new ItemService();
    $query = OrderDetail::query()
        ->join('orders', ...)
        ->join('items', ...)
        ->where('orders.status', OrderEnum::Dispatched->value);
    
    // Act
    $result = $itemService->aggregateTopSellingItems($query, 10);
    
    // Assert
    $this->assertIsArray($result);
    $this->assertArrayHasKey('product_name', $result[0]);
    $this->assertArrayHasKey('sale_count', $result[0]);
    $this->assertIsInt($result[0]['sale_count']);
}
```

### Unit Test: OrderDetailService
```php
public function test_get_top_selling_products_delegates_to_item_service()
{
    // Arrange
    $itemService = Mockery::mock(ItemService::class);
    $orderDetailService = new OrderDetailService($itemService);
    
    $itemService->shouldReceive('aggregateTopSellingItems')
        ->once()
        ->andReturn([
            ['product_name' => 'Test', 'sale_count' => 100]
        ]);
    
    // Act
    $result = $orderDetailService->getTopSellingProducts([
        'platform_id' => 5,
        'limit' => 10
    ]);
    
    // Assert
    $this->assertCount(1, $result);
}
```

---

## Benefits Summary

| Aspect | Before | After |
|--------|--------|-------|
| **Coupling** | OrderDetailService knows about aggregation | OrderDetailService delegates to ItemService |
| **Reusability** | Aggregation logic tied to one method | Aggregation can be reused anywhere |
| **Testability** | Must test query + aggregation together | Can test independently |
| **Clarity** | Mixed responsibilities | Clear separation |
| **Maintainability** | Changes affect OrderDetailService | Changes isolated to ItemService |

---

## SQL Query (Unchanged)

The final SQL query remains the same:
```sql
SELECT 
    items.name as product_name,
    SUM(order_details.qty) as sale_count
FROM order_details
JOIN orders ON order_details.order_id = orders.id
JOIN items ON order_details.item_id = items.id
WHERE orders.status = 6  -- Dispatched
    AND orders.created_at >= ?
    AND orders.created_at <= ?
    AND items.platform_id = ?
    AND items.deal_id = ?
GROUP BY items.id, items.name
ORDER BY sale_count DESC
LIMIT ?
```

---

## Backward Compatibility

✅ **100% Backward Compatible**
- API endpoint unchanged
- Controller unchanged
- Request/response format unchanged
- Same query results
- No breaking changes

---

## Files Modified

1. ✅ **`app/Services/Items/ItemService.php`**
   - Added `aggregateTopSellingItems()` method
   - Added DB and Builder imports

2. ✅ **`app/Services/OrderDetailService.php`**
   - Added ItemService dependency
   - Removed aggregation logic
   - Removed DB import
   - Delegates to ItemService

---

## Verification

### Code Quality ✅
```
✅ No syntax errors
✅ No linting errors
✅ All imports correct
✅ Dependency injection working
✅ Type hints in place
```

### Architecture ✅
```
✅ Clear separation of concerns
✅ Single responsibility per service
✅ Reusable aggregation method
✅ Testable components
```

---

## Future Extensions

Now that ItemService has aggregation capabilities, it can be extended with:

1. **Revenue Aggregation**
   ```php
   public function aggregateRevenueByItem(Builder $query, int $limit): array
   ```

2. **Category Aggregation**
   ```php
   public function aggregateByCategory(Builder $query): array
   ```

3. **Trend Analysis**
   ```php
   public function aggregateItemTrends(Builder $query, string $period): array
   ```

---

## Status

✅ **REFACTORING COMPLETE**
✅ **NO ERRORS**
✅ **NO BREAKING CHANGES**
✅ **IMPROVED ARCHITECTURE**
✅ **READY FOR USE**

---

## Key Takeaway

The aggregation logic has been properly extracted to `ItemService`, making it:
- **Reusable** across different services
- **Testable** independently
- **Maintainable** with clear responsibilities
- **Scalable** for future item-related aggregations

This completes the proper layering:
- **Controller** → **Dashboard Service** (authorization) → **OrderDetail Service** (query) → **Item Service** (aggregation)

