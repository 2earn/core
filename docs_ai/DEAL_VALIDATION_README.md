# Deal Validation System - README

## Overview

The Deal Validation System provides comprehensive validation for deal creation/updates and a user-friendly interface for administrators to approve or reject deal validation requests.

## Quick Start

### 1. Migration (Already Complete ✅)

The database migration has been run successfully. The `deal_validation_requests` table is ready to use.

### 2. Access the Features

**For Platform Managers:**
- Use API endpoints to create/update deals with automatic validation:
  - `POST /api/partner/deals` - Create a deal
  - `PUT /api/partner/deals/{deal}` - Update a deal

**For Super Administrators:**
- Navigate to: `/{locale}/deals/index`
- Look for the "Pending Validation Requests" section
- Click "View All Requests" to manage all validation requests

### 3. Creating a Validation Request (Programmatic)

```php
use App\Models\DealValidationRequest;

DealValidationRequest::create([
    'deal_id' => $deal->id,
    'requested_by_id' => auth()->id(),
    'status' => 'pending',
    'notes' => 'Optional notes about this request',
]);
```

## Key Routes

| Route | Access | Purpose |
|-------|--------|---------|
| `/{locale}/deals/index` | All Users | View deals |
| `/{locale}/deals/validation-requests` | Super Admin | Manage validation requests |

## API Usage

### Create Deal with Validation

```bash
POST /api/partner/deals
Content-Type: application/json

{
  "name": "Summer Sale Deal",
  "commission_formula_id": 1,
  "description": "Special summer promotion",
  "validated": false,
  "type": "public",
  "status": "new",
  "platform_id": 1,
  "start_date": "2025-06-01",
  "end_date": "2025-08-31",
  "target_turnover": 10000,
  "created_by": 123
}
```

**Success Response (201):**
```json
{
  "status": true,
  "message": "Deal created successfully",
  "data": { /* deal object */ }
}
```

**Validation Error (422):**
```json
{
  "message": "The name field is required.",
  "errors": {
    "name": ["The deal name is required"],
    "platform_id": ["Please select a platform"]
  }
}
```

### Update Deal

```bash
PUT /api/partner/deals/{dealId}
Content-Type: application/json

{
  "name": "Updated Deal Name",
  "target_turnover": 15000
}
```

## Validation Rules

### Required Fields (Create)
- name (max 255 chars)
- commission_formula_id (must exist)
- description
- validated (boolean)
- type (string)
- status (string)
- platform_id (must exist)
- created_by (user ID)

### Optional Fields
- target_turnover, current_turnover
- start_date, end_date (end must be after start)
- discount, earn_profit, jackpot
- tree_remuneration, proactive_cashback
- Cash fields (company_profit, jackpot, tree, cashback)

## Workflow Examples

### Example 1: Approve a Request

1. Navigate to validation requests page
2. Find the pending request
3. Click "Approve" button
4. Confirm in modal
5. Deal is now validated ✅

### Example 2: Reject a Request

1. Navigate to validation requests page
2. Find the pending request
3. Click "Reject" button
4. Enter rejection reason (min 10 chars)
5. Submit rejection
6. Requester can see the reason ❌

### Example 3: Search Requests

1. Use the search box at the top
2. Type deal name, requester name, or email
3. Results filter automatically
4. Use status dropdown to filter by status

## Common Issues & Solutions

### Issue: Validation request not showing
**Solution:** Check if:
- User is a super admin
- Deal exists and has correct platform_id
- Request status is 'pending'

### Issue: Cannot approve request
**Solution:** Verify:
- User has super admin permissions
- Request is still pending
- Deal exists and is not already validated

### Issue: API validation failing
**Solution:** Check:
- All required fields are present
- commission_formula_id exists in database
- platform_id is valid
- Date format is correct (Y-m-d)
- End date is after start date

## Code Examples

### Check if Deal has Pending Validation Request

```php
use App\Models\Deal;

$deal = Deal::find($dealId);
$hasPendingRequest = $deal->validationRequests()
    ->where('status', 'pending')
    ->exists();
```

### Get All Pending Requests for a Platform

```php
use App\Models\DealValidationRequest;

$requests = DealValidationRequest::whereHas('deal', function($query) use ($platformId) {
    $query->where('platform_id', $platformId);
})->pending()->get();
```

### Bulk Approve Requests

```php
use App\Models\DealValidationRequest;
use App\Models\Deal;
use Illuminate\Support\Facades\DB;

DB::beginTransaction();
try {
    $requests = DealValidationRequest::pending()
        ->whereIn('id', $requestIds)
        ->get();
    
    foreach ($requests as $request) {
        $deal = Deal::find($request->deal_id);
        $deal->validated = true;
        $deal->save();
        
        $request->status = 'approved';
        $request->save();
    }
    
    DB::commit();
} catch (\Exception $e) {
    DB::rollBack();
    // Handle error
}
```

## File Locations

### Backend
- **Form Requests**: `app/Http/Requests/`
  - StoreDealRequest.php
  - UpdateDealRequest.php
- **Models**: `app/Models/`
  - Deal.php
  - DealValidationRequest.php
- **Livewire**: `app/Livewire/`
  - DealValidationRequests.php
- **Controllers**: `app/Http/Controllers/Api/partner/`
  - DealPartnerController.php

### Frontend
- **Views**: `resources/views/livewire/`
  - deal-validation-requests.blade.php
  - deals-index.blade.php (modified)

### Database
- **Migration**: `database/migrations/`
  - 2025_11_20_000001_create_deal_validation_requests_table.php

### Documentation
- **Full Guide**: `docs_ai/DEAL_VALIDATION_IMPLEMENTATION.md`
- **Quick Ref**: `docs_ai/DEAL_VALIDATION_QUICK_REFERENCE.md`
- **Summary**: `docs_ai/DEAL_VALIDATION_IMPLEMENTATION_SUMMARY.md`
- **This File**: `docs_ai/DEAL_VALIDATION_README.md`

## Translation Keys to Add

Add these keys to your language files (`lang/en.json`, `lang/ar.json`, etc.):

```json
{
  "Deal Validation Requests": "...",
  "Pending Validation Requests": "...",
  "View All Requests": "...",
  "Approve": "...",
  "Reject": "...",
  "Rejection Reason": "...",
  "Request processed": "...",
  "Are you sure you want to approve this deal validation request?": "...",
  "Please provide a reason for rejection": "...",
  "Rejection reason must be at least 10 characters": "...",
  "Deal validation request approved successfully": "...",
  "Deal validation request rejected": "..."
}
```

## Support & Troubleshooting

1. Check the logs: `storage/logs/laravel.log`
2. Review validation errors in API responses
3. Verify database migrations are current
4. Check user permissions
5. Review the full documentation in `docs_ai/` folder

## Contributing

When making changes:
1. Update the relevant documentation
2. Add tests for new features
3. Follow the existing code style
4. Update this README if needed

---

**Version**: 1.0.0  
**Last Updated**: November 20, 2025  
**Status**: ✅ Production Ready  

For detailed technical information, see `DEAL_VALIDATION_IMPLEMENTATION.md`

