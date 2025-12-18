# Top Selling Deals Chart - Quick Reference

## API Endpoint
```
GET /api/partner/sales/dashboard/top-deals
```

## Request Parameters

| Parameter | Type | Required | Default | Validation |
|-----------|------|----------|---------|------------|
| `start_date` | string | No | null | Date format (Y-m-d) |
| `end_date` | string | No | null | Date format, >= start_date |
| `platform_id` | integer | No | null | Must exist in platforms |
| `limit` | integer | No | 5 | Min: 1, Max: 100 |

## Response Format

```json
{
    "status": true,
    "message": "Top-selling deals retrieved successfully",
    "data": {
        "top_deals": [
            {
                "deal_id": 1,
                "deal_name": "Summer Sale 2025",
                "total_sales": 1250,
                "sales_count": 45
            }
        ]
    }
}
```

## Response Fields

- **deal_id**: Unique identifier of the deal
- **deal_name**: Name of the deal
- **total_sales**: Sum of all quantities (qty) sold for successful orders
- **sales_count**: Number of successful orders (Dispatched status)

## Quick Examples

### Basic Request
```bash
curl -X GET "http://your-domain/api/partner/sales/dashboard/top-deals"
```

### With Platform Filter
```bash
curl -X GET "http://your-domain/api/partner/sales/dashboard/top-deals?platform_id=3&limit=10"
```

### With Date Range
```bash
curl -X GET "http://your-domain/api/partner/sales/dashboard/top-deals?start_date=2025-01-01&end_date=2025-12-31"
```

## Business Logic

- ✅ Only counts **Dispatched** orders (successful orders)
- ✅ **total_sales** = SUM of order_details.qty
- ✅ **sales_count** = COUNT of distinct orders
- ✅ Orders by total_sales DESC
- ✅ Joins: Orders → OrderDetails → Items → Deals

## Status Codes

- **200**: Success
- **422**: Validation failed
- **500**: Server error

## Files Involved

1. **Service**: `app/Services/Dashboard/SalesDashboardService.php`
   - Method: `getTopSellingDeals()`

2. **Controller**: `app/Http/Controllers/Api/partner/SalesDashboardController.php`
   - Method: `getTopSellingDeals()`

3. **Route**: `routes/api.php`
   - Name: `api_sales_dashboard_top_deals`

## Testing Command

```bash
# Route list check
php artisan route:list | grep top-deals

# Clear cache if needed
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

## Common Use Cases

1. **Dashboard Widget**: Display top 5 deals
   - `?limit=5`

2. **Monthly Report**: Analyze deals for a month
   - `?start_date=2025-12-01&end_date=2025-12-31&limit=20`

3. **Platform Analysis**: Compare deals in specific platform
   - `?platform_id=5&limit=15`

4. **Quarterly Review**: Top performers in quarter
   - `?start_date=2025-10-01&end_date=2025-12-31&limit=10`

## Key Notes

- Default limit is 5 deals
- Only successful (Dispatched) orders counted
- Results sorted by total_sales descending
- Platform authorization checked automatically
- All dates should be in Y-m-d format (e.g., 2025-12-16)

