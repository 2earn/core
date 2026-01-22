# PlatformPartnerControllerTest - Column Name Fix ✅

**Date:** January 19, 2026  
**Test:** `test_can_change_platform_type`  
**Status:** ✅ FIXED AND PASSING

## Issue

**Error:**
```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'old_type_id' in 'WHERE'
```

## Root Cause

The test was using incorrect column names when asserting database records:
- Used: `old_type_id` and `new_type_id`
- Actual columns: `old_type` and `new_type`

## Migration Structure

From `2025_11_18_140638_create_platform_type_change_requests_table.php`:
```php
Schema::create('platform_type_change_requests', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('platform_id');
    $table->integer('old_type');      // ← Not old_type_id
    $table->integer('new_type');      // ← Not new_type_id
    $table->string('status')->default('pending');
    $table->timestamps();
});
```

## Model Fillable Fields

From `PlatformTypeChangeRequest.php`:
```php
protected $fillable = [
    'platform_id',
    'old_type',        // ← Correct column name
    'new_type',        // ← Correct column name
    'status',
    'rejection_reason',
    'requested_by',
    'reviewed_by',
    'reviewed_at',
];
```

## Fix Applied

**File:** `tests/Feature/Api/Partner/PlatformPartnerControllerTest.php`

**Before (WRONG):**
```php
$this->assertDatabaseHas('platform_type_change_requests', [
    'platform_id' => $platform->id,
    'old_type_id' => 3,              // ❌ Column doesn't exist
    'new_type_id' => 1,              // ❌ Column doesn't exist
    'requested_by' => $this->user->id
]);
```

**After (CORRECT):**
```php
$this->assertDatabaseHas('platform_type_change_requests', [
    'platform_id' => $platform->id,
    'old_type' => 3,                 // ✅ Correct column name
    'new_type' => 1,                 // ✅ Correct column name
    'requested_by' => $this->user->id
]);
```

## Test Result

```
✓ can change platform type (6 assertions)
Tests: 1 passed
Duration: 0.46s
```

## Impact

- **PlatformPartnerControllerTest:** 9/23 tests now passing (was 8/23)
- **Overall Partner API:** 73/90 tests passing (81%)

## Lesson Learned

Always verify column names from migrations before writing test assertions. Don't assume `_id` suffix for all foreign/type columns.

---

**Status:** ✅ Test is now PASSING!
