# Deal Performance Chart / Timeline Endpoint

## Overview
API endpoint that provides data for visualizing the performance of a deal over time, including revenue tracking and progress metrics.

## Implementation Date
December 8, 2025

## Endpoint Details

### Route
```
GET /api/partner/deals/performance/chart
```

### Route Name
```
deals_performance_chart
```

## Request Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `user_id` | integer | Yes | ID of the user (must exist in users table) |
| `deal_id` | integer | Yes | ID of the deal to analyze |
| `start_date` | date | No | Start date for chart data (defaults to deal start_date) |
| `end_date` | date | No | End date for chart data (defaults to deal end_date) |
| `view_mode` | string | No | Aggregation mode: `daily`, `weekly`, or `monthly` (default: `daily`) |

## Response Structure

### Success Response (200 OK)
```json
{
  "status": true,
  "data": {
    "deal_id": 42,
    "target_amount": 100000.00,
    "current_revenue": 62500.50,
    "expected_progress": 45.50,
    "actual_progress": 62.50,
    "chart_data": [
      {
        "date": "2025-01-01",
        "revenue": 5000.00
      },
      {
        "date": "2025-01-02",
        "revenue": 7500.50
      },
      {
        "date": "2025-01-03",
        "revenue": 10000.00
      }
    ]
  }
}
```

### Response Fields

| Field | Type | Description |
|-------|------|-------------|
| `deal_id` | integer | ID of the deal |
| `target_amount` | float | Total revenue target (from `target_turnover`) |
| `current_revenue` | float | Revenue achieved so far (SUM of `order_details.total_amount`) |
| `expected_progress` | float | % of time elapsed: `(current_date - start_date) / (end_date - start_date) * 100` |
| `actual_progress` | float | % of revenue achieved: `(current_revenue / target_amount) * 100` |
| `chart_data` | array | Revenue values per time unit |
| `chart_data[].date` | string | Date of revenue record |
| `chart_data[].revenue` | float | Revenue recorded on that date |

## Progress Calculations

### Expected Progress (Time-Based)
Represents how much time has elapsed in the deal period:

- **Before start_date**: Returns 0%
- **After end_date**: Returns 100%
- **During deal period**: `(days_elapsed / total_days) * 100`

Example:
- Deal runs from Jan 1 to Jan 31 (31 days)
- Current date is Jan 16 (15 days elapsed)
- Expected progress: `(15 / 31) * 100 = 48.39%`

### Actual Progress (Revenue-Based)
Represents how much of the revenue target has been achieved:

- Formula: `(current_revenue / target_amount) * 100`
- Returns 0% if target_amount is 0

Example:
- Target: $100,000
- Current Revenue: $62,500
- Actual progress: `(62,500 / 100,000) * 100 = 62.50%`

## View Modes

### Daily View
- **Aggregation**: Groups revenue by individual day
- **Date Format**: `YYYY-MM-DD` (e.g., `2025-01-15`)
- **SQL Format**: `%Y-%m-%d`
- **Use Case**: Detailed day-by-day analysis

### Weekly View
- **Aggregation**: Groups revenue by week number
- **Date Format**: `YYYY-WWW` (e.g., `2025-W03`)
- **SQL Format**: `%Y-%u`
- **Use Case**: Weekly trends and patterns

### Monthly View
- **Aggregation**: Groups revenue by month
- **Date Format**: `YYYY-MM-01` (e.g., `2025-01-01`)
- **SQL Format**: `%Y-%m`
- **Use Case**: Long-term monthly overview

## Revenue Calculation

Revenue is calculated from orders associated with the deal's items:

```sql
SELECT SUM(order_details.total_amount) as revenue
FROM orders
JOIN order_details ON orders.id = order_details.order_id
JOIN items ON order_details.item_id = items.id
WHERE items.deal_id = ?
  AND orders.payment_datetime BETWEEN ? AND ?
  AND orders.payment_datetime IS NOT NULL
GROUP BY DATE_FORMAT(orders.payment_datetime, '[format]')
```

### Key Points:
- Only includes orders with completed payment (`payment_datetime IS NOT NULL`)
- Uses `order_details.total_amount` for accurate per-item revenue
- Filters by date range using `payment_datetime`
- Groups by payment date (not order creation date)

## Error Responses

### Validation Error (422 Unprocessable Entity)
```json
{
  "status": "Failed",
  "message": "Validation failed",
  "errors": {
    "deal_id": ["The deal id field is required."],
    "view_mode": ["The view mode must be one of: daily, weekly, monthly."]
  }
}
```

### Access Denied (500 Internal Server Error)
```json
{
  "status": false,
  "message": "Failed to retrieve deal performance chart: Deal not found or access denied"
}
```

### Server Error (500 Internal Server Error)
```json
{
  "status": false,
  "message": "Failed to retrieve deal performance chart: [error details]"
}
```

## Authorization & Permissions

The endpoint automatically verifies that the user has access to the deal:
- User must be Marketing Manager, Financial Manager, or Owner of the deal's platform
- Returns error if deal not found or user lacks permission

## Example Usage

### Basic Daily View
```bash
GET /api/partner/deals/performance/chart?user_id=123&deal_id=42
```

### Weekly View with Custom Date Range
```bash
GET /api/partner/deals/performance/chart?user_id=123&deal_id=42&view_mode=weekly&start_date=2025-01-01&end_date=2025-03-31
```

### Monthly View for Entire Deal Period
```bash
GET /api/partner/deals/performance/chart?user_id=123&deal_id=42&view_mode=monthly
```

## Implementation Details

### Service Layer
**File:** `app/Services/Deals/DealService.php`

**Main Method:** `getDealPerformanceChart()`

**Helper Methods:**
- `getRevenueChartData()` - Aggregates revenue data by view mode
- `calculateDealRevenue()` - Calculates total revenue for date range
- `calculateExpectedProgress()` - Calculates time-based progress percentage

### Controller Layer
**File:** `app/Http/Controllers/Api/partner/DealPartnerController.php`

**Method:** `performanceChart()`

### Route Registration
**File:** `routes/api.php`

```php
Route::get('deals/performance/chart', [DealPartnerController::class, 'performanceChart'])
    ->name('deals_performance_chart');
```

## Database Relations

```
Deal
  └── Items
        └── OrderDetails
              └── Orders (with payment_datetime)
```

## Use Cases

1. **Dashboard Visualization**: Display revenue trends over time
2. **Performance Monitoring**: Compare expected vs actual progress
3. **Target Tracking**: Monitor progress toward revenue goals
4. **Trend Analysis**: Identify patterns in daily/weekly/monthly sales
5. **Reporting**: Generate performance reports for stakeholders

## Performance Considerations

- Query uses indexed joins for optimal performance
- Date range filtering reduces dataset size
- Aggregation happens at database level (not in PHP)
- Results are formatted and rounded for consistency

## Testing Recommendations

1. **Test with different view modes**: Verify daily, weekly, monthly aggregations
2. **Test date range filtering**: Ensure custom dates work correctly
3. **Test with no revenue**: Verify behavior when no orders exist
4. **Test edge cases**: Deal before start_date, after end_date
5. **Test permissions**: Verify unauthorized users cannot access data
6. **Test zero target**: Ensure no division by zero errors
7. **Test incomplete deals**: Deals with partial revenue

## Related Endpoints

- `GET /api/partner/deals/dashboard/indicators` - Overview metrics for multiple deals
- `GET /api/partner/deals/{id}` - Get detailed deal information
- `GET /api/partner/deals` - List all accessible deals

## Notes

- All monetary values are rounded to 2 decimal places
- All percentages are rounded to 2 decimal places
- Chart data is sorted chronologically (oldest to newest)
- Empty periods (days/weeks/months with no revenue) are not included in chart_data
- Uses Carbon library for date calculations
- Logs all requests and errors for debugging

