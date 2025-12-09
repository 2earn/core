# Sales Dashboard KPI Implementation

## Overview
This document describes the implementation of the Sales Dashboard KPI endpoint that provides key performance indicators for sales analytics.

## Endpoint Details

### Endpoint URL
```
GET /api/v1/dashboard/sales/kpis
```

### Authentication
- **Required**: Yes (auth:sanctum middleware)
- **Route Name**: `api_sales_dashboard_kpis`

### Request Parameters

| Parameter | Type | Required | Description | Validation |
|-----------|------|----------|-------------|------------|
| `start_date` | date | No | Start date for filtering orders | Valid date format |
| `end_date` | date | No | End date for filtering orders | Valid date, must be >= start_date |
| `platform_id` | integer | No | Filter by specific platform | Must exist in platforms table |

### Response Format

#### Success Response (200)
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

#### Error Response (422 - Validation Error)
```json
{
    "status": "Failed",
    "message": "Validation failed",
    "errors": {
        "end_date": ["The end date must be a date after or equal to start date."]
    }
}
```

#### Error Response (500 - Server Error)
```json
{
    "status": "Failed",
    "message": "Error retrieving KPIs",
    "error": "Error message details"
}
```

## KPI Metrics Explanation

### 1. **total_sales**
- **Description**: Total count of all orders
- **Mapping**: All orders regardless of status
- **Use Case**: Shows overall order volume

### 2. **orders_in_progress**
- **Description**: Orders with "Ready" status
- **Status Value**: `OrderEnum::Ready` (value: 2)
- **Mapping**: Orders that are prepared and ready for dispatch
- **Use Case**: Shows orders waiting to be dispatched

### 3. **orders_successful**
- **Description**: Orders with "Dispatched" status
- **Status Value**: `OrderEnum::Dispatched` (value: 6)
- **Mapping**: Orders successfully delivered/dispatched
- **Use Case**: Shows completed successful orders

### 4. **orders_failed**
- **Description**: Orders with "Failed" status
- **Status Value**: `OrderEnum::Failed` (value: 5)
- **Mapping**: Orders that failed during processing
- **Use Case**: Shows failed order count for analysis

### 5. **total_customers**
- **Description**: Count of unique customers (distinct user_id)
- **Calculation**: Distinct count of user_id in filtered orders
- **Use Case**: Shows unique customer count for the filtered period

## Order Status Reference

Based on `Core\Enum\OrderEnum`:
- `New` = 1
- `Ready` = 2 (Orders in Progress)
- `Simulated` = 3
- `Paid` = 4
- `Failed` = 5 (Failed Orders)
- `Dispatched` = 6 (Successful Orders)

## Implementation Architecture

### Files Created

1. **Service Layer**: `app/Services/Dashboard/SalesDashboardService.php`
   - Handles business logic for KPI calculations
   - Builds queries with filters
   - Returns structured KPI data

2. **Controller**: `app/Http/Controllers/Api/Admin/SalesDashboardController.php`
   - Validates incoming requests
   - Calls the service layer
   - Returns formatted JSON responses
   - Handles error logging

3. **Route**: Added to `routes/api.php`
   - Protected by `auth:sanctum` middleware
   - Under `/api/v1/` prefix

### Database Relations

The implementation uses these model relationships:
```
Order
├── user (belongsTo User)
└── OrderDetails (hasMany)
    └── item (belongsTo Item)
        └── platform (hasOne Platform)
```

### Query Logic

#### Platform Filtering
Orders are filtered by platform through the following relationship chain:
1. Order → OrderDetails → Item → Platform
2. Uses `whereHas` to filter orders that have items belonging to the specified platform

#### Date Filtering
- Filters on `orders.created_at` column
- `start_date`: Greater than or equal (>=)
- `end_date`: Less than or equal (<=)

## Usage Examples

### Example 1: Get all KPIs without filters
```bash
GET /api/v1/dashboard/sales/kpis
Authorization: Bearer {token}
```

### Example 2: Get KPIs for a specific date range
```bash
GET /api/v1/dashboard/sales/kpis?start_date=2024-01-01&end_date=2024-12-31
Authorization: Bearer {token}
```

### Example 3: Get KPIs for a specific platform
```bash
GET /api/v1/dashboard/sales/kpis?platform_id=5
Authorization: Bearer {token}
```

### Example 4: Get KPIs with all filters
```bash
GET /api/v1/dashboard/sales/kpis?start_date=2024-01-01&end_date=2024-12-31&platform_id=5
Authorization: Bearer {token}
```

## Testing Recommendations

### Test Cases

1. **No Filters**: Should return all orders KPIs
2. **Date Range Only**: Should filter by date range correctly
3. **Platform Only**: Should filter by platform correctly
4. **Combined Filters**: Should apply all filters correctly
5. **Invalid Date Range**: Should return validation error (end_date < start_date)
6. **Invalid Platform ID**: Should return validation error (non-existent platform)
7. **Unauthorized Access**: Should return 401 without valid token

### Sample SQL Query
To verify the logic manually:
```sql
-- Total Sales
SELECT COUNT(*) FROM orders;

-- Orders In Progress (Ready)
SELECT COUNT(*) FROM orders WHERE status = 2;

-- Orders Successful (Dispatched)
SELECT COUNT(*) FROM orders WHERE status = 6;

-- Orders Failed
SELECT COUNT(*) FROM orders WHERE status = 5;

-- Total Customers
SELECT COUNT(DISTINCT user_id) FROM orders;

-- With Platform Filter
SELECT COUNT(DISTINCT o.user_id) 
FROM orders o
INNER JOIN order_details od ON o.id = od.order_id
INNER JOIN items i ON od.item_id = i.id
WHERE i.platform_id = ?;
```

## Error Handling

The implementation includes comprehensive error handling:
- **Validation Errors**: Returns 422 with detailed validation messages
- **Database Errors**: Logs error and returns 500 with generic message
- **Exception Handling**: All exceptions are caught, logged, and returned as proper responses

## Logging

All operations are logged with the prefix `[SalesDashboardController]` and `[SalesDashboardService]`:
- Validation failures
- Successful KPI retrieval
- Error conditions with full stack traces

## Performance Considerations

1. **Indexing**: Ensure indexes exist on:
   - `orders.created_at`
   - `orders.status`
   - `orders.user_id`
   - `items.platform_id`
   - Foreign key columns

2. **Query Optimization**: 
   - Uses `whereHas` for efficient relationship filtering
   - Clones queries to avoid redundant query building
   - Uses `distinct()` for unique customer count

3. **Caching**: Consider implementing caching for frequently accessed date ranges

## Future Enhancements

Possible improvements:
1. Add revenue calculations (total_revenue, average_order_value)
2. Add comparison with previous period (% change)
3. Add breakdown by status (New, Paid, Simulated)
4. Add time-series data for charts
5. Add export functionality (CSV, Excel)
6. Add real-time updates using WebSockets

## Dependencies

- Laravel Framework
- OrderEnum (Core\Enum\OrderEnum)
- Order Model (App\Models\Order)
- OrderDetail Model (App\Models\OrderDetail)
- Item Model (App\Models\Item)

## Quick Reference

**Service**: `App\Services\Dashboard\SalesDashboardService`
**Controller**: `App\Http\Controllers\Api\Admin\SalesDashboardController`
**Route**: `GET /api/v1/dashboard/sales/kpis`
**Middleware**: `auth:sanctum`
**Route Name**: `api_sales_dashboard_kpis`

