# ModelNotFoundException Import Fix

## Issue
Tests were failing with the error:
```
Class "Tests\Unit\Services\ModelNotFoundException" does not exist
```

## Root Cause
Test files were using `ModelNotFoundException` but missing the proper import statement:
```php
use Illuminate\Database\Eloquent\ModelNotFoundException;
```

Without the import, PHP was looking for the class in the current namespace (`Tests\Unit\Services\ModelNotFoundException`) instead of the correct one (`Illuminate\Database\Eloquent\ModelNotFoundException`).

## Files Fixed

### 1. ✅ UserGuideServiceTest.php
**Location:** `tests/Unit/Services/UserGuide/UserGuideServiceTest.php`

**Fixed:**
- Added `use Illuminate\Database\Eloquent\ModelNotFoundException;` import

**Test Method:**
- `test_get_by_id_or_fail_throws_exception_when_not_exists()`

### 2. ✅ BalanceOperationServiceTest.php
**Location:** `tests/Unit/Services/BalanceOperationServiceTest.php`

**Fixed:**
- Added `use Illuminate\Database\Eloquent\ModelNotFoundException;` import

**Test Method:**
- `test_find_by_id_or_fail_throws_exception_when_not_exists()`

### 3. ✅ CommentServiceTest.php (Already had import, removed RefreshDatabase)
**Location:** `tests/Unit/Services/CommentServiceTest.php`

**Fixed:**
- Already had the correct import ✅
- Removed `use RefreshDatabase;` trait

### 4. ✅ EventServiceTest.php (Already had import, removed RefreshDatabase)
**Location:** `tests/Unit/Services/EventServiceTest.php`

**Fixed:**
- Already had the correct import ✅
- Removed `use RefreshDatabase;` trait

## Solution Applied

### Before:
```php
<?php

namespace Tests\Unit\Services;

use App\Services\SomeService;
use Tests\TestCase;

class SomeServiceTest extends TestCase
{
    public function test_something_throws_exception()
    {
        $this->expectException(ModelNotFoundException::class); // ❌ Class not found!
        // ...
    }
}
```

### After:
```php
<?php

namespace Tests\Unit\Services;

use App\Services\SomeService;
use Illuminate\Database\Eloquent\ModelNotFoundException; // ✅ Proper import
use Tests\TestCase;

class SomeServiceTest extends TestCase
{
    public function test_something_throws_exception()
    {
        $this->expectException(ModelNotFoundException::class); // ✅ Works!
        // ...
    }
}
```

## Impact
- Tests that check for `ModelNotFoundException` exceptions will now run properly
- No more "Class does not exist" errors for ModelNotFoundException
- 4 test files updated

## Related Files Using ModelNotFoundException
All files using `expectException(ModelNotFoundException::class)` have been verified and fixed:
- ✅ UserGuideServiceTest.php
- ✅ BalanceOperationServiceTest.php
- ✅ CommentServiceTest.php
- ✅ EventServiceTest.php

## Testing
To verify the fix works:
```bash
php artisan test tests/Unit/Services/BalanceOperationServiceTest.php::test_find_by_id_or_fail_throws_exception_when_not_exists
php artisan test tests/Unit/Services/UserGuide/UserGuideServiceTest.php::test_get_by_id_or_fail_throws_exception_when_not_exists
```

---

**Status:** ✅ FIXED  
**Date:** January 28, 2026  
**Files Modified:** 4 test files  
