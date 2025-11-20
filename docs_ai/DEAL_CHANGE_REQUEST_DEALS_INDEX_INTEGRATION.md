# Deal Change Request - Deals Index Integration

## Summary

Successfully integrated Deal Change Request indicators into the deals index page, allowing admins to see and approve pending change requests directly from the deals list.

## Changes Made

### 1. Deals Index Blade View (`resources/views/livewire/deals-index.blade.php`)

#### A. Added "Pending Change Requests" Section (Super Admin Only)
**Location:** After the "Pending Validation Requests" section

**Features:**
- Card header with icon and title
- "View All Change Requests" button linking to approval page
- Info alert explaining the purpose
- Only visible to super admins

```blade
<!-- Deal Change Requests Section -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header">
                <i class="fas fa-file-edit me-2"></i>
                <h5>Pending Change Requests</h5>
                <a href="route to change requests">View All Change Requests</a>
            </div>
            <div class="card-body">
                Info alert
            </div>
        </div>
    </div>
</div>
```

#### B. Added Pending Change Request Alert to Each Deal Card
**Location:** After commission information, before action buttons

**Features:**
- Success-colored alert box (green)
- Shows number of fields changed
- Shows who requested the change
- "Review" button linking to approval page
- Only visible to super admins
- Only shown if deal has a pending change request

```blade
@if(\App\Models\User::isSuperAdmin() && $deal->pendingChangeRequest)
    <div class="alert alert-success">
        <i class="fas fa-file-edit"></i> Pending Update Request
        <span class="badge">3 field(s) changed</span>
        <small>Requested by: User Name</small>
        <a href="route" class="btn btn-success">Review</a>
    </div>
@endif
```

### 2. DealsIndex Livewire Component (`app/Livewire/DealsIndex.php`)

#### Updated prepareQuery() Method
**Added eager loading for:**
- `platform` - The platform relationship
- `commissionFormula` - The commission formula relationship
- `pendingChangeRequest.requestedBy` - Pending change request with requester info

**Purpose:** Prevents N+1 query issues when displaying change request information

```php
$query->with([
    'platform', 
    'commissionFormula', 
    'pendingChangeRequest.requestedBy'
]);
```

## Visual Layout

### Top Section (Super Admin Only)
```
┌─────────────────────────────────────────────────┐
│ 📋 Pending Validation Requests                  │
│                      [View All Requests Button] │
│ (Validation requests content)                   │
└─────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────┐
│ 📝 Pending Change Requests               [NEW]  │
│                [View All Change Requests Button]│
│ ℹ️ Review and approve pending deal updates     │
└─────────────────────────────────────────────────┘
```

### Deal Card Layout (When Change Request Exists)
```
┌─────────────────────────────────────────────────┐
│ Deal Name                                       │
│ Platform: Platform Name                         │
│                                                 │
│ Status | Type | Validation                      │
│                                                 │
│ Initial Commission | Final Commission           │
│                                                 │
│ ✅ Pending Update Request        [Review]      │ ← NEW
│    3 field(s) changed                           │
│    Requested by: John Doe                       │
│                                                 │
│ [Show] [Edit] [Delete]                          │
└─────────────────────────────────────────────────┘
```

## User Experience Flow

### For Super Admins:
1. **View Deals Index** - See all deals with filters
2. **See Change Request Section** - At the top, before deals list
3. **See Deal with Pending Change** - Green alert box appears
4. **Click "Review" Button** - Redirects to `/deals/change-requests` page
5. **Approve/Reject Changes** - On the change requests page

### For Non-Admin Users:
- No change request sections visible
- No change request alerts on deals
- Everything remains as before

## Benefits

✅ **Visibility**: Admins immediately see which deals have pending changes
✅ **Quick Access**: "Review" button provides direct link to approval page
✅ **Information Rich**: Shows field count and requester name
✅ **Consistent Design**: Matches validation requests section
✅ **Performance**: Eager loading prevents database performance issues

## Route Used

The blade views use this route:
```php
route('deals_change_requests', ['locale' => app()->getLocale()])
```

This routes to: `/{locale}/deals/change-requests`

## Display Conditions

### Change Request Section (Top)
- Only visible if: `\App\Models\User::isSuperAdmin()` returns true

### Deal Change Request Alert
- Only visible if: 
  - User is super admin AND
  - Deal has a pending change request (`$deal->pendingChangeRequest` exists)

## Data Displayed

### For Each Pending Change Request:
- **Icon**: File edit icon (fas fa-file-edit)
- **Title**: "Pending Update Request"
- **Badge**: Number of fields changed
- **User Info**: Who requested the change
- **Action**: "Review" button

### From Database:
- `$deal->pendingChangeRequest` - The latest pending change request
- `$deal->pendingChangeRequest->changes` - Array of field changes
- `$deal->pendingChangeRequest->requestedBy->name` - Requester's name

## Styling

### Colors:
- **Section Card**: Success/Green theme (matching change request system)
- **Alert Box**: `alert-success` with `border-success`
- **Badge**: `bg-success`
- **Button**: `btn-success`

### Icons:
- **Section**: `fas fa-file-edit`
- **Alert**: `fas fa-file-edit`
- **Button**: `fas fa-check-double`
- **User**: `fas fa-user`

## Translation Keys

Add these to your language files:
```json
{
  "Pending Change Requests": "...",
  "View All Change Requests": "...",
  "Review and approve pending deal update requests": "...",
  "Pending Update Request": "...",
  "field(s) changed": "...",
  "Requested by": "...",
  "Review": "..."
}
```

## Database Query Optimization

**Before:** Each deal would trigger 3 separate queries:
1. Get platform
2. Get commission formula
3. Get pending change request

**After:** All relationships loaded in a single query using eager loading:
```php
$query->with(['platform', 'commissionFormula', 'pendingChangeRequest.requestedBy']);
```

**Result:** Much better performance, especially with many deals

## Testing Checklist

- [ ] Verify change request section appears for super admins
- [ ] Verify section is hidden for non-admin users
- [ ] Create a deal change request
- [ ] Verify green alert appears on the deal card
- [ ] Verify field count is displayed correctly
- [ ] Verify requester name is shown
- [ ] Click "Review" button and verify navigation
- [ ] Test with deals that have no pending changes
- [ ] Test with multiple deals having pending changes
- [ ] Verify eager loading is working (check query count)

## Files Modified

1. ✅ `resources/views/livewire/deals-index.blade.php`
   - Added change requests section at top
   - Added pending change alert to each deal card

2. ✅ `app/Livewire/DealsIndex.php`
   - Added eager loading for `pendingChangeRequest.requestedBy`

## Integration Complete

The deals index now shows:
1. A dedicated section for pending change requests (top of page)
2. Visual indicators on individual deals with pending changes
3. Quick access to the approval page via "Review" buttons
4. Complete information about pending changes (field count, requester)

Admins can now easily identify and review deal change requests directly from the deals index page!

