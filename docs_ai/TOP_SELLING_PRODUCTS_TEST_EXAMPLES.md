# Top-Selling Products API Test Examples

## Test Requests

### 1. Basic Request (Minimum Parameters)
```bash
curl -X GET "http://localhost/api/partner/sales/dashboard/top-products?user_id=1" \
  -H "Accept: application/json"
```

**Expected Response**:
```json
{
    "status": true,
    "message": "Top-selling products retrieved successfully",
    "data": [
        {"product_name": "Product A", "sale_count": 100},
        {"product_name": "Product B", "sale_count": 85}
    ]
}
```

---

### 2. With Platform Filter
```bash
curl -X GET "http://localhost/api/partner/sales/dashboard/top-products?user_id=1&platform_id=5" \
  -H "Accept: application/json"
```

---

### 3. With Date Range
```bash
curl -X GET "http://localhost/api/partner/sales/dashboard/top-products?user_id=1&start_date=2025-01-01&end_date=2025-12-31" \
  -H "Accept: application/json"
```

---

### 4. With Custom Limit
```bash
curl -X GET "http://localhost/api/partner/sales/dashboard/top-products?user_id=1&limit=20" \
  -H "Accept: application/json"
```

---

### 5. Full Parameters
```bash
curl -X GET "http://localhost/api/partner/sales/dashboard/top-products?user_id=1&platform_id=5&deal_id=10&start_date=2025-11-01&end_date=2025-11-30&limit=15" \
  -H "Accept: application/json"
```

---

### 6. With Deal Filter Only
```bash
curl -X GET "http://localhost/api/partner/sales/dashboard/top-products?user_id=1&deal_id=10&limit=20" \
  -H "Accept: application/json"
```

---

## Error Test Cases

### Test 1: Missing Required Parameter (user_id)
```bash
curl -X GET "http://localhost/api/partner/sales/dashboard/top-products" \
  -H "Accept: application/json"
```

**Expected Response** (422):
```json
{
    "status": "Failed",
    "message": "Validation failed",
    "errors": {
        "user_id": ["The user id field is required."]
    }
}
```

---

### Test 2: Invalid Date Format
```bash
curl -X GET "http://localhost/api/partner/sales/dashboard/top-products?user_id=1&start_date=invalid-date" \
  -H "Accept: application/json"
```

**Expected Response** (422):
```json
{
    "status": "Failed",
    "message": "Validation failed",
    "errors": {
        "start_date": ["The start date is not a valid date."]
    }
}
```

---

### Test 3: End Date Before Start Date
```bash
curl -X GET "http://localhost/api/partner/sales/dashboard/top-products?user_id=1&start_date=2025-12-31&end_date=2025-01-01" \
  -H "Accept: application/json"
```

**Expected Response** (422):
```json
{
    "status": "Failed",
    "message": "Validation failed",
    "errors": {
        "end_date": ["The end date must be a date after or equal to start date."]
    }
}
```

---

### Test 4: Invalid Platform ID
```bash
curl -X GET "http://localhost/api/partner/sales/dashboard/top-products?user_id=1&platform_id=99999" \
  -H "Accept: application/json"
```

**Expected Response** (422):
```json
{
    "status": "Failed",
    "message": "Validation failed",
    "errors": {
        "platform_id": ["The selected platform id is invalid."]
    }
}
```

---

### Test 5: Limit Out of Range
```bash
curl -X GET "http://localhost/api/partner/sales/dashboard/top-products?user_id=1&limit=150" \
  -H "Accept: application/json"
```

**Expected Response** (422):
```json
{
    "status": "Failed",
    "message": "Validation failed",
    "errors": {
        "limit": ["The limit must not be greater than 100."]
    }
}
```

---

### Test 6: User Without Platform Access
```bash
curl -X GET "http://localhost/api/partner/sales/dashboard/top-products?user_id=1&platform_id=5" \
  -H "Accept: application/json"
```

**Expected Response** (500):
```json
{
    "status": "Failed",
    "message": "Error retrieving top-selling products",
    "error": "User does not have a role in this platform"
}
```

---

## Postman/Insomnia Collection

### GET Request Configuration
**URL**: `{{base_url}}/api/partner/sales/dashboard/top-products`  
**Method**: GET  
**Headers**:
- `Accept: application/json`
- `Authorization: Bearer {{token}}` (if authentication required)

**Query Parameters**:
| Key | Value | Description |
|-----|-------|-------------|
| user_id | 1 | Required - User ID |
| platform_id | 5 | Optional - Platform filter |
| deal_id | 10 | Optional - Deal filter |
| start_date | 2025-01-01 | Optional - Start date |
| end_date | 2025-12-31 | Optional - End date |
| limit | 10 | Optional - Result limit |

---

## JavaScript/Axios Example

```javascript
// Using Axios
const getTopSellingProducts = async (filters) => {
    try {
        const params = {
            user_id: filters.userId,
            ...(filters.platformId && { platform_id: filters.platformId }),
            ...(filters.dealId && { deal_id: filters.dealId }),
            ...(filters.startDate && { start_date: filters.startDate }),
            ...(filters.endDate && { end_date: filters.endDate }),
            ...(filters.limit && { limit: filters.limit })
        };

        const response = await axios.get('/api/partner/sales/dashboard/top-products', {
            params,
            headers: {
                'Accept': 'application/json'
            }
        });

        console.log('Top Products:', response.data.data);
        return response.data.data;
    } catch (error) {
        console.error('Error:', error.response?.data || error.message);
        throw error;
    }
};

// Usage
const filters = {
    userId: 1,
    platformId: 5,
    dealId: 10,
    startDate: '2025-01-01',
    endDate: '2025-12-31',
    limit: 20
};

getTopSellingProducts(filters);
```

---

## PHP/Laravel HTTP Client Example

```php
use Illuminate\Support\Facades\Http;

$response = Http::get('http://localhost/api/partner/sales/dashboard/top-products', [
    'user_id' => 1,
    'platform_id' => 5,
    'deal_id' => 10,
    'start_date' => '2025-01-01',
    'end_date' => '2025-12-31',
    'limit' => 20
]);

if ($response->successful()) {
    $topProducts = $response->json('data');
    foreach ($topProducts as $product) {
        echo "{$product['product_name']}: {$product['sale_count']} sales\n";
    }
} else {
    echo "Error: " . $response->json('message');
}
```

---

## Expected Data Verification

### Verify Sale Count Calculation
The `sale_count` should equal the **SUM of quantities** from `order_details` table where:
- Order status = 6 (Dispatched)
- Matches date range filters
- Matches platform filter
- Grouped by product

### SQL Query to Verify
```sql
SELECT 
    items.name as product_name,
    SUM(order_details.qty) as sale_count
FROM order_details
JOIN orders ON order_details.order_id = orders.id
JOIN items ON order_details.item_id = items.id
WHERE orders.status = 6
    AND orders.created_at >= '2025-01-01'
    AND orders.created_at <= '2025-12-31'
    AND items.platform_id = 5
    AND items.deal_id = 10
GROUP BY items.id, items.name
ORDER BY sale_count DESC
LIMIT 10;
```

---

## Performance Testing

### Load Test with Apache Bench
```bash
ab -n 1000 -c 10 "http://localhost/api/partner/sales/dashboard/top-products?user_id=1"
```

### Expected Performance
- Response time: < 500ms
- Memory usage: < 50MB
- Database queries: 1-2 queries per request

---

## Integration Testing with PHPUnit

```php
<?php

namespace Tests\Feature\Api\Partner;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SalesDashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_top_selling_products_success()
    {
        $user = User::factory()->create();

        $response = $this->getJson('/api/partner/sales/dashboard/top-products', [
            'user_id' => $user->id,
            'limit' => 10
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    '*' => [
                        'product_name',
                        'sale_count'
                    ]
                ]
            ]);
    }

    public function test_get_top_selling_products_validation_error()
    {
        $response = $this->getJson('/api/partner/sales/dashboard/top-products');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['user_id']);
    }

    public function test_get_top_selling_products_with_filters()
    {
        $user = User::factory()->create();

        $response = $this->getJson('/api/partner/sales/dashboard/top-products', [
            'user_id' => $user->id,
            'start_date' => '2025-01-01',
            'end_date' => '2025-12-31',
            'limit' => 20
        ]);

        $response->assertStatus(200);
    }
}
```

---

## Troubleshooting

### Common Issues

1. **Empty Data Array**
   - Check if there are dispatched orders in date range
   - Verify platform_id has products
   - Check user has access to platform

2. **Authorization Error**
   - Verify user has role in specified platform
   - Check PlatformService::userHasRoleInPlatform() method

3. **Validation Errors**
   - Ensure all required parameters present
   - Check date format (YYYY-MM-DD)
   - Verify IDs exist in database

4. **Performance Issues**
   - Check database indexes on: orders.status, orders.created_at, items.platform_id
   - Consider caching for frequently accessed data
   - Monitor query execution time

---

**Testing Checklist**:
- [ ] Test with minimum parameters (user_id only)
- [ ] Test with all parameters
- [ ] Test date range filtering
- [ ] Test platform filtering
- [ ] Test limit parameter
- [ ] Test validation errors
- [ ] Test authorization
- [ ] Test with no data
- [ ] Verify sale count calculation
- [ ] Check response format
- [ ] Performance test
- [ ] Integration test

