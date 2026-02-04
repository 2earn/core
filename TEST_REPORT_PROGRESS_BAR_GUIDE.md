# Test Report Progress Bar - Usage Guide

## ✅ What's New

The `php artisan test:report` command now includes:

### 1. **Real-time Progress Bar**
- Shows current test number vs total tests (e.g., `125/500`)
- Displays percentage completion
- Shows which test is currently running
- Updates in real-time as tests execute

### 2. **New Command Options**

```powershell
# Default usage with progress bar
php artisan test:report

# Verbose mode - see all test output (useful for debugging)
php artisan test:report --verbose

# Custom timeout (default is 1800 seconds / 30 minutes)
php artisan test:report --timeout=3600

# Skip running tests, just generate report from existing results
php artisan test:report --skip-tests

# Open report in browser automatically
php artisan test:report --open

# Combine options
php artisan test:report --verbose --timeout=3600 --open
```

## 🎯 Usage Examples

### Normal Run with Progress Bar
```powershell
php artisan test:report
```
**Output:**
```
🧪 Test Report Generator

📝 Running tests...

 125/500 [=========>------------------]  25% - UserServiceTest::testCreateUser
```

### Debug Hanging Tests
```powershell
php artisan test:report --verbose --timeout=600
```
This will:
- Show full test output as it runs
- Identify exactly which test is hanging
- Timeout after 10 minutes instead of 30

### Quick Report Generation
If tests are already run and you just want to regenerate the HTML:
```powershell
php artisan test:report --skip-tests --open
```

## 🐛 Troubleshooting Slow/Hanging Tests

### Find the Problematic Test
```powershell
# Run the diagnostic script
.\find-slow-test.ps1

# Or manually filter tests
php artisan test --filter="international"
```

### Common Issues with "get international image works" Test

1. **External API calls without timeout**
   - Add timeout to HTTP client
   - Mock external services

2. **Missing HTTP client mocks**
   - Ensure `Http::fake()` is called
   - Mock all external endpoints

3. **Database issues**
   - Check if transactions are rolling back
   - Ensure proper cleanup in test teardown

### Skip Specific Slow Tests Temporarily
```php
// In your test file
public function test_get_international_image_works()
{
    if (env('SKIP_SLOW_TESTS', false)) {
        $this->markTestSkipped('Slow test skipped');
    }
    
    // ... rest of test
}
```

Then run:
```powershell
$env:SKIP_SLOW_TESTS="true"; php artisan test:report
```

## 📊 Progress Bar Features

- **Current/Total**: Shows `125/500` format
- **Percentage**: Real-time percentage completion
- **Visual Bar**: ASCII progress indicator
- **Test Name**: Shows shortened name of current test
- **Warnings**: Special indicator for known slow tests

## ⚠️ Timeout Handling

If tests timeout, the command will:
1. Stop test execution
2. Show error message
3. Attempt to generate report from partial results
4. Continue if JUnit XML was partially generated

## 🔧 Configuration

Default timeout: **1800 seconds (30 minutes)**

To change permanently, edit `GenerateTestReport.php`:
```php
protected $signature = 'test:report
    {--timeout=3600 : Maximum execution time in seconds}';
```

## 📈 Performance Tips

1. **Increase timeout for large test suites**
   ```powershell
   php artisan test:report --timeout=3600
   ```

2. **Run tests in parallel** (if you have Laravel's parallel testing)
   ```powershell
   php artisan test --parallel
   ```

3. **Skip known slow tests** during development
   ```powershell
   php artisan test --exclude-group=slow
   ```

4. **Use database transactions** in tests for faster cleanup

## 🎉 Benefits

- ✅ See exactly which test is running
- ✅ Know how many tests are left
- ✅ Identify hanging tests immediately
- ✅ Better estimate of completion time
- ✅ No more staring at a blank screen
- ✅ Increased timeout to handle large test suites
