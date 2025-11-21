# Shares Sold Card Layout - Visual Guide

## Layout Overview

The Shares Sold page now uses a modern card-based layout instead of a traditional table, making it more responsive and easier to read.

## Page Structure

```
┌─────────────────────────────────────────────────────────────────┐
│  Shares Sold : market status                                    │
├─────────────────────────────────────────────────────────────────┤
│  [Search by mobile or name...]              [Per Page: 1000 ▼] │
├─────────────────────────────────────────────────────────────────┤
│  [Sort by ID ▼]  [Sort by Date ▼]                              │
├─────────────────────────────────────────────────────────────────┤
│  ┌───────────────────────────────────────────────────────────┐ │
│  │ Card 1                                                     │ │
│  │ ┌───────────┬────────────────┬────────────────┬─────────┐ │ │
│  │ │ User Info │ Shares Info    │ Financial Info │ Actions │ │ │
│  │ │ 3 cols    │ 4 cols         │ 4 cols         │ 1 col   │ │ │
│  │ └───────────┴────────────────┴────────────────┴─────────┘ │ │
│  └───────────────────────────────────────────────────────────┘ │
│  ┌───────────────────────────────────────────────────────────┐ │
│  │ Card 2                                                     │ │
│  │ ...                                                        │ │
│  └───────────────────────────────────────────────────────────┘ │
├─────────────────────────────────────────────────────────────────┤
│  « Previous  1 2 3 ... 10  Next »                             │
└─────────────────────────────────────────────────────────────────┘
```

## Card Layout Details

### Each Card Contains 4 Sections:

```
┌─────────────────────────────────────────────────────────────────────────┐
│  ┌──────────────┐  ┌─────────────────┐  ┌─────────────────┐  ┌──────┐ │
│  │  User Info   │  │  Shares Info    │  │ Financial Info  │  │Action│ │
│  │  (col-md-3)  │  │  (col-md-4)     │  │  (col-md-4)     │  │(1col)│ │
│  ├──────────────┤  ├─────────────────┤  ├─────────────────┤  ├──────┤ │
│  │ 🇺🇸 John Doe │  │ Total Shares:   │  │ Sell Price Now: │  │ [👁] │ │
│  │ +1234567890  │  │ 1,000           │  │ $10,500.00      │  │      │ │
│  │              │  │                 │  │                 │  │      │ │
│  │ 2024-11-21   │  │ Num of Shares:  │  │ Gains:          │  │      │ │
│  │ 10:30:45     │  │ 1,000           │  │ $500.00 ✅      │  │      │ │
│  │              │  │                 │  │                 │  │      │ │
│  │ [Transfert   │  │ Share Price:    │  │ Real Sold Amt:  │  │      │ │
│  │  Made] 🟢    │  │ $10.00          │  │ $10,500.00      │  │      │ │
│  │              │  │                 │  │                 │  │      │ │
│  │              │  │ Total Price:    │  │                 │  │      │ │
│  │              │  │ $10,000.00      │  │                 │  │      │ │
│  └──────────────┘  └─────────────────┘  └─────────────────┘  └──────┘ │
└─────────────────────────────────────────────────────────────────────────┘
```

## Section Breakdown

### 1. User Info Section (3 columns, border-right)
- **Flag + Name**: Country flag icon + Full name
- **Mobile**: Phone number below name
- **Date**: Transaction date and time
- **Status Badge**: Clickable badge with status
  - 🟢 Green: "Transfert Made" (payed = 1)
  - 🔴 Red: "Free" (payed = 0)
  - 🟡 Yellow: "Mixed" (payed = 2)

### 2. Shares Info Section (4 columns, border-right)
- **Total Shares**: Formatted total number
- **Number of Shares**: Raw share count
- **Share Price**: Unit price per share
- **Total Price**: Calculated total (unit_price × shares)

Layout: 2×2 grid
```
┌─────────────┬─────────────┐
│Total Shares │Number Shares│
├─────────────┼─────────────┤
│Share Price  │Total Price  │
└─────────────┴─────────────┘
```

### 3. Financial Info Section (4 columns)
- **Sell Price Now**: Current market value (blue text)
- **Gains**: Profit/Loss (green if positive, red if negative)
- **Real Sold Amount**: Current balance amount

Layout: 2×1 + 1 full width
```
┌─────────────┬─────────────┐
│Sell Price   │Gains        │
├─────────────┴─────────────┤
│Real Sold Amount           │
└───────────────────────────┘
```

### 4. Actions Section (1 column, centered)
- **View Button**: Eye icon button to open modal
- Triggers: `wire:click="openModal(...)"`

## Status Badges

All status badges are **clickable** and open the transfer modal:

```blade
🟢 Transfert Made  (bg-success)
🔴 Free           (bg-danger)
🟡 Mixed          (bg-warning)
```

## Color Coding

- **Gains**:
  - Green (text-success): Positive gains
  - Red (text-danger): Negative gains/losses
- **Sell Price Now**: Blue (text-primary)
- **Labels**: Muted gray (text-muted, small)
- **Values**: Semi-bold (fw-semibold)

## Responsive Behavior

### Desktop (md and up):
- All 4 sections in one row
- Border separators between sections
- Optimal information density

### Tablet (sm to md):
- Sections may stack in 2×2 layout
- User Info + Shares Info in first row
- Financial Info + Actions in second row

### Mobile (xs):
- All sections stack vertically
- Full width for each section
- Maintains readability

## Empty State

When no data is available:
```
┌─────────────────────────────┐
│                             │
│       📄 (large icon)       │
│                             │
│    No data available        │
│                             │
└─────────────────────────────┘
```

## Modal Interaction

Clicking on:
- Status badges
- View button (eye icon)

Opens modal with:
- Country flag
- Phone number (disabled)
- Editable amount field
- Cancel/Submit buttons

## Search & Sort

### Search Bar:
- Searches mobile number OR name
- 300ms debounce for performance
- Real-time results

### Sort Buttons:
- Sort by ID: Sorts by record ID
- Sort by Date: Sorts by created_at timestamp
- Arrow indicator shows current sort direction
- Toggle between ascending/descending

## Advantages Over Table

1. ✅ **Better Readability**: Information grouped logically
2. ✅ **Mobile Friendly**: Responsive card layout
3. ✅ **Visual Hierarchy**: Clear sections with borders
4. ✅ **Status Visibility**: Large, colorful status badges
5. ✅ **Compact**: All info visible without scrolling horizontally
6. ✅ **Professional**: Modern card-based UI design
7. ✅ **Accessible**: Better for screen readers and keyboard navigation
8. ✅ **Scannable**: Easy to scan multiple records quickly

## CSS Classes Used

- `shadow-sm`: Subtle card shadow
- `border-end`: Right border for section separation
- `fw-semibold`: Semi-bold text for values
- `text-muted`: Gray text for labels
- `small`: Smaller font size for labels
- `avatar-xs`: Extra small avatar size for flags
- `badge`: Bootstrap badge component
- `bg-success/danger/warning`: Status colors
- `text-success/danger/primary`: Value colors

## Implementation Notes

- All wire:click directives maintain Livewire reactivity
- No JavaScript required
- Fully server-side rendered with Livewire
- Maintains existing functionality while improving UX
- Compatible with all browsers

