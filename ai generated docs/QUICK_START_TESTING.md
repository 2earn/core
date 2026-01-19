# Quick Start - API Testing

## 🚀 Run Your First Test in 3 Steps

### Step 1: Setup (One-time only)

```powershell
# Copy testing environment configuration
Copy-Item .env.testing.example .env.testing

# If you haven't already, install dependencies
# composer install
```

### Step 2: Configure Database (Choose One Option)

**Option A: SQLite (Fastest - Recommended for quick testing)**
```powershell
# Edit .env.testing and ensure these lines are set:
# DB_CONNECTION=sqlite
# DB_DATABASE=:memory:
```

**Option B: MySQL (More realistic)**
```powershell
# Create test database
# mysql -u root -p
# CREATE DATABASE 2earn_test;
# exit;

# Edit .env.testing:
# DB_CONNECTION=mysql
# DB_DATABASE=2earn_test
# DB_USERNAME=root
# DB_PASSWORD=your_password
```

### Step 3: Run Tests

```powershell
# Windows - Using the batch script
.\run-tests.bat platform

# Or using Laravel Artisan
php artisan test tests/Feature/Api/Partner/PlatformPartnerControllerTest.php

# Or run all tests
php artisan test
```

---

## 📋 What You'll See

### Successful Test Output:
```
   PASS  Tests\Feature\Api\Partner\PlatformPartnerControllerTest
  ✓ can list platforms for partner
  ✓ can list platforms with pagination
  ✓ can search platforms
  ✓ list platforms fails without user id
  ✓ can create platform successfully
  ... (and 22 more)

  Tests:  27 passed
  Time:   5.23s
```

### If Tests Fail:
Don't worry! This is normal. Common issues:

1. **Missing Factories**
   ```
   Error: Class 'Database\Factories\PlatformValidationRequestFactory' not found
   ```
   **Fix:** Create the factory:
   ```powershell
   php artisan make:factory PlatformValidationRequestFactory
   ```

2. **Database Tables Missing**
   ```
   Error: Table 'platforms' doesn't exist
   ```
   **Fix:** Run migrations:
   ```powershell
   php artisan migrate --env=testing
   ```

3. **Authentication Issues**
   ```
   Error: Unauthenticated
   ```
   **Fix:** Ensure Passport is set up or check middleware

---

## 🎯 What's Being Tested

The test suite covers all PlatformPartnerController endpoints:

| Endpoint | Method | Test Cases | Status |
|----------|--------|------------|--------|
| `/api/partner/platforms` | GET | 4 tests | ✅ |
| `/api/partner/platforms` | POST | 3 tests | ✅ |
| `/api/partner/platforms/{id}` | GET | 2 tests | ✅ |
| `/api/partner/platforms/{id}` | PUT | 2 tests | ✅ |
| `/api/partner/platforms/validate` | POST | 1 test | ✅ |
| `/api/partner/platforms/change` | POST | 4 tests | ✅ |
| `/api/partner/platforms/validation/cancel` | POST | 1 test | ✅ |
| `/api/partner/platforms/change/cancel` | POST | 1 test | ✅ |
| `/api/partner/platforms/top-selling` | GET | 3 tests | ✅ |
| Authorization & Security | - | 2 tests | ✅ |

**Total: 27 comprehensive test cases**

---

## 🔍 Understanding Test Results

### Green (Passed) ✅
```
✓ can create platform successfully
```
- The functionality works as expected
- No action needed

### Red (Failed) ❌
```
✗ can create platform successfully
Expected status code 201 but received 422
```
- Something isn't working
- Check the error message
- Fix the code or adjust the test

### Yellow (Skipped) ⚠️
```
- can create platform successfully (skipped)
```
- Test was intentionally skipped
- Usually marked with `$this->markTestSkipped()`

---

## 🛠️ Debugging Failed Tests

### 1. Run Only the Failing Test
```powershell
php artisan test --filter test_can_create_platform
```

### 2. Add Debugging Output
```php
// In your test:
$response = $this->postJson('/api/platforms', $data);
dd($response->json()); // Dump and die to see response
```

### 3. Check Logs
```powershell
# View Laravel logs
Get-Content storage/logs/laravel.log -Tail 50
```

### 4. Enable Query Logging
```php
// In your test setUp():
\DB::enableQueryLog();

// After the test action:
dd(\DB::getQueryLog());
```

---

## 📚 Next Steps After First Run

### If All Tests Pass (Unlikely on first run)
1. Celebrate! 🎉
2. Run with coverage: `.\run-tests.bat coverage`
3. Create tests for other controllers
4. Setup CI/CD pipeline

### If Some Tests Fail (Expected)
1. Read error messages carefully
2. Create missing factories for related models:
   ```powershell
   php artisan make:factory PlatformValidationRequestFactory
   php artisan make:factory PlatformChangeRequestFactory
   php artisan make:factory PlatformTypeChangeRequestFactory
   ```
3. Ensure all migrations are run
4. Check model relationships and methods
5. Re-run tests after fixes

### Creating Missing Factories

**Example: PlatformValidationRequestFactory**
```php
<?php
namespace Database\Factories;

use App\Models\PlatformValidationRequest;
use App\Models\Platform;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlatformValidationRequestFactory extends Factory
{
    protected $model = PlatformValidationRequest::class;

    public function definition(): array
    {
        return [
            'platform_id' => Platform::factory(),
            'status' => 'pending',
            'requested_by' => User::factory(),
        ];
    }
}
```

---

## 💡 Pro Tips

### 1. Run Tests Before Pushing Code
```powershell
# Quick check before commit
php artisan test --stop-on-failure
```

### 2. Use Parallel Testing for Speed
```powershell
php artisan test --parallel
```

### 3. Watch Tests During Development
```powershell
# Install Laravel Pint first
# composer require laravel/pint --dev

# Then use with file watching (requires additional setup)
# Or manually run after each change
```

### 4. Focus on Red-Green-Refactor
1. 🔴 **Red:** Write a failing test
2. 🟢 **Green:** Make it pass with minimal code
3. 🔵 **Refactor:** Improve the code while keeping tests green

---

## 🎓 Learning by Example

### Test Structure Explained
```php
public function test_can_create_platform_successfully()
{
    // ARRANGE: Set up test data
    $platformData = [
        'name' => 'Test Platform',
        'type' => 'social',
        'created_by' => $this->user->id,
    ];

    // ACT: Perform the action being tested
    $response = $this->postJson('/api/partner/platforms', $platformData);

    // ASSERT: Verify the results
    $response->assertStatus(201);  // Check HTTP status
    
    $this->assertDatabaseHas('platforms', [  // Check database
        'name' => 'Test Platform'
    ]);
}
```

---

## 📞 Quick Command Reference

```powershell
# Run all tests
php artisan test

# Run only Platform tests
.\run-tests.bat platform

# Run specific test
php artisan test --filter test_can_create_platform

# Run with coverage
.\run-tests.bat coverage

# Run in parallel (faster)
.\run-tests.bat parallel

# Stop on first failure
php artisan test --stop-on-failure

# Verbose output
php artisan test -v
```

---

## ✅ Checklist Before Running

- [ ] Composer dependencies installed
- [ ] `.env.testing` file created
- [ ] Database configured (SQLite or MySQL)
- [ ] Migrations run (if using MySQL)
- [ ] Ready to test!

---

## 🆘 Getting Help

If you're stuck:

1. **Check error messages** - they're usually helpful
2. **Review the test file** - comments explain each test
3. **Check API_TESTING_GUIDE.md** - comprehensive guide
4. **Look at Laravel docs** - https://laravel.com/docs/testing

---

**Ready? Let's run your first test!**

```powershell
.\run-tests.bat platform
```

Good luck! 🚀
