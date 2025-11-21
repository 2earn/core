# Assign Platform Roles - Quick Reference

## 🎯 Quick Access
**URL**: `/{locale}/platform/role-assignments` (e.g., `/en/platform/role-assignments`)  
**Role Required**: Super Admin

---

## 📊 Component Features

### ✅ Display & Filter
- View all role assignment requests
- Filter by status: All / Pending / Approved / Rejected
- Search by: user name, email, platform, role
- Pagination (10 per page)

### ✅ Approve Assignment
- Updates platform model with user
- Changes status to 'approved'
- Logs action

### ✅ Reject Assignment
- Requires reason (min 10 chars)
- Changes status to 'rejected'
- Stores rejection reason
- Logs action

---

## 🔄 Workflow

```
API Request → Creates Assignment (pending)
     ↓
Admin Reviews → Approve or Reject
     ↓
Approve: Updates Platform Model
Reject: Stores Rejection Reason
```

---

## 📝 Role Mapping

| Role | Platform Field |
|------|----------------|
| `owner` | `owner_id` |
| `marketing_manager` | `marketing_manager_id` |
| `financial_manager` | `financial_manager_id` |

---

## 🗂️ Database Table: `assign_platform_roles`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint | Primary key |
| `platform_id` | bigint | Foreign key to platforms |
| `user_id` | bigint | User to be assigned |
| `role` | string | Role name |
| `status` | string | pending/approved/rejected |
| `rejection_reason` | text | Reason if rejected |
| `created_by` | bigint | Request creator |
| `updated_by` | bigint | Last updater |

---

## 🔌 API Endpoint

**Endpoint**: `POST /api/partner/users/add-role`

**Request**:
```json
{
  "platform_id": 1,
  "user_id": 123,
  "role": "owner"
}
```

**Response**:
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

## 💻 Component Methods

### Approve
```php
wire:click="approve($assignmentId)"
```
- Validates pending status
- Updates platform model
- Sets status to 'approved'
- Shows success message

### Reject
```php
wire:click="openRejectModal($assignmentId)"
```
- Opens modal
- Validates reason (min 10 chars)
- Sets status to 'rejected'
- Stores reason
- Shows success message

### Filter
```php
wire:model.live="selectedStatus"
wire:model.live.debounce.300ms="search"
```

---

## 🎨 Status Badges

- 🟡 **Pending**: Yellow badge, shows Approve/Reject buttons
- 🟢 **Approved**: Green badge, shows "Processed"
- 🔴 **Rejected**: Red badge, shows "View Reason" button

---

## 📁 Files

| File | Location |
|------|----------|
| Model | `app/Models/AssignPlatformRole.php` |
| Component | `app/Livewire/AssignPlatformRolesIndex.php` |
| View | `resources/views/livewire/assign-platform-roles-index.blade.php` |
| Migration | `database/migrations/2025_11_21_*_create_assign_platform_roles_table.php` |
| Controller | `app/Http/Controllers/Api/partner/UserPartnerController.php` |

---

## 🧪 Quick Test

### 1. Create Assignment
```bash
curl -X POST http://localhost/api/partner/users/add-role \
  -H "Content-Type: application/json" \
  -d '{"platform_id": 1, "user_id": 123, "role": "owner"}'
```

### 2. View in UI
Visit: `/en/platform/role-assignments`

### 3. Approve/Reject
Click buttons in the UI

### 4. Verify
Check platform table for updated user ID

---

## 📊 Query Examples

### Get Pending Assignments
```php
AssignPlatformRole::where('status', 'pending')->get();
```

### Get Rejected with Reasons
```php
AssignPlatformRole::where('status', 'rejected')
    ->whereNotNull('rejection_reason')
    ->get();
```

### Get User's Assignments
```php
AssignPlatformRole::where('user_id', $userId)
    ->with('platform')
    ->get();
```

---

## 🔍 Logging

**Search in logs**: `[AssignPlatformRolesIndex]`

**Log Location**: `storage/logs/laravel.log`

---

## ⚡ Quick Actions

### Run Migration
```bash
php artisan migrate
```

### Clear Cache
```bash
php artisan cache:clear
php artisan view:clear
```

### View Routes
```bash
php artisan route:list | grep platform
```

---

## 🚨 Troubleshooting

| Issue | Solution |
|-------|----------|
| Button not working | Check Livewire is loaded |
| Platform not updating | Verify role field exists |
| Search not working | Check relationships loaded |
| Permission denied | Verify Super Admin role |

---

**Created**: November 21, 2025  
**Status**: Ready to Use ✅

