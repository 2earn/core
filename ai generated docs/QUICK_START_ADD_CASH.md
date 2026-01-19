# Add Cash Balance - Quick Start Guide

## 🎯 Quick Access

**URL:** `/{locale}/balances/add-cash` (e.g., `/en/balances/add-cash`)

**Permission Required:** Super Admin

---

## 📁 Files Created

1. **Component:** `app/Livewire/AddCashBalance.php`
2. **View:** `resources/views/livewire/add-cash-balance.blade.php`
3. **Route:** Added to `routes/web.php`

---

## 🚀 How to Use (3 Simple Steps)

### Step 1: Search for User
Type user's ID, name, or email in the search box

### Step 2: Enter Amount
Input the cash amount to add (minimum $0.01)

### Step 3: Submit
Click "Add Cash Balance" button

---

## ✅ What Happens

- ✓ User's cash balance increases by specified amount
- ✓ Transaction recorded with unique reference
- ✓ Operation logged with admin ID as operator
- ✓ Success message displayed
- ✓ Balance immediately available to user

---

## 🔧 Technical Details

### Operation Type
**SI_CB** (System Input - Cash Balance) - ID: 18

### Method Signature
```php
CashBalances::addLine(
    array $cashBalances,
    $item_id = null,
    $deal_id = null,
    $order_id = null,
    $platform_id = null,
    $order_detail_id = null
)
```

### Database Tables Affected
- `cash_balances` - New transaction record
- `user_current_balance_horisontal` - Balance calculation reference

---

## 💡 Features

- **Live Search:** Real-time user search with 300ms debounce
- **Validation:** Client and server-side validation
- **Auto-complete:** Select from dropdown suggestions
- **Error Handling:** Comprehensive error messages
- **Transaction Logging:** All operations logged for audit
- **Auto-dismiss Alerts:** Success/error messages auto-hide after 5s

---

## 🛡️ Security

- Protected by `IsSuperAdmin` middleware
- Validates user existence before transaction
- Records operator ID (current admin)
- All transactions logged with timestamp

---

## 📊 Example Transaction

**Input:**
- User: John Doe (ID: 197604395)
- Amount: $100.00
- Description: "Welcome bonus"

**Result:**
```
Reference: 018130120250001
Operation: SI_CB
Operator: 197604001 (Admin ID)
Beneficiary: 197604395
Value: 100.00
New Balance: Previous Balance + 100.00
```

---

## 🐛 Troubleshooting

| Issue | Solution |
|-------|----------|
| User not found | Verify user exists in database |
| Balance not updating | Check `user_current_balance_horisontal` table |
| Route 404 | Run `php artisan route:clear` |
| View not found | Run `php artisan view:clear` |

---

## 📝 Notes

- Default platform_id is set to 1 if not provided
- Beneficiary_id_auto is auto-calculated from beneficiary_id
- Empty description defaults to "Cash balance added by admin"
- Operator defaults to beneficiary if no admin is logged in

---

## 🔗 Related Components

- User Balance CB: `/user/balance-cb`
- Balances Index: `/balances/index`
- User Details: `/user/{idUser}/details`

---

## 📞 Need Help?

Refer to full documentation: `ADD_CASH_BALANCE_README.md`

---

**Created:** January 13, 2026
**Version:** 1.0

