# Setting Service Migration - Complete

## Overview
Successfully migrated all `Setting::Where()` and `Setting::WhereIn()` calls to use the new `SettingService` abstraction layer.

## What Was Done

### 1. Created SettingService
**File**: `app/Services/Settings/SettingService.php`

New service class that provides:
- `getIntegerValues(array $settingIds)` - Fetch multiple integer values by IDs
- `getDecimalValues(array $settingIds)` - Fetch multiple decimal values by IDs
- `getIntegerValue(int|string $settingId)` - Fetch single integer value
- `getDecimalValue(int|string $settingId)` - Fetch single decimal value
- `getIntegerByParameterName(string $parameterName)` - Fetch integer by parameter name
- `getDecimalByParameterName(string $parameterName)` - Fetch decimal by parameter name
- `getStringByParameterName(string $parameterName)` - Fetch string by parameter name
- `getSettingByParameterName(string $parameterName)` - Fetch full Setting object by parameter name
- `getById(int|string $settingId)` - Get single Setting object by ID
- `getByIds(array $settingIds)` - Get multiple Setting objects by IDs

### 2. Updated Helper Functions
**File**: `app/Helpers/helpers.php`

Added new helper:
- `getSettingService()` - Returns SettingService instance from container

Updated existing helpers to use SettingService:
- `getSettingParam($paramName)`
- `getSettingDecimalParam($paramName, $default)`
- `getSettingIntegerParam($paramName, $default)`
- `getSettingStringParam($paramName, $default)`

Replaced direct Setting queries in functions:
- `getUserByContact()` - Setting ID 25
- `getSwitchBlock()` - Setting ID 29
- `getHalfActionValue()` - Setting IDs 16, 17, 18
- `getGiftedActions()` - Setting IDs 20, 18, 21
- `actualActionValue()` - Setting IDs 16, 17, 18
- `usdToSar()` - Setting ID 30
- `checkUserBalancesInReservation()` - Setting ID 32

### 3. Updated Livewire Components

#### Home.php
- Injected `SettingService` in mount method
- Replaced direct Setting queries with service calls for VIP/Flash sale settings (IDs: 20, 18, 21)

#### BuyShares.php
- Added `SettingService` to imports
- Injected service in render method
- Replaced Setting queries for VIP calculations

#### Trading.php
- Added `SettingService` to imports
- Injected service in render method
- Replaced Setting queries for VIP calculations

### 4. Updated Services

#### Sponsorship.php
**File**: `app/Services/Sponsorship/Sponsorship.php`
- Injected `SettingService` in constructor
- Replaced `Setting::WhereIn()` with `settingService->getIntegerValues()`
- Updated to use associative array access for setting values (24, 25, 26, 27, 28, 31, 32)

### 5. Updated Controllers

#### SharesController.php
**File**: `app/Http/Controllers/SharesController.php`
- Added `SettingService` to constructor injection
- Updated `getActionValues()` method to use service (Settings: 16, 17, 18)

#### ApiController.php
**File**: `app/Http/Controllers/ApiController.php`
- Added `SettingService` to constructor injection
- Updated `buyAction()` method - Setting ID 19
- Updated callback method - Setting ID 30

## Benefits

1. **Decoupling**: Code no longer directly depends on the Setting Eloquent model
2. **Centralization**: All Setting access logic is in one place
3. **Type Safety**: Methods return properly typed values (int, float, string)
4. **Testability**: Easier to mock SettingService in tests
5. **Consistency**: Uniform API across the application
6. **Maintainability**: Changes to Setting access logic only need updates in one place

## Migration Pattern

### Before:
```php
$setting = Setting::WhereIn('idSETTINGS', ['16', '17', '18'])
    ->orderBy('idSETTINGS')
    ->pluck('IntegerValue');
$initial_value = $setting[0];
$final_value = $setting[1];
$total_actions = $setting[2];
```

### After:
```php
$settingValues = $settingService->getIntegerValues(['16', '17', '18']);
$initial_value = $settingValues['16'];
$final_value = $settingValues['17'];
$total_actions = $settingValues['18'];
```

### Before (Single Value):
```php
$hours = Setting::Where('idSETTINGS', '25')
    ->orderBy('idSETTINGS')
    ->pluck('IntegerValue')
    ->first();
```

### After (Single Value):
```php
$hours = getSettingService()->getIntegerValue('25');
// or in injected context:
$hours = $this->settingService->getIntegerValue('25');
```

## Testing

All files passed PHP syntax validation:
- ✅ app/Helpers/helpers.php
- ✅ app/Livewire/Home.php
- ✅ app/Livewire/BuyShares.php
- ✅ app/Livewire/Trading.php
- ✅ app/Services/Sponsorship/Sponsorship.php
- ✅ app/Http/Controllers/ApiController.php
- ✅ app/Http/Controllers/SharesController.php

Routes loaded successfully without errors.

## Files Modified

1. `app/Services/Settings/SettingService.php` (NEW)
2. `app/Helpers/helpers.php`
3. `app/Livewire/Home.php`
4. `app/Livewire/BuyShares.php`
5. `app/Livewire/Trading.php`
6. `app/Services/Sponsorship/Sponsorship.php`
7. `app/Http/Controllers/SharesController.php`
8. `app/Http/Controllers/ApiController.php`

## Next Steps (Optional)

1. Add unit tests for SettingService
2. Consider caching frequently accessed settings
3. Add setting validation/constraints
4. Create enum for common setting IDs to avoid magic numbers
5. Add documentation for each setting ID and its purpose

## Notes

- The service maintains backward compatibility through helper functions
- Existing code using helper functions continues to work unchanged
- Direct Setting model queries are now replaced with service calls
- No breaking changes to public APIs

