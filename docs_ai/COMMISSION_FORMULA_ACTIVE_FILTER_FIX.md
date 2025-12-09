# Commission Formula Active Filter Fix

## Problem
When applying the `active` filter with value `false` or `0`, the API was returning 0 rows instead of the inactive Plan label.

## Root Cause
The issue was caused by two problems in the filter logic:

### Problem 1: Controller Logic
In `CommissionFormulaPartnerController.php`, the original code was:
```php
$active = $request->boolean('active', null);
if (!is_null($active)) {
    $filters['is_active'] = intval($active);
}
```

This converted boolean values to integers (true → 1, false → 0), which was correct for the database.

### Problem 2: Service Logic  
In `CommissionFormulaService.php`, the original code was:
```php
if (isset($filters['is_active'])) {
    $query->where('is_active', $filters['is_active']);
}
```

The problem: `isset($filters['is_active'])` returns `false` when the value is `0` (for inactive formulas), causing the filter to be skipped entirely.

## Solution

### Fix 1: Controller
Changed to use `$request->has('active')` to check if the parameter exists:
```php
// Handle active filter - check if parameter exists
if ($request->has('active')) {
    $filters['is_active'] = $request->boolean('active');
}
```

This ensures:
- The filter is only added when the parameter is explicitly provided
- The boolean value (true/false) is passed directly to the service

### Fix 2: Service
Changed to use `array_key_exists()` instead of `isset()`:
```php
// Apply active filter - ensure proper boolean handling
if (array_key_exists('is_active', $filters)) {
    // Cast to boolean to ensure consistency
    $query->where('is_active', (bool) $filters['is_active']);
}
```

This ensures:
- The filter is applied even when the value is `0` or `false`
- Explicit boolean casting for database compatibility

## Why This Works

### isset() vs array_key_exists()
```php
$filters = ['is_active' => 0];

isset($filters['is_active']);        // Returns: false (WRONG!)
array_key_exists('is_active', $filters);  // Returns: true (CORRECT!)
```

The `isset()` function returns `false` for array values that are `null`, `0`, `false`, or empty string. This caused the filter to be skipped when trying to find inactive formulas.

The `array_key_exists()` function only checks if the key exists in the array, regardless of its value.

## Database Behavior

The `is_active` column is defined as:
- Type: `boolean` (stored as tinyint in MySQL)
- Values: `0` (false) or `1` (true)
- Cast in model: `'is_active' => 'boolean'`

Laravel's query builder handles boolean casting:
```php
// These are all equivalent for MySQL:
->where('is_active', true)    // Becomes: WHERE is_active = 1
->where('is_active', false)   // Becomes: WHERE is_active = 0
->where('is_active', 1)       // Becomes: WHERE is_active = 1
->where('is_active', 0)       // Becomes: WHERE is_active = 0
```

## API Usage

### Get Active Formulas
```bash
GET /api/partner/commission-formulas?active=1
GET /api/partner/commission-formulas?active=true
```

### Get Inactive Formulas
```bash
GET /api/partner/commission-formulas?active=0
GET /api/partner/commission-formulas?active=false
```

### Get All Formulas (No Filter)
```bash
GET /api/partner/commission-formulas
```

## Testing

To verify the fix works:

### Test 1: Direct Database Query
```php
use App\Models\CommissionFormula;

// Should return active formulas
$active = CommissionFormula::where('is_active', true)->count();

// Should return inactive formulas
$inactive = CommissionFormula::where('is_active', false)->count();

echo "Active: {$active}, Inactive: {$inactive}";
```

### Test 2: Service Method
```php
use App\Services\Commission\CommissionFormulaService;

$service = app(CommissionFormulaService::class);

// Test with true
$result1 = $service->getPaginatedFormulas(['is_active' => true]);
echo "Active formulas: {$result1['total']}\n";

// Test with false
$result2 = $service->getPaginatedFormulas(['is_active' => false]);
echo "Inactive formulas: {$result2['total']}\n";

// Test with no filter
$result3 = $service->getPaginatedFormulas([]);
echo "All formulas: {$result3['total']}\n";
```

### Test 3: API Endpoint
```bash
# Test active filter
curl "http://your-domain/api/partner/commission-formulas?active=1"

# Test inactive filter
curl "http://your-domain/api/partner/commission-formulas?active=0"

# Test no filter
curl "http://your-domain/api/partner/commission-formulas"
```

## Files Modified

1. **Controller**: `app/Http/Controllers/Api/partner/CommissionFormulaPartnerController.php`
   - Removed `dd()` debug statement
   - Changed filter check from `!is_null($active)` to `$request->has('active')`
   - Removed `intval()` conversion, pass boolean directly

2. **Service**: `app/Services/Commission/CommissionFormulaService.php`
   - Changed `isset()` to `array_key_exists()` in `getPaginatedFormulas()`
   - Changed `isset()` to `array_key_exists()` in `getCommissionFormulas()`
   - Added explicit `(bool)` casting for consistency

## Prevention

To prevent similar issues in the future:

1. **Always use `array_key_exists()`** when checking for filter presence, especially for boolean or numeric filters
2. **Use `isset()`** only when you want to skip `null`, `0`, `false`, or empty values
3. **Document filter behavior** in method docblocks
4. **Add unit tests** for edge cases (false, 0, null, empty)

## Related Methods

The same fix was applied to both filtering methods:
- `getCommissionFormulas()` - For general filtering
- `getPaginatedFormulas()` - For API pagination

Both now correctly handle:
- `is_active = true` → Active formulas
- `is_active = false` → Inactive formulas
- `is_active` not in filters → All formulas

## Additional Notes

The explicit `(bool)` casting in the service ensures consistency regardless of how the value is passed:
- Integer: `0` → `false`, `1` → `true`
- String: `"0"` → `false`, `"1"` → `true`
- Boolean: `false` → `false`, `true` → `true`

This makes the service robust against different input types from various sources (API, Livewire, CLI, etc.).

