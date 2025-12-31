# UserNotificationSettingsService Implementation - Complete

## Summary
Successfully created `UserNotificationSettingsService` and refactored the `ChangePassword` Livewire component to use it instead of direct `UserNotificationSettings` model queries.

## Changes Made

### 1. Created UserNotificationSettingsService
**File:** `app/Services/UserNotificationSettingsService.php`

New service class for managing user notification settings with the following methods:

#### Methods:
- `updateNotificationSetting(string $idUser, int $notificationId, $value): int`
  - Updates a specific notification setting for a user
  - Returns number of rows updated
  - Replaces direct `UserNotificationSettings::where()->update()` calls

- `getNotificationSetting(string $idUser, int $notificationId): ?UserNotificationSettings`
  - Retrieves a specific notification setting for a user
  - Returns model instance or null

- `getUserNotificationSettings(string $idUser)`
  - Gets all notification settings for a user
  - Returns collection of settings

- `upsertNotificationSetting(string $idUser, int $notificationId, $value): UserNotificationSettings`
  - Creates or updates a notification setting
  - Returns the created/updated model
  - Uses Laravel's updateOrCreate

- `deleteUserNotificationSettings(string $idUser): int`
  - Deletes all notification settings for a user
  - Returns number of rows deleted
  - Can be used in NotificationRepository

### 2. Refactored ChangePassword Component
**File:** `app/Livewire/ChangePassword.php`

#### Changes:
- Removed direct `UserNotificationSettings` model import
- Added `UserNotificationSettingsService` import
- Added `$notificationSettingsService` property
- Updated `boot()` method to inject both services
- Refactored `ParamSendChanged()` method to use service

#### Methods Updated:
- `ParamSendChanged()` - Now uses service instead of direct model query

## Before vs After

### Before:
```php
use Core\Models\UserNotificationSettings;

public function ParamSendChanged(settingsManager $settingManager)
{
    $authUser = $settingManager->getAuthUser();
    if (!$authUser) return;

    UserNotificationSettings::where('idUser', $authUser->idUser)
        ->where('idNotification', NotificationSettingEnum::change_pwd_sms->value)
        ->update(['value' => $this->sendPassSMS]);
}
```

### After:
```php
use App\Services\UserNotificationSettingsService;

protected UserNotificationSettingsService $notificationSettingsService;

public function boot(UserService $userService, UserNotificationSettingsService $notificationSettingsService)
{
    $this->userService = $userService;
    $this->notificationSettingsService = $notificationSettingsService;
}

public function ParamSendChanged(settingsManager $settingManager)
{
    $authUser = $settingManager->getAuthUser();
    if (!$authUser) return;

    $this->notificationSettingsService->updateNotificationSetting(
        $authUser->idUser,
        NotificationSettingEnum::change_pwd_sms->value,
        $this->sendPassSMS
    );
}
```

## Benefits

1. **Separation of Concerns**: Notification settings logic centralized in service
2. **Reusability**: Service can be used across the application
3. **Testability**: Easier to mock service for testing
4. **Maintainability**: Changes to notification settings operations centralized
5. **Type Safety**: Proper type hints and return types
6. **Consistency**: Follows same service pattern as other refactored components
7. **Flexibility**: Multiple methods for different use cases (get, update, upsert, delete)

## Usage Examples

### In Livewire Components:
```php
class MyComponent extends Component
{
    protected UserNotificationSettingsService $notificationSettingsService;

    public function boot(UserNotificationSettingsService $notificationSettingsService)
    {
        $this->notificationSettingsService = $notificationSettingsService;
    }

    public function toggleNotification($idUser, $notificationId, $enabled)
    {
        // Update setting
        $this->notificationSettingsService->updateNotificationSetting(
            $idUser, 
            $notificationId, 
            $enabled
        );
    }
    
    public function getNotificationStatus($idUser, $notificationId)
    {
        // Get specific setting
        $setting = $this->notificationSettingsService->getNotificationSetting(
            $idUser, 
            $notificationId
        );
        return $setting?->value ?? false;
    }
}
```

### In Controllers:
```php
class NotificationController extends Controller
{
    public function __construct(
        protected UserNotificationSettingsService $notificationSettingsService
    ) {}

    public function update(Request $request)
    {
        $this->notificationSettingsService->upsertNotificationSetting(
            $request->user()->idUser,
            $request->notification_id,
            $request->enabled
        );
        
        return response()->json(['success' => true]);
    }
    
    public function index(Request $request)
    {
        $settings = $this->notificationSettingsService
            ->getUserNotificationSettings($request->user()->idUser);
            
        return view('notifications.settings', compact('settings'));
    }
}
```

### In Services:
```php
class UserService
{
    public function deleteUser(string $idUser)
    {
        $notificationService = app(UserNotificationSettingsService::class);
        
        // Clean up notification settings
        $notificationService->deleteUserNotificationSettings($idUser);
        
        // Delete user...
    }
}
```

## Service API Reference

### UserNotificationSettingsService Methods:

| Method | Parameters | Returns | Description |
|--------|-----------|---------|-------------|
| `updateNotificationSetting` | `$idUser`, `$notificationId`, `$value` | `int` | Update specific setting |
| `getNotificationSetting` | `$idUser`, `$notificationId` | `?Model` | Get specific setting |
| `getUserNotificationSettings` | `$idUser` | `Collection` | Get all user settings |
| `upsertNotificationSetting` | `$idUser`, `$notificationId`, `$value` | `Model` | Create or update setting |
| `deleteUserNotificationSettings` | `$idUser` | `int` | Delete all user settings |

## Potential Future Enhancements

1. Add bulk update method for multiple notification settings
2. Add validation for notification IDs
3. Add caching layer for frequently accessed settings
4. Add event dispatching when settings change
5. Add notification setting groups/categories
6. Add default settings initialization method
7. Add method to reset settings to defaults
8. Integration with NotificationRepository

## Related Files That Could Use This Service

Based on grep search results:
- `app/DAL/NotificationRepository.php` - Line 24: `UserNotificationSettings::where('idUser', $idUser)->delete();`
  - Could use `$this->notificationSettingsService->deleteUserNotificationSettings($idUser)`

## Notes

- Service properly injected via `boot()` method in Livewire component
- All existing functionality preserved
- No breaking changes to component API
- Pre-existing warnings about missing return types remain (unrelated to refactoring)
- Model import removed as it's now encapsulated in service

## Testing

Example test for the service:

```php
public function test_update_notification_setting()
{
    $service = app(UserNotificationSettingsService::class);
    
    $rowsUpdated = $service->updateNotificationSetting(
        'USER123',
        NotificationSettingEnum::change_pwd_sms->value,
        true
    );
    
    $this->assertEquals(1, $rowsUpdated);
    
    $setting = $service->getNotificationSetting(
        'USER123',
        NotificationSettingEnum::change_pwd_sms->value
    );
    
    $this->assertTrue($setting->value);
}
```

## Date
December 31, 2025

