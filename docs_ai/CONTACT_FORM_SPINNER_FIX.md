# Contact Form Spinner Loading Fix

## Issue
The spinner was displayed all the time because the `wire:loading wire:target="saveContactEvent()"` directive was targeting a JavaScript function instead of a Livewire method. Since `saveContactEvent()` is a JavaScript function (not a Livewire method), Livewire couldn't properly detect when to show/hide the loading state.

## Root Cause
```blade
<!-- BEFORE: Incorrect wire:loading target -->
<div wire:loading wire:target="saveContactEvent()" class="d-inline">
    <span class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true"></span>
</div>
```

**Problem:** `wire:loading wire:target` only works with Livewire methods (like `wire:click="save"`), not JavaScript functions (like `onclick="saveContactEvent()"`).

## Solution Implemented

### 1. Removed Incorrect Livewire Loading State
Removed the `wire:loading wire:target="saveContactEvent()"` directive since it was targeting a JavaScript function.

### 2. Added Manual JavaScript Loading Control
Instead, we now manually control the loading state using JavaScript:

```blade
<!-- Button with manual loading control -->
<button type="button" onclick="saveContactEvent()" class="btn btn-success" id="saveContactBtn">
    <i class="ri-save-line align-bottom me-1"></i>
    <span id="btnText">{{ $isEditMode ? __('Update Contact') : __('Save Contact') }}</span>
    <span class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true" id="btnSpinner"></span>
</button>
```

### 3. JavaScript Loading State Management

```javascript
function saveContactEvent() {
    const saveBtn = document.getElementById("saveContactBtn");
    const btnSpinner = document.getElementById("btnSpinner");
    
    // ... validation logic ...
    
    if (validateContact()) {
        // Show loading state
        saveBtn.disabled = true;
        btnSpinner.classList.remove("d-none");
        
        $.ajax({
            // ... AJAX call ...
            success: function (response) {
                if (response.message === "") {
                    // Keep button disabled - Livewire will redirect after save
                    window.Livewire.dispatch('save', [...]);
                } else {
                    // Hide loading state on validation error
                    saveBtn.disabled = false;
                    btnSpinner.classList.add("d-none");
                }
            },
            error: function() {
                // Hide loading state on error
                saveBtn.disabled = false;
                btnSpinner.classList.add("d-none");
            }
        });
    }
}
```

## How It Works

### Normal Flow:
1. User clicks "Save Contact" button
2. JavaScript function `saveContactEvent()` is called
3. Button is disabled and spinner shows
4. Phone validation AJAX call is made
5. If valid, Livewire `save` method is dispatched
6. Button stays disabled (page will redirect after save)

### Error Flow:
1. User clicks "Save Contact" button
2. JavaScript function `saveContactEvent()` is called
3. Button is disabled and spinner shows
4. Phone validation AJAX call is made
5. If error occurs, button is re-enabled and spinner is hidden
6. User can try again

## Key Changes

### Button HTML:
- Added `id="saveContactBtn"` for JavaScript reference
- Added `id="btnText"` wrapper for button text
- Added `id="btnSpinner"` for the spinner element
- Spinner starts with `d-none` class (hidden by default)

### JavaScript:
- Added `const saveBtn = document.getElementById("saveContactBtn")`
- Added `const btnSpinner = document.getElementById("btnSpinner")`
- Show loading: `saveBtn.disabled = true` + `btnSpinner.classList.remove("d-none")`
- Hide loading: `saveBtn.disabled = false` + `btnSpinner.classList.add("d-none")`

## Benefits

1. **Accurate Loading State**: Spinner only shows when actually processing
2. **Better UX**: Button is disabled during processing to prevent double-clicks
3. **Error Recovery**: Button is re-enabled if validation fails
4. **Consistent Behavior**: Works reliably across all browsers

## Technical Notes

### Why Not Use wire:loading?
- `wire:loading` only works with Livewire methods called via `wire:click`, `wire:submit`, etc.
- Our flow uses JavaScript → AJAX → Livewire dispatch
- Manual control is more appropriate for this complex flow

### Spinner Position
- Positioned with `ms-2` (margin-start) after the button text
- Uses Bootstrap's `spinner-border-sm` for proper sizing
- Hidden by default with `d-none`, shown by removing this class

## Files Modified
- `resources/views/livewire/manage-contact.blade.php`

## Date
November 13, 2025

