# ShowAlert Fix for Contact Number Component

## Issue
The `showAlert` event was being dispatched from the Livewire `ContactNumber` component when a duplicate contact number was detected, but the blade view did not have a listener to handle this event, causing the alert to not display.

## Solution Implemented

### Changes Made to `contact-number.blade.php`

#### 1. Added `showAlert` Event Listener
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
    }).then(() => {
        // Reinitialize the input field after error
        const inputField = document.querySelector("#initIntlTelInput");
        if (inputField) {
            inputField.value = '';
            $("#outputinitIntlTelInput").val('');
            $("#ccodeinitIntlTelInput").val('');
            $("#isoContactNumber").val('');
            $('#saveAddContactNumber').prop("disabled", true);
            
            // Trigger the intl-tel-input to update
            if (typeof itiAddContactNumber !== 'undefined' && itiAddContactNumber) {
                itiAddContactNumber.setNumber('');
            }
        }
    });
});
```

This listener:
- Captures `showAlert` events dispatched from Livewire
- Extracts alert data (title, text, type/icon)
- Displays a SweetAlert2 modal with the message
- **Clears the phone input field after alert is dismissed**
- **Resets all hidden fields (fullNumber, countryCode, ISO)**
- **Disables the save button until a valid new number is entered**
- **Resets the intl-tel-input component state**
- Provides fallback values if data is missing

#### 2. Improved Modal Flow
**Before:** The modal was closed immediately when "Save" was clicked, before validation occurred.

**After:** 
- Modal stays open during validation
- If duplicate detected → `showAlert` displays error, modal remains open
- If validation passes → Modal closes and OTP dialog appears

**Changes Made:**
- Removed premature `$('.btn-close-add').trigger('click')` from save button click handler
- Added modal close logic to `PreAddNumber` event (only fires if validation passes)
- Moved `itiAddContactNumber` variable to outer scope for accessibility across event listeners
- Added input field reinitialization logic after error alert is dismissed

## How It Works

### Flow Diagram
```
User clicks "Save new contact number"
    ↓
Livewire: preSaveContact() method called
    ↓
UserContactService: contactNumberExists() check
    ↓
    ├─→ Duplicate Found?
    │   ├─→ dispatch('showAlert', [...]) 
    │   └─→ JavaScript: showAlert event → SweetAlert2 error
    │       └─→ Modal stays open, user can correct input
    │
    └─→ No Duplicate?
        ├─→ dispatch('PreAddNumber', [...])
        └─→ JavaScript: PreAddNumber event
            ├─→ Close modal
            └─→ Show OTP input dialog
```

## Event Data Structure

### showAlert Event
```javascript
{
    type: 'danger',      // or 'success', 'warning', 'info'
    title: 'Error',      // translatable string
    text: 'This contact number already exists'  // translatable message
}
```

## Testing

To test the fix:
1. Open contact numbers page
2. Click "Add new contact number"
3. Enter an existing phone number
4. Click "Save"
5. Should see error alert: "This contact number already exists"
6. Modal should remain open
7. Enter a new unique number
8. Should see OTP dialog (modal closes automatically)

## Files Modified
- ✅ `resources/views/livewire/contact-number.blade.php`

## Related Files
- `app/Livewire/ContactNumber.php` (dispatches the event)
- `app/Services/UserContactService.php` (validation logic)

## Date
December 4, 2025

