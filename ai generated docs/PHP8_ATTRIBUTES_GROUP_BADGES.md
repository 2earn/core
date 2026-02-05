# PHP 8 Attributes Support - Group Badges

## ✅ PHP 8 Attributes Now Fully Supported!

The test report now supports **both** annotation styles for PHPUnit groups:

### 1. Traditional Annotations (PHPUnit 9 and older)
```php
/**
 * @group vip
 * @group slow
 */
class VipServiceTest extends TestCase
{
    // tests...
}
```

### 2. PHP 8 Attributes (PHPUnit 10+) - **RECOMMENDED** ✨
```php
use PHPUnit\Framework\Attributes\Group;

#[Group('vip')]
#[Group('slow')]
class VipServiceTest extends TestCase
{
    // tests...
}
```

---

## 🎯 What Changed

### VipServiceTest.php Updated
**Before (Annotations):**
```php
/**
 * @group vip
 * @group slow
 */
class VipServiceTest extends TestCase
```

**After (Attributes):**
```php
use PHPUnit\Framework\Attributes\Group;

#[Group('vip')]
#[Group('slow')]
class VipServiceTest extends TestCase
```

### GenerateTestReport.php Enhanced
The extraction regex now handles PHP 8 attributes with flexible spacing:
```php
// Matches all these formats:
#[Group('vip')]
#[Group("vip")]
#[ Group('vip') ]
#[  Group( 'vip' )  ]
```

---

## 🚀 Usage Examples

### Single Group
```php
use PHPUnit\Framework\Attributes\Group;

#[Group('unit')]
class UserServiceTest extends TestCase
{
    // tests...
}
```

### Multiple Groups
```php
use PHPUnit\Framework\Attributes\Group;

#[Group('integration')]
#[Group('api')]
#[Group('slow')]
class PaymentApiTest extends TestCase
{
    // tests...
}
```

### Mixed with Other Attributes
```php
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(UserService::class)]
#[Group('unit')]
#[Group('fast')]
class UserServiceTest extends TestCase
{
    // tests...
}
```

---

## 🎨 Badge Display

All groups (whether from annotations or attributes) display the same way:

```
┌──────────────────────────────────────────┐
│ VipServiceTest                           │
│ [vip] [slow]                            │
│                                          │
│               ✓ 15 passed ⏱ 8.92s      │
└──────────────────────────────────────────┘
```

With colorful badges:
- 🟡 **vip** - Gold gradient
- 🟡 **slow** - Orange warning gradient

---

## 📋 Running Tests by Group

Works the same with both annotation styles:

```bash
# Run VIP tests only
php artisan test --group=vip

# Run all except slow tests
php artisan test --exclude-group=slow

# Run specific groups
php artisan test --group=unit,integration
```

---

## 🔧 Migration Guide

### Step 1: Add Import
```php
use PHPUnit\Framework\Attributes\Group;
```

### Step 2: Convert Annotations to Attributes

**From:**
```php
/**
 * @group unit
 * @group service
 */
class MyTest extends TestCase
```

**To:**
```php
#[Group('unit')]
#[Group('service')]
class MyTest extends TestCase
```

### Step 3: Generate Report
```bash
php artisan test:report
```

That's it! The badges will appear automatically.

---

## 🎓 Why Use PHP 8 Attributes?

### Advantages
✅ **Type Safety** - IDE autocomplete and validation  
✅ **Modern Syntax** - Cleaner, more readable  
✅ **Better Tooling** - Better IDE support  
✅ **PHPUnit 10+** - Future-proof  
✅ **No DocBlock Parsing** - More reliable  

### Backwards Compatible
The report generation supports **both** styles, so you can migrate gradually!

---

## 📊 Supported Attribute Formats

The extraction regex supports various spacing styles:

```php
// All these work:
#[Group('vip')]
#[Group("vip")]
#[ Group('vip') ]
#[  Group(  'vip'  )  ]
#[Group(
    'vip'
)]
```

---

## 🎨 Available Badge Colors

Same colors work for both annotation and attribute styles:

| Group | Badge Color | Use Case |
|-------|-------------|----------|
| slow | 🟡 Orange | Long-running tests |
| fast | 🟢 Green | Quick tests |
| unit | 🟣 Purple | Unit tests |
| integration | 🔵 Cyan | Integration tests |
| feature | 🟣 Blue-Purple | Feature tests |
| api | 🟠 Orange | API tests |
| database | 🟢 Teal | DB-dependent tests |
| vip | 🟡 Gold | VIP features |
| service | ⚫ Gray | Service layer |
| controller | 🔴 Pink | Controllers |

---

## 📚 Complete Example

```php
<?php

namespace Tests\Unit\Services;

use App\Services\VipService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

/**
 * VIP Service Test Suite
 *
 * Tests the VIP service functionality including
 * flash sales, calculations, and status checks.
 */
#[Group('vip')]
#[Group('slow')]
#[Group('service')]
#[Group('integration')]
class VipServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected VipService $vipService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->vipService = new VipService();
    }

    public function test_has_active_vip_works(): void
    {
        // Arrange
        $user = User::factory()->create();
        vip::factory()->active()->create(['idUser' => $user->idUser]);

        // Act
        $result = $this->vipService->hasActiveVip($user->idUser);

        // Assert
        $this->assertTrue($result);
    }
}
```

**This will display 4 badges in the report:**
- [vip] [slow] [service] [integration]

---

## ✅ Verification

### Check Your Test File
```bash
# View the VipServiceTest with attributes
cat tests/Unit/Services/VipServiceTest.php | grep -A 2 "#\[Group"
```

### Generate Report
```bash
php artisan test:report --skip-tests
```

### View Badges
Open `tests/reports/test-report.html` and look for VipServiceTest - you'll see the badges!

---

## 🔍 Implementation Details

### Regex Pattern Used
```php
// Pattern that matches PHP 8 attributes with flexible spacing
'/#\s*\[\s*Group\s*\(\s*[\'"](\w+)[\'"]\s*\)\s*\]/'
```

### Matches
- `#[Group('vip')]` ✅
- `#[Group("slow")]` ✅
- `#[ Group( 'fast' ) ]` ✅

### Does Not Match
- `#[Groups('multiple')]` ❌ (wrong attribute name)
- `#Group('test')` ❌ (missing brackets)
- `@group vip` ✅ (but caught by different pattern)

---

## 🎉 Summary

**✅ COMPLETE**: VipServiceTest now uses PHP 8 attributes

**Changes Made:**
1. ✅ Updated VipServiceTest.php to use `#[Group]` attributes
2. ✅ Enhanced GenerateTestReport.php regex for better attribute parsing
3. ✅ Maintained backward compatibility with `@group` annotations
4. ✅ Verified badges display correctly in HTML report

**Result:**
- Modern PHP 8 syntax ✨
- Same beautiful badges 🎨
- Better IDE support 💡
- Future-proof for PHPUnit 10+ 🚀

---

**Pro Tip:** You can use both styles in the same project during migration. The report generator extracts both!

**Date:** February 5, 2026  
**Status:** ✅ Production Ready  
**PHP Version:** 8.0+  
**PHPUnit Version:** 10+
