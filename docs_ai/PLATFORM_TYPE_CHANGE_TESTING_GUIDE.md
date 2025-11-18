# Platform Type Change - Testing & Verification Guide

## 🧪 Complete Testing Guide

### Pre-Testing Checklist
- [x] Database migration executed
- [x] Routes registered
- [x] Models created
- [x] Controllers updated
- [x] Views created
- [x] No critical errors

---

## 1️⃣ API Endpoint Testing

### Test 1: Valid Type Change (Type 3 → Type 1)
**Setup:** Find or create a platform with type = 3

```bash
# Request
curl -X POST "http://localhost/api/partner/platform/change" \
  -H "Content-Type: application/json" \
  -d '{
    "platform_id": 1,
    "type_id": 1
  }'

# Expected Response (201)
{
  "status": true,
  "message": "Platform type change request created successfully",
  "data": {
    "id": 1,
    "platform_id": 1,
    "old_type": 3,
    "new_type": 1,
    "status": "pending",
    "created_at": "2025-11-18T14:06:38.000000Z",
    "updated_at": "2025-11-18T14:06:38.000000Z"
  }
}
```

### Test 2: Valid Type Change (Type 3 → Type 2)
```bash
curl -X POST "http://localhost/api/partner/platform/change" \
  -H "Content-Type: application/json" \
  -d '{
    "platform_id": 2,
    "type_id": 2
  }'

# Expected: 201 Created
```

### Test 3: Valid Type Change (Type 2 → Type 1)
```bash
curl -X POST "http://localhost/api/partner/platform/change" \
  -H "Content-Type: application/json" \
  -d '{
    "platform_id": 3,
    "type_id": 1
  }'

# Expected: 201 Created
```

### Test 4: Invalid - Type 1 Cannot Change
```bash
curl -X POST "http://localhost/api/partner/platform/change" \
  -H "Content-Type: application/json" \
  -d '{
    "platform_id": 4,
    "type_id": 2
  }'

# Expected Response (403)
{
  "status": "Failed",
  "message": "Type 1 (Full) platforms cannot change their type"
}
```

### Test 5: Invalid - Type 2 Cannot Change to Type 3
```bash
curl -X POST "http://localhost/api/partner/platform/change" \
  -H "Content-Type: application/json" \
  -d '{
    "platform_id": 5,
    "type_id": 3
  }'

# Expected Response (403)
{
  "status": "Failed",
  "message": "Type 2 platforms can only change to types: 1"
}
```

### Test 6: Invalid - Same Type
```bash
curl -X POST "http://localhost/api/partner/platform/change" \
  -H "Content-Type: application/json" \
  -d '{
    "platform_id": 6,
    "type_id": 3
  }'

# Expected Response (422)
{
  "status": "Failed",
  "message": "New type cannot be the same as current type"
}
```

### Test 7: Invalid - Non-existent Platform
```bash
curl -X POST "http://localhost/api/partner/platform/change" \
  -H "Content-Type: application/json" \
  -d '{
    "platform_id": 99999,
    "type_id": 1
  }'

# Expected Response (404)
{
  "status": "Failed",
  "message": "Platform not found"
}
```

### Test 8: Validation - Missing Parameters
```bash
curl -X POST "http://localhost/api/partner/platform/change" \
  -H "Content-Type: application/json" \
  -d '{
    "platform_id": 1
  }'

# Expected Response (422)
{
  "status": "Failed",
  "message": "Validation failed",
  "errors": {
    "type_id": ["The type id field is required."]
  }
}
```

---

## 2️⃣ Admin UI Testing

### Test 1: Access Type Change Requests Page
1. Login as admin
2. Navigate to: `http://localhost/{locale}/platform/type-change/requests`
3. **Expected:** Page loads showing list of requests

### Test 2: View Platform Index with Pending Request
1. Navigate to: `http://localhost/{locale}/platform/index`
2. Find platform with pending request
3. **Expected:** 
   - Warning badge visible: "⚠ Pending Type Change Request"
   - Type change details shown: "Paiement → Full"
   - "Validate" button visible

### Test 3: Click Validate Button
1. Click "Validate" button on platform card
2. **Expected:** Redirects to Type Change Requests page

### Test 4: Click Header Button
1. On Platform Index page
2. Click "Type Change Requests" button in header
3. **Expected:** Redirects to Type Change Requests page

### Test 5: Search Functionality
1. On Type Change Requests page
2. Enter platform name in search box
3. **Expected:** Results filter in real-time (300ms debounce)

### Test 6: Status Filter
1. Select "Pending" from status dropdown
2. **Expected:** Shows only pending requests
3. Select "Approved"
4. **Expected:** Shows only approved requests
5. Select "All"
6. **Expected:** Shows all requests

### Test 7: Approve Request
1. Find a pending request
2. Click "Approve" button
3. **Expected:** Confirmation dialog appears
4. Confirm approval
5. **Expected:**
   - Success message: "Platform type change request approved successfully"
   - Request status badge changes to "Approved"
   - Platform type in database updated
   - Request status in database = "approved"

### Test 8: Reject Request
1. Find a pending request
2. Click "Reject" button
3. **Expected:** Confirmation dialog appears
4. Confirm rejection
5. **Expected:**
   - Success message: "Platform type change request rejected successfully"
   - Request status badge changes to "Rejected"
   - Platform type in database unchanged
   - Request status in database = "rejected"

### Test 9: Prevent Reprocessing
1. Find an approved or rejected request
2. **Expected:** No action buttons visible
3. **Expected:** Message displays: "Request processed"

### Test 10: Pagination
1. Create more than 10 requests
2. Navigate to Type Change Requests page
3. **Expected:** Pagination controls visible
4. Click "Next" page
5. **Expected:** Shows next set of results

---

## 3️⃣ Database Verification

### Verify Table Structure
```sql
DESCRIBE platform_type_change_requests;

-- Expected columns:
-- id, platform_id, old_type, new_type, status, created_at, updated_at
```

### Verify Foreign Key
```sql
SELECT 
    CONSTRAINT_NAME,
    TABLE_NAME,
    REFERENCED_TABLE_NAME
FROM information_schema.KEY_COLUMN_USAGE
WHERE TABLE_NAME = 'platform_type_change_requests'
AND REFERENCED_TABLE_NAME IS NOT NULL;

-- Expected: Foreign key to platforms table with CASCADE delete
```

### Verify Indexes
```sql
SHOW INDEXES FROM platform_type_change_requests;

-- Expected: Index on (platform_id, status)
```

### Check Request Records
```sql
SELECT * FROM platform_type_change_requests;

-- Verify data integrity
```

### Check Platform Type After Approval
```sql
-- Before approval
SELECT id, name, type FROM platforms WHERE id = 1;

-- Approve request via UI

-- After approval
SELECT id, name, type FROM platforms WHERE id = 1;

-- Expected: type should be updated to new_type from request
```

---

## 4️⃣ Integration Testing

### Test 1: End-to-End Workflow
1. **API:** Create type change request via API
2. **Database:** Verify request created with status = "pending"
3. **UI:** Platform Index shows warning badge
4. **UI:** Click "Validate" → redirects to requests page
5. **UI:** Approve request
6. **Database:** Verify platform type updated
7. **Database:** Verify request status = "approved"
8. **UI:** Warning badge disappears from Platform Index

### Test 2: Multiple Requests for Same Platform
1. Create type change request for platform A
2. Try to create another request for platform A
3. **Expected:** Both requests can be created
4. Approve first request
5. **Expected:** Platform type changes
6. Second request should show transition from new type

### Test 3: Concurrent Admin Actions
1. Admin A views pending requests
2. Admin B views same pending requests
3. Admin A approves request #1
4. Admin B tries to approve same request #1
5. **Expected:** Error message: "This request has already been processed"

---

## 5️⃣ Error Handling Tests

### Test 1: Database Connection Lost
1. Stop database server
2. Try to approve request
3. **Expected:** Graceful error message
4. Check logs for detailed error

### Test 2: Invalid Request ID
1. Manually call `approveRequest(99999)` with non-existent ID
2. **Expected:** Error caught and displayed

### Test 3: Platform Deleted Before Approval
1. Create request for platform A
2. Delete platform A
3. Try to approve request
4. **Expected:** Error handled gracefully (cascade delete removes request)

---

## 6️⃣ Performance Testing

### Test 1: N+1 Query Check
1. Create 50 requests
2. Access Type Change Requests page
3. Check Laravel Debugbar or logs
4. **Expected:** Eager loading prevents N+1 queries

### Test 2: Search Performance
1. Create 100+ requests
2. Search by platform name
3. **Expected:** Results return quickly (< 500ms)

### Test 3: Pagination Performance
1. Navigate through multiple pages
2. **Expected:** Consistent load times

---

## 7️⃣ Security Testing

### Test 1: SQL Injection
```bash
curl -X POST "http://localhost/api/partner/platform/change" \
  -H "Content-Type: application/json" \
  -d '{
    "platform_id": "1 OR 1=1",
    "type_id": 1
  }'

# Expected: Validation error, not SQL execution
```

### Test 2: XSS Prevention
1. Create platform with name: `<script>alert('XSS')</script>`
2. Create type change request
3. View on requests page
4. **Expected:** Script tag escaped, not executed

### Test 3: Authorization
1. Access requests page without login
2. **Expected:** Redirect to login (if auth middleware active)

---

## 8️⃣ Logging Verification

### Check Application Logs
```bash
# View recent logs
tail -f storage/logs/laravel.log

# Search for platform type change logs
grep "PlatformPartnerController" storage/logs/laravel.log
grep "PlatformTypeChangeRequests" storage/logs/laravel.log
```

### Expected Log Entries
1. **Request Creation:**
   ```
   [PlatformPartnerController] Platform type change request created
   ```

2. **Approval:**
   ```
   [PlatformTypeChangeRequests] Request approved
   ```

3. **Rejection:**
   ```
   [PlatformTypeChangeRequests] Request rejected
   ```

4. **Errors:**
   ```
   [PlatformPartnerController] Error approving request
   ```

---

## 9️⃣ Browser Compatibility Testing

### Test on Multiple Browsers
- [ ] Chrome/Edge (Chromium)
- [ ] Firefox
- [ ] Safari (if on Mac)
- [ ] Mobile Chrome
- [ ] Mobile Safari

### Test Responsive Design
- [ ] Desktop (1920x1080)
- [ ] Tablet (768x1024)
- [ ] Mobile (375x667)

---

## 🔟 Localization Testing

### Test Language Support
1. Switch application locale
2. Navigate to Type Change Requests page
3. **Expected:** All labels translated
4. Check for missing translations

### Translation Keys to Verify
- `Pending Type Change Request`
- `Type Change Requests`
- `Approve`
- `Reject`
- `From`
- `To`
- `Pending`
- `Approved`
- `Rejected`

---

## ✅ Final Verification Checklist

### Routes
- [ ] API route registered: `POST /api/partner/platform/change`
- [ ] Web route registered: `GET /{locale}/platform/type-change/requests`
- [ ] Routes accessible without errors

### Database
- [ ] Migration executed successfully
- [ ] Table created with correct structure
- [ ] Foreign keys working
- [ ] Indexes created
- [ ] Sample data can be inserted

### Models
- [ ] PlatformTypeChangeRequest model works
- [ ] Relationships defined correctly
- [ ] Fillable fields set
- [ ] Can query and manipulate data

### Controllers
- [ ] API method validates input correctly
- [ ] API method creates requests
- [ ] Livewire component loads requests
- [ ] Approve function works
- [ ] Reject function works

### Views
- [ ] Type Change Requests page renders
- [ ] Platform Index shows warning badges
- [ ] Validate buttons link correctly
- [ ] Status badges display correctly
- [ ] Search works
- [ ] Filters work
- [ ] Pagination works

### Error Handling
- [ ] Validation errors display
- [ ] Database errors caught
- [ ] User-friendly error messages
- [ ] Errors logged properly

### Performance
- [ ] No N+1 queries
- [ ] Page loads quickly
- [ ] Search is responsive
- [ ] No memory leaks

### Security
- [ ] SQL injection prevented
- [ ] XSS prevented
- [ ] CSRF protection active
- [ ] Authorization working

---

## 🐛 Common Issues & Solutions

### Issue 1: Warning Badge Not Showing
**Solution:** Clear cache and verify eager loading
```bash
php artisan cache:clear
php artisan view:clear
```

### Issue 2: Routes Not Found
**Solution:** Clear route cache
```bash
php artisan route:clear
php artisan route:cache
```

### Issue 3: Database Error on Approval
**Solution:** Check foreign key constraints and platform existence

### Issue 4: Livewire Not Updating
**Solution:** Clear Livewire temporary files
```bash
php artisan livewire:delete-uploaded-files
```

### Issue 5: Search Not Working
**Solution:** Check Livewire version and wire:model syntax

---

## 📊 Test Results Template

```
Date: _______________
Tester: _______________

API Tests:
[ ] Test 1-8: Pass / Fail

UI Tests:
[ ] Test 1-10: Pass / Fail

Database Tests:
[ ] Verification: Pass / Fail

Integration Tests:
[ ] Test 1-3: Pass / Fail

Error Handling:
[ ] Test 1-3: Pass / Fail

Performance:
[ ] Test 1-3: Pass / Fail

Security:
[ ] Test 1-3: Pass / Fail

Logging:
[ ] Verification: Pass / Fail

Browser Compatibility:
[ ] All browsers: Pass / Fail

Localization:
[ ] Translations: Pass / Fail

Final Status: PASS / FAIL
Notes: _______________________________________________
```

---

## 🎯 Success Criteria

All tests must pass for production deployment:
- ✅ API endpoint responds correctly to all scenarios
- ✅ Admin UI loads and functions properly
- ✅ Database operations complete successfully
- ✅ No errors in logs (except expected validation errors)
- ✅ Performance is acceptable (< 1s page load)
- ✅ Security tests pass
- ✅ Works across browsers and devices

---

**Testing Version:** 1.0  
**Last Updated:** November 18, 2025  
**Status:** Ready for Testing

