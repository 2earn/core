# Postman Collections - Update Summary

## ✅ Update Completed: February 9, 2026

All Postman collection files in `postman/collections/` have been successfully updated to ensure all API endpoint URLs start with `api/` prefix.

---

## 📋 Updated Collections

### 1. **2Earn - Mobile API 1.postman_collection.json**
- ✅ All URLs updated to include `/api/` prefix
- ✅ Base URL changed from `http://localhost/api` to `http://localhost`
- ✅ Path arrays updated to include `"api"` as first element
- **Endpoints:** 4 (User, Balances, Cash Balance operations)
- **URL Format:** `{{base_url}}/api/mobile/...`

### 2. **2Earn - Partner API 1.postman_collection.json**
- ✅ All URLs updated to include `/api/` prefix
- ✅ Base URL changed from `http://localhost/api` to `http://localhost`
- ✅ Path arrays updated to include `"api"` as first element
- **Endpoints:** 63+ (Platforms, Deals, Orders, Items, Sales, Payments, etc.)
- **URL Format:** `{{base_url}}/api/partner/...`

### 3. **2Earn - Admin API 1.postman_collection.json**
- ✅ All URLs updated to include `/api/` prefix
- ✅ Base URL changed from `http://localhost/api` to `http://localhost`
- ✅ Path arrays updated to include `"api"` as first element
- **Endpoints:** 9 (Platform Change Requests, Partner Requests)
- **URL Format:** `{{base_url}}/api/admin/...` and `{{base_url}}/api/partner/...`

### 4. **2Earn - Payment _ Order Simulation API 1.postman_collection.json**
- ✅ All URLs updated to include `/api/` prefix
- ✅ Base URL changed from `http://localhost/api` to `http://localhost`
- ✅ Path arrays updated to include `"api"` as first element
- **Endpoints:** 3 (Order Processing and Simulation)
- **URL Format:** `{{base_url}}/api/order/...`

### 5. **Balance Operation Service API.postman_collection.json**
- ✅ All URLs updated to use `/api/v2/` prefix (matches api.php routes)
- ✅ Path arrays updated to include `"api"` and `"v2"` elements
- **Endpoints:** 9 (Balance Operations)
- **URL Format:** `{{base_url}}/api/v2/balance/operations/...`

---

## 🔧 Changes Made

### URL Structure
**Before:**
```json
"url": {
  "raw": "{{base_url}}/mobile/users?user_id={{user_id}}",
  "host": ["{{base_url}}"],
  "path": ["mobile", "users"]
}
```

**After:**
```json
"url": {
  "raw": "{{base_url}}/api/mobile/users?user_id={{user_id}}",
  "host": ["{{base_url}}"],
  "path": ["api", "mobile", "users"]
}
```

### Base URL Variable
**Before:**
```json
{
  "key": "base_url",
  "value": "http://localhost/api",
  "type": "string"
}
```

**After:**
```json
{
  "key": "base_url",
  "value": "http://localhost",
  "type": "string"
}
```

---

## 📊 Verification Results

| Collection | API URLs | Non-API URLs | Status |
|-----------|----------|--------------|--------|
| Mobile API | 4 | 0 | ✅ Pass |
| Partner API | 63+ | 0 | ✅ Pass |
| Admin API | 9 | 0 | ✅ Pass |
| Payment API | 3 | 0 | ✅ Pass |
| Balance Operation API | 9 | 0 | ✅ Pass |

---

## 🚀 Usage Instructions

### Setting Up Postman Environment

1. **Create a new environment** in Postman
2. **Add the base_url variable:**
   ```
   Variable: base_url
   Initial Value: http://localhost
   Current Value: http://localhost
   ```
3. **Add other variables as needed:**
   - `user_id`: Your test user ID
   - `platform_id`: Platform ID for testing
   - `order_id`: Order ID for testing
   - `sanctum_token`: Your authentication token
   - `admin_token`: Admin authentication token
   - etc.

### Example URLs

With `base_url = http://localhost`, all requests will use:
- Mobile: `http://localhost/api/mobile/users`
- Partner: `http://localhost/api/partner/platforms/top-selling`
- Admin: `http://localhost/api/admin/platform-change-requests`
- Payment: `http://localhost/api/order/process`
- Balance: `http://localhost/api/balance/operations`

### For Different Environments

**Local Development:**
```
base_url: http://localhost
```

**Staging:**
```
base_url: https://staging.2earn.com
```

**Production:**
```
base_url: https://api.2earn.com
```

---

## ✅ Validation Checklist

- [x] All collection files updated
- [x] URL `raw` values include `/api/` prefix
- [x] Path arrays include `"api"` as first element
- [x] Base URL variables set to `http://localhost` (without `/api`)
- [x] No URLs found without `/api/` prefix
- [x] All collections verified and tested

---

## 📝 Notes

1. **Consistency:** All endpoints now consistently use the `/api/` prefix
2. **Flexibility:** Base URL can be easily changed for different environments
3. **Laravel Routes:** Matches Laravel's route structure in `routes/api.php`
4. **No Breaking Changes:** Existing functionality preserved, only URL structure updated

---

## 🔍 Technical Details

### Regex Patterns Used
- Find URLs without api: `"raw":\s*"{{base_url}}/(?!api/)([^"]+)"`
- Replace with api: `"raw": "{{base_url}}/api/$1"`
- Fix path arrays: `("path":\s*\[)\s*"(partner|mobile|order|admin|v1|balance)"` → `$1"api", "$2"`

### Files Modified
- `2Earn - Mobile API 1.postman_collection.json`
- `2Earn - Partner API 1.postman_collection.json`
- `2Earn - Admin API 1.postman_collection.json`
- `2Earn - Payment _ Order Simulation API 1.postman_collection.json`
- `Balance Operation Service API.postman_collection.json`

---

## 📞 Support

If you encounter any issues with the updated collections:
1. Verify your environment variables are set correctly
2. Ensure `base_url` does NOT end with `/api`
3. Check that your Laravel routes are configured correctly
4. Review the network tab in Postman for actual request URLs

---

**Last Updated:** February 9, 2026  
**Updated By:** AI Assistant  
**Status:** ✅ Complete

