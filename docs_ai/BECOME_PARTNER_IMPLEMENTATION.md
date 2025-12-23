# Become a Partner Feature - Implementation Guide

## Overview
A complete "Become a Partner" feature has been implemented that allows users to submit partnership requests with 5 required fields. The feature includes form submission, admin validation/rejection workflow, and status tracking.

## What Was Created

### 1. Database
- **Migration**: `2025_12_23_000001_create_partner_requests_table.php`
  - Creates `partner_requests` table with fields:
    - company_name
    - business_sector_id (FK to business_sectors)
    - platform_url
    - platform_description
    - partnership_reason
    - status, note, request_date, examination_date
    - user_id, examiner_id (FK to users)
    - Auditing fields (created_by, updated_by)

- **Migration**: `2025_12_23_000002_add_partner_field_to_users_table.php`
  - Adds `partner` column to users table to track partner status

### 2. Models
- **PartnerRequest** (`app/Models/PartnerRequest.php`)
  - Uses `HasAuditing` trait
  - Relationships:
    - `user()` - The applicant
    - `examiner()` - The admin who reviews it
    - `businessSector()` - Selected business sector

### 3. Enums
- **BePartnerRequestStatus** (`Core/Enum/BePartnerRequestStatus.php`)
  - States: InProgress (1), Validated2earn (2), Validated (3), Rejected (4)

### 4. Services
- **PartnerRequestService** (`app/Services/PartnerRequest/PartnerRequestService.php`)
  - Methods:
    - `getLastPartnerRequest($userId)` - Get last request for user
    - `getLastPartnerRequestByStatus($userId, $status)` - Get last request by status
    - `createPartnerRequest($data)` - Create new request
    - `hasInProgressRequest($userId)` - Check if user has in-progress request
    - `getPartnerRequestById($id)` - Retrieve request by ID
    - `updatePartnerRequest($id, $data)` - Update request
    - `getPartnerRequestsByStatus($status)` - Get all requests with specific status

### 5. Livewire Components
- **PartnerRequestForm** (`app/Livewire/PartnerRequestForm.php`)
  - User-facing form to submit partnership requests
  - Validates 5 fields with custom rules and messages
  - Checks for existing in-progress requests
  - Redirects to additional-income page after submission

- **PartnerRequestIndex** (`app/Livewire/PartnerRequestIndex.php`)
  - Admin list view for managing partner requests
  - Features:
    - Search by company name or user email/name
    - Filter by status
    - Pagination (15 per page)

- **PartnerRequestShow** (`app/Livewire/PartnerRequestShow.php`)
  - Admin detail view for individual requests
  - Features:
    - Display all request information
    - Validate button (approve request)
    - Reject button with modal for entering rejection reason
    - Status-based UI (no actions if already reviewed)

### 6. Views
- **partner-request-form.blade.php**
  - Form fields:
    1. Company Name (required, max 255)
    2. Business Sector (required, dropdown)
    3. Platform URL (required, valid URL)
    4. Platform Description (required, min 10 chars)
    5. Reason for Partnership (required, min 20 chars)
  - Includes validation error messages
  - Submit and back buttons

- **partner-request-index.blade.php**
  - Admin table listing all requests
  - Search and filter controls
  - Status badges (color-coded)
  - View button for each request

- **partner-request-show.blade.php**
  - Detailed request display
  - User and company information
  - Platform details with link
  - Rejection modal for admin feedback
  - Action buttons (Validate/Reject) visible only for in-progress requests

- **additional-income.blade.php (UPDATED)**
  - Added "Become a Partner" card
  - Shows status: Verified Partner, In Progress, Rejected, or Call-to-Action
  - Displays rejection reason if applicable
  - Submit/Resubmit buttons

### 7. Routes
Added to `routes/web.php`:

**User Routes (under `/business-hub`):**
```
GET /be-partner/form → PartnerRequestForm (named: partner_request_form)
```

**Admin Routes (under `/configuration/requests`):**
```
GET /partner → PartnerRequestIndex (named: requests_partner)
GET /partner/{id}/show → PartnerRequestShow (named: requests_partner_show)
```

**Updated:**
- `AdditionalIncome` component now includes `lastPartnerRequest` data

## Workflow

### User Workflow
1. User navigates to "Additional Income" (`/business-hub/additional-income`)
2. Sees "Become a Partner" card with status info
3. If eligible, clicks "Submit Partnership Request"
4. Fills out form with 5 required fields
5. Submits form
6. Request created with status "In Progress"
7. User sees status update on Additional Income page
8. After admin review:
   - If validated: Shows "Verified Partner" status
   - If rejected: Shows rejection reason and "Submit Again" button

### Admin Workflow
1. Navigate to `/requests/partner`
2. View all partner requests with search/filter
3. Click "View" to see request details
4. Two options:
   - **Validate**: Approves request, sets status to "Validated"
   - **Reject**: Opens modal to enter rejection reason, sets status to "Rejected"
5. Examiner ID and examination date automatically recorded

## Form Validation Rules

| Field | Rules |
|-------|-------|
| Company Name | required, string, max:255 |
| Business Sector | required, exists:business_sectors,id |
| Platform URL | required, url |
| Platform Description | required, string, min:10 |
| Partnership Reason | required, string, min:20 |

## Database Migration Steps

```bash
# Run migrations
php artisan migrate

# This creates:
# 1. partner_requests table
# 2. partner column in users table
```

## Files Created/Modified

### Created Files:
1. `database/migrations/2025_12_23_000001_create_partner_requests_table.php`
2. `database/migrations/2025_12_23_000002_add_partner_field_to_users_table.php`
3. `app/Models/PartnerRequest.php`
4. `Core/Enum/BePartnerRequestStatus.php`
5. `app/Services/PartnerRequest/PartnerRequestService.php`
6. `app/Livewire/PartnerRequestForm.php`
7. `app/Livewire/PartnerRequestIndex.php`
8. `app/Livewire/PartnerRequestShow.php`
9. `resources/views/livewire/partner-request-form.blade.php`
10. `resources/views/livewire/partner-request-index.blade.php`
11. `resources/views/livewire/partner-request-show.blade.php`

### Modified Files:
1. `app/Livewire/AdditionalIncome.php` - Added partner request imports and data
2. `resources/views/livewire/additional-income.blade.php` - Added partner card
3. `routes/web.php` - Added partner request routes

## Key Features

✅ User-friendly form with validation
✅ Admin dashboard for managing requests
✅ Search and filter functionality
✅ Approve/Reject workflow
✅ Status tracking for users
✅ Auditing trail (created_by, updated_by)
✅ Multi-language support via translation keys
✅ Responsive design (Bootstrap)
✅ Real-time validation with Livewire
✅ Modal for rejection feedback

## Testing Checklist

- [ ] Run migrations: `php artisan migrate`
- [ ] Test form submission with valid data
- [ ] Test form validation (all fields)
- [ ] Test duplicate in-progress request prevention
- [ ] Verify request appears in admin panel
- [ ] Test search functionality
- [ ] Test status filtering
- [ ] Test request validation by admin
- [ ] Test request rejection with reason
- [ ] Verify status updates on user's additional-income page
- [ ] Test translations are working
- [ ] Verify audit fields are populated

## Notes

- The feature mirrors existing "Committed Investor" and "Instructor" request functionality
- Uses same naming conventions and patterns
- Includes proper error handling with logging
- All fields are properly indexed and validated
- Ready for production deployment after testing

