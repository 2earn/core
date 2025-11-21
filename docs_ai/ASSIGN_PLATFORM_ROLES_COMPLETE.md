# Assign Platform Roles Management - Complete Implementation

## Overview
A complete Livewire component system for managing platform role assignments with approval/rejection workflow. When a role assignment is approved, it updates the platform model with the assigned user. When rejected, it stores the rejection reason.

## Created/Modified Files

### 1. **Model: `AssignPlatformRole`**
**Location**: `app/Models/AssignPlatformRole.php`

#### Features
- Tracks platform role assignment requests
- Status workflow: pending → approved/rejected
- Stores rejection reason for rejected assignments
- Auditing support (created_by, updated_by)
- Foreign key relationships to Platform and User

#### Constants
```php
const STATUS_PENDING = 'pending';
const STATUS_APPROVED = 'approved';
const STATUS_REJECTED = 'rejected';
```

#### Fillable Fields
- `platform_id` - ID of the platform
- `user_id` - ID of the user to be assigned
- `role` - Role name (owner, marketing_manager, financial_manager)
- `status` - Current status (pending/approved/rejected)
- `rejection_reason` - Reason if rejected
- `created_by` - User who created the request
- `updated_by` - User who last updated the request

#### Relationships
- `platform()` - BelongsTo Platform
- `user()` - BelongsTo User (the user being assigned)
- `creator()` - BelongsTo User (who created the request)
- `updater()` - BelongsTo User (who last updated)

---

### 2. **Migration: `create_assign_platform_roles_table`**
**Location**: `database/migrations/2025_11_21_081719_create_assign_platform_roles_table.php`

#### Table Structure
```sql
CREATE TABLE assign_platform_roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    platform_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    role VARCHAR(255) NOT NULL,
    status VARCHAR(255) DEFAULT 'pending',
    rejection_reason TEXT NULL,
    created_by BIGINT UNSIGNED NULL,
    updated_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (platform_id) REFERENCES platforms(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL,
    
    UNIQUE KEY (platform_id, user_id, role)
);
```

#### To Run Migration
```bash
php artisan migrate
```

---

### 3. **Livewire Component: `AssignPlatformRolesIndex`**
**Location**: `app/Livewire/AssignPlatformRolesIndex.php`

#### Features
- ✅ Display all role assignment requests
- ✅ Filter by status (all/pending/approved/rejected)
- ✅ Search by user name, email, platform name, or role
- ✅ Pagination (10 items per page)
- ✅ Approve assignments (updates platform model)
- ✅ Reject assignments (requires reason, min 10 chars)
- ✅ View rejection reason for rejected items
- ✅ Real-time updates with Livewire
- ✅ Comprehensive logging
- ✅ Database transaction support

#### Public Properties
```php
public $selectedStatus = 'all';      // Filter by status
public $search = '';                 // Search term
public $rejectionReason = '';        // Rejection reason input
public $selectedAssignmentId = null; // Current assignment being processed
public $showRejectModal = false;     // Modal visibility
```

#### Methods

##### `approve($assignmentId)`
Approves a role assignment and updates the platform model.

**Workflow:**
1. Validates assignment is still pending
2. Finds the platform
3. Updates platform based on role:
   - `owner` → Updates `platform.owner_id`
   - `marketing_manager` → Updates `platform.marketing_manager_id`
   - `financial_manager` → Updates `platform.financial_manager_id`
4. Sets assignment status to 'approved'
5. Logs the action
6. Shows success message

**Validation:**
- Checks if assignment is still pending
- Throws exception for invalid roles

##### `openRejectModal($assignmentId)`
Opens the rejection modal for a specific assignment.

##### `closeRejectModal()`
Closes the rejection modal and clears form data.

##### `reject()`
Rejects a role assignment with a reason.

**Validation Rules:**
- `rejectionReason`: required, string, min:10, max:500

**Workflow:**
1. Validates rejection reason
2. Validates assignment is still pending
3. Sets assignment status to 'rejected'
4. Stores rejection reason
5. Logs the action
6. Shows success message

##### `render()`
Renders the component with filtered and paginated data.

**Query Features:**
- Eager loads relationships (platform, user, creator)
- Orders by created_at DESC (newest first)
- Filters by status
- Searches across user name, email, platform name, and role
- Paginates results (10 per page)

---

### 4. **Blade View**
**Location**: `resources/views/livewire/assign-platform-roles-index.blade.php`

#### Features
- Responsive table layout
- Filter by status dropdown
- Real-time search
- Color-coded status badges:
  - 🟡 Pending (yellow)
  - 🟢 Approved (green)
  - 🔴 Rejected (red)
- Action buttons for pending items:
  - ✅ Approve (with confirmation)
  - ❌ Reject (opens modal)
- View rejection reason tooltip for rejected items
- Flash messages for success/error
- Pagination controls
- Reject modal with textarea for reason

#### UI Components
```html
<!-- Status Badge -->
@if($assignment->status === 'pending')
    <span class="badge badge-sm bg-gradient-warning">Pending</span>
@elseif($assignment->status === 'approved')
    <span class="badge badge-sm bg-gradient-success">Approved</span>
@elseif($assignment->status === 'rejected')
    <span class="badge badge-sm bg-gradient-danger">Rejected</span>
@endif

<!-- Action Buttons (Pending) -->
<button wire:click="approve({{ $assignment->id }})" 
        wire:confirm="Are you sure?"
        class="btn btn-success btn-sm">
    <i class="fas fa-check"></i> Approve
</button>
<button wire:click="openRejectModal({{ $assignment->id }})" 
        class="btn btn-danger btn-sm">
    <i class="fas fa-times"></i> Reject
</button>
```

---

### 5. **Updated Controller: `UserPartnerController`**
**Location**: `app/Http/Controllers/Api/partner/UserPartnerController.php`

#### Changes
- Now saves role assignment to database
- Uses `AssignPlatformRole::updateOrCreate()`
- Sets status to 'pending' by default
- Returns assignment ID in response
- Removed direct Spatie role assignment (now done on approval)

---

### 6. **Route**
**Location**: `routes/web.php`

```php
Route::get('/platform/role-assignments', \App\Livewire\AssignPlatformRolesIndex::class)
    ->name('platform_role_assignments');
```

**Full Path**: `/{locale}/platform/role-assignments`

**Middleware**: 
- `auth` - Requires authentication
- `IsSuperAdmin` - Requires super admin role
- `setlocale` - Sets locale from URL

---

## Workflow Diagram

```
┌─────────────────────────────────────────────────────┐
│ 1. API Request (POST /api/partner/users/add-role)  │
│    - platform_id: 1                                 │
│    - user_id: 123                                   │
│    - role: "owner"                                  │
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────┐
│ 2. UserPartnerController creates AssignPlatformRole │
│    - status: "pending"                              │
│    - Returns: {"status": true, "data": {...}}       │
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────┐
│ 3. Admin views in AssignPlatformRolesIndex          │
│    - Sees pending request                           │
│    - Can filter/search                              │
└──────────────────┬──────────────────────────────────┘
                   │
         ┌─────────┴─────────┐
         │                   │
         ▼                   ▼
┌────────────────┐   ┌──────────────────┐
│  4a. APPROVE   │   │   4b. REJECT     │
│                │   │                  │
│ - Update       │   │ - Set status to  │
│   platform     │   │   "rejected"     │
│   model:       │   │ - Store reason   │
│   * owner_id   │   │ - Log action     │
│   * mgr_id     │   │                  │
│ - Set status   │   └──────────────────┘
│   "approved"   │
│ - Log action   │
└────────────────┘
```

---

## Usage Guide

### Accessing the Component
1. Navigate to: `/{locale}/platform/role-assignments`
   - Example: `/en/platform/role-assignments`
2. Must be logged in as Super Admin

### Filtering Assignments
**By Status:**
- Select from dropdown: All, Pending, Approved, Rejected

**By Search:**
- Type in search box
- Searches: User name, User email, Platform name, Role
- Real-time search with 300ms debounce

### Approving an Assignment
1. Find the pending assignment
2. Click **Approve** button
3. Confirm the action
4. System will:
   - Update the platform model with the user
   - Set status to 'approved'
   - Show success message

**Platform Updates Based on Role:**
| Role | Platform Field Updated |
|------|----------------------|
| `owner` | `owner_id` |
| `marketing_manager` | `marketing_manager_id` |
| `financial_manager` | `financial_manager_id` |

### Rejecting an Assignment
1. Find the pending assignment
2. Click **Reject** button
3. Modal opens
4. Enter rejection reason (min 10 characters, max 500)
5. Click **Reject Assignment**
6. System will:
   - Set status to 'rejected'
   - Save rejection reason
   - Show success message

### Viewing Rejection Reason
For rejected assignments:
- Hover over the **View Reason** button
- Tooltip shows the rejection reason

---

## API Integration

The component works with the existing API endpoint:

**Endpoint**: `POST /api/partner/users/add-role`

**Request:**
```json
{
  "platform_id": 1,
  "user_id": 123,
  "role": "owner"
}
```

**Response:**
```json
{
  "status": true,
  "message": "Role assign request sent successfully, waiting for approval",
  "data": {
    "id": 15,
    "user_id": 123,
    "platform_id": 1,
    "role": "owner"
  }
}
```

---

## Valid Roles

The system supports these roles:

| Role | Description | Platform Field |
|------|-------------|----------------|
| `owner` | Platform Owner | `owner_id` |
| `marketing_manager` | Marketing Manager | `marketing_manager_id` |
| `financial_manager` | Financial Manager | `financial_manager_id` |

**Note**: To add more roles, update:
1. The `approve()` method switch statement
2. Platform migration to add new role fields
3. Validation rules if needed

---

## Logging

All actions are logged with comprehensive details:

### Approval Log
```php
Log::info('[AssignPlatformRolesIndex] Role assignment approved', [
    'assignment_id' => $assignmentId,
    'user_id' => $assignment->user_id,
    'platform_id' => $assignment->platform_id,
    'role' => $assignment->role,
    'approved_by' => auth()->id()
]);
```

### Rejection Log
```php
Log::info('[AssignPlatformRolesIndex] Role assignment rejected', [
    'assignment_id' => $this->selectedAssignmentId,
    'user_id' => $assignment->user_id,
    'platform_id' => $assignment->platform_id,
    'role' => $assignment->role,
    'rejection_reason' => $this->rejectionReason,
    'rejected_by' => auth()->id()
]);
```

### Error Log
```php
Log::error('[AssignPlatformRolesIndex] Failed to approve/reject', [
    'assignment_id' => $assignmentId,
    'error' => $e->getMessage(),
    'trace' => $e->getTraceAsString()
]);
```

**Log Location**: `storage/logs/laravel.log`

---

## Database Queries

### Get All Pending Assignments
```php
AssignPlatformRole::where('status', 'pending')
    ->with(['platform', 'user', 'creator'])
    ->orderBy('created_at', 'desc')
    ->get();
```

### Get Assignments for Specific Platform
```php
AssignPlatformRole::where('platform_id', $platformId)
    ->with(['user'])
    ->get();
```

### Get Assignments for Specific User
```php
AssignPlatformRole::where('user_id', $userId)
    ->with(['platform'])
    ->get();
```

---

## Testing

### Manual Testing Checklist

#### Create Assignment via API
```bash
curl -X POST http://localhost/api/partner/users/add-role \
  -H "Content-Type: application/json" \
  -d '{
    "platform_id": 1,
    "user_id": 123,
    "role": "owner"
  }'
```

#### Test Approval
1. ✅ Navigate to `/en/platform/role-assignments`
2. ✅ Find pending assignment
3. ✅ Click Approve
4. ✅ Confirm action
5. ✅ Verify platform model updated
6. ✅ Verify status changed to 'approved'
7. ✅ Check logs

#### Test Rejection
1. ✅ Find pending assignment
2. ✅ Click Reject
3. ✅ Enter reason (too short - should fail)
4. ✅ Enter valid reason (min 10 chars)
5. ✅ Submit
6. ✅ Verify status changed to 'rejected'
7. ✅ Verify reason stored
8. ✅ Check logs

#### Test Filters
1. ✅ Filter by status (all/pending/approved/rejected)
2. ✅ Search by user name
3. ✅ Search by platform name
4. ✅ Search by role
5. ✅ Clear filters

#### Test Pagination
1. ✅ Create 15+ assignments
2. ✅ Verify pagination appears
3. ✅ Navigate between pages
4. ✅ Verify filter persists across pages

---

## Security Considerations

### Current Implementation
- ✅ Requires authentication
- ✅ Requires Super Admin role
- ✅ Database transactions prevent partial updates
- ✅ Validation on rejection reason
- ✅ Confirmation before approval
- ✅ Comprehensive logging

### Recommended Enhancements
1. **Permission-Based Access**: Check specific permission instead of Super Admin
2. **Activity Log**: Track all actions in activity table
3. **Email Notifications**: Notify users when approved/rejected
4. **Audit Trail**: Keep history of status changes
5. **Rate Limiting**: Prevent spam approvals/rejections

---

## Troubleshooting

### Issue: Approval Button Not Working
**Solution**: Check browser console for JavaScript errors. Ensure Livewire is loaded.

### Issue: Platform Not Updating
**Solution**: 
1. Check if platform has the role field (e.g., `owner_id`)
2. Verify role name matches switch case exactly
3. Check logs for errors

### Issue: Search Not Working
**Solution**: Ensure relationships are loaded (`with(['platform', 'user'])`).

### Issue: Pagination Resets on Filter
**Solution**: Already handled with `updatingSearch()` and `updatingSelectedStatus()` methods.

---

## Files Summary

| File | Lines | Purpose |
|------|-------|---------|
| `AssignPlatformRole.php` | ~80 | Model with relationships |
| `2025_11_21_*_create_assign_platform_roles_table.php` | ~40 | Database migration |
| `AssignPlatformRolesIndex.php` | ~220 | Livewire component logic |
| `assign-platform-roles-index.blade.php` | ~180 | UI view |
| `UserPartnerController.php` | ~95 | API controller (updated) |
| `web.php` | +1 line | Route registration |

**Total**: ~616 lines of code

---

## Future Enhancements

### Phase 1
- [ ] Email notifications on approval/rejection
- [ ] Bulk approve/reject
- [ ] Export to CSV/Excel
- [ ] Advanced filters (date range, created by)

### Phase 2
- [ ] History/audit trail tab
- [ ] Revert approved assignments
- [ ] Multiple roles per user per platform
- [ ] Custom role types

### Phase 3
- [ ] API endpoints for approve/reject
- [ ] Mobile-responsive improvements
- [ ] Real-time notifications (Pusher/Echo)
- [ ] Dashboard widgets

---

**Created**: November 21, 2025  
**Version**: 1.0  
**Status**: Production Ready ✅

