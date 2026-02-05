# Quick Reference: User Discount Balance API

## ⚡ Quick Start

### Endpoint
```
GET /api/partner/users/discount-balance?user_id={id}
```

### Example Request
```bash
curl -X GET "http://localhost:8000/api/partner/users/discount-balance?user_id=1" \
     -H "Accept: application/json"
```

### Example Response
```json
{
    "status": true,
    "message": "Discount balance retrieved successfully",
    "data": {
        "user_id": 1,
        "idUser": "123456789",
        "user_name": "John Doe",
        "user_email": "john@example.com",
        "discount_balance": 250.50
    }
}
```

---

## 📋 Key Information

| Property | Value |
|----------|-------|
| **Method** | GET |
| **Route Name** | `api_partner_users_discount_balance` |
| **Controller** | `UserPartnerController@getDiscountBalance` |
| **Middleware** | `check.url` (IP validation) |
| **Authentication** | None (IP-based) |

---

## 🔑 Request Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `user_id` | integer | ✅ Yes | User's auto-increment ID (not idUser) |

---

## ✅ Response Fields

| Field | Type | Description |
|-------|------|-------------|
| `status` | boolean | Success status |
| `message` | string | Response message |
| `data.user_id` | integer | User's auto-increment ID |
| `data.idUser` | string | User's legacy ID (9 chars) |
| `data.user_name` | string | User's full name |
| `data.user_email` | string | User's email address |
| `data.discount_balance` | float | Current discount balance |

---

## 🚨 Status Codes

| Code | Meaning | Description |
|------|---------|-------------|
| **200** | Success | Balance retrieved successfully |
| **404** | Not Found | User doesn't exist |
| **422** | Validation Error | Invalid or missing user_id |
| **403** | Forbidden | Invalid IP address |
| **500** | Server Error | Internal server error |

---

## 💡 Usage Examples

### JavaScript/Fetch
```javascript
async function getDiscountBalance(userId) {
    const response = await fetch(
        `/api/partner/users/discount-balance?user_id=${userId}`
    );
    const data = await response.json();
    return data.data.discount_balance;
}
```

### jQuery
```javascript
$.ajax({
    url: '/api/partner/users/discount-balance',
    data: { user_id: 1 },
    success: function(response) {
        console.log('Balance:', response.data.discount_balance);
    }
});
```

### PHP/Laravel
```php
$response = Http::get('/api/partner/users/discount-balance', [
    'user_id' => 1
]);
$balance = $response->json('data.discount_balance');
```

---

## ⚠️ Important Notes

### Dual ID System
- API accepts: `user_id` (integer, auto-increment)
- Database uses: `idUser` (string, 9 characters)
- Response includes: **both** `user_id` and `idUser`

### Null Handling
- No balance record → Returns `0`
- Null discount_balance → Returns `0`
- Actual balance → Returns float value

### IP Validation
- Requires whitelisted IP address
- Configure in `check.url` middleware
- Returns 403 if IP not allowed

---

## 🐛 Common Errors

### Error: "Validation failed"
**Cause:** Missing or invalid `user_id`  
**Solution:** Provide valid integer user_id in query params

### Error: "User not found"
**Cause:** User with given ID doesn't exist  
**Solution:** Verify user_id exists in database

### Error: "Unauthorized. Invalid IP."
**Cause:** Request from non-whitelisted IP  
**Solution:** Add IP to whitelist or configure middleware

---

## 📊 Database Table

**Table:** `user_current_balance_horisontals`

**Key Columns:**
- `user_id` → References `users.idUser` (legacy)
- `user_id_auto` → References `users.id` (modern)
- `discount_balance` → The balance value (double)

---

## 🧪 Testing

**Test File:** `tests/Feature/Api/Partner/UserPartnerControllerTest.php`

**Run Tests:**
```bash
php artisan test tests/Feature/Api/Partner/UserPartnerControllerTest.php
```

**Test Coverage:** ✅ 8 tests, 38 assertions, 100% pass rate

---

## 📚 Related Documentation

- **Full Documentation:** [GET_USER_DISCOUNT_BALANCE_ENDPOINT.md](./GET_USER_DISCOUNT_BALANCE_ENDPOINT.md)
- **Implementation Summary:** [GET_USER_DISCOUNT_BALANCE_IMPLEMENTATION_SUMMARY.md](./GET_USER_DISCOUNT_BALANCE_IMPLEMENTATION_SUMMARY.md)
- **Postman Collection:** [get-user-discount-balance-api.postman_collection.json](../get-user-discount-balance-api.postman_collection.json)

---

## 🔄 Version History

| Version | Date | Changes |
|---------|------|---------|
| 1.0 | Feb 5, 2026 | Initial implementation |

---

## 👤 Support

For issues or questions:
1. Check full documentation
2. Review test cases
3. Check Laravel logs: `storage/logs/laravel.log`
4. Look for `[UserPartnerController]` prefix in logs

---

**Status:** ✅ Production Ready  
**Last Updated:** February 5, 2026
