# User Contact Service Refactoring

## Overview
Refactored the `ContactNumber` Livewire component to use a dedicated `UserContactService` instead of direct database queries and model calls. This improves code organization, reusability, and testability.

## Changes Made

### 1. Created New Service: `UserContactService`
**File:** `app/Services/UserContactService.php`

The service provides the following methods:

#### `getByUserIdWithSearch(int $userId, ?string $search = null): Collection`
- Retrieves user contact numbers by user ID
- Supports optional search filtering by mobile number or ID
- Replaces the direct DB query in the `render()` method

#### `setActiveNumber(int $userId, int $contactId): bool`
- Deactivates all contact numbers for a user
- Activates the specified contact number
- Replaces raw DB update queries in the `setActiveNumber()` method

#### `deleteContact(int $contactId): bool`
- Validates the contact number exists
- Prevents deletion of active numbers
- Prevents deletion of ID numbers (isID = 1)
- Provides proper exception handling with meaningful error messages
- Replaces the manual validation and deletion logic in the `deleteContact()` method

#### `contactNumberExists(int $userId, string $fullNumber): bool`
- Checks if a contact number already exists for a user by full number
- Used in `preSaveContact()` to prevent duplicate contact numbers
- Returns true if the number exists, false otherwise

#### `contactNumberExistsByMobile(int $userId, string $mobile, string $iso): bool`
- Checks if a contact number exists by mobile and country ISO code
- Alternative check method for validation
- Returns true if the number exists, false otherwise

#### `createContactNumber(int $userId, string $mobile, int $countryId, string $iso, string $fullNumber): UserContactNumber`
- Creates a new contact number for a user
- Sets proper defaults (active = 0, isID = 0)
- Available for future use when refactoring `saveContactNumber()` method

### 2. Updated `ContactNumber` Livewire Component
**File:** `app/Livewire/ContactNumber.php`

#### Added Import
```php
use App\Services\UserContactService;
```

#### Updated Methods

**`deleteContact()`**
- Added `UserContactService $userContactService` parameter
- Replaced manual validation and deletion with service call
- Improved exception handling with try-catch block

**`setActiveNumber()`**
- Added `UserContactService $userContactService` parameter
- Replaced raw DB updates with service method call
- Cleaner, more maintainable code

**`preSaveContact()`**
- Added `UserContactService $userContactService` parameter
- Added validation check to prevent duplicate contact numbers
- Shows alert to user if contact number already exists
- Prevents sending OTP for duplicate numbers

**`render()`**
- Added `UserContactService $userContactService` parameter
- Replaced complex DB query with service method call
- Simplified method logic significantly

### 3. Updated Contact Number Blade View
**File:** `resources/views/livewire/contact-number.blade.php`

#### Added JavaScript Event Listeners

**`showAlert` Event Listener**
- Listens for `showAlert` events dispatched from Livewire
- Displays SweetAlert2 modal with error/success messages
- Handles duplicate contact number validation errors
- Customizable title, text, and icon type

**Modal Flow Improvements**
- Modal now stays open during validation
- Only closes when validation passes and OTP is being sent
- Better user experience with immediate error feedback

```javascript
window.addEventListener('showAlert', event => {
    const alertData = event.detail[0];
    Swal.fire({
        title: alertData.title || '{{ __("Notification") }}',
        text: alertData.text || '',
        icon: alertData.type || 'info',
        confirmButtonText: '{{ __("ok") }}',
        customClass: {
            confirmButton: 'btn btn-primary'
        }
    });
});
```

## Benefits

1. **Separation of Concerns**: Business logic moved to dedicated service
2. **Reusability**: Service methods can be used across the application
3. **Testability**: Service can be unit tested independently
4. **Maintainability**: Easier to modify and extend functionality
5. **Consistency**: Follows Laravel service pattern used in other parts of the application
6. **Error Handling**: Centralized and improved exception handling
7. **Validation**: Prevents duplicate contact numbers before OTP generation

## Future Improvements

Consider refactoring the `saveContactNumber()` method to use the `createContactNumber()` service method for consistency.

## Testing Recommendations

1. Test contact number listing with search functionality
2. Test setting active number
3. Test deleting contact numbers (various scenarios: active, inactive, isID)
4. **Test adding duplicate contact numbers to verify validation works**
5. **Test adding new unique contact numbers**
6. Verify error handling and validation messages
7. Test that OTP is not sent for duplicate numbers

## Files Modified
- ✅ Created: `app/Services/UserContactService.php`
- ✅ Updated: `app/Livewire/ContactNumber.php`
- ✅ Updated: `resources/views/livewire/contact-number.blade.php`

## Date
December 4, 2025

