# Top-Selling Products Histogram - Quick Reference

## Endpoint
```
GET /api/partner/sales/dashboard/top-products
```

## Parameters
- `user_id` (required): User ID for authorization
- `start_date` (optional): Filter start date
- `end_date` (optional): Filter end date  
- `platform_id` (optional): Specific platform filter
- `deal_id` (optional): Specific deal filter
- `limit` (optional): Number of results (default: 10, max: 100)

## Response
```json
{
    "status": true,
    "message": "Top-selling products retrieved successfully",
    "data": [
        {
            "product_name": "Product Name",
            "sale_count": 150
        }
    ]
}
```

## Example Usage
```bash
# Basic request
GET /api/partner/sales/dashboard/top-products?user_id=123

# With all filters
GET /api/partner/sales/dashboard/top-products?user_id=123&platform_id=5&deal_id=10&start_date=2025-01-01&end_date=2025-12-31&limit=20
```

## Key Points
- ✅ Only counts **successfully dispatched orders** (status = Dispatched)
- ✅ Sale count = **SUM of quantities** from order_details
- ✅ Results **ordered by sale_count** descending
- ✅ **Authorization check**: User must have role in platform (if platform_id provided)
- ✅ Filters on `orders.created_at` for date range

## Files Modified
- `app/Services/Dashboard/SalesDashboardService.php` - Added getTopSellingProducts()
- `app/Http/Controllers/Api/partner/SalesDashboardController.php` - Added endpoint
- `routes/api.php` - Added route

## Related Documentation
- Full implementation: `TOP_SELLING_PRODUCTS_IMPLEMENTATION.md`

