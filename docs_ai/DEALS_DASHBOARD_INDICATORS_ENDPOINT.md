# Deals Dashboard Indicators & Progress Endpoint

## Overview
New API endpoint that provides comprehensive dashboard indicators and progress metrics for deals, filtered by various criteria.

## Implementation Date
December 8, 2025

## Endpoint Details

### Route
```
GET /api/partner/deals/dashboard/indicators
```

### Route Name
```
deals_dashboard_indicators
```

## Request Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `user_id` | integer | Yes | ID of the user (must exist in users table) |
| `start_date` | date | No | Filter deals with start_date >= this value |
| `end_date` | date | No | Filter deals with end_date <= this value (must be after or equal to start_date) |
| `platform_id` | integer | No | Filter by specific platform ID |
| `deal_id` | integer | No | Filter by specific deal ID |

## Response Structure

### Success Response (200 OK)
```json
{
  "status": true,
  "data": {
    "total_deals": 25,
    "pending_request_deals": 3,
    "validated_deals": 20,
    "expired_deals": 5,
    "active_deals_count": 15,
    "total_revenue": 125000.50,
    "global_revenue_percentage": 62.50
  }
}
```

### Response Fields

| Field | Type | Description | Calculation |
|-------|------|-------------|-------------|
| `total_deals` | integer | Total number of deals (all statuses) | `COUNT(*) FROM deals WHERE filters applied` |
| `pending_request_deals` | integer | Deals with a pending validation request | `COUNT(*) WHERE has pending validation request` |
| `validated_deals` | integer | Number of deals in validated status | `COUNT(*) WHERE validated = 1` |
| `expired_deals` | integer | Deals whose end_date has passed | `COUNT(*) WHERE end_date < NOW()` |
| `active_deals_count` | integer | Deals currently active (status = Opened) | `COUNT(*) WHERE status = 2` |
| `total_revenue` | float | Sum of revenue for filtered deals | `SUM(current_turnover)` |
| `global_revenue_percentage` | float | Progress toward global revenue target | `(SUM(current_turnover) / SUM(target_turnover)) * 100` |

## Error Responses

### Validation Error (422 Unprocessable Entity)
```json
{
  "status": "Failed",
  "message": "Validation failed",
  "errors": {
    "user_id": ["The user id field is required."],
    "end_date": ["The end date must be after or equal to start date."]
  }
}
```

### Server Error (500 Internal Server Error)
```json
{
  "status": false,
  "message": "Failed to retrieve dashboard indicators: [error details]"
}
```

## Authorization & Permissions

- The endpoint applies user permission checks automatically
- Only shows deals where the user is:
  - Marketing Manager of the platform
  - Financial Manager of the platform
  - Owner of the platform

## Implementation Details

### Service Layer
**File:** `app/Services/Deals/DealService.php`

**Method:** `getDashboardIndicators()`

```php
public function getDashboardIndicators(
    int     $userId,
    ?string $startDate = null,
    ?string $endDate = null,
    ?int    $platformId = null,
    ?int    $dealId = null
): array
```

### Controller Layer
**File:** `app/Http/Controllers/Api/partner/DealPartnerController.php`

**Method:** `dashboardIndicators()`

### Key Features

1. **Permission-Based Filtering**: Automatically filters deals based on user's platform roles
2. **Multiple Filter Options**: Supports filtering by date range, platform, and specific deal
3. **Query Optimization**: Uses query cloning to efficiently calculate multiple metrics
4. **Comprehensive Metrics**: Provides 7 different key performance indicators
5. **Revenue Tracking**: Calculates both absolute and percentage-based revenue metrics

## Example Usage

### Basic Request
```bash
GET /api/partner/deals/dashboard/indicators?user_id=123
```

### With Date Range Filter
```bash
GET /api/partner/deals/dashboard/indicators?user_id=123&start_date=2025-01-01&end_date=2025-12-31
```

### With Platform Filter
```bash
GET /api/partner/deals/dashboard/indicators?user_id=123&platform_id=5
```

### With Multiple Filters
```bash
GET /api/partner/deals/dashboard/indicators?user_id=123&start_date=2025-01-01&end_date=2025-12-31&platform_id=5
```

### With Specific Deal
```bash
GET /api/partner/deals/dashboard/indicators?user_id=123&deal_id=42
```

## Business Logic

### Deal Status Values (DealStatus Enum)
- `New = 1` - Newly created deal
- `Opened = 2` - Active/running deal
- `Closed = 3` - Closed deal
- `Archived = 4` - Archived deal

### Validation Request Status
- `pending` - Awaiting admin approval
- `approved` - Approved by admin
- `rejected` - Rejected by admin
- `cancelled` - Cancelled by requester

### Revenue Calculation
- **Total Revenue**: Sum of all `current_turnover` values for filtered deals
- **Global Revenue Percentage**: 
  - Numerator: Total revenue (sum of current_turnover)
  - Denominator: Total target (sum of target_turnover)
  - Formula: `(total_revenue / total_target) * 100`
  - Returns 0 if total target is 0

## Testing Recommendations

1. **Test with no filters**: Verify all accessible deals are included
2. **Test with date filters**: Ensure correct date range filtering
3. **Test with platform filter**: Verify platform-specific results
4. **Test with deal filter**: Verify single deal indicators
5. **Test permission checks**: Ensure users only see authorized deals
6. **Test with expired deals**: Verify correct identification of expired deals
7. **Test revenue calculations**: Verify accuracy of percentage calculations
8. **Test with zero target turnover**: Ensure no division by zero errors

## Related Models

- `Deal` - Main deal model
- `DealValidationRequest` - Validation request tracking
- `Platform` - Platform association for permission checks

## Migration Requirements

None - Uses existing database schema

## Dependencies

- Laravel Framework
- DealService
- Deal Model
- DealValidationRequest Model
- DealStatus Enum

## Notes

- All monetary values are rounded to 2 decimal places
- Percentages are rounded to 2 decimal places
- The endpoint uses GET method (not POST) for better REST practices
- Query cloning is used to maintain filter consistency across all metrics
- The endpoint logs all requests and errors for debugging purposes

