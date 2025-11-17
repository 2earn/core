# User List - Quick Reference Guide

## Overview
The User List page displays all users in a modern card-based layout with search and pagination.

---

## Features

### 🔍 Search
- **Location**: Top of page, center input field
- **Searches**: Name, Mobile Number, User ID
- **Type**: Live search (updates as you type)
- **Clear**: Click × in search box or delete text

### 📄 Pagination
- **Options**: 20, 50, or 100 users per page
- **Location**: Top-left dropdown
- **Navigation**: Bottom of page (Previous/Next buttons)

### 👤 User Card Information

Each card displays:

#### Header
- 🏳️ **Country Flag**: User's country
- 🔢 **User ID**: Unique identifier badge
- 👤 **Name**: Full name from metadata
- 📱 **Mobile**: Phone number
- 🏷️ **Status Badge**: Current account status

#### Dates Section
- 📅 **Created**: Account creation date/time
- 🔒 **Password**: Plain text password (if stored)

#### 💰 Balances
Five balance buttons (click to see details):
- **CB**: Cash Balance (Blue)
- **BFS**: BFS Balance (Gray)
- **DB**: Discount Balance (Blue)
- **SMS**: SMS Balance (Yellow)
- **Shares**: Shares Balance (Green)

#### 👑 VIP History (if applicable)
- ⏱️ **Periode**: VIP duration
- 📊 **Minshares**: Minimum shares required
- 📈 **Coeff**: Coefficient multiplier
- 📅 **Date**: VIP start date
- 📝 **Note**: Additional notes

#### 📋 More Details (if available)
- 🔑 **OPT Activation Code**: One-time password
- 👤 **Register Upline**: Referring user

#### 🎯 Actions
Four action buttons per user:
1. **Add Cash**: Transfer cash to user
2. **Promote**: Promote on platforms
3. **VIP**: Manage VIP status (✓ if active)
4. **Update Password**: Change user password

---

## How To Use

### Search for a User
1. Type in the search box
2. Results filter automatically
3. Search works on: name, mobile, or user ID

### View Balance Details
1. Click any balance button (CB, BFS, DB, SMS, Shares)
2. Modal opens with transaction history
3. Click X or outside modal to close

### Transfer Cash
1. Click **Add Cash** button
2. Enter amount in modal
3. Click **Transfer du cash**
4. Confirmation message appears
5. Page refreshes with new balance

### Make User VIP
1. Click **VIP** button
2. Fill in VIP form:
   - Minshares (required)
   - Periode/hours (required)
   - Coefficient (required)
   - Note (optional)
3. Click **Submit**
4. SMS sent to notify user

### Update User Password
1. Click **Update Password** button
2. Enter new password (min 6 characters)
3. Click **Update Password**
4. Confirmation message appears

### Promote User
1. Click **Promote** button
2. Redirects to platform promotion page

---

## Card Color Coding

### Status Badges
- 🟦 **Blue**: Various account statuses
- Based on `StatusRequest` enum

### Balance Buttons
- 🔵 **Info/Blue**: Cash Balance (CB)
- ⚪ **Secondary/Gray**: BFS Balance
- 🔵 **Primary/Blue**: Discount Balance (DB)
- 🟡 **Warning/Yellow**: SMS Balance
- 🟢 **Success/Green**: Shares Balance

### VIP Indicators
- 🟣 **Purple**: Active VIP
- ✓ Checkmark shows on VIP button

### Information Sections
- 🔵 **Blue Background**: Periode
- 🟢 **Green Background**: Minshares
- 🟡 **Yellow Background**: Coefficient
- 🔵 **Cyan Background**: Date
- ⚪ **Gray Background**: Note

---

## Keyboard Shortcuts

- **Tab**: Navigate between search and page selector
- **Enter**: In search box - triggers search
- **Esc**: Close any open modal

---

## Responsive Behavior

### Desktop (>992px)
- Search controls in one row
- 4 action buttons per row
- All VIP history in one row

### Tablet (768-992px)
- Search controls stacked
- 3-4 action buttons per row
- VIP history 2-3 per row

### Mobile (<768px)
- All controls stacked
- 2 action buttons per row
- Balance buttons wrap
- Cards full width

---

## Empty States

### No Users Found
- 🔍 Search icon displayed
- Message: "No users found"
- Appears when search returns no results

### No VIP History
- Section hidden if user never had VIP

### No Additional Details
- Section hidden if no OPT or upline info

---

## Modal Windows

### Add Cash Modal
- **Purpose**: Transfer money to user
- **Fields**: Amount (required)
- **Shows**: User's phone and country flag
- **Action**: Transfer + Send SMS notification

### Update Password Modal
- **Purpose**: Change user's password
- **Fields**: New password (min 8 chars)
- **Shows**: User's phone number
- **Action**: Updates password in database

### VIP Modal
- **Purpose**: Activate VIP status
- **Fields**:
  - Minshares (required, number)
  - Periode (required, hours)
  - Coefficient (required, decimal)
  - Note (optional, text)
- **Shows**: User's phone and country flag
- **Action**: Activate VIP + Send SMS

### Balance Detail Modals
- **Purpose**: Show transaction history
- **Types**: CB, BFS, DB, SMS (all use same modal)
- **Shows**: Ranks, ID, Reference, Date, Operation, Value, Balance
- **Format**: DataTable with sorting

### Shares Balance Modal
- **Purpose**: Show shares transaction history
- **Shows**: Reference, Date, Value, Real Amount, Balance, Unit Price, Total
- **Format**: DataTable with sorting

---

## Tips & Best Practices

### Searching
✅ **Do**: Use partial names or numbers
✅ **Do**: Use international format for phone search
❌ **Don't**: Use special characters unnecessarily

### Cash Transfers
✅ **Do**: Verify user before transferring
✅ **Do**: Enter correct decimal amounts
❌ **Don't**: Transfer negative amounts
❌ **Don't**: Transfer more than available

### VIP Management
✅ **Do**: Set reasonable periode and coefficient
✅ **Do**: Add notes for future reference
❌ **Don't**: Set extreme coefficients
❌ **Don't**: Forget to verify VIP activation

### Password Updates
✅ **Do**: Use secure passwords (8+ chars)
✅ **Do**: Double-check user identity first
❌ **Don't**: Change your own password here
❌ **Don't**: Use weak passwords

---

## Troubleshooting

### Search Not Working
- Ensure you're typing in the search box
- Check spelling and formatting
- Try partial search terms

### Balances Not Showing
- Click the specific balance button
- Check if user has transactions
- Verify modal opens correctly

### Cash Transfer Failed
- Check amount is valid (positive number)
- Verify user ID is correct
- Check error message in alert

### VIP Activation Failed
- Ensure all required fields filled
- Verify numbers are valid
- Check coefficient is decimal format

### Modal Won't Close
- Click X button in top-right
- Click outside modal area
- Press Esc key

---

## Technical Notes

### Performance
- Only current page of users loaded
- Search queries are optimized
- Balance details loaded on-demand

### Data Refresh
- Search results update immediately
- Page reloads after cash transfer
- VIP status updates in real-time

### Browser Support
- Modern browsers (Chrome, Firefox, Safari, Edge)
- Requires JavaScript enabled
- Responsive design for all screen sizes

---

## Support Information

### Related Pages
- **Contacts**: Similar card-based design
- **Deals**: Similar filtering and search
- **Platform Promotion**: Linked from Promote button

### Data Sources
- Users table
- Metta_users table (metadata)
- Countries table (flags and names)
- VIP table (VIP status)
- Amounts table (balances)

### Permissions Required
- View users list
- Update user passwords
- Transfer cash
- Manage VIP status

---

## Changelog

### Version 2.0 (Layer Design)
- ✅ Converted to card-based layout
- ✅ Added live search
- ✅ Improved mobile experience
- ✅ Better visual hierarchy
- ✅ Consistent with other pages

### Version 1.0 (Original)
- ✅ DataTable-based design
- ✅ Server-side processing
- ✅ Basic search and pagination

---

**Last Updated**: November 13, 2025
**Design Pattern**: Layer-based cards
**Framework**: Laravel + Livewire

