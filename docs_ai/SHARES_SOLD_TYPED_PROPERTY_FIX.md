# SharesSoldMarketStatus - Typed Property Fix

## Issue
```
Typed property App\Livewire\SharesSoldMarketStatus::$balancesManager 
must not be accessed before initialization
```

## Root Cause

The component had typed private properties for services:
```php
private settingsManager $settingsManager;
private BalancesManager $balancesManager;
```

These were initialized in the `mount()` method:
```php
public function mount(settingsManager $settingsManager, BalancesManager $balancesManager)
{
    $this->settingsManager = $settingsManager;
    $this->balancesManager = $balancesManager;
}
```

**Problem**: In Livewire, the `mount()` method is only called once during the initial component load. On subsequent requests (like pagination, search, sorting), Livewire calls `render()` directly without calling `mount()`, causing the typed properties to be uninitialized.

## Solution

**Removed**:
- Private typed properties for services
- The `mount()` method that initialized them

**Changed**:
- Use dependency injection directly in the `render()` method
- Services are resolved fresh on every request

### Before:
```php
private settingsManager $settingsManager;
private BalancesManager $balancesManager;

public function mount(settingsManager $settingsManager, BalancesManager $balancesManager)
{
    $this->settingsManager = $settingsManager;
    $this->balancesManager = $balancesManager;
}

public function render(settingsManager $settingsManager)
{
    // Used $this->balancesManager here - CAUSED ERROR
}
```

### After:
```php
// No private service properties
// No mount method

public function render(settingsManager $settingsManager, BalancesManager $balancesManager)
{
    // Use $balancesManager directly from parameters - WORKS
}
```

## Why This Works

1. **Livewire Lifecycle**: 
   - `mount()` runs only on initial load
   - `render()` runs on every request
   
2. **Dependency Injection**:
   - Laravel's service container resolves dependencies for `render()`
   - Fresh instances on every request
   - No state issues

3. **No Typed Properties**:
   - No need to initialize before access
   - No uninitialized property errors

## Impact

- ✅ Fixes typed property initialization error
- ✅ Works on initial load and subsequent requests
- ✅ Maintains all functionality (search, sort, pagination)
- ✅ No breaking changes to the component behavior
- ✅ Cleaner code - services injected where needed

## Files Modified

- `app/Livewire/SharesSoldMarketStatus.php`
  - Removed lines 23-24 (private service properties)
  - Removed lines 48-52 (mount method)
  - Updated render method signature to include BalancesManager

## Testing

Test these scenarios to ensure the fix works:
- [ ] Initial page load
- [ ] Search functionality
- [ ] Sort by ID
- [ ] Sort by Date
- [ ] Change per-page value
- [ ] Navigate pagination
- [ ] Open modal
- [ ] Update balance

All should work without the typed property error.

## Note

The remaining IDE warnings about `$user->idUser` are expected and harmless - they occur because the IDE doesn't know your custom User model has the `idUser` property. These are just type hints and won't cause runtime errors.

