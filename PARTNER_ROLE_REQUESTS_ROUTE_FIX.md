# Partner Role Requests Route - Issue Resolved

## Date: January 22, 2026

## Issue
Error: `Route [partner_role_requests] not defined.`
Location: `resources\views\livewire\partner-entity-role-manager.blade.php:28`

---

## Root Cause
The route was properly defined in `routes/web.php`, but Laravel's cached routes were not updated after the route was added, causing the application to not recognize the new route.

---

## Solution Applied

### 1. Verified Route Registration
**File:** `routes/web.php` (Line 241)
```php
Route::prefix('/partner')->name('partner_')->group(function () {
    Route::get('/index', \App\Livewire\PartnerIndex::class)->name('index');
    Route::get('/{partnerId}/roles', \App\Livewire\PartnerEntityRoleManager::class)->name('roles');
    Route::get('/role-requests', \App\Livewire\PartnerRoleRequestManage::class)->name('role_requests');
    // ...
});
```

**Route Details:**
- **Name:** `partner_role_requests`
- **Path:** `{locale}/partner/role-requests`
- **Component:** `App\Livewire\PartnerRoleRequestManage`

### 2. Cleared All Caches
Executed the following commands to clear Laravel's caches:

```bash
php artisan route:clear
php artisan route:cache
php artisan view:clear
php artisan config:clear
php artisan cache:clear
```

### 3. Verified Route Accessibility
Tested the route using Tinker:
```bash
php artisan tinker --execute="echo route('partner_role_requests', 'en');"
```

**Output:**
```
http://2earn.test/en/partner/role-requests
```

✅ Route is working correctly!

---

## Blade File Implementation
**File:** `resources/views/livewire/partner-entity-role-manager.blade.php` (Line 28)

```blade
<a href="{{ route('partner_role_requests', app()->getLocale()) }}"
   class="btn btn-outline-primary btn-sm">
    <i class="ri-file-list-3-line me-1"></i>{{ __('Partner Role Requests') }}
</a>
```

This follows the same pattern as other routes in the file (e.g., `partner_index`).

---

## Route Confirmation

Running `php artisan route:list --path=partner/role` shows:

```
GETHEAD   {locale}/partner/role-requests ........ partner_role_requests › App\Livewire\PartnerRoleRequestManage
```

---

## Status

✅ Route is registered correctly in web.php
✅ Route cache cleared and regenerated
✅ View cache cleared
✅ Config cache cleared
✅ Application cache cleared
✅ Route is accessible via route() helper
✅ Blade syntax is correct
✅ Issue resolved

---

## Prevention

To avoid this issue in the future, remember to clear caches after adding new routes:

```bash
php artisan route:clear
```

Or during development, disable route caching entirely by not using `route:cache`.

---

## Testing

To test the link:
1. Navigate to any partner's entity role management page: `/{locale}/partner/{partnerId}/roles`
2. Click the "Partner Role Requests" button in the header
3. You should be redirected to: `/{locale}/partner/role-requests`

The link is now fully functional! 🎉
