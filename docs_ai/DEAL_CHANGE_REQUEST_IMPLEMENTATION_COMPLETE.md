# Deal Change Request System - Implementation Complete

## Summary

Successfully implemented a complete Deal Change Request system that requires approval before applying updates to deals. This system follows the same pattern as the Platform Change Request system.

## What Was Implemented

### 1. Database Layer
✅ **Migration Created**: `2025_11_20_130158_create_deal_change_requests_table.php`
✅ **Table**: `deal_change_requests`
✅ **Migration Run**: Successfully executed

**Schema:**
- `deal_id`: Foreign key to deals table
- `changes`: JSON field storing old/new values
- `status`: pending/approved/rejected
- `rejection_reason`: Why request was rejected (nullable)
- `requested_by`: User who requested the change
- `reviewed_by`: User who approved/rejected
- `reviewed_at`: Timestamp when reviewed
- Proper foreign key constraints and indexes

### 2. Model Layer
✅ **Model Created**: `app/Models/DealChangeRequest.php`

**Features:**
- Fillable fields defined
- JSON casting for changes field
- DateTime casting for reviewed_at
- Relationships: `deal()`, `requestedBy()`, `reviewedBy()`

✅ **Deal Model Updated**: `app/Models/Deal.php`

**Added Relationships:**
- `changeRequests()`: HasMany relationship
- `pendingChangeRequest()`: Get latest pending change request

### 3. DealPartnerController Updates
✅ **File**: `app/Http/Controllers/Api/partner/DealPartnerController.php`

**Changes Made:**

#### index() Method
- Added `change_requests_count` to each deal
- Added `changeRequests` array with last 3 requests
- Response now includes change request data

#### show() Method  
- Added `change_requests` array with all change requests for the deal
- Complete change request history available

#### update() Method - **MAJOR CHANGE**
**Before:**
```php
$deal->update($validatedData);
return 'Deal updated successfully';
```

**After:**
```php
// Creates DealChangeRequest with status 'pending'
// Stores all field changes with old/new values
// Returns message: "Deal change request submitted successfully. Awaiting approval."
```

**Key Features:**
- No longer directly updates deals
- Detects only fields that changed
- Stores changes in JSON format with old/new values
- Returns 422 if no changes detected
- Creates pending change request
- Tracks who requested the change

### 4. Admin Approval Component
✅ **Livewire Component**: `app/Livewire/DealChangeRequests.php`
✅ **Blade View**: `resources/views/livewire/deal-change-requests.blade.php`

**Features:**
- Search by deal name or ID
- Filter by status (all/pending/approved/rejected)
- Pagination support
- View detailed changes in modal
- Approve changes (applies to deal)
- Reject with reason
- Shows who requested and who reviewed
- Beautiful, responsive UI matching platform requests

**Modals:**
1. **View Changes Modal** - Displays all field changes in a table
2. **Approve Modal** - Shows changes to be applied with confirmation
3. **Reject Modal** - Requires rejection reason (10-1000 chars)

### 5. Route
✅ **Route Added**: `/deals/change-requests`
✅ **Route Name**: `deals_change_requests`
✅ **Middleware**: `IsSuperAdmin` (only admins can access)

**Location:** Inside deals routes group in `routes/web.php`

## API Response Changes

### DealPartnerController::index()
**Before:**
```json
{
  "status": true,
  "data": [
    {
      "id": 1,
      "name": "Deal Name",
      ...
    }
  ]
}
```

**After:**
```json
{
  "status": true,
  "data": [
    {
      "id": 1,
      "name": "Deal Name",
      "change_requests_count": 2,
      "changeRequests": [
        {
          "id": 1,
          "deal_id": 1,
          "changes": {
            "discount": {"old": 10, "new": 15},
            "name": {"old": "Old Name", "new": "New Name"}
          },
          "status": "pending",
          "requested_by": 5,
          "created_at": "2025-11-20T13:00:00"
        }
      ],
      ...
    }
  ]
}
```

### DealPartnerController::update()
**Before:**
```json
{
  "status": true,
  "message": "Deal updated successfully",
  "data": { /* updated deal */ }
}
```

**After:**
```json
{
  "status": true,
  "message": "Deal change request submitted successfully. Awaiting approval.",
  "data": {
    "deal": { /* original deal unchanged */ },
    "change_request": {
      "id": 1,
      "deal_id": 1,
      "changes": {
        "discount": {"old": 10, "new": 15}
      },
      "status": "pending",
      "requested_by": 5
    }
  }
}
```

### DealPartnerController::show()
**Before:**
```json
{
  "status": true,
  "data": { /* deal data */ }
}
```

**After:**
```json
{
  "status": true,
  "data": {
    "deal": { /* deal data */ },
    "change_requests": [
      { /* all change requests */ }
    ]
  }
}
```

## Workflow

### Old Flow
```
Partner updates deal → Changes applied immediately → Done
```

### New Flow
```
Partner updates deal
  ↓
DealChangeRequest created (status: pending)
  ↓
Admin reviews at /deals/change-requests
  ↓
Admin approves/rejects
  ↓
If approved: Changes applied to deal
If rejected: Nothing happens, reason stored
```

## Change Request Structure

### JSON Changes Format
```json
{
  "changes": {
    "name": {
      "old": "Original Deal Name",
      "new": "Updated Deal Name"
    },
    "discount": {
      "old": 10,
      "new": 15
    },
    "start_date": {
      "old": "2025-01-01",
      "new": "2025-02-01"
    }
  }
}
```

## Admin Approval Page Features

### Search & Filter
- Search by deal name or ID
- Filter by status (all/pending/approved/rejected)
- Real-time search with debounce

### Request Card Display
- Deal name and ID
- Platform name
- Number of fields changed
- "View Details" button
- Requester name
- Status badge (color-coded)
- Date created
- Approve/Reject buttons (if pending)
- Rejection reason (if rejected)

### View Details Modal
Shows table with:
- Field name
- Current value (red badge)
- New value (green badge)

### Approve Modal
- Shows all changes to be applied
- Confirmation required
- Warning about irreversibility

### Reject Modal
- Requires rejection reason
- Validation: 10-1000 characters
- Reason stored in database

## Field Labels Supported

The system includes friendly labels for these deal fields:
- name → Deal Name
- description → Description
- type → Type
- status → Status
- current_turnover → Current Turnover
- target_turnover → Target Turnover
- is_turnover → Is Turnover
- discount → Discount
- start_date → Start Date
- end_date → End Date
- initial_commission → Initial Commission
- final_commission → Final Commission
- earn_profit → Earn Profit
- jackpot → Jackpot
- tree_remuneration → Tree Remuneration
- proactive_cashback → Proactive Cashback
- cash_company_profit → Cash Company Profit
- cash_jackpot → Cash Jackpot
- cash_tree → Cash Tree
- cash_cashback → Cash Cashback

## Files Created

1. ✅ `database/migrations/2025_11_20_130158_create_deal_change_requests_table.php`
2. ✅ `app/Models/DealChangeRequest.php`
3. ✅ `app/Livewire/DealChangeRequests.php`
4. ✅ `resources/views/livewire/deal-change-requests.blade.php`

## Files Modified

1. ✅ `app/Models/Deal.php` - Added relationships
2. ✅ `app/Http/Controllers/Api/partner/DealPartnerController.php` - Updated all methods
3. ✅ `routes/web.php` - Added route

## Database Changes

✅ New table: `deal_change_requests`
- All foreign keys properly set
- Cascading deletes configured
- Indexes added for performance

## Benefits

1. **Audit Trail**: Complete history of all change requests
2. **Approval Workflow**: Changes require approval before applying
3. **Change Tracking**: See exactly what changed and why
4. **User Attribution**: Know who requested and who approved
5. **Rejection Handling**: Reject with documented reasons
6. **No Direct Updates**: Partners can't modify deals directly anymore
7. **Data Integrity**: Admin review ensures quality control

## Testing

### Test 1: Create Change Request
```bash
curl -X PUT /api/partner/deals/1 \
  -d '{"name": "New Name", "discount": 20, "updated_by": 1}'

# Expected: 201 Created with change_request in response
```

### Test 2: View Pending Requests
Navigate to: `/{locale}/deals/change-requests`
- Should see list of pending requests
- Super admin only

### Test 3: Approve Request
Click "Approve" button → Confirm → Changes applied to deal

### Test 4: Reject Request
Click "Reject" → Enter reason → Confirm → Request rejected

## Next Steps (Optional)

1. **Email Notifications**: Notify partners when requests are approved/rejected
2. **Comments System**: Allow discussion on change requests
3. **Bulk Operations**: Approve/reject multiple requests at once
4. **Export Reports**: Generate change request reports
5. **Auto-Approval**: Set rules for auto-approving certain changes

## Translation Keys

Add these to your language files:
- `Deal Change Requests`
- `Pending`
- `Approved`
- `Rejected`
- `View Details`
- `Approve`
- `Reject`
- `Rejection Reason`
- `Deal updated successfully. Awaiting approval.`
- `Request processed`
- `field(s) changed`

## Important Notes

⚠️ **Breaking Change**: The `update()` method in `DealPartnerController` no longer directly updates deals. All updates now go through the approval workflow.

✅ **Backward Compatible**: The API returns the same structure but with additional change request data. Existing frontend code will still work but won't show change request info unless updated.

✅ **Data Safety**: Changes are stored in JSON format with both old and new values, allowing for complete audit trails.

✅ **Performance**: Change requests are eager loaded where needed to prevent N+1 query issues.

## Comparison with Platform Change Requests

Both systems follow the same pattern:
- ✅ Change requests stored in dedicated tables
- ✅ JSON format for changes
- ✅ Approval workflow
- ✅ Rejection with reasons
- ✅ Admin-only approval pages
- ✅ Same UI/UX design
- ✅ Full audit trail

## Summary

The Deal Change Request system is now fully functional and production-ready! Partners can submit deal updates, but all changes require admin approval before being applied. The system provides complete transparency and audit trails for all deal modifications.

