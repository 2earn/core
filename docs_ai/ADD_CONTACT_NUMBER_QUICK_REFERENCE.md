# Add Contact Number Component - Quick Reference

## Component Structure

### ContactNumber Component
**Purpose:** Display and manage existing contact numbers

**Location:** 
- PHP: `app/Livewire/ContactNumber.php`
- View: `resources/views/livewire/contact-number.blade.php`

**Responsibilities:**
- List all contact numbers
- Search/filter contact numbers
- Set active number
- Delete contact numbers

**Methods:**
- `deleteContact($id)` - Delete a contact number
- `setActiveNumber($checked, $id)` - Set number as active
- `render()` - Display the contact numbers table

**Events Listening:**
- `setActiveNumber`
- `deleteContact`

---

### AddContactNumber Component
**Purpose:** Add new contact numbers

**Location:**
- PHP: `app/Livewire/AddContactNumber.php`
- View: `resources/views/livewire/add-contact-number.blade.php`

**Responsibilities:**
- Show "Add Contact" modal
- Validate phone number input
- Check for duplicates
- Generate and send OTP
- Verify OTP and save number

**Methods:**
- `preSaveContact($fullNumber, $isoP, $mobile)` - Validate and send OTP
- `saveContactNumber($code, $iso, $mobile, $fullNumber)` - Verify OTP and save

**Events Listening:**
- `preSaveContact`
- `saveContactNumber`

**Events Dispatching:**
- `showAlert` - Display error/success alerts
- `PreAddNumber` - Show OTP verification dialog

---

## Usage

### Display Contact Numbers List
```blade
@livewire('contact-number')
```

### Include Add Contact Modal
```blade
@livewire('add-contact-number')
```

### Trigger Add Modal
```html
<button data-bs-toggle="modal" data-bs-target="#AddContactNumberModel">
    Add Contact Number
</button>
```

---

## Services Used

### UserContactService
Located: `app/Services/UserContactService.php`

**Methods Used:**
- `getByUserIdWithSearch($userId, $search)` - Get contact numbers with search
- `setActiveNumber($userId, $contactId)` - Set active number
- `deleteContact($contactId)` - Delete contact number
- `contactNumberExists($userId, $fullNumber)` - Check for duplicates

---

## JavaScript Events

### showAlert Event
**Dispatched:** When validation fails (e.g., duplicate number)
**Data:**
```javascript
{
    type: 'danger',    // 'success', 'warning', 'info', 'danger'
    title: 'Error',
    text: 'Error message'
}
```

### PreAddNumber Event
**Dispatched:** After OTP is sent successfully
**Data:**
```javascript
{
    type: 'warning',
    title: 'Opt',
    FullNumber: '00123456789',
    FullNumberNew: '00987654321',
    userMail: 'user@example.com',
    isoP: 'us',
    mobile: '1234567890',
    msgSend: 'We will send...'
}
```

---

## Flow Diagrams

### Add Contact Flow
```
1. User clicks "Add Contact" button
2. Modal opens (AddContactNumber component)
3. User enters phone number
4. intl-tel-input validates format
5. User clicks "Save"
6. JavaScript dispatches 'preSaveContact' event
7. AddContactNumber::preSaveContact() validates
8. UserContactService checks for duplicates
9a. If duplicate → dispatch('showAlert') → show error → reset field
9b. If valid → Generate OTP → Send SMS/Email → dispatch('PreAddNumber')
10. OTP dialog appears (SweetAlert)
11. User enters OTP
12. JavaScript dispatches 'saveContactNumber' event
13. AddContactNumber::saveContactNumber() verifies OTP
14. Create contact number record
15. Redirect to contact list with success message
```

### Delete Contact Flow
```
1. User clicks "Delete" button
2. SweetAlert confirmation appears
3. User confirms
4. JavaScript dispatches 'deleteContact' event
5. ContactNumber::deleteContact() called
6. UserContactService validates and deletes
7. Redirect with success/error message
```

### Set Active Number Flow
```
1. User clicks "Active" button or toggle
2. SweetAlert confirmation appears
3. User confirms
4. JavaScript dispatches 'setActiveNumber' event
5. ContactNumber::setActiveNumber() called
6. UserContactService deactivates all, activates selected
7. Redirect with success message
```

---

## Key Features

✅ **Duplicate Detection** - Prevents adding existing numbers
✅ **OTP Verification** - Secure number verification via SMS/Email
✅ **Input Validation** - Real-time phone number format validation
✅ **Country Selection** - Auto-detect country with manual override
✅ **Field Reset** - Auto-clear on error for better UX
✅ **Modal Management** - Smart modal open/close logic
✅ **Service Layer** - All database operations through UserContactService
✅ **Event-Driven** - Clean component communication via Livewire events

---

## Troubleshooting

### Modal doesn't open
- Check `data-bs-target="#AddContactNumberModel"` matches modal ID
- Verify Bootstrap JavaScript is loaded
- Check console for JavaScript errors

### Phone input not working
- Verify intl-tel-input library is loaded
- Check `#inputNumberContact` div exists
- Verify utils.js script is accessible

### Duplicate check not working
- Verify UserContactService is imported
- Check `contactNumberExists()` method
- Verify database connection

### OTP not received
- Check SMS service configuration
- Verify email settings (if using email)
- Check user has valid phone/email

---

## Date
December 4, 2025

