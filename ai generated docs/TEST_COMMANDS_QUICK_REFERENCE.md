# 🧪 Test Commands Quick Reference

## Run Tests

```bash
# Run all tests (excluding slow group)
php artisan test --exclude-group=slow

# Run specific test file
php artisan test tests/Unit/Services/UserServiceTest.php

# Run specific test method
php artisan test --filter=test_get_user_by_id_user_works

# Run with coverage
php artisan test --coverage
```

## Generate Test Report

```bash
# Generate report (auto-excludes slow tests)
php artisan test:report

# Generate and open in browser
php artisan test:report --open

# Use existing test results
php artisan test:report --skip-tests --open

# Include slow tests (may hang!)
php artisan test:report --include-slow

# Show full output while running
php artisan test:report --show-output
```

## Test Groups

```bash
# Run only vip tests (individual - works)
php artisan test --group=vip --filter=test_get_active_vip_by_user_id_works

# Exclude multiple groups
php artisan test --exclude-group=slow --exclude-group=integration

# Run only feature tests
php artisan test tests/Feature/
```

## Troubleshooting

```bash
# If tests hang, exclude slow group
php artisan test --exclude-group=slow

# If report generation hangs
php artisan test:report  # Already excludes slow by default

# To test VipService individually
php artisan test --filter=test_get_active_vip_by_user_id_works
```

## Quick Stats

- **Total Tests**: ~1384 tests
- **Duration**: ~132-197 seconds (excluding slow)
- **Success Rate**: ~99%
- **Excluded by Default**: VipServiceTest (hangs)

## Files

- **Test Report**: `tests/reports/test-report.html`
- **JUnit XML**: `tests/reports/junit.xml`
- **Coverage**: `tests/reports/coverage/`

## Documentation

- `TEST_SUITE_RESOLUTION_FINAL_SUMMARY.md` - Complete resolution
- `VIPSERVICETEST_HANGING_ISSUE.md` - VipService issue details
- `TEST_REPORT_COMMAND_USAGE.md` - Full command documentation
- `GENERATETESTREPORT_UPDATE.md` - Command update details
