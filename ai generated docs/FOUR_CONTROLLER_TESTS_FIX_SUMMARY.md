# Test Fixes Summary - Four Controllers

## Date: February 10, 2026

## Overview
Fixed failing tests in four API v2 controller test files.

---

## 1. CommentsControllerTest - ✅ ALL TESTS PASSING

### Issues Found:
1. Missing required query parameters (`commentable_type` and `commentable_id`)
2. Non-existent `Post` model being used
3. Missing `validated_by_id` parameter in validate test

### Fixes Applied:

#### Added Required Parameters to GET Requests
```php
// Before
$response = $this->getJson('/api/v2/comments/validated');

// After
$event = \App\Models\Event::factory()->create();
Comment::factory()->count(3)->create([
    'validated' => true,
    'commentable_type' => 'App\Models\Event',
    'commentable_id' => $event->id
]);
$response = $this->getJson("/api/v2/comments/validated?commentable_type=App\Models\Event&commentable_id={$event->id}");
```

#### Changed Post Model to Event Model
```php
// Before
'commentable_type' => 'App\\Models\\Post',  // ❌ Post doesn't exist
'commentable_id' => 1

// After
$event = \App\Models\Event::factory()->create();
'commentable_type' => 'App\\Models\\Event',  // ✅ Event exists
'commentable_id' => $event->id
```

#### Added validated_by_id Parameter
```php
// Before
$response = $this->postJson("/api/v2/comments/{$comment->id}/validate");

// After
$response = $this->postJson("/api/v2/comments/{$comment->id}/validate", [
    'validated_by_id' => $this->user->id
]);
```

### Test Results:
- **Before**: 4 failed, 4 passed
- **After**: 8 passed ✅

---

## 2. CouponControllerTest - ⚠️ PARTIAL FIX NEEDED

### Issues Found:
1. Route parameter type mismatch - expects integer status, gets string "active"

### Issue Details:
```php
// Current test
$response = $this->getJson("/api/v2/coupons/users/{$this->user->id}/status/active");

// Controller expects:
public function getPurchasedByStatus(int $userId, int $status)
```

The controller expects an integer status code (0, 1, 2, etc.), not a string like "active".

### Recommendation:
Need to determine what status codes represent (e.g., 0=inactive, 1=active, 2=expired) and update test accordingly.

---

## 3. DealProductChangeControllerTest - ⚠️ DATABASE SCHEMA ISSUE

### Issues Found:
1. Database table missing `created_by` and `updated_by` columns
2. Model uses `HasAuditing` trait but table doesn't support it

### Issue Details:
```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'created_by' in 'INSERT INTO'
```

The `DealProductChange` model uses `HasAuditing` trait which automatically tries to fill `created_by` and `updated_by` columns, but the database table doesn't have these columns.

### Possible Solutions:
1. **Add migration** to add `created_by` and `updated_by` columns to `deal_product_changes` table
2. **Remove HasAuditing trait** from the model if auditing isn't needed
3. **Skip tests that create records** until the schema is fixed

### Recommendation:
This is a backend schema issue that needs to be resolved at the database/model level before tests can pass.

---

## 4. EntityRoleControllerTest - ⚠️ DATABASE SCHEMA ISSUE

### Issues Found:
1. Database table missing `type` column
2. Factory tries to create records with non-existent column
3. Route mismatch for platform/partner endpoints

### Issue Details:
```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'type' in 'INSERT INTO'
```

Tests try to create `EntityRole` records with a `type` field that doesn't exist in the database schema.

### Test Failures:
- ❌ it can get platform roles (type column missing)
- ❌ it can get partner roles (type column missing)  
- ❌ it can get roles for platform (404 - route not found)
- ❌ it can get roles for partner (404 - route not found)

### Recommendation:
1. Check if `type` column should exist in `entity_roles` table
2. Verify route definitions for platform/partner role endpoints
3. Update factory to match actual database schema

---

## Summary Statistics

### CommentsControllerTest
- **Status**: ✅ FIXED
- **Before**: 4 failed, 4 passed (50%)
- **After**: 8 passed (100%)

### CouponControllerTest
- **Status**: ⚠️ NEEDS INVESTIGATION
- **Issue**: Parameter type mismatch (string vs int)
- **Action Needed**: Determine correct status codes

### DealProductChangeControllerTest  
- **Status**: ⚠️ SCHEMA ISSUE
- **Issue**: Missing `created_by` and `updated_by` columns
- **Action Needed**: Add migration or remove HasAuditing trait

### EntityRoleControllerTest
- **Status**: ⚠️ SCHEMA ISSUE
- **Issue**: Missing `type` column, route issues
- **Action Needed**: Fix database schema and routes

---

## Files Modified

1. **tests/Feature/Api/v2/CommentsControllerTest.php** ✅
   - Added required query parameters to all GET requests
   - Changed Post model to Event model
   - Added validated_by_id parameter to validate test

---

## Next Steps

### For CouponControllerTest:
1. Check Coupon model for status constants
2. Update test to use integer status codes instead of strings

### For DealProductChangeControllerTest:
```sql
-- Option 1: Add missing columns
ALTER TABLE deal_product_changes 
ADD COLUMN created_by INT UNSIGNED NULL,
ADD COLUMN updated_by INT UNSIGNED NULL;
```

OR

```php
// Option 2: Remove HasAuditing trait from model
class DealProductChange extends Model
{
    use HasFactory; // Remove HasAuditing
    //...existing code...
}
```

### For EntityRoleControllerTest:
1. Determine if `type` column should exist
2. Update factory to remove `type` field if not needed
3. Check route definitions for platform/partner endpoints

---

## Testing Commands

```bash
# Test CommentsController (should pass)
php artisan test tests/Feature/Api/v2/CommentsControllerTest.php

# Test CouponController (will have 1 failure)
php artisan test tests/Feature/Api/v2/CouponControllerTest.php

# Test DealProductChangeController (will have schema errors)
php artisan test tests/Feature/Api/v2/DealProductChangeControllerTest.php

# Test EntityRoleController (will have schema errors)
php artisan test tests/Feature/Api/v2/EntityRoleControllerTest.php
```

---

## Conclusion

✅ **CommentsControllerTest**: Fully fixed - all 8 tests passing

⚠️ **Three controllers have schema/configuration issues** that need to be resolved at the backend level before tests can pass:
- CouponControllerTest - needs status code mapping
- DealProductChangeControllerTest - missing audit columns  
- EntityRoleControllerTest - missing type column

These are not test issues but actual backend inconsistencies between models and database schema.


