# Deal Validation Request Implementation

## Overview
This document describes the implementation of the Deal Validation Request system, which allows platform managers to request validation for deals and administrators to approve or reject these requests.

## Components Created

### 1. Form Request Classes

#### StoreDealRequest (`app/Http/Requests/StoreDealRequest.php`)
- **Purpose**: Validates data when creating a new deal
- **Key Validation Rules**:
  - `name`: required, string, max 255 characters
  - `commission_formula_id`: required, must exist in commission_formulas table
  - `description`: required, string
  - `platform_id`: required, must exist in platforms table
  - `start_date` and `end_date`: date validation with end_date after start_date
  - All numeric fields: nullable numeric validation
- **Authorization**: Returns `true` (authorized by middleware)

#### UpdateDealRequest (`app/Http/Requests/UpdateDealRequest.php`)
- **Purpose**: Validates data when updating an existing deal
- **Key Validation Rules**: Similar to StoreDealRequest but uses `sometimes` for partial updates
- **Authorization**: Returns `true` (authorized by middleware)

### 2. Database Migration

#### File: `database/migrations/2025_11_20_000001_create_deal_validation_requests_table.php`

**Table Structure:**
```sql
CREATE TABLE deal_validation_requests (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    deal_id BIGINT UNSIGNED NOT NULL,
    requested_by_id BIGINT UNSIGNED NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    rejection_reason TEXT NULL,
    notes TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (deal_id) REFERENCES deals(id) ON DELETE CASCADE,
    FOREIGN KEY (requested_by_id) REFERENCES users(id) ON DELETE CASCADE,
    
    INDEX (status),
    INDEX (deal_id)
);
```

### 3. Model

#### DealValidationRequest (`app/Models/DealValidationRequest.php`)

**Relationships:**
- `deal()`: BelongsTo relationship with Deal model
- `requestedBy()`: BelongsTo relationship with User model

**Scopes:**
- `pending()`: Filter pending requests
- `approved()`: Filter approved requests
- `rejected()`: Filter rejected requests

**Helper Methods:**
- `isPending()`: Check if request is pending
- `isApproved()`: Check if request is approved
- `isRejected()`: Check if request is rejected

**Fillable Fields:**
- deal_id
- requested_by_id
- status
- rejection_reason
- notes

### 4. Livewire Component

#### DealValidationRequests (`app/Livewire/DealValidationRequests.php`)

**Public Properties:**
- `$search`: Search keyword for filtering
- `$statusFilter`: Filter by status (all, pending, approved, rejected)
- `$perPage`: Number of items per page (default: 10)
- `$showRejectModal`: Controls reject modal visibility
- `$rejectRequestId`: ID of request being rejected
- `$rejectionReason`: Reason for rejection
- `$showApproveModal`: Controls approve modal visibility
- `$approveRequestId`: ID of request being approved

**Key Methods:**

1. **approveRequest()**
   - Validates the deal by setting `validated = true`
   - Updates request status to 'approved'
   - Uses database transaction for data integrity
   - Dispatches 'refreshDeals' event
   - Logs the approval action

2. **rejectRequest()**
   - Validates rejection reason (minimum 10 characters)
   - Updates request status to 'rejected'
   - Stores rejection reason
   - Uses database transaction
   - Logs the rejection action

3. **render()**
   - Queries deal validation requests with relationships
   - Applies search and status filters
   - Restricts visibility based on user permissions
   - Returns paginated results

**Permissions:**
- Super admins can see all requests
- Platform managers can only see requests for their platforms

### 5. View

#### File: `resources/views/livewire/deal-validation-requests.blade.php`

**Sections:**
1. **Breadcrumb**: Navigation path
2. **Filters Card**: Search input and status filter dropdown
3. **Requests List**: Card layout showing:
   - Deal information (name, ID, platform)
   - Requester information (name, email)
   - Request status and date
   - Action buttons (Approve/Reject for pending requests)
   - Additional info (deal type, status, notes)
   - Rejection reason (if rejected)
4. **Pagination**: Shows results with page navigation
5. **Approve Modal**: Confirmation dialog for approval
6. **Reject Modal**: Form to enter rejection reason

**Features:**
- Responsive design with Bootstrap classes
- Real-time search with Livewire
- Empty state message when no requests found
- Icon-based visual indicators
- Color-coded status badges

### 6. Controller Updates

#### DealPartnerController (`app/Http/Controllers/Api/partner/DealPartnerController.php`)

**Changes:**
- `store()` method now uses `StoreDealRequest` for validation
- `update()` method now uses `UpdateDealRequest` for validation
- Removed manual validation code in favor of Form Requests
- Cleaner, more maintainable code

### 7. Model Updates

#### Deal Model (`app/Models/Deal.php`)

**New Relationship:**
```php
public function validationRequests(): HasMany
{
    return $this->hasMany(DealValidationRequest::class);
}
```

### 8. Route Updates

#### File: `routes/web.php`

**New Route:**
```php
Route::get('/validation-requests', \App\Livewire\DealValidationRequests::class)
    ->name('validation_requests');
```

**Full Path**: `/{locale}/deals/validation-requests`
**Route Name**: `deals_validation_requests`
**Middleware**: IsSuperAdmin (only accessible by super administrators)

### 9. Deals Index Integration

#### File: `resources/views/livewire/deals-index.blade.php`

**New Section Added:**
- Displays pending validation requests section (for super admins only)
- Shows link to view all requests
- Embedded DealValidationRequests component
- Positioned between filters and results sections

## Usage Flow

### For Platform Managers (Creating a Deal):

1. Platform manager creates a deal via API or web interface
2. Deal is created with `validated = false` status
3. A validation request can be created in the `deal_validation_requests` table
4. Manager waits for administrator approval

### For Super Administrators:

1. Navigate to Deals Index page
2. See "Pending Validation Requests" section
3. Click "View All Requests" to see full list
4. For each pending request:
   - Click "Approve" to validate the deal
   - Click "Reject" to reject with reason
5. Approved deals have `validated = true`
6. Rejected requests store the rejection reason

## API Endpoints

The Form Requests are used in the following API endpoints:

### Create Deal
- **Endpoint**: `POST /api/partner/deals`
- **Request**: Uses `StoreDealRequest`
- **Response**: JSON with created deal data

### Update Deal
- **Endpoint**: `PUT /api/partner/deals/{deal}`
- **Request**: Uses `UpdateDealRequest`
- **Response**: JSON with updated deal data

## Security Features

1. **Authorization**: Routes protected by IsSuperAdmin middleware
2. **Validation**: Comprehensive validation rules in Form Requests
3. **Database Integrity**: Foreign key constraints and transactions
4. **Audit Trail**: Logs all approval/rejection actions
5. **Cascade Deletion**: Validation requests deleted when deal is deleted

## Localization

All user-facing strings use Laravel's `__()` helper for translation support:
- Form labels and placeholders
- Validation error messages
- Success/error notifications
- UI labels and buttons

## Database Indexes

For optimal performance:
- Index on `status` column for filtering
- Index on `deal_id` for relationship queries
- Foreign key indexes automatically created

## Future Enhancements

Potential improvements:
1. Email notifications for approval/rejection
2. Batch approval functionality
3. Comments/discussion thread on requests
4. File attachments for supporting documents
5. Automatic validation rules based on deal criteria
6. Validation history tracking
7. Delegated approval workflow

## Testing Checklist

- [ ] Create deal via API with valid data
- [ ] Create deal via API with invalid data (test validation)
- [ ] Update deal via API
- [ ] Create validation request
- [ ] View validation requests as super admin
- [ ] Approve validation request
- [ ] Reject validation request with reason
- [ ] Test search functionality
- [ ] Test status filter
- [ ] Test pagination
- [ ] Verify permissions (non-admin access blocked)
- [ ] Test cascade deletion
- [ ] Verify audit logs

## Files Modified/Created

### Created:
1. `app/Http/Requests/StoreDealRequest.php`
2. `app/Http/Requests/UpdateDealRequest.php`
3. `app/Models/DealValidationRequest.php`
4. `app/Livewire/DealValidationRequests.php`
5. `resources/views/livewire/deal-validation-requests.blade.php`
6. `database/migrations/2025_11_20_000001_create_deal_validation_requests_table.php`
7. `docs_ai/DEAL_VALIDATION_IMPLEMENTATION.md` (this file)

### Modified:
1. `app/Http/Controllers/Api/partner/DealPartnerController.php`
2. `app/Models/Deal.php`
3. `routes/web.php`
4. `resources/views/livewire/deals-index.blade.php`

## Migration Status

✅ Migration successfully run: `2025_11_20_000001_create_deal_validation_requests_table`

Table `deal_validation_requests` created successfully.

---

**Implementation Date**: November 20, 2025
**Status**: ✅ Complete

