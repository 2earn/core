# Quick Test Runner Guide for BalanceOperationServiceTest

## Run All Tests
```bash
php artisan test tests/Unit/Services/Balances/BalanceOperationServiceTest.php
```

## Run with Detailed Output (Test Names)
```bash
php artisan test tests/Unit/Services/Balances/BalanceOperationServiceTest.php --testdox
```

## Run Specific Test Method
```bash
php artisan test --filter test_create_operation_creates_new_operation
php artisan test --filter test_delete_operation_deletes_successfully
php artisan test --filter test_get_filtered_operations_returns_paginated_results
```

## Run with Coverage (if xdebug is enabled)
```bash
php artisan test tests/Unit/Services/Balances/BalanceOperationServiceTest.php --coverage
```

## Run and Stop on First Failure
```bash
php artisan test tests/Unit/Services/Balances/BalanceOperationServiceTest.php --stop-on-failure
```

## Run All Balance Tests
```bash
php artisan test tests/Unit/Services/Balances/
```

## Alternative: Using PHPUnit Directly
```bash
vendor\bin\phpunit tests/Unit/Services/Balances/BalanceOperationServiceTest.php
vendor\bin\phpunit tests/Unit/Services/Balances/BalanceOperationServiceTest.php --testdox
```

## Expected Output
All 13 tests should pass:
- ✅ test_get_filtered_operations_returns_paginated_results
- ✅ test_get_filtered_operations_filters_by_search
- ✅ test_get_operation_by_id_returns_operation_when_exists
- ✅ test_get_operation_by_id_returns_null_when_not_exists
- ✅ test_get_all_operations_returns_all_operations
- ✅ test_create_operation_creates_new_operation
- ✅ test_update_operation_updates_successfully
- ✅ test_update_operation_returns_false_when_not_found
- ✅ test_delete_operation_deletes_successfully
- ✅ test_delete_operation_returns_false_when_not_found
- ✅ test_get_operation_category_name_returns_name_when_exists
- ✅ test_get_operation_category_name_returns_dash_when_not_found
- ✅ test_get_operation_category_name_returns_dash_when_null

## Troubleshooting

### If tests fail with database errors:
1. Ensure your database is running (MySQL/MariaDB)
2. Check `.env` or `.env.testing` file for correct database credentials
3. Run migrations: `php artisan migrate --env=testing`

### If tests fail with class not found:
1. Clear cache: `php artisan cache:clear`
2. Clear config: `php artisan config:clear`
3. Regenerate autoload: `composer dump-autoload`

### If factory errors occur:
1. Check that all required fields in BalanceOperation model are present in factory
2. Ensure factory file is in correct location: `database/factories/BalanceOperationFactory.php`
