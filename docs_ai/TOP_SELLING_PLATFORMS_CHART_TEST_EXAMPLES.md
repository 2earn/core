# Top Selling Platforms Chart - Test Examples

## Test Scenarios

### 1. Basic Request (Default Parameters)
**Endpoint:** `GET /api/partner/sales/dashboard/top-platforms`

**Expected:**
- Returns top 10 platforms
- All-time data (no date filtering)
- Ordered by sales descending

**cURL:**
```bash
curl -X GET "http://localhost/api/partner/platforms/top-selling" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

**Expected Response:**
```json
{
    "status": true,
    "message": "Top-selling platforms retrieved successfully",
    "data": {
        "top_platforms": [
            {
                "platform_id": 1,
                "platform_name": "Platform A",
                "total_sales": 25000.50
            },
            {
                "platform_id": 2,
                "platform_name": "Platform B",
                "total_sales": 18500.75
            }
            // ... up to 10 platforms
        ]
    }
}
```

---

### 2. Limited Results
**Endpoint:** `GET /api/partner/sales/dashboard/top-platforms?limit=5`

**Expected:**
- Returns top 5 platforms
- All-time data

**cURL:**
```bash
curl -X GET "http://localhost/api/partner/sales/dashboard/top-platforms?limit=5" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

---

### 3. Date Range Filter
**Endpoint:** `GET /api/partner/sales/dashboard/top-platforms?start_date=2025-01-01&end_date=2025-12-31`

**Expected:**
- Returns top 10 platforms
- Only data from 2025
- Ordered by sales descending

**cURL:**
```bash
curl -X GET "http://localhost/api/partner/sales/dashboard/top-platforms?start_date=2025-01-01&end_date=2025-12-31" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

---

### 4. Combined Filters
**Endpoint:** `GET /api/partner/sales/dashboard/top-platforms?start_date=2025-01-01&end_date=2025-12-31&limit=3`

**Expected:**
- Returns top 3 platforms
- Only data from 2025

**cURL:**
```bash
curl -X GET "http://localhost/api/partner/sales/dashboard/top-platforms?start_date=2025-01-01&end_date=2025-12-31&limit=3" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

---

### 5. Monthly Data
**Endpoint:** `GET /api/partner/sales/dashboard/top-platforms?start_date=2025-12-01&end_date=2025-12-31`

**Expected:**
- Returns top 10 platforms
- Only December 2025 data

**cURL:**
```bash
curl -X GET "http://localhost/api/partner/sales/dashboard/top-platforms?start_date=2025-12-01&end_date=2025-12-31" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

---

### 6. Maximum Limit
**Endpoint:** `GET /api/partner/sales/dashboard/top-platforms?limit=100`

**Expected:**
- Returns up to 100 platforms
- All-time data

**cURL:**
```bash
curl -X GET "http://localhost/api/partner/sales/dashboard/top-platforms?limit=100" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

---

## Error Test Cases

### 7. Invalid Date Format
**Endpoint:** `GET /api/partner/sales/dashboard/top-platforms?start_date=2025/01/01`

**Expected:**
- Status: 422
- Validation error for `start_date`

**cURL:**
```bash
curl -X GET "http://localhost/api/partner/sales/dashboard/top-platforms?start_date=2025/01/01" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

**Expected Response:**
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

### 8. End Date Before Start Date
**Endpoint:** `GET /api/partner/sales/dashboard/top-platforms?start_date=2025-12-31&end_date=2025-01-01`

**Expected:**
- Status: 422
- Validation error for `end_date`

**cURL:**
```bash
curl -X GET "http://localhost/api/partner/sales/dashboard/top-platforms?start_date=2025-12-31&end_date=2025-01-01" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

**Expected Response:**
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

### 9. Invalid Limit (Too High)
**Endpoint:** `GET /api/partner/sales/dashboard/top-platforms?limit=101`

**Expected:**
- Status: 422
- Validation error for `limit`

**cURL:**
```bash
curl -X GET "http://localhost/api/partner/sales/dashboard/top-platforms?limit=101" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

**Expected Response:**
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

### 10. Invalid Limit (Too Low)
**Endpoint:** `GET /api/partner/sales/dashboard/top-platforms?limit=0`

**Expected:**
- Status: 422
- Validation error for `limit`

**cURL:**
```bash
curl -X GET "http://localhost/api/partner/sales/dashboard/top-platforms?limit=0" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

**Expected Response:**
```json
{
    "status": "Failed",
    "message": "Validation failed",
    "errors": {
        "limit": ["The limit must be at least 1."]
    }
}
```

---

### 11. Missing Authentication
**Endpoint:** `GET /api/partner/sales/dashboard/top-platforms`

**Expected:**
- Status: 401
- Unauthenticated error

**cURL:**
```bash
curl -X GET "http://localhost/api/partner/sales/dashboard/top-platforms" \
  -H "Accept: application/json"
```

---

## JavaScript/Fetch Examples

### Basic Fetch
```javascript
async function getTopPlatforms() {
    try {
        const response = await fetch('/api/partner/sales/dashboard/top-platforms', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.status) {
            console.log('Top Platforms:', data.data.top_platforms);
            return data.data.top_platforms;
        } else {
            console.error('Error:', data.message);
        }
    } catch (error) {
        console.error('Request failed:', error);
    }
}
```

### With Parameters
```javascript
async function getTopPlatforms(startDate, endDate, limit = 10) {
    const params = new URLSearchParams();
    
    if (startDate) params.append('start_date', startDate);
    if (endDate) params.append('end_date', endDate);
    if (limit) params.append('limit', limit);
    
    try {
        const response = await fetch(`/api/partner/sales/dashboard/top-platforms?${params}`, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.status) {
            return data.data.top_platforms;
        } else {
            throw new Error(data.message);
        }
    } catch (error) {
        console.error('Error fetching top platforms:', error);
        throw error;
    }
}

// Usage
getTopPlatforms('2025-01-01', '2025-12-31', 5)
    .then(platforms => console.log(platforms))
    .catch(error => console.error(error));
```

---

## Axios Examples

### Basic Request
```javascript
import axios from 'axios';

async function getTopPlatforms() {
    try {
        const response = await axios.get('/api/partner/sales/dashboard/top-platforms', {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });
        
        return response.data.data.top_platforms;
    } catch (error) {
        console.error('Error:', error.response?.data || error.message);
        throw error;
    }
}
```

### With Parameters
```javascript
import axios from 'axios';

async function getTopPlatforms(params = {}) {
    try {
        const response = await axios.get('/api/partner/sales/dashboard/top-platforms', {
            params: {
                start_date: params.startDate,
                end_date: params.endDate,
                limit: params.limit || 10
            },
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });
        
        return response.data.data.top_platforms;
    } catch (error) {
        if (error.response?.status === 422) {
            console.error('Validation errors:', error.response.data.errors);
        } else {
            console.error('Error:', error.response?.data || error.message);
        }
        throw error;
    }
}

// Usage
getTopPlatforms({ 
    startDate: '2025-01-01', 
    endDate: '2025-12-31', 
    limit: 5 
})
.then(platforms => console.log(platforms))
.catch(error => console.error(error));
```

---

## Postman Collection

### Request Setup
1. **Method:** GET
2. **URL:** `{{base_url}}/api/partner/sales/dashboard/top-platforms`
3. **Authorization:** Bearer Token
4. **Headers:**
   - Accept: application/json
5. **Query Params:**
   - start_date (optional)
   - end_date (optional)
   - limit (optional)

### Pre-request Script (Optional)
```javascript
// Set current date range for testing
const today = new Date();
const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
const endOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);

pm.environment.set("start_date", startOfMonth.toISOString().split('T')[0]);
pm.environment.set("end_date", endOfMonth.toISOString().split('T')[0]);
```

### Tests Script
```javascript
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

pm.test("Response has correct structure", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData).to.have.property('status');
    pm.expect(jsonData).to.have.property('message');
    pm.expect(jsonData).to.have.property('data');
    pm.expect(jsonData.data).to.have.property('top_platforms');
});

pm.test("Top platforms is an array", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData.data.top_platforms).to.be.an('array');
});

pm.test("Each platform has required fields", function () {
    var jsonData = pm.response.json();
    if (jsonData.data.top_platforms.length > 0) {
        var platform = jsonData.data.top_platforms[0];
        pm.expect(platform).to.have.property('platform_id');
        pm.expect(platform).to.have.property('platform_name');
        pm.expect(platform).to.have.property('total_sales');
    }
});
```

---

## Test Checklist

- [ ] Basic request returns data
- [ ] Limit parameter works correctly
- [ ] Date range filtering works
- [ ] Combined filters work
- [ ] Invalid date format rejected
- [ ] Invalid limit range rejected
- [ ] End date before start date rejected
- [ ] Unauthorized request rejected
- [ ] Results are ordered by sales (descending)
- [ ] Platform names are correct
- [ ] Total sales values are accurate
- [ ] Response time is acceptable
- [ ] Logging works correctly

---

## Database Verification Queries

### Check Raw Data
```sql
SELECT 
    p.id as platform_id,
    p.name as platform_name,
    SUM(cbd.purchase_value) as total_sales,
    COUNT(*) as breakdown_count
FROM commission_break_downs cbd
INNER JOIN platforms p ON cbd.platform_id = p.id
WHERE cbd.platform_id IS NOT NULL
  AND cbd.purchase_value IS NOT NULL
GROUP BY p.id, p.name
ORDER BY total_sales DESC
LIMIT 10;
```

### Check Specific Platform
```sql
SELECT 
    SUM(purchase_value) as total_sales,
    COUNT(*) as breakdown_count
FROM commission_break_downs
WHERE platform_id = 1
  AND purchase_value IS NOT NULL;
```

### Check Date Range
```sql
SELECT 
    p.id as platform_id,
    p.name as platform_name,
    SUM(cbd.purchase_value) as total_sales
FROM commission_break_downs cbd
INNER JOIN platforms p ON cbd.platform_id = p.id
WHERE cbd.platform_id IS NOT NULL
  AND cbd.purchase_value IS NOT NULL
  AND cbd.created_at >= '2025-01-01'
  AND cbd.created_at <= '2025-12-31'
GROUP BY p.id, p.name
ORDER BY total_sales DESC
LIMIT 10;
```

---

## Notes

- Replace `YOUR_TOKEN` with actual authentication token
- Replace `localhost` with actual server URL
- All dates should be in `YYYY-MM-DD` format
- Limit must be between 1 and 100
- Results are always ordered by total_sales descending

