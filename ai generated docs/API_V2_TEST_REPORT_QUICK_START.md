# Quick Start: Testing API v2 with GenerateTestReport

## Test Only API v2 Controllers

### Basic Usage
```bash
php artisan test:report --path=tests/Feature/Api/v2
```

### With Auto-open Browser
```bash
php artisan test:report --path=tests/Feature/Api/v2 --open
```

### Exclude Slow Tests
```bash
php artisan test:report --path=tests/Feature/Api/v2 --exclude-group=slow --open
```

### With Custom Timeout (30 minutes)
```bash
php artisan test:report --path=tests/Feature/Api/v2 --timeout=1800 --open
```

## Expected Output

```
🧪 Test Report Generator

📝 Running tests...
   Test path: tests/Feature/Api/v2

📊 220+ tests will be executed

  PASS  Tests\Feature\Api\v2\DealControllerTest
  ✓ it can get filtered deals
  ✓ it requires is super admin field
  ✓ it can get all deals
  ...

  PASS  Tests\Feature\Api\v2\EventControllerTest
  ✓ it can get paginated events
  ✓ it can search events
  ...

[... more test output ...]

✅ Test report generated successfully!

┌────────────────┬──────────┐
│ Metric         │ Value    │
├────────────────┼──────────┤
│ Total Tests    │ 220      │
│ Passed         │ 215      │
│ Failed         │ 5        │
│ Errors         │ 0        │
│ Skipped        │ 0        │
│ Success Rate   │ 97.73%   │
│ Total Time     │ 123.45s  │
└────────────────┴──────────┘

📁 Report location: C:\laragon\www\2earn\tests\reports\test-report.html
🌐 Opening report in browser...
```

## What Gets Tested

When you run with `--path=tests/Feature/Api/v2`, the following test files are executed:

### All 34 API v2 Controller Tests:
✅ AssignPlatformRoleControllerTest
✅ BalanceInjectorCouponControllerTest
✅ BalancesOperationsControllerTest
✅ BusinessSectorControllerTest
✅ CommentsControllerTest
✅ CommissionBreakDownControllerTest
✅ CommunicationBoardControllerTest
✅ CommunicationControllerTest
✅ CouponControllerTest
✅ DealControllerTest
✅ DealProductChangeControllerTest
✅ EntityRoleControllerTest
✅ EventControllerTest
✅ FaqControllerTest
✅ HashtagControllerTest
✅ ItemControllerTest
✅ NewsControllerTest
✅ OrderControllerTest
✅ PartnerControllerTest
✅ PartnerPaymentControllerTest
✅ PendingDealChangeRequestsControllerTest
✅ PendingDealValidationRequestsControllerTest
✅ PendingPlatformChangeRequestsInlineControllerTest
✅ PendingPlatformRoleAssignmentsInlineControllerTest
✅ PlatformChangeRequestControllerTest
✅ PlatformControllerTest
✅ PlatformTypeChangeRequestControllerTest
✅ PlatformValidationRequestControllerTest
✅ RoleControllerTest
✅ TranslaleModelControllerTest
✅ TranslateTabsControllerTest
✅ TranslationMergeControllerTest
✅ UserBalancesControllerTest
✅ UserGuideControllerTest

## Report Features

The generated HTML report will show:
- **Overview Dashboard**: Total tests, pass/fail counts, success rate
- **Test Suite Breakdown**: Each controller test file with its results
- **Individual Test Results**: Each test method with pass/fail status
- **Execution Time**: Time taken for each test and total time
- **Error Details**: Full error messages for failed tests
- **Groups**: Test groups/tags for each test suite
- **Search & Filter**: Interactive filtering in the HTML report

## Other Useful Commands

### Test Specific Controller
```bash
php artisan test:report --path=tests/Feature/Api/v2/DealControllerTest.php --open
```

### Test All Feature Tests
```bash
php artisan test:report --path=tests/Feature --open
```

### Test All Unit Tests
```bash
php artisan test:report --path=tests/Unit --open
```

### Skip Test Execution (Use Existing Results)
```bash
php artisan test:report --path=tests/Feature/Api/v2 --skip-tests --open
```

## Tips

1. **First Run**: The first test run will be slower as it sets up the database
2. **Browser Auto-open**: Use `--open` to automatically view the report
3. **CI/CD**: Use `--timeout` to prevent timeouts in CI pipelines
4. **Debugging**: Test specific files when debugging failures
5. **Parallel**: You can run different paths in parallel in different terminals

## Troubleshooting

### Tests Taking Too Long?
```bash
# Exclude slow or external tests
php artisan test:report --path=tests/Feature/Api/v2 --exclude-group=slow --exclude-group=external
```

### Need More Time?
```bash
# Increase timeout to 1 hour
php artisan test:report --path=tests/Feature/Api/v2 --timeout=3600
```

### Want to See Previous Results?
```bash
# Skip re-running tests
php artisan test:report --skip-tests --open
```

## Report Location

The HTML report is always saved to:
```
tests/reports/test-report.html
```

You can open it manually anytime by opening this file in your browser.

---

**Date**: February 10, 2026
**Version**: Enhanced with --path option

