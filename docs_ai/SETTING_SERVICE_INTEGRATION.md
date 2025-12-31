# Setting Service Integration - Complete

## Summary
Successfully refactored the `Balances` class to use `SettingService` instead of direct Eloquent queries for updating settings.

## Changes Made

### 1. Enhanced SettingService
**File:** `app/Services/Settings/SettingService.php`

Added new update methods:

- `updateByParameterName($parameterName, $data)` - Generic update method
- `updateIntegerByParameterName($parameterName, $value)` - Update integer value
- `updateDecimalByParameterName($parameterName, $value)` - Update decimal value
- `updateStringByParameterName($parameterName, $value)` - Update string value

### 2. Updated Balances Class
**File:** `app/Services/Balances/Balances.php`

#### Added:
- Import for `SettingService`
- Static property `$settingService` to hold service instance
- Static method `getSettingService()` to lazily initialize setting service

#### Refactored Methods:
- `getBalanceCompter()` - Now uses `SettingService::updateIntegerByParameterName()` instead of direct Eloquent query

## Before vs After

### Before:
```php
public function getBalanceCompter()
{
    $value = getSettingIntegerParam('BALANCES_COMPTER', 1);

    $value++;
    $newValue = (string)$value;
    Setting::where('ParameterName', 'BALANCES_COMPTER')->update(['IntegerValue' => $newValue]);
    return substr((string)pow(10, 7 - strlen($newValue)), 1) . $newValue;
}
```

### After:
```php
public function getBalanceCompter()
{
    $value = getSettingIntegerParam('BALANCES_COMPTER', 1);

    $value++;
    $newValue = (string)$value;
    self::getSettingService()->updateIntegerByParameterName('BALANCES_COMPTER', $newValue);
    return substr((string)pow(10, 7 - strlen($newValue)), 1) . $newValue;
}
```

## Benefits

1. **Separation of Concerns**: Setting operations are now encapsulated in a dedicated service
2. **Reusability**: SettingService can be used throughout the application
3. **Consistency**: All setting operations follow the same pattern
4. **Testability**: Easier to mock and test setting operations
5. **Maintainability**: Changes to setting logic are centralized
6. **Type Safety**: Service methods have proper type hints
7. **Return Values**: Update methods return the number of rows affected

## SettingService API

### Read Operations:
- `getIntegerValues(array $settingIds): array`
- `getDecimalValues(array $settingIds): array`
- `getIntegerValue(int|string $settingId): ?int`
- `getDecimalValue(int|string $settingId): ?float`
- `getSettingByParameterName(string $parameterName): ?Setting`
- `getIntegerByParameterName(string $parameterName): ?int`
- `getDecimalByParameterName(string $parameterName): ?float`
- `getStringByParameterName(string $parameterName): ?string`
- `getById(int|string $settingId): ?Setting`
- `getByIds(array $settingIds)`

### Write Operations (New):
- ✅ `updateByParameterName(string $parameterName, array $data): int`
- ✅ `updateIntegerByParameterName(string $parameterName, int|string $value): int`
- ✅ `updateDecimalByParameterName(string $parameterName, float|string $value): int`
- ✅ `updateStringByParameterName(string $parameterName, string $value): int`

## Usage Examples

```php
// Update integer value
$settingService = app(SettingService::class);
$rowsUpdated = $settingService->updateIntegerByParameterName('BALANCES_COMPTER', 100);

// Update decimal value
$rowsUpdated = $settingService->updateDecimalByParameterName('TAX_RATE', 15.5);

// Update string value
$rowsUpdated = $settingService->updateStringByParameterName('SITE_NAME', 'My Site');

// Custom update
$rowsUpdated = $settingService->updateByParameterName('SETTING_NAME', [
    'IntegerValue' => 100,
    'StringValue' => 'description',
    'updated_by' => auth()->id()
]);
```

## Notes

- All existing code continues to work without changes
- Service is lazily initialized only when needed
- No breaking changes to the public API
- All update methods return the number of rows updated

## Date
December 31, 2025

