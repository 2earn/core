# Simulation Service Refactoring

## Summary
Extracted simulation comparison logic from `OrderSimulationController` into a dedicated `SimulationService` to improve code maintainability and reusability.

## Changes Made

### 1. Created SimulationService
**File:** `app/Services/SimulationService.php`

Created a new service class with the following features:
- `compareWithLastSimulation(int $orderId, array $currentSimulation): array`
  - Retrieves the last saved simulation for the order
  - Compares current simulation with the last saved one
  - Returns comparison result with match status and details
  - Includes comprehensive logging for debugging

### 2. Updated OrderSimulationController
**File:** `app/Http/Controllers/Api/payment/OrderSimulationController.php`

#### Changes:
- Added `SimulationService` dependency injection via constructor
- Replaced duplicate comparison logic in both `processOrder()` and `runSimulation()` methods with single service call
- Reduced code duplication significantly (removed ~30 lines of duplicate code per method)

#### Before:
```php
// Duplicate comparison logic in both methods
$lastSimulation = SimulationOrder::getLatestForOrder($orderId);
if ($lastSimulation && $lastSimulation->simulation_data) {
    // Compare key fields
    $currentFinalAmount = $simulation['order']->amount_after_discount ?? null;
    $lastFinalAmount = $lastSimulation->simulation_data['order']['amount_after_discount'] ?? null;

    if ($currentFinalAmount !== $lastFinalAmount) {
        // Error handling logic
    }
}
```

#### After:
```php
// Clean service call
$comparisonResult = $this->simulationService->compareWithLastSimulation($orderId, $simulation);
if (!$comparisonResult['matches']) {
    return response()->json([
        'status' => 'Failed',
        'message' => 'Simulation mismatch error. Current simulation differs from last saved simulation.',
        'error_code' => 'SIMULATION_MISMATCH',
        'details' => $comparisonResult['details']
    ], Response::HTTP_CONFLICT);
}
```

## Benefits

1. **Single Responsibility Principle**: Comparison logic is now in a dedicated service
2. **DRY (Don't Repeat Yourself)**: Eliminated duplicate code across multiple methods
3. **Maintainability**: Changes to comparison logic only need to be made in one place
4. **Testability**: Service can be easily unit tested in isolation
5. **Reusability**: Comparison logic can be reused in other controllers/services if needed
6. **Better Logging**: Centralized logging with consistent LOG_PREFIX

## Comparison Logic

The service compares:
- **Final Amount**: `amount_after_discount` field from the order
- If amounts don't match, returns detailed information about the mismatch
- If no previous simulation exists, considers it a match (first simulation scenario)

## Return Structure

```php
[
    'matches' => bool,  // true if simulations match or no previous simulation
    'details' => [      // populated only if matches = false
        'current_final_amount' => float,
        'last_final_amount' => float,
        'last_simulation_date' => string (ISO 8601 format)
    ]
]
```

## Date
February 6, 2026
