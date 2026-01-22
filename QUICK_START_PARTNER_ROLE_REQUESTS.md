# Partner Role Request - Quick Start Guide

## Installation Complete ✅

The Partner Role Request system has been successfully installed and configured.

## What Was Created

### 1. Database Table
- `partner_role_requests` - Migration executed ✅

### 2. Backend Components
- **Model**: `PartnerRoleRequest.php`
- **Controller**: `PartnerRolePartnerController.php`
- **Livewire**: `PartnerRoleRequestManage.php`
- **Factory**: `PartnerRoleRequestFactory.php`

### 3. Frontend Components
- **View**: `partner-role-request-manage.blade.php`

### 4. Partner API Routes (all registered ✅)
```
GET    /api/partner/role-requests           - List requests
GET    /api/partner/role-requests/{id}      - Get request
POST   /api/partner/role-requests           - Create request
POST   /api/partner/role-requests/{id}/cancel - Cancel request
```

**Admin Actions (Livewire Component Only):**
- Approve requests (creates EntityRole)
- Reject requests (with reason)

---

## Quick Test

### Create a Request (Partner API)
```bash
curl -X POST "http://localhost/api/partner/role-requests" \
  -H "Content-Type: application/json" \
  -d '{
    "partner_id": 1,
    "user_id": 5,
    "role_name": "manager",
    "reason": "Need a manager",
    "requested_by": 2
  }'
```

### List All Requests (Partner API)
```bash
curl "http://localhost/api/partner/role-requests?partner_id=1"
```

### Approve/Reject Requests (Admin Back Office)
Use the Livewire component in your admin panel:
```php
Route::get('/admin/partner-role-requests', function () {
    return view('your-layout', [
        'component' => '<livewire:partner-role-request-manage />'
    ]);
});
```

---

## Files Reference

### Documentation
- 📄 `PARTNER_ROLE_REQUEST_SYSTEM.md` - Full system documentation
- 📄 `PARTNER_ROLE_REQUEST_IMPLEMENTATION_SUMMARY.md` - Implementation details
- 📄 `partner-role-requests-api.postman_collection.json` - Postman collection

### Code Files
- 📁 `app/Models/PartnerRoleRequest.php`
- 📁 `app/Http/Controllers/Api/partner/PartnerRolePartnerController.php`
- 📁 `app/Livewire/PartnerRoleRequestManage.php`
- 📁 `resources/views/livewire/partner-role-request-manage.blade.php`
- 📁 `database/factories/PartnerRoleRequestFactory.php`
- 📁 `database/migrations/2026_01_22_093629_create_partner_role_requests_table.php`
- 📁 `tests/Feature/Api/Partner/PartnerRoleRequestTest.php`

---

## Key Features

✅ Create role assignment requests for partners (Partner API)
✅ Approve requests - creates EntityRole automatically (Admin only)
✅ Reject requests with reasons (Admin only)
✅ Cancel pending requests (Partner API)
✅ List and filter requests (Partner API)
✅ Statistics dashboard (Admin UI)
✅ Full API support for partners
✅ Livewire management UI for admins

---

## Workflow

1. **Create Request** → Partner user requests a role assignment via API
2. **Review** → Admin reviews via Livewire component in back office
3. **Approve** → Admin approves, creates EntityRole in database automatically
4. **Reject** → Admin rejects with reason, no EntityRole created
5. **Cancel** → Partner cancels pending request via API

---

## Status Values

- `pending` - Awaiting review
- `approved` - Approved by admin, EntityRole created
- `rejected` - Rejected by admin with reason
- `cancelled` - Cancelled by partner user

---

## Permissions

### Partner Users Can:
- ✅ Create role requests
- ✅ View their requests
- ✅ Cancel pending requests

### Admin Users Can:
- ✅ View all requests
- ✅ Approve requests (creates EntityRole)
- ✅ Reject requests (with reason)
- ✅ View statistics and history

---

## Next Actions

1. **Import Postman Collection** to test Partner API endpoints
2. **Run Tests**: `php artisan test --filter PartnerRoleRequestTest`
3. **Add Livewire to Admin Menu** for approving/rejecting
4. **Configure Admin Permissions** as needed
5. **Add Notifications** (optional enhancement)

---

## Support Files

All documentation and examples are in the project root:
- Full API documentation
- Postman collection for testing
- PHPUnit tests
- Implementation summary

Enjoy your new Partner Role Request system! 🎉
