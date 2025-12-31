# CashToBfs Livewire Component - SettingService Integration

## Summary
Refactored the `CashToBfs` Livewire component to use `SettingService` instead of direct database queries.

## Changes Made

### File: `app/Livewire/CashToBfs.php`

#### Removed:
- `use Illuminate\Support\Facades\DB;` - No longer needed

#### Added:
- `use App\Services\Settings\SettingService;` - Service import (already present, just cleaned up usage)

#### Updated:
- `mount()` method now properly injects `SettingService`
- Replaced `DB::table('settings')->where("idSETTINGS", "=", "13")->first()` with `$settingService->getDecimalValue(13)`

## Before vs After

### Before:
```php
public function mount($filter, Request $request)
{
    // ...existing code...
    
    $seting = DB::table('settings')->where("idSETTINGS", "=", "13")->first();
    $this->prix_sms = $seting->DecimalValue ?? 1.5;
}
```

### After:
```php
public function mount($filter, Request $request, SettingService $settingService)
{
    // ...existing code...
    
    $this->prix_sms = $settingService->getDecimalValue(13) ?? 1.5;
}
```

## Benefits

1. **Cleaner Code**: One line instead of two
2. **Service Layer**: Uses proper service layer architecture
3. **Type Safety**: Service method has proper type hints
4. **Consistency**: Follows the same pattern as other refactored components
5. **Maintainability**: Changes to setting retrieval logic centralized in service
6. **No Direct DB Access**: Removed direct database query from component

## Notes

- The setting ID `13` appears to be related to SMS pricing
- Component already had `SettingService` in imports but wasn't using it optimally
- Removed unused `DB` facade import
- Pre-existing warning about dynamic property `$showCanceled` remains (not related to this change)

## Date
December 31, 2025

