# Entity Role Management Implementation

## Overview
This document describes the implementation of Entity Role Management for both Platform and Partner modules. The feature allows administrators to assign roles to specific entities (Platforms or Partners) and optionally associate users with those roles.

## Implementation Date
January 16, 2026

---

## Components Created

### 1. Platform Entity Role Manager

#### Files:
- **Component**: `app/Livewire/PlatformEntityRoleManager.php`
- **View**: `resources/views/livewire/platform-entity-role-manager.blade.php`
- **Route**: `{locale}/platform/{platformId}/roles` → named as `platform_roles`

#### Features:
- Add new roles to a platform
- Assign users to roles (optional)
- Edit existing roles
- Update user assignments
- Revoke (delete) roles
- Real-time user search with autocomplete
- Paginated role list
- Audit trail (created by, updated by)

### 2. Partner Entity Role Manager

#### Files:
- **Component**: `app/Livewire/PartnerEntityRoleManager.php`
- **View**: `resources/views/livewire/partner-entity-role-manager.blade.php`
- **Route**: `{locale}/partner/{partnerId}/roles` → named as `partner_roles`

#### Features:
- Same functionality as Platform Entity Role Manager
- Adapted for Partner entities

---

## Database Structure

### EntityRole Model
The `EntityRole` model uses polymorphic relationships to support both Platform and Partner entities.

**Relationships:**
- `roleable()` - Polymorphic relationship (Platform or Partner)
- `user()` - BelongsTo User (assigned user)
- `creator()` - BelongsTo User (who created the role)
- `updater()` - BelongsTo User (who last updated the role)

**Fields:**
- `id` - Primary key
- `name` - Role name (e.g., Admin, Manager, Viewer)
- `roleable_id` - ID of the entity (Platform or Partner)
- `roleable_type` - Type of entity (App\Models\Platform or App\Models\Partner)
- `user_id` - ID of assigned user (nullable)
- `created_by` - User who created the role
- `updated_by` - User who last updated the role
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

---

## User Interface

### Platform Index
Added "Manage Roles" button in the platform card actions:
- **Location**: `resources/views/livewire/platform-index.blade.php`
- **Button**: Blue button with shield icon
- **Route**: Links to `platform_roles` route

### Partner Index
Added "Manage Roles" button in the partner card actions:
- **Location**: `resources/views/livewire/partner-index.blade.php`
- **Button**: Blue button with shield icon
- **Route**: Links to `partner_roles` route

### Entity Role Manager UI
Both Platform and Partner role managers share the same modern, responsive design:

#### Add Role Section
- Role name input (required)
- User search with autocomplete dropdown
- Real-time user filtering by name or email
- Selected user preview with remove option
- Add button to submit

#### Roles List Section
- Card-based layout for each role
- Display role name with ID badge
- Show assigned user (with avatar and details)
- Display creation and update information
- Edit and Revoke buttons for each role
- Inline edit mode with form
- Pagination support
- Empty state message when no roles exist

---

## Routes

### Platform Routes
```php
Route::prefix('/platform')->name('platform_')->group(function () {
    Route::get('/index', \App\Livewire\PlatformIndex::class)->name('index');
    Route::get('/{platformId}/roles', \App\Livewire\PlatformEntityRoleManager::class)->name('roles');
});
```

### Partner Routes
```php
Route::prefix('/partner')->name('partner_')->group(function () {
    Route::get('/index', \App\Livewire\PartnerIndex::class)->name('index');
    Route::get('/{partnerId}/roles', \App\Livewire\PartnerEntityRoleManager::class)->name('roles');
    Route::middleware(['IsSuperAdmin'])->group(function () {
        Route::get('/create', \App\Livewire\PartnerCreateUpdate::class)->name('create');
        Route::get('/{id}/edit', \App\Livewire\PartnerCreateUpdate::class)->name('update');
        Route::get('/{id}/show', \App\Livewire\PartnerShow::class)->name('show');
    });
});
```

---

## Component Methods

### PlatformEntityRoleManager & PartnerEntityRoleManager

Both components implement the same methods:

#### Public Properties
- `$platformId` / `$partnerId` - Entity ID
- `$platform` / `$partner` - Entity model instance
- `$newRoleName` - Role name for new role
- `$newRoleUserId` - User ID for new role assignment
- `$userSearch` - Search term for user autocomplete
- `$showUserDropdown` - Boolean to show/hide dropdown
- `$editingRoleId` - ID of role being edited
- `$editRoleName` - Role name during editing
- `$editRoleUserId` - User ID during editing

#### Methods
1. **`mount($entityId)`** - Initialize component with entity
2. **`updatedUserSearch()`** - Toggle user dropdown visibility
3. **`selectUser($userId)`** - Handle user selection from dropdown
4. **`addRole()`** - Create new entity role
5. **`editRole($roleId)`** - Enter edit mode for a role
6. **`updateRole()`** - Save changes to existing role
7. **`cancelEdit()`** - Exit edit mode without saving
8. **`revokeRole($roleId)`** - Delete a role
9. **`getSearchedUsersProperty()`** - Computed property for user search results
10. **`render()`** - Render the view with paginated roles

---

## Validation Rules

### Add Role
```php
'newRoleName' => 'required|string|max:255'
'newRoleUserId' => 'nullable|exists:users,id'
```

### Update Role
```php
'editRoleName' => 'required|string|max:255'
'editRoleUserId' => 'nullable|exists:users,id'
```

---

## Usage Examples

### Access Platform Role Manager
1. Navigate to Platform Index: `/{locale}/platform/index`
2. Click "Manage Roles" button on any platform card
3. URL: `/{locale}/platform/{platformId}/roles`

### Access Partner Role Manager
1. Navigate to Partner Index: `/{locale}/partner/index`
2. Click "Manage Roles" button on any partner card
3. URL: `/{locale}/partner/{partnerId}/roles`

### Add a New Role
1. Enter role name (e.g., "Admin", "Manager")
2. Optionally search and select a user
3. Click "Add Role" button
4. Success message will appear

### Edit a Role
1. Click edit icon (pencil) on any role
2. Modify role name or change assigned user
3. Click green check button to save
4. Click gray X button to cancel

### Revoke a Role
1. Click delete icon (trash) on any role
2. Confirm deletion in the popup
3. Role will be removed from database

---

## Security & Authorization

- Routes are protected by authentication middleware
- User assignments validate that the user exists in the database
- All database operations are wrapped in try-catch blocks
- Error messages are logged for debugging
- Flash messages provide user feedback

---

## Design Patterns Used

1. **Polymorphic Relationships** - EntityRole works with both Platform and Partner
2. **Repository Pattern** - Service layer abstracts database queries
3. **Component-Based Architecture** - Livewire components for reactive UI
4. **Separation of Concerns** - Business logic in component, presentation in blade
5. **DRY Principle** - Shared functionality between Platform and Partner managers

---

## Styling

- Bootstrap 5 utility classes
- Custom hover effects
- Responsive design (mobile-friendly)
- Icon library: RemixIcon (`ri-*`)
- Color scheme: Primary (blue), Info, Warning, Danger, Success
- Card-based layout for better visual hierarchy

---

## Future Enhancements (Potential)

1. Role permissions management
2. Bulk role assignment
3. Role templates
4. Export roles to CSV/Excel
5. Role usage analytics
6. Email notifications on role assignment
7. Role expiration dates
8. Role approval workflow

---

## Testing Checklist

- [x] Routes registered correctly
- [x] Components created without errors
- [x] Views render properly
- [x] User search autocomplete works
- [x] Add role functionality
- [x] Edit role functionality
- [x] Delete role functionality
- [x] Validation messages display
- [x] Flash messages work
- [x] Pagination works
- [x] Breadcrumbs navigation
- [x] Responsive design on mobile
- [x] Icons display correctly

---

## Related Files

### Models
- `app/Models/EntityRole.php`
- `app/Models/Platform.php`
- `app/Models/Partner.php`
- `app/Models/User.php`

### Migrations
- `database/migrations/2026_01_15_120000_create_entity_roles_table.php`
- `database/migrations/2026_01_16_082234_add_user_id_to_entity_roles_table.php`

### Documentation
- `ENTITY_ROLE_REFACTORING.md` - Original EntityRole model documentation
- `ENTITY_ROLE_USER_RELATION.md` - User relationship documentation
- `PLATFORM_PARTNER_ENTITY_ROLE_MANAGEMENT.md` - This document

---

## Support & Maintenance

For issues or questions regarding entity role management:
1. Check error logs in `storage/logs/laravel.log`
2. Verify database migrations are up to date
3. Clear route cache: `php artisan route:clear`
4. Clear view cache: `php artisan view:clear`
5. Check Livewire documentation for component-specific issues

---

## Conclusion

The Entity Role Management system provides a flexible and user-friendly way to manage roles for both Platforms and Partners. The implementation follows Laravel and Livewire best practices, ensuring maintainability and scalability.
