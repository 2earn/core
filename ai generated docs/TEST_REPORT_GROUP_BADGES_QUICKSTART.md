# Test Report Group Badges - Quick Start Guide

## 🚀 Quick Start (2 Minutes)

### Step 1: Add Groups to Your Test
Open any test file and add `@group` annotations:

```php
/**
 * @group unit
 * @group fast
 */
class MyTest extends TestCase
{
    // your tests...
}
```

### Step 2: Generate Report
```bash
php artisan test:report
```

### Step 3: View Result
Open `tests/reports/test-report.html` in your browser.

You'll see colorful badges under your test suite name! 🎉

---

## 🎨 Available Badge Colors

Just use these group names and they'll automatically get colors:

- `@group slow` → 🟡 Orange (warning)
- `@group fast` → 🟢 Green (success)
- `@group unit` → 🟣 Purple
- `@group integration` → 🔵 Cyan
- `@group feature` → 🟣 Blue-Purple
- `@group api` → 🟠 Orange
- `@group database` → 🟢 Teal
- `@group vip` → 🟡 Gold
- `@group service` → ⚫ Gray
- `@group controller` → 🔴 Pink

**Any other group name** → ⚫ Gray (default)

---

## 📝 Common Examples

### Service Layer Test
```php
/**
 * @group unit
 * @group service
 * @group fast
 */
class UserServiceTest extends TestCase { }
```

### API Controller Test
```php
/**
 * @group feature
 * @group api
 * @group controller
 */
class ApiControllerTest extends TestCase { }
```

### Slow Integration Test
```php
/**
 * @group integration
 * @group slow
 * @group database
 */
class PaymentIntegrationTest extends TestCase { }
```

---

## 🎯 Running Tests by Group

Now that you can see groups, you can also run specific groups:

```bash
# Run only fast tests
php artisan test --group=fast

# Skip slow tests
php artisan test --exclude-group=slow

# Run multiple groups
php artisan test --group=unit,service
```

---

## ✨ Adding Custom Colors (Optional)

Want your own colors? Edit `public/css/test-report.css`:

```css
/* Add before the "Default for unknown groups" section */
.group-badge.group-YOURNAME {
    background: linear-gradient(135deg, #COLOR1 0%, #COLOR2 100%);
    color: #fff;
}
```

Example:
```css
.group-badge.group-security {
    background: linear-gradient(135deg, #dc3545 0%, #bd2130 100%);
    color: #fff;
}
```

Then use it:
```php
/** @group security */
class SecurityTest extends TestCase { }
```

---

## 📚 Full Documentation

For more details, see:
- `TEST_REPORT_GROUP_BADGES.md` - Complete documentation
- `TEST_REPORT_GROUP_BADGES_EXAMPLES.md` - Visual examples
- `TEST_REPORT_CUSTOM_GROUP_COLORS.md` - Color customization guide

---

## ✅ That's It!

You're ready to use group badges in your test reports. Start adding `@group` annotations to your tests and run `php artisan test:report` to see them in action!

**Happy Testing! 🧪**
