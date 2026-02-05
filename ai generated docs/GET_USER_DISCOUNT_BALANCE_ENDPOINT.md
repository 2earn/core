# Get User Discount Balance API Endpoint - Documentation

## Overview
This document describes the endpoint to retrieve a user's discount balance from the `user_current_balance_horisontals` table.

## Endpoint Details
**Route:** `GET /api/partner/users/discount-balance`

**Route Name:** `api_partner_users_discount_balance`

**Controller Method:** `UserPartnerController@getDiscountBalance`

**Middleware:** `check.url` (IP validation)

## Request Parameters

### Query Parameters
- `user_id` (required, integer, exists in users table)
  - The auto-increment ID of the user (not idUser)
  - Must exist in the users table

## Request Example

```bash
# Get discount balance for a user
GET /api/partner/users/discount-balance?user_id=1
```

## Response Format

### Success Response (200 - OK)

#### When Balance Record Exists
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

#### When No Balance Record Found
```json
{
    "status": true,
    "message": "No balance record found. Discount balance is 0.",
    "data": {
        "user_id": 1,
        "idUser": "123456789",
        "user_name": "John Doe",
        "user_email": "john@example.com",
        "discount_balance": 0
    }
}
```

### Error Responses

#### User Not Found (404 - NOT FOUND)
```json
{
    "status": false,
    "message": "User not found"
}
```

#### Validation Failed (422 - UNPROCESSABLE ENTITY)

**Missing user_id:**
```json
{
    "status": false,
    "message": "Validation failed",
    "errors": {
        "user_id": [
            "User ID is required"
        ]
    }
}
```

**Invalid user_id (non-existent):**
```json
{
    "status": false,
    "message": "Validation failed",
    "errors": {
        "user_id": [
            "The specified user does not exist"
        ]
    }
}
```

**Invalid user_id (non-integer):**
```json
{
    "status": false,
    "message": "Validation failed",
    "errors": {
        "user_id": [
            "User ID must be an integer"
        ]
    }
}
```

#### Server Error (500 - INTERNAL SERVER ERROR)
```json
{
    "status": false,
    "message": "Failed to retrieve discount balance: [error details]"
}
```

#### Unauthorized IP (403 - FORBIDDEN)
```json
{
    "error": "Unauthorized. Invalid IP."
}
```

## Business Logic

1. **Request Validation:**
   - Validates that `user_id` is provided as a query parameter
   - Validates that `user_id` is an integer
   - Validates that the user exists in the users table

2. **User Lookup:**
   - Retrieves user by auto-increment `id` field
   - Returns 404 if user not found
   - Extracts `idUser` field for balance lookup

3. **Balance Retrieval:**
   - Uses `UserCurrentBalanceHorisontalService` to retrieve discount balance
   - Service queries `user_current_balance_horisontals` table using `idUser` field
   - Returns the `discount_balance` column value

4. **Response Handling:**
   - If balance record exists: Returns the discount_balance value (cast to float)
   - If balance record doesn't exist or discount_balance is null: Returns 0
   - Includes both `user_id` (auto-increment) and `idUser` (legacy) in response

5. **Error Handling:**
   - Catches all exceptions and logs with full stack trace
   - Returns appropriate HTTP status codes for different error scenarios

## Database Schema

### user_current_balance_horisontals Table
```sql
- id (bigint, auto-increment, primary key)
- user_id (bigint, references users.idUser) -- Legacy user ID
- user_id_auto (bigint, references users.id) -- Auto-increment user ID
- cash_balance (double, nullable)
- bfss_balance (longtext, nullable, default: '[]')
- discount_balance (double, nullable) -- **This field is returned**
- tree_balance (double, nullable)
- sms_balance (double, nullable)
- share_balance (double, nullable)
- chances_balance (longtext, nullable, default: '[]')
- timestamps (created_at, updated_at)
```

## Important Notes

### Dual User ID System
The system uses **two user ID fields**:
- `id`: Auto-increment integer (modern)
- `idUser`: 9-character string (legacy)

**API accepts:** `user_id` (auto-increment `id`)
**Service uses:** `idUser` (legacy string ID)
**Response includes:** Both `user_id` and `idUser`

This dual-ID system ensures backward compatibility with the legacy system.

## Use Cases

1. **Display User Balance:**
   - Show discount balance in user profile or dashboard
   - Display available discount balance before checkout

2. **Balance Verification:**
   - Verify user has sufficient discount balance for transactions
   - Check balance before applying discounts

3. **Financial Reports:**
   - Generate reports showing user discount balances
   - Audit user financial status

4. **Balance Monitoring:**
   - Track discount balance changes over time
   - Monitor balance levels for notifications

## Testing

The endpoint is covered by comprehensive tests in `UserPartnerControllerTest.php`:

- `test_can_get_discount_balance_for_user` - Successful retrieval with existing balance
- `test_get_discount_balance_returns_zero_when_no_balance_record` - No balance record exists
- `test_get_discount_balance_returns_zero_when_discount_is_null` - Balance record exists but discount_balance is null
- `test_get_discount_balance_fails_without_user_id` - Missing required parameter
- `test_get_discount_balance_fails_with_invalid_user_id` - Non-existent user ID
- `test_get_discount_balance_fails_with_non_integer_user_id` - Invalid data type
- `test_get_discount_balance_with_different_balance_values` - Various balance amounts (0, 0.01, 999.99, 10000.50)
- `test_get_discount_balance_fails_without_valid_ip` - IP validation

**Test Results:** ✅ All 8 tests passing (38 assertions)

## Related Endpoints

- `GET /api/mobile/balances` - Get all user balances (mobile API)
- `GET /api/partner/users/platforms` - Get user's partner platforms
- `POST /api/v1/add-cash` - Add cash balance to user

## Security

- **IP Validation:** Requires valid IP address via `check.url` middleware
- **Input Validation:** Strict validation of user_id parameter
- **Error Handling:** Secure error messages without exposing sensitive data
- **Logging:** Comprehensive logging for audit trails

## Service Layer

### UserCurrentBalanceHorisontalService

**Method Used:** `getStoredDiscount(int $idUser)`

**Returns:** 
- `float|null` - The discount balance value
- `null` - If no balance record exists

**Implementation:**
```php
public function getStoredDiscount(int $idUser)
{
    return $this->getStoredUserBalances($idUser, 'discount_balance');
}

public function getStoredUserBalances(int $idUser, ?string $balances = null)
{
    if (is_null($balances)) {
        return UserCurrentBalanceHorisontal::where('user_id', $idUser)->first();
    }
    return UserCurrentBalanceHorisontal::where('user_id', $idUser)->pluck($balances)->first();
}
```

## Performance Considerations

- **Single Query:** Uses a single database query via pluck()
- **Indexed Lookups:** Queries use indexed `user_id` field
- **Efficient Response:** Minimal data transfer (only necessary fields)
- **Caching Opportunity:** Balance data could be cached if needed

## Example Integration

### JavaScript/Fetch
```javascript
async function getUserDiscountBalance(userId) {
    try {
        const response = await fetch(`/api/partner/users/discount-balance?user_id=${userId}`);
        const data = await response.json();
        
        if (data.status && data.data.discount_balance) {
            console.log(`User has ${data.data.discount_balance} discount balance`);
            return data.data.discount_balance;
        }
        return 0;
    } catch (error) {
        console.error('Error fetching discount balance:', error);
        return 0;
    }
}
```

### PHP/Laravel
```php
use Illuminate\Support\Facades\Http;

$response = Http::get('https://your-domain.com/api/partner/users/discount-balance', [
    'user_id' => 1
]);

if ($response->successful() && $response->json('status')) {
    $discountBalance = $response->json('data.discount_balance');
    echo "Discount Balance: {$discountBalance}";
}
```

## Troubleshooting

### Issue: Returns 0 for existing user
**Cause:** No balance record in `user_current_balance_horisontals` table
**Solution:** Initialize user balance using `initUserCurrentBalance()` method

### Issue: 500 error returned
**Cause:** User has `id` but no `idUser` field
**Solution:** Ensure all users have both ID fields populated

### Issue: 403 Unauthorized IP
**Cause:** Request coming from IP not in whitelist
**Solution:** Add IP to allowed list or configure `check.url` middleware

## Changelog

### Version 1.0 (February 5, 2026)
- Initial implementation
- GET endpoint for retrieving user discount balance
- Request validation using GetDiscountBalanceRequest
- Comprehensive error handling
- Full test coverage (8 tests, 38 assertions)
- Documentation created

## Future Enhancements

1. **Batch Endpoint:** Get discount balances for multiple users
2. **Balance History:** Show balance change history over time
3. **Caching:** Implement Redis cache for frequent balance queries
4. **Webhooks:** Real-time balance change notifications
5. **Rate Limiting:** Prevent abuse of balance check endpoint
