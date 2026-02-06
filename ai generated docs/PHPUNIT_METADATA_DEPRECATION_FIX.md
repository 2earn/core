# PHPUnit Metadata Deprecation Warnings Fix
**Date:** February 6, 2026

## Problem
PHPUnit was showing deprecation warnings for three test files:
```
WARN  Metadata found in doc-comment for class Tests\Unit\Services\CommentServiceTest. 
      Metadata in doc-comments is deprecated and will no longer be supported in PHPUnit 12. 
      Update your test code to use attributes instead.

WARN  Metadata found in doc-comment for class Tests\Unit\Services\EventServiceTest. 
      Metadata in doc-comments is deprecated and will no longer be supported in PHPUnit 12. 
      Update your test code to use attributes instead.

WARN  Metadata found in doc-comment for class Tests\Feature\Api\Partner\DealPartnerControllerTest. 
      Metadata in doc-comments is deprecated and will no longer be supported in PHPUnit 12. 
      Update your test code to use attributes instead.
```

## Root Cause
The test files were using **doc-comment annotations** (@group) to define PHPUnit metadata, which is deprecated in PHPUnit 10+ and will be removed in PHPUnit 12.

## Solution
Converted doc-comment annotations to **PHP 8 attributes** using the `#[Group]` attribute.

## Changes Made

### 1. CommentServiceTest.php

**Before:**
```php
use Tests\TestCase;

/**
 * @group unit
 * @group service
 * @group fast
 */
class CommentServiceTest extends TestCase
```

**After:**
```php
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('unit')]
#[Group('service')]
#[Group('fast')]
class CommentServiceTest extends TestCase
```

### 2. EventServiceTest.php

**Before:**
```php
use Tests\TestCase;

/**
 * @group unit
 * @group service
 * @group database
 */
class EventServiceTest extends TestCase
```

**After:**
```php
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('unit')]
#[Group('service')]
#[Group('database')]
class EventServiceTest extends TestCase
```

### 3. DealPartnerControllerTest.php

**Before:**
```php
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @group feature
 * @group api
 * @group controller
 */
class DealPartnerControllerTest extends TestCase
```

**After:**
```php
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Group;

#[Group('feature')]
#[Group('api')]
#[Group('controller')]
class DealPartnerControllerTest extends TestCase
```

## Test Results
All tests passing with **no warnings**:

```
✅ Tests\Unit\Services\CommentServiceTest - 9 tests passed
✅ Tests\Unit\Services\EventServiceTest - 12 tests passed
✅ Tests\Feature\Api\Partner\DealPartnerControllerTest - 15 tests passed

Total: 36 tests passed (423 assertions)
Duration: 2.82s
```

## Files Modified
1. `tests/Unit/Services/CommentServiceTest.php`
2. `tests/Unit/Services/EventServiceTest.php`
3. `tests/Feature/Api/Partner/DealPartnerControllerTest.php`

## Benefits
1. ✅ **No more deprecation warnings** - Clean test output
2. ✅ **PHPUnit 12 ready** - Tests will work in future PHPUnit versions
3. ✅ **Better IDE support** - PHP attributes provide better autocomplete and type checking
4. ✅ **Modern PHP** - Uses PHP 8+ features (attributes)

## PHPUnit Attributes Reference
Common PHPUnit attributes that replace doc-comment annotations:

| Doc-Comment | Attribute |
|------------|-----------|
| `@group name` | `#[Group('name')]` |
| `@depends test` | `#[Depends('test')]` |
| `@dataProvider provider` | `#[DataProvider('provider')]` |
| `@covers Class` | `#[CoversClass(Class::class)]` |
| `@test` | `#[Test]` |
| `@before` | `#[Before]` |
| `@after` | `#[After]` |

## Recommendations
1. Search for other test files with doc-comment annotations
2. Convert them proactively to attributes
3. Update coding standards to use attributes for new tests

## Search Command
To find other files that might need updating:
```bash
grep -r "@group" tests/ --include="*.php"
```

---
**Status:** ✅ Complete - All warnings resolved

