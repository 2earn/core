# Deal Validation Quick Reference

## Quick Links

### Routes
- **Deals Index**: `/{locale}/deals/index` → `deals_index`
- **Validation Requests**: `/{locale}/deals/validation-requests` → `deals_validation_requests` (Super Admin only)

### API Endpoints
- **Create Deal**: `POST /api/partner/deals` (uses `StoreDealRequest`)
- **Update Deal**: `PUT /api/partner/deals/{deal}` (uses `UpdateDealRequest`)

## Key Components

### Models
- **Deal**: `App\Models\Deal`
- **DealValidationRequest**: `App\Models\DealValidationRequest`

### Livewire Components
- **DealsIndex**: `App\Livewire\DealsIndex`
- **DealValidationRequests**: `App\Livewire\DealValidationRequests`

### Form Requests
- **StoreDealRequest**: Validates deal creation
- **UpdateDealRequest**: Validates deal updates

## Common Tasks

### Creating a Deal Validation Request Programmatically

```php
use App\Models\DealValidationRequest;

$request = DealValidationRequest::create([
    'deal_id' => $dealId,
    'requested_by_id' => auth()->id(),
    'status' => 'pending',
    'notes' => 'Please validate this deal',
]);
```

### Approving a Request

```php
use App\Models\DealValidationRequest;
use App\Models\Deal;
use Illuminate\Support\Facades\DB;

DB::beginTransaction();
try {
    $request = DealValidationRequest::findOrFail($requestId);
    $deal = Deal::findOrFail($request->deal_id);
    
    $deal->validated = true;
    $deal->save();
    
    $request->status = 'approved';
    $request->save();
    
    DB::commit();
} catch (\Exception $e) {
    DB::rollBack();
    // Handle error
}
```

### Rejecting a Request

```php
$request = DealValidationRequest::findOrFail($requestId);
$request->status = 'rejected';
$request->rejection_reason = 'Reason for rejection';
$request->save();
```

### Querying Validation Requests

```php
// Get all pending requests
$pending = DealValidationRequest::pending()->get();

// Get requests for a specific deal
$dealRequests = DealValidationRequest::where('deal_id', $dealId)->get();

// Get requests with relationships
$requests = DealValidationRequest::with(['deal.platform', 'requestedBy'])->get();
```

## Database Schema

### deal_validation_requests Table

| Column | Type | Description |
|--------|------|-------------|
| id | bigint unsigned | Primary key |
| deal_id | bigint unsigned | Foreign key to deals table |
| requested_by_id | bigint unsigned | Foreign key to users table |
| status | enum | pending, approved, rejected |
| rejection_reason | text | Reason for rejection (nullable) |
| notes | text | Additional notes (nullable) |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Update timestamp |

**Indexes**: status, deal_id

## Validation Rules (StoreDealRequest)

### Required Fields
- `name` (max: 255)
- `commission_formula_id` (must exist)
- `description`
- `validated` (boolean)
- `type` (string)
- `status` (string)
- `platform_id` (must exist)
- `created_by` (must exist)

### Optional Fields
- `target_turnover` (numeric)
- `current_turnover` (numeric)
- `is_turnover` (boolean)
- `discount` (numeric)
- `start_date` (date)
- `end_date` (date, must be after start_date)
- `earn_profit` (numeric)
- `jackpot` (numeric)
- `tree_remuneration` (numeric)
- `proactive_cashback` (numeric)
- Cash fields (numeric)

## Livewire Events

### Dispatched Events
- `refreshDeals`: Triggers deals list refresh after approval

### Listened Events
- `refreshValidationRequests`: Refreshes validation requests list

## Permissions

### Super Admin Only
- View all validation requests
- Approve/reject requests
- Access validation requests page

### Platform Managers
- See requests for their platforms only
- Cannot approve/reject (UI restricted)

## UI Components in Deals Index

The deals index now includes a section showing pending validation requests (for super admins):

```blade
@if(\App\Models\User::isSuperAdmin())
    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h5>{{__('Pending Validation Requests')}}</h5>
                    <a href="{{route('deals_validation_requests', ['locale' => app()->getLocale()])}}">
                        {{__('View All Requests')}}
                    </a>
                </div>
                <div class="card-body">
                    @livewire('deal-validation-requests')
                </div>
            </div>
        </div>
    </div>
@endif
```

## Status Flow

```
Deal Created (validated = false)
         ↓
Validation Request Created (status = pending)
         ↓
    ┌────┴────┐
    ↓         ↓
Approved   Rejected
    ↓         ↓
validated  rejection_reason
= true     stored
```

## Translation Keys

Add these to your language files:

```json
{
  "Deal Validation Requests": "Deal Validation Requests",
  "Pending Validation Requests": "Pending Validation Requests",
  "View All Requests": "View All Requests",
  "Approve": "Approve",
  "Reject": "Reject",
  "Rejection Reason": "Rejection Reason",
  "Request processed": "Request processed",
  "Deal validated successfully": "Deal validated successfully",
  "Deal validation request approved successfully": "Deal validation request approved successfully",
  "Deal validation request rejected": "Deal validation request rejected",
  "Please provide a reason for rejection": "Please provide a reason for rejection",
  "Rejection reason must be at least 10 characters": "Rejection reason must be at least 10 characters"
}
```

## Troubleshooting

### Request not showing for platform manager
- Check if user has proper platform permissions (owner, financial_manager, marketing_manager)
- Verify the deal's platform_id matches user's platforms

### Approval failing
- Check database transaction logs
- Verify deal exists and is not already validated
- Check user has super admin permissions

### Validation errors on deal creation
- Review StoreDealRequest validation rules
- Check if commission_formula_id exists
- Verify platform_id is valid
- Ensure dates are properly formatted

---

**Last Updated**: November 20, 2025

