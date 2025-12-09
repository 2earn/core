# Sales Dashboard KPI - Partner Role Verification

## Overview
The Sales Dashboard KPI endpoint now includes verification that the user has an approved role in the specified platform before returning KPI data.

## Updated Implementation

### Controller Location
`app/Http/Controllers/Api/partner/SalesDashboardController.php`

### Service Enhancement
`app/Services/Dashboard/SalesDashboardService.php`

## New Validation Logic

### User-Platform Role Check

When both `user_id` and `platform_id` parameters are provided, the service verifies that the user is one of:
1. **Owner** - `platform.owner_id` matches `user_id`
2. **Marketing Manager** - `platform.marketing_manager_id` matches `user_id`
3. **Financial Manager** - `platform.financial_manager_id` matches `user_id`

```php
public function userHasRoleInPlatform(int $userId, int $platformId): bool
{
    return Platform::where('id', $platformId)
        ->where(function ($query) use ($userId) {
            $query->where('owner_id', $userId)
                ->orWhere('marketing_manager_id', $userId)
                ->orWhere('financial_manager_id', $userId);
        })
        ->exists();
}
```

## Endpoint Details

### URL
```
GET /api/partner/sales/dashboard/kpis
```

### Required Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| user_id | integer | **Yes** | User ID to check platform role |
| platform_id | integer | No* | Platform ID to filter KPIs |
| start_date | date | No | Start date for filtering |
| end_date | date | No | End date for filtering |

*Note: If `platform_id` is provided, the system will verify that `user_id` has an approved role in that platform.

## Response Scenarios

### 1. Success - User has role in platform
**Request:**
```bash
GET /api/partner/sales/dashboard/kpis?user_id=123&platform_id=5&start_date=2024-01-01&end_date=2024-12-31
```

**Response (200):**
```json
{
    "status": true,
    "message": "KPIs retrieved successfully",
    "data": {
        "total_sales": 150,
        "orders_in_progress": 25,
        "orders_successful": 100,
        "orders_failed": 10,
        "total_customers": 75
    }
}
```

### 2. Error - User does NOT have role in platform
**Request:**
```bash
GET /api/partner/sales/dashboard/kpis?user_id=123&platform_id=5
```

**Response (500):**
```json
{
    "status": "Failed",
    "message": "Error retrieving KPIs",
    "error": "User does not have an approved role in this platform"
}
```

### 3. Validation Error - Missing user_id
**Request:**
```bash
GET /api/partner/sales/dashboard/kpis?platform_id=5
```

**Response (422):**
```json
{
    "status": "Failed",
    "message": "Validation failed",
    "errors": {
        "user_id": ["The user id field is required."]
    }
}
```

## Role Verification Flow

```
1. Request received with user_id and platform_id
   ↓
2. Validate request parameters
   ↓
3. Check if user has approved role in platform
   ↓
   ├─ YES: Continue to fetch KPIs
   │        ↓
   │     Return KPI data
   │
   └─ NO: Throw exception
            ↓
         Return error response
```

## Database Table Reference

### platforms
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| name | string | Platform name |
| owner_id | bigint | Owner user ID |
| marketing_manager_id | bigint | Marketing manager user ID |
| financial_manager_id | bigint | Financial manager user ID |
| business_sector_id | bigint | Business sector reference |
| enabled | boolean | Platform status |
| type | string | Platform type |
| created_by | bigint | User who created |
| updated_by | bigint | User who updated |
| created_at | timestamp | Created timestamp |
| updated_at | timestamp | Updated timestamp |

### Role Identification
A user has a role in a platform if their `user_id` matches any of:
- `owner_id` - Full platform owner
- `marketing_manager_id` - Marketing manager
- `financial_manager_id` - Financial manager

## Logging

### Success Log
```
[SalesDashboardController] KPIs retrieved successfully
{
    "filters": {
        "user_id": 123,
        "platform_id": 5,
        "start_date": "2024-01-01",
        "end_date": "2024-12-31"
    },
    "kpis": {...}
}
```

### Warning Log - No Role
```
[SalesDashboardService] User does not have role in platform
{
    "user_id": 123,
    "platform_id": 5
}
```

### Error Log
```
[SalesDashboardService] Error fetching KPI data: User does not have an approved role in this platform
{
    "filters": {...},
    "trace": "..."
}
```

## Usage Examples

### Example 1: Valid user with platform role
```bash
curl -X GET "http://yourdomain.com/api/partner/sales/dashboard/kpis?user_id=10&platform_id=3" \
  -H "Accept: application/json"
```

### Example 2: With date filters
```bash
curl -X GET "http://yourdomain.com/api/partner/sales/dashboard/kpis?user_id=10&platform_id=3&start_date=2024-01-01&end_date=2024-12-31" \
  -H "Accept: application/json"
```

### Example 3: JavaScript/Axios
```javascript
const getKPIs = async (userId, platformId, dateRange = {}) => {
    try {
        const response = await axios.get('/api/partner/sales/dashboard/kpis', {
            params: {
                user_id: userId,
                platform_id: platformId,
                start_date: dateRange.startDate,
                end_date: dateRange.endDate
            }
        });
        
        if (response.data.status) {
            return response.data.data;
        } else {
            throw new Error(response.data.message);
        }
    } catch (error) {
        if (error.response?.data?.error === 'User does not have an approved role in this platform') {
            console.error('Access denied: User not authorized for this platform');
        }
        throw error;
    }
};
```

## Security Considerations

1. **Required user_id**: Ensures that every request is associated with a user
2. **Role Verification**: Only platform owners, marketing managers, or financial managers can view platform KPIs
3. **Direct Field Check**: Validates against platform's manager fields (owner_id, marketing_manager_id, financial_manager_id)
4. **Middleware**: Protected by `check.url` middleware in partner routes
5. **Logging**: All access attempts are logged for audit purposes

## Testing Checklist

- [ ] Valid user with approved role - should return KPIs
- [ ] Valid user without role - should return error
- [ ] User with pending role - should return error
- [ ] User with rejected role - should return error
- [ ] Missing user_id - should return validation error
- [ ] Invalid user_id - should return validation error
- [ ] Invalid platform_id - should return validation error
- [ ] User with role + date filters - should return filtered KPIs
- [ ] User without platform_id (only user_id) - should work if no platform filter needed

## Route Configuration

**Namespace**: `App\Http\Controllers\Api\partner`
**Prefix**: `/api/partner/`
**Middleware**: `check.url`
**Route Name**: `api_sales_dashboard_kpis`
**Method**: `GET`
**Path**: `/sales/dashboard/kpis`

## Changes Summary

### Service Changes
- ✅ Added `userHasRoleInPlatform()` method
- ✅ Added role verification in `getKpiData()` before fetching data
- ✅ Enhanced error handling and logging
- ✅ Added `AssignPlatformRole` model import

### Controller Changes
- ✅ Made `user_id` required parameter
- ✅ Added `user_id` to filters array passed to service
- ✅ Maintained existing validation rules

### Route Changes
- ✅ Route registered in partner section with `check.url` middleware
- ✅ Full path: `/api/partner/sales/dashboard/kpis`

