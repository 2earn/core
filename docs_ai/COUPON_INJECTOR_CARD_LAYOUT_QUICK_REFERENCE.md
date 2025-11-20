# Coupon Injector Card-Based Layout - Quick Reference

## 🎯 What Changed

**BEFORE:** Traditional HTML table with DataTables
**AFTER:** Modern card-based layers with Livewire

## 🎨 Visual Design

### Card Structure
Each coupon is now displayed as a card with:
- **Left Border Color Coding**:
  - 🟢 Green = Consumed
  - 🟡 Yellow = Available/Pending
- **Checkbox** for selection
- **Main info** with icons (ticket, barcode, calendar)
- **Badges** for category, value, status
- **Action buttons** (Details, Delete)
- **Collapsible details** section

### Layout
```
[Search Bar] [Select All ☑] [Sort: Date | Value | Status]
┌─────────────────────────────────────────────────────┐
│ ☐ 🎫 PIN-XXX              Category  Value      [🗑] │
│    📊 SN: XXX-XXX         [Badge]   [Badge]    [ℹ] │
│    📅 2025-11-20          Status                    │
│                           [Badge]                   │
└─────────────────────────────────────────────────────┘
```

## 🚀 Features

### Search & Filter
- Real-time search (300ms debounce)
- Search fields: pin, sn, value, category
- Quick sort buttons: Date, Value, Status
- Sort direction indicators (arrows)

### Selection & Actions
- Select All checkbox in filter bar
- Individual checkboxes per card
- Bulk delete (only non-consumed)
- Single delete with confirmation

### Responsive
- **Desktop**: Full horizontal layout
- **Tablet**: Wrapped columns
- **Mobile**: Stacked vertical layout

## 📱 Mobile-Friendly

Cards automatically stack on smaller screens:
- No horizontal scrolling
- Touch-friendly buttons
- Better information hierarchy
- Clear visual status

## 🎨 Icons Used

- 🎫 `mdi-ticket-confirmation` - Coupon PIN
- 📊 `mdi-barcode` - Serial Number
- 📅 `mdi-calendar` - Dates
- 🔍 `mdi-magnify` - Search
- 💰 `mdi-currency-usd` - Value sort
- ✅ `mdi-check-circle` - Status sort
- ℹ️ `mdi-information` - Details
- 🗑️ `mdi-delete` - Delete

## 💻 Technical Details

### Component: `CouponInjectorIndex.php`
```php
// Properties
public $search = '';
public $selectedIds = [];
public $selectAll = false;
public $sortField = 'created_at';
public $sortDirection = 'desc';

// Methods
sortBy($field)          // Toggle column sorting
delete($id)             // Delete single coupon
deleteSelected()        // Bulk delete selected
updatedSelectAll()      // Handle select all
getCouponsProperty()    // Query with filters
```

### View: `coupon-injector-index.blade.php`
- No JavaScript required
- Pure Livewire directives
- Bootstrap 5 responsive grid
- Reuses existing partial views

## ✨ Advantages

1. **No DataTables dependency** - Lighter, faster
2. **Better mobile experience** - Responsive cards
3. **Cleaner code** - No jQuery/AJAX
4. **Real-time updates** - Livewire magic
5. **Visual hierarchy** - Cards vs flat table
6. **Status indicators** - Color-coded borders
7. **Easier to maintain** - Single component
8. **Accessibility** - Better screen reader support

## 🔧 Customization

### Change pagination size:
```php
->paginate(10)  // Change to 25, 50, etc.
```

### Add new sort field:
```blade
<button wire:click="sortBy('field_name')">
    {{__('Label')}}
    @if($sortField === 'field_name')
        <i class="mdi mdi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
    @endif
</button>
```

### Modify search fields:
```php
->where('pin', 'like', '%' . $this->search . '%')
->orWhere('new_field', 'like', '%' . $this->search . '%')
```

## 🎯 Best Practices

1. Keep cards consistent in height (use flex)
2. Use icon indicators for quick scanning
3. Maintain color coding for status
4. Keep action buttons in same position
5. Expandable details for extra info
6. Loading states for all actions

## 📊 Performance

- Server-side pagination (10 items)
- Debounced search (300ms)
- Optimized single query
- Lazy loading with Livewire
- No heavy JavaScript libraries

## 🔍 Empty State

When no results found:
- Large icon display
- Helpful message
- Suggestion to adjust filters

---

**Last Updated:** November 20, 2025
**Version:** 2.0 (Card-Based Layout)

