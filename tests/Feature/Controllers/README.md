# Controller Tests

This directory contains comprehensive test suites for all controllers in the 2earn application.

## Test Files (26 Total)

All controller tests follow Laravel naming conventions and are located in this directory.

### File Naming Convention
- Pattern: `*ControllerTest.php`
- Example: `ApiControllerTest.php`, `VoucherControllerTest.php`

### Test Structure
Each test file includes:
- PHPDoc annotations with full documentation
- Proper namespace: `Tests\Feature\Controllers`
- Test method stubs with descriptive names
- `setUp()` method with authenticated user
- `DatabaseTransactions` trait for automatic rollback
- Service mocking where appropriate

## Running Tests

### Run all controller tests:
```bash
php artisan test tests/Feature/Controllers
```

### Run specific controller test:
```bash
php artisan test --filter ApiControllerTest
```

### Run with verbosity:
```bash
php artisan test tests/Feature/Controllers -v
```

### List all tests without running:
```bash
php artisan test tests/Feature/Controllers --list-tests
```

## Test Status

⚠️ **All tests are currently skipped** with `markTestSkipped()` to allow for progressive implementation.

Each skipped test includes a note explaining what's required for implementation:
- Required models
- Required factories
- Required services
- Required database setup
- Required external dependencies

## Implementation Guide

To implement a test:

1. Open the test file (e.g., `ApiControllerTest.php`)
2. Choose a test method
3. Remove the `$this->markTestSkipped()` line
4. Implement the test logic
5. Create any required factories or seeders
6. Run the test: `php artisan test --filter TestMethodName`
7. Fix any issues
8. Repeat for other tests

## Documentation

For detailed information, see:
- **CONTROLLER_TESTS_SUMMARY.md** - Complete documentation of all tests
- **TESTS_QUICK_REFERENCE.md** - Quick reference guide
- **CONTROLLER_TESTS_MIGRATION_COMPLETE.md** - Migration summary

## Coverage

All 26 controllers have corresponding test files:
- ✅ ApiController
- ✅ BalancesController
- ✅ BalancesOperationsController
- ✅ ContactsController
- ✅ ContactUserController
- ✅ Controller (base)
- ✅ CountriesController
- ✅ CouponsController
- ✅ DealsController
- ✅ FinancialRequestController
- ✅ HomeController
- ✅ NotificationsController
- ✅ OAuthController
- ✅ PlatformController
- ✅ PostController
- ✅ RepresentativesController
- ✅ RequestController
- ✅ RolesController
- ✅ SettingsController
- ✅ SharesController
- ✅ SmsController
- ✅ TargetController
- ✅ UsersBalancesController
- ✅ UserssController
- ✅ VipController
- ✅ VoucherController

---

**Last Updated:** January 23, 2026
