# Platform Index - Enabled/Disabled Badge Implementation

## Overview
Added visual status badges to display whether a platform is enabled or disabled in the platform index cards.

**Date:** November 17, 2025
**File:** `resources/views/livewire/platform-index.blade.php`

## Changes Made

### 1. Badge in Header Section
Added status badge next to the platform ID in the header section of each card.

**Location:** Below the platform name and translation button

**Design:**
```
┌─────────────────────────────────────────────┐
│ Platform Name                               │
│ ID: 123  [✓ Enabled]                       │
└─────────────────────────────────────────────┘
```

**Implementation:**
- **Enabled:** Green badge with checkmark icon (`bg-success-subtle text-success`)
- **Disabled:** Red badge with close icon (`bg-danger-subtle text-danger`)
- Icons: `ri-checkbox-circle-line` (enabled) and `ri-close-circle-line` (disabled)

### 2. Status Box in Info Grid
Added a dedicated "Status" column in the three-column info grid for better visibility.

**Previous Grid (2 columns):**
```
┌─────────────────────┬─────────────────────┐
│ Type                │ Created             │
│ Digital             │ Nov 17, 2025        │
└─────────────────────┴─────────────────────┘
```

**New Grid (3 columns):**
```
┌──────────────┬──────────────┬──────────────┐
│ Type         │ Status       │ Created      │
│ Digital      │ [✓ Enabled]  │ Nov 17, 2025 │
└──────────────┴──────────────┴──────────────┘
```

**Implementation:**
- Changed from 2-column (`col-6`) to 3-column (`col-4`) layout
- Added middle column for Status
- **Enabled:** Green badge (`bg-success`) with checkmark icon
- **Disabled:** Red badge (`bg-danger`) with close icon
- Larger badge size (`fs-6`) for better visibility

## Visual Design

### Enabled Platform Badge
- **Color:** Green (`bg-success-subtle text-success` in header, `bg-success` in grid)
- **Icon:** `ri-checkbox-circle-line` (checkmark in circle)
- **Text:** "Enabled"

### Disabled Platform Badge
- **Color:** Red (`bg-danger-subtle text-danger` in header, `bg-danger` in grid)
- **Icon:** `ri-close-circle-line` (X in circle)
- **Text:** "Disabled"

## Responsive Layout

The three-column grid (`col-4`) automatically adjusts on smaller screens:
- **Desktop:** 3 columns side by side
- **Tablet:** May wrap to 2+1 columns
- **Mobile:** Stacks vertically

## Benefits

1. **Clear Visual Status** - Immediately visible if platform is active
2. **Dual Display** - Status shown in both header and info grid
3. **Icon-Enhanced** - Icons make status instantly recognizable
4. **Color-Coded** - Green = good (enabled), Red = inactive (disabled)
5. **Consistent Design** - Matches existing badge styles in the application
6. **Better Grid Balance** - Three-column layout looks more balanced

## Badge Locations

### Location 1: Header Section (Subtle)
```blade
<p class="text-muted mb-0">
    <span class="badge badge-soft-secondary">ID: {{$platform->id}}</span>
    @if($platform->enabled)
        <span class="badge bg-success-subtle text-success ms-2">
            <i class="ri-checkbox-circle-line align-middle me-1"></i>{{__('Enabled')}}
        </span>
    @else
        <span class="badge bg-danger-subtle text-danger ms-2">
            <i class="ri-close-circle-line align-middle me-1"></i>{{__('Disabled')}}
        </span>
    @endif
</p>
```

### Location 2: Info Grid (Prominent)
```blade
<div class="col-4">
    <div class="p-3 bg-light rounded">
        <p class="text-muted mb-1 fs-6">{{__('Status')}}</p>
        @if($platform->enabled)
            <span class="badge bg-success fs-6">
                <i class="ri-checkbox-circle-line align-middle me-1"></i>{{__('Enabled')}}
            </span>
        @else
            <span class="badge bg-danger fs-6">
                <i class="ri-close-circle-line align-middle me-1"></i>{{__('Disabled')}}
            </span>
        @endif
    </div>
</div>
```

## Bootstrap Classes Used

### Badge Styling
- `badge` - Base badge class
- `bg-success-subtle text-success` - Soft green badge (header)
- `bg-danger-subtle text-danger` - Soft red badge (header)
- `bg-success` - Solid green badge (grid)
- `bg-danger` - Solid red badge (grid)
- `fs-6` - Font size 6 for better visibility
- `ms-2` - Margin start (left spacing)
- `me-1` - Margin end (right spacing for icons)

### Grid Layout
- `col-4` - 4-column width (3 columns total)
- `g-2` - Gap of 2 between grid items
- `p-3` - Padding 3
- `bg-light` - Light background
- `rounded` - Rounded corners

### Icons
- `ri-checkbox-circle-line` - Checkmark circle icon (enabled)
- `ri-close-circle-line` - Close circle icon (disabled)
- `align-middle` - Vertical alignment

## Platform Model

The `enabled` field is a boolean in the Platform model:
- **Location:** `Core/Models/Platform.php`
- **Type:** Boolean (true/false or 1/0)
- **Usage:** `$platform->enabled`

## Translations

The badges use Laravel's translation system:
- `{{__('Enabled')}}` - Translatable "Enabled" text
- `{{__('Disabled')}}` - Translatable "Disabled" text
- `{{__('Status')}}` - Translatable "Status" label

## Testing Checklist

- ✅ Enabled platforms show green badge with checkmark
- ✅ Disabled platforms show red badge with X icon
- ✅ Badges appear in both header and info grid
- ✅ Three-column grid layout is balanced
- ✅ Responsive layout works on mobile
- ✅ Icons are properly aligned
- ✅ Translations work in all languages
- ✅ Colors are consistent with design system

## Conclusion

The platform index now clearly displays the enabled/disabled status with visual badges in two locations, making it immediately apparent which platforms are active or inactive. The three-column grid provides better visual balance and organization of platform information.

