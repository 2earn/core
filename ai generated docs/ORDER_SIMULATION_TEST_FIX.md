# Order Simulation Controller Test Fix
**Date:** February 6, 2026

## Problem
The test `test_process_order_still_works` in `OrderSimulationControllerTest` was failing with the error:
```
RuntimeException: Could not load mock App\Services\Orders\Ordering, class already exists
```

## Root Cause
The test was using **Mockery alias mocking** to mock the `Ordering` service class which uses static methods. Alias mocking creates a global mock that persists across tests, causing conflicts when:
1. Tests run in a suite (the alias already exists from previous tests)
2. The class is already loaded in memory

The error occurred because:
- `Mockery::mock('alias:' . Ordering::class)` tries to create a class alias
- When the class already exists, it throws "class already exists" error
- The approach of checking for `Mockery_0_App_Services_Orders_Ordering` was fragile and unreliable

## Solution
**Removed all mocking** and let the tests run with the real `Ordering` service. This is the proper approach because:

1. **Better test coverage**: Tests now verify the actual service behavior, not just controller logic
2. **No mock conflicts**: No more "class already exists" errors
3. **Simpler code**: Removed complex mock setup logic
4. **Database transactions**: The existing `DatabaseTransactions` trait ensures test isolation

## Changes Made

### 1. Removed Mockery Dependencies
**Before:**
```php
use App\Services\Orders\Ordering;
use Mockery;

protected function getOrderingMock()
{
    // Complex logic to handle alias mocking
}

protected function tearDown(): void
{
    Mockery::close();
    parent::tearDown();
}
```

**After:**
```php
// No Mockery imports needed
// No mock helper methods needed
```

### 2. Added User Balance Setup
**Added to `setUp()` method:**
```php
// Create balance record for user
UserCurrentBalanceHorisontal::factory()->create([
    'user_id' => $this->user->idUser,
    'user_id_auto' => $this->user->id,
    'cash_balance' => 1000.00,
    'discount_balance' => 100.00,
    'bfss_balance' => [],
    'chances_balance' => [],
]);
```

**Why needed:**
- `Ordering::simulate()` calls `Balances::getStoredUserBalances()` which requires a `UserCurrentBalanceHorisontal` record
- Without this, tests fail with "Attempt to read property 'cash_balance' on null"

### 3. Removed Mock Expectations from Tests
**Before:**
```php
$mockSimulation = [
    'order' => $order,
    'order_deal' => [],
    'bfssTables' => []
];

$mock = $this->getOrderingMock();
$mock->shouldReceive('simulate')
    ->once()
    ->andReturn($mockSimulation);
$mock->shouldReceive('run')
    ->once()
    ->with($mockSimulation);
```

**After:**
```php
// No mocking - real service runs
$response = $this->postJson($this->baseUrl . '/process', [
    'order_id' => $order->id
]);
```

## Test Results
All 11 tests passing:
```
✓ test_can_simulate_order_successfully
✓ test_simulate_order_fails_without_order_id
✓ test_simulate_order_fails_with_invalid_order_id
✓ test_simulate_order_fails_with_invalid_status
✓ test_can_run_simulation_successfully
✓ test_run_simulation_fails_without_order_id
✓ test_run_simulation_fails_with_invalid_order_id
✓ test_run_simulation_fails_with_invalid_status
✓ test_process_order_still_works ← FIXED
✓ test_simulate_order_fails_without_valid_ip
✓ test_run_simulation_fails_without_valid_ip

Tests:    11 passed (38 assertions)
Duration: 1.65s
```

## Files Modified
- `tests/Feature/Api/Payment/OrderSimulationControllerTest.php`

## Key Learnings
1. **Avoid alias mocking for loaded classes**: When a class is already loaded, alias mocking causes conflicts
2. **Mock at boundaries, not services**: For feature tests, it's often better to test with real services
3. **Use factories properly**: Ensure test data includes all necessary relationships and dependencies
4. **DatabaseTransactions provides isolation**: No need for complex mocking to prevent database side effects

## Alternative Approaches Considered

### 1. Overload Mocking
```php
Mockery::mock('overload:' . Ordering::class)
```
**Rejected**: Doesn't work for already-loaded classes

### 2. Namespace Mocking
Using test doubles in the same namespace
**Rejected**: Too complex, requires restructuring

### 3. Refactor Controller to Use Dependency Injection
Inject `Ordering` service instead of using static methods
**Rejected**: Would require major refactoring of production code for test purposes

### 4. Selected Approach: Remove Mocking ✅
Use real service with proper test data setup
**Benefits:**
- Simple and maintainable
- Better test coverage
- No mock conflicts
- Follows Laravel testing best practices

## Recommendations
1. Consider this pattern for other controller tests that mock static services
2. Ensure all factories create complete object graphs (including related records)
3. Use feature tests to test real behavior, unit tests for isolated logic
4. Reserve mocking for external services (APIs, payment gateways, etc.)

---
**Status:** ✅ Complete and verified

