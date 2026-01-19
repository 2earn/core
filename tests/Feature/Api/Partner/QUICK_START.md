# Partner API Test Suite - Quick Start Guide

## Overview
Complete automated test suite for all `api_partner_*` routes has been generated successfully.

## Test Files Created

✅ **11 Test Files Generated:**
1. `PlatformPartnerControllerTest.php` - Platform management (existing)
2. `DealPartnerControllerTest.php` - Deal management
3. `DealProductChangeControllerTest.php` - Product change tracking
4. `OrderPartnerControllerTest.php` - Order management
5. `OrderDetailsPartnerControllerTest.php` - Order details
6. `ItemsPartnerControllerTest.php` - Item management
7. `SalesDashboardControllerTest.php` - Sales dashboard
8. `PartnerPaymentControllerTest.php` - Payment management
9. `PartnerRequestControllerTest.php` - Partner requests
10. `PlanLabelPartnerControllerTest.php` - Plan labels
11. `UserPartnerControllerTest.php` - User management

## Quick Test Commands

### Run All Partner Tests
```powershell
php artisan test tests/Feature/Api/Partner
```

### Run Specific Test File
```powershell
php artisan test tests/Feature/Api/Partner/DealPartnerControllerTest.php
```

### Run Tests with Detailed Output
```powershell
php artisan test tests/Feature/Api/Partner --testdox
```

### Run Tests with Coverage Report
```powershell
php artisan test tests/Feature/Api/Partner --coverage
```

## Test Coverage Summary

Each test file includes comprehensive tests for:
- ✅ **CRUD Operations** (Create, Read, Update)
- ✅ **List with Pagination** 
- ✅ **Search and Filtering**
- ✅ **Validation Failures**
- ✅ **Authentication Checks**
- ✅ **Authorization (IP Whitelist)**
- ✅ **Edge Cases**

## Total Test Count (Estimated)

- **PlatformPartnerControllerTest**: ~15 tests
- **DealPartnerControllerTest**: ~14 tests
- **DealProductChangeControllerTest**: ~7 tests
- **OrderPartnerControllerTest**: ~8 tests
- **OrderDetailsPartnerControllerTest**: ~4 tests
- **ItemsPartnerControllerTest**: ~7 tests
- **SalesDashboardControllerTest**: ~10 tests
- **PartnerPaymentControllerTest**: ~10 tests
- **PartnerRequestControllerTest**: ~5 tests
- **PlanLabelPartnerControllerTest**: ~4 tests
- **UserPartnerControllerTest**: ~5 tests

**Total: ~89 automated tests**

## Prerequisites Before Running

### 1. Database Setup
Ensure test database is configured in `.env.testing`:
```env
DB_CONNECTION=mysql
DB_DATABASE=2earn_testing
DB_HOST=127.0.0.1
DB_PORT=3306
DB_USERNAME=root
DB_PASSWORD=
```

### 2. Create Test Database
```powershell
mysql -u root -e "CREATE DATABASE IF NOT EXISTS 2earn_testing;"
```

### 3. Run Migrations
```powershell
php artisan migrate --env=testing
```

### 4. Check Required Factories
Ensure these factories exist:
- `UserFactory`
- `PlatformFactory`
- `DealFactory`
- `OrderFactory`
- `ItemFactory`
- `PartnerPaymentFactory`
- `PartnerRequestFactory`
- `PlanLabelFactory`
- `RoleFactory`
- `OrderDetailFactory`
- `DealProductChangeFactory`

## Running Tests Step-by-Step

### Step 1: Verify Test Structure
```powershell
dir tests\Feature\Api\Partner
```

### Step 2: Run a Single Test First
```powershell
php artisan test tests/Feature/Api/Partner/PlanLabelPartnerControllerTest.php
```

### Step 3: Run All Tests
```powershell
php artisan test tests/Feature/Api/Partner
```

### Step 4: Check Coverage
```powershell
php artisan test tests/Feature/Api/Partner --coverage-html coverage
```

## Common Issues and Solutions

### Issue: Factory Not Found
**Solution**: Create missing factory:
```powershell
php artisan make:factory ModelNameFactory
```

### Issue: Migration Errors
**Solution**: Reset test database:
```powershell
php artisan migrate:fresh --env=testing
```

### Issue: IP Whitelist Errors
**Solution**: Tests already mock IP to `127.0.0.1`. Check middleware configuration.

### Issue: Missing Models
**Solution**: Verify all models exist in `app/Models/` directory.

## Test Execution Best Practices

1. **Isolate Tests**: Each test uses `DatabaseTransactions` trait
2. **Clean State**: Database rolls back after each test
3. **Mock External Dependencies**: IP addresses and middleware are mocked
4. **Clear Assertions**: Each test has specific expected outcomes
5. **Descriptive Names**: Test methods clearly describe what they test

## Next Steps

1. ✅ Review test files for any project-specific adjustments
2. ✅ Ensure all factories match your model structure
3. ✅ Run tests to identify any missing dependencies
4. ✅ Add additional edge case tests as needed
5. ✅ Integrate tests into CI/CD pipeline

## CI/CD Integration

Add to your GitHub Actions or GitLab CI:
```yaml
test:
  script:
    - php artisan migrate --env=testing
    - php artisan test tests/Feature/Api/Partner --coverage
```

## Maintenance

- **Update tests** when API endpoints change
- **Add new tests** for new partner features
- **Review coverage** regularly to maintain quality
- **Document changes** in test commit messages

## Support

For issues or questions about the test suite:
1. Check test output for specific error messages
2. Review the main README.md for detailed documentation
3. Verify all prerequisites are met
4. Check factory definitions match model requirements

---

**Generated on**: January 19, 2026  
**Test Suite Version**: 1.0  
**Coverage Target**: 90%+ for all partner API endpoints
