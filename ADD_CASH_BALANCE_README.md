# Add Cash Balance Component

## Overview
This Livewire component allows administrators to add cash balance to any user's account. It provides a user-friendly interface to search for users and add cash with proper tracking and validation.

## Files Created

### 1. Component Class
**Path:** `app/Livewire/AddCashBalance.php`

This is the main Livewire component that handles:
- User search functionality (by ID, name, or email)
- Form validation
- Cash balance addition logic
- Error handling and logging

### 2. Blade View
**Path:** `resources/views/livewire/add-cash-balance.blade.php`

The view provides:
- User search with live dropdown results
- Amount input with validation
- Optional description field
- Success/error message display
- Instructions card

### 3. Route
**Path:** `routes/web.php`

Route added:
```php
Route::get('/add-cash', AddCashBalance::class)->name('add_cash');
```

Full URL: `/{locale}/balances/add-cash`

## Features

### 🔍 User Search
- Search by User ID, Name, or Email
- Live search results with 300ms debounce
- Displays up to 10 matching users
- Shows user ID and email in dropdown

### 💰 Cash Addition
- Amount input with validation (minimum $0.01)
- Optional description field (max 255 characters)
- Real-time form validation
- Automatic balance calculation

### 🔒 Security
- Protected by `IsSuperAdmin` middleware
- Validates user existence
- Logs all transactions
- Tracks operator ID (current admin)

### 📝 Transaction Details
The component creates a transaction with:
- **Operation Type:** `SI_CB` (System Input - Cash Balance)
- **Operator:** Current authenticated admin
- **Beneficiary:** Selected user
- **Reference:** Auto-generated unique reference
- **Description:** Custom or default description
- **Current Balance:** Updated balance after addition

## How to Use

1. **Access the Component**
   - Navigate to: `/{locale}/balances/add-cash` (e.g., `/en/balances/add-cash`)
   - Must be logged in as Super Admin

2. **Search for User**
   - Type in the search box (ID, name, or email)
   - Select the desired user from the dropdown results

3. **Enter Amount**
   - Input the amount to add (must be greater than $0.01)
   - Optionally add a description

4. **Submit**
   - Click "Add Cash Balance" button
   - Success message will appear if successful

## Technical Details

### Dependencies
- `App\Models\CashBalances` - Model for cash balance operations
- `App\Models\User` - User model
- `App\Services\Balances\Balances` - Balance service
- `Core\Enum\BalanceOperationsEnum` - Operation type enum

### Database Tables Used
- `users` - User information
- `cash_balances` - Cash balance transactions
- `user_current_balance_horisontal` - Current balance snapshot

### Validation Rules
```php
'selectedUserId' => 'required|exists:users,idUser'
'amount' => 'required|numeric|min:0.01'
'description' => 'nullable|string|max:255'
```

## Example Usage

### Scenario: Adding $100 to User

1. Admin navigates to `/en/balances/add-cash`
2. Searches for user by email: `user@example.com`
3. Selects user from dropdown (ID: 197604395)
4. Enters amount: `100`
5. Enters description: `Promotional bonus`
6. Clicks "Add Cash Balance"

**Result:**
- User's cash balance increases by $100
- Transaction recorded in `cash_balances` table
- Reference auto-generated (e.g., `018130120250001`)
- Success message displayed

## Code Example

### How the Cash Addition Works

```php
// Get user's current balance
$userCurrentBalancehorisontal = Balances::getStoredUserBalances($idUser);

// Add new cash balance line
CashBalances::addLine([
    'balance_operation_id' => BalanceOperationsEnum::SI_CB->value,
    'operator_id' => Auth::id() ?? $idUser,
    'beneficiary_id' => $idUser,
    'reference' => $balances->getReference(BalanceOperationsEnum::SI_CB->value),
    'description' => $description,
    'value' => $value,
    'current_balance' => $userCurrentBalancehorisontal->cash_balance + $value
], null, null, null, null, null); // item_id, deal_id, order_id, platform_id, order_detail_id
```

## Error Handling

The component handles various error scenarios:

- **User Not Found:** Displays "User balance record not found"
- **Invalid Amount:** Shows validation error
- **Database Error:** Logs error and shows generic error message
- **No User Selected:** Disables submit button

## Logging

All transactions are logged with:
- Beneficiary ID
- Operator ID
- Amount added
- Timestamp

Errors are also logged for debugging:
```php
Log::error('Error adding cash balance: ' . $e->getMessage());
```

## UI Components

### Alert Messages
- **Success:** Green alert with checkmark icon
- **Error:** Red alert with warning icon
- Auto-dismiss after 5 seconds

### Form Elements
- Search input with live results
- Number input with currency symbol ($)
- Text input for description
- Submit button (disabled when no user selected)

### Instructions Card
Provides step-by-step guide for users

## Customization

### Changing Operation Type
To use a different operation type, modify:
```php
'balance_operation_id' => BalanceOperationsEnum::YOUR_TYPE->value,
```

### Modifying Search Limit
Change the limit in `updatedSearch()` method:
```php
->limit(10) // Change this number
```

### Custom Validation Messages
Add to the `$messages` array in the component class.

## Access Control

This component is protected by the `IsSuperAdmin` middleware and is only accessible to:
- Users with SUPER ADMIN role
- Authenticated users within the admin group

## Testing

### Manual Testing Steps
1. Test user search with various inputs
2. Verify validation errors display correctly
3. Add cash and verify balance updates
4. Check transaction appears in cash_balances table
5. Verify logging captures transactions

### Test Cases
- Valid user selection and amount
- Invalid amount (negative, zero)
- No user selected
- Special characters in search
- Large amounts
- Empty description (should use default)

## Troubleshooting

### Issue: User not found
**Solution:** Ensure user exists in `users` table and has a record in `user_current_balance_horisontal`

### Issue: Balance not updating
**Solution:** Check if `CashBalances::addLine()` method is working correctly and database permissions

### Issue: Route not accessible
**Solution:** Clear route cache: `php artisan route:clear`

## Future Enhancements

Potential improvements:
- Bulk cash addition for multiple users
- CSV upload for mass additions
- Transaction history view
- Balance adjustment (deduct cash)
- Email notifications to users
- Export transaction reports

## Related Files

- `database/seeders/AddCashSeeder.php` - Seeder with similar logic
- `app/Models/CashBalances.php` - Cash balance model
- `app/Services/Balances/Balances.php` - Balance service
- `Core/Enum/BalanceOperationsEnum.php` - Operation types

## Support

For issues or questions, contact the development team or refer to the Laravel Livewire documentation.

