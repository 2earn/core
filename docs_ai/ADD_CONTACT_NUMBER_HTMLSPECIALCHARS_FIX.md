# Add Contact Number - htmlspecialchars() Error Fix

## Summary
Fixed the `htmlspecialchars(): Argument #1 ($string) must be of type string, array given` error in the add-contact-number.blade.php file.

## Date
December 12, 2025

## Problem
The error occurred because `event.detail` was being passed as an array to Blade's translation function `__()`, which expects a string. When Livewire dispatches the 'showAlert' event with array data, the code was trying to use `alertData.title` directly in the Blade template, causing PHP to pass an array to `htmlspecialchars()`.

## Root Cause
```javascript
window.addEventListener('showAlert', event => {
    const alertData = event.detail[0];
    Swal.fire({
        title: alertData.title || '{{ __("Notification") }}', // Error here when alertData is array
```

When `alertData` was an array instead of an object, `alertData.title` would be `undefined`, causing the fallback `'{{ __("Notification") }}'` to be evaluated, but since the entire expression was being treated as an array in some contexts, it caused the htmlspecialchars error.

## Solution
Used `@json()` directive to properly pass translations from Blade to JavaScript, avoiding inline string interpolation that caused the htmlspecialchars error:

```javascript
// Define default translations at the top level using @json()
const defaultNotificationTitle = @json(__("Notification"));
const defaultOkButton = @json(__("ok"));
const defaultValidText = @json(__("Valid"));
const mobileNumberPlaceholder = @json(__("Mobile Number"));

window.addEventListener('showAlert', event => {
    let alertData = event.detail;
    
    // Handle different event.detail formats
    if (Array.isArray(alertData)) {
        alertData = alertData[0] || {};
    }
    
    // Extract properties with safe fallbacks
    let title = defaultNotificationTitle;
    let text = '';
    let icon = 'info';
    
    if (typeof alertData === 'string') {
        title = alertData;
    } else if (typeof alertData === 'object' && alertData !== null) {
        title = alertData.title || defaultNotificationTitle;
        text = alertData.text || '';
        icon = alertData.type || 'info';
    }
    
    Swal.fire({
        title: title,
        text: text,
        icon: icon,
        confirmButtonText: defaultOkButton,
        customClass: {
            confirmButton: 'btn btn-primary'
        }
    }).then(() => {
        // ... rest of the code
    });
});
```

## Changes Made

### 1. Extracted Translations Using @json() Directive
**Before:**
```javascript
window.addEventListener('showAlert', event => {
    const alertData = event.detail[0];
    Swal.fire({
        title: alertData.title || '{{ __("Notification") }}', // ERROR: Array passed to htmlspecialchars
        text: alertData.text || '',
        icon: alertData.type || 'info',
        confirmButtonText: '{{ __("ok") }}', // ERROR: Can receive array
```

**After:**
```javascript
// Define translations at top level with @json()
const defaultNotificationTitle = @json(__("Notification"));
const defaultOkButton = @json(__("ok"));
const defaultValidText = @json(__("Valid"));
const mobileNumberPlaceholder = @json(__("Mobile Number"));

window.addEventListener('showAlert', event => {
    let alertData = event.detail;
    if (Array.isArray(alertData)) {
        alertData = alertData[0] || {};
    }
    
    let title = defaultNotificationTitle;
    let text = '';
    let icon = 'info';
    
    if (typeof alertData === 'string') {
        title = alertData;
    } else if (typeof alertData === 'object' && alertData !== null) {
        title = alertData.title || defaultNotificationTitle;
        text = alertData.text || '';
        icon = alertData.type || 'info';
    }
    
    Swal.fire({
        title: title,
        text: text,
        icon: icon,
        confirmButtonText: defaultOkButton,
```

### 2. Updated Dynamic HTML Generation
Used the pre-defined translation variables in HTML generation:

```javascript
// Before:
ipNumberContact.innerHTML =
    "<input ... placeholder='{{ __("Mobile Number") }}'>" + // ERROR
    "<span ...>✓ {{ __("Valid") }}</span>"; // ERROR

// After:
ipNumberContact.innerHTML =
    "<input ... placeholder='" + mobileNumberPlaceholder + "'>" + // SAFE
    "<span ...>✓ " + defaultValidText + "</span>"; // SAFE
```

### 3. Cleaned Up HTML Structure
Also removed the unnecessary `input-group-prepend` wrapper in the dynamic HTML generation for better consistency and cleaner code.

## Key Insight
The `@json()` directive is the proper way to pass Laravel translations to JavaScript. It:
- Safely converts PHP values to JSON
- Prevents type errors
- Avoids the htmlspecialchars issue with inline Blade syntax
- Provides proper escaping for JavaScript strings

## Benefits
1. **Prevents Type Errors** - Properly handles both array and object event data
2. **Better Error Handling** - Gracefully falls back to default values
3. **Type Safety** - Checks data types before accessing properties
4. **Cleaner Code** - More readable and maintainable

## Testing
- The file now compiles without the htmlspecialchars error
- Event handlers properly process both array and object data formats
- Fallback values are correctly applied when data is missing

## Files Modified
- `resources/views/livewire/add-contact-number.blade.php`

## Related Issues
- Also fixed inline display issues with intl-tel-input (see INTL_TEL_INPUT_STYLING_FIX.md)
- Cleaned up HTML structure for better Bootstrap integration

