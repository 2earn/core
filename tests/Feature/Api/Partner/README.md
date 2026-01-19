# Partner API Test Suite Documentation

This directory contains automated tests for all Partner API endpoints (`api_partner_*` routes).

## Test Files Overview

### 1. PlatformPartnerControllerTest.php
Tests for platform management endpoints:
- GET `/api/partner/platforms/platforms` - List platforms
- GET `/api/partner/platforms/platforms/{id}` - Show platform
- POST `/api/partner/platforms/platforms` - Create platform
- PUT `/api/partner/platforms/platforms/{id}` - Update platform
- POST `/api/partner/platforms/change` - Change platform type
- POST `/api/partner/platforms/validate` - Validate request
- POST `/api/partner/platforms/validation/cancel` - Cancel validation
- POST `/api/partner/platforms/change/cancel` - Cancel change request
- GET `/api/partner/platforms/top-selling` - Top selling platforms

### 2. DealPartnerControllerTest.php
Tests for deal management endpoints:
- GET `/api/partner/deals/deals` - List deals
- GET `/api/partner/deals/deals/{id}` - Show deal
- POST `/api/partner/deals/deals` - Create deal
- PUT `/api/partner/deals/deals/{id}` - Update deal
- PATCH `/api/partner/deals/{deal}/status` - Change status
- POST `/api/partner/deals/validate` - Validate request
- POST `/api/partner/deals/validation/cancel` - Cancel validation
- POST `/api/partner/deals/change/cancel` - Cancel change request
- GET `/api/partner/deals/dashboard/indicators` - Dashboard indicators
- GET `/api/partner/deals/performance/chart` - Performance chart

### 3. DealProductChangeControllerTest.php
Tests for product change tracking:
- GET `/api/partner/deals/product-changes` - List product changes
- GET `/api/partner/deals/product-changes/{id}` - Show product change
- GET `/api/partner/deals/product-changes/statistics` - Statistics

### 4. OrderPartnerControllerTest.php
Tests for order management:
- GET `/api/partner/orders/orders` - List orders
- GET `/api/partner/orders/orders/{id}` - Show order
- POST `/api/partner/orders/orders` - Create order
- PUT `/api/partner/orders/orders/{id}` - Update order
- PATCH `/api/partner/orders/{order}/status` - Change status

### 5. OrderDetailsPartnerControllerTest.php
Tests for order details:
- POST `/api/partner/orders/details` - Create order detail
- PUT `/api/partner/orders/details/{id}` - Update order detail

### 6. ItemsPartnerControllerTest.php
Tests for item management:
- POST `/api/partner/items` - Create item
- PUT `/api/partner/items/{id}` - Update item
- GET `/api/partner/items/deal/{dealId}` - List items for deal
- POST `/api/partner/items/deal/add-bulk` - Add items in bulk
- POST `/api/partner/items/deal/remove-bulk` - Remove items in bulk

### 7. SalesDashboardControllerTest.php
Tests for sales dashboard:
- GET `/api/partner/sales/dashboard/kpis` - KPIs
- GET `/api/partner/sales/dashboard/evolution-chart` - Sales evolution
- GET `/api/partner/sales/dashboard/top-products` - Top products
- GET `/api/partner/sales/dashboard/top-deals` - Top deals
- GET `/api/partner/sales/dashboard/transactions` - Transactions
- GET `/api/partner/sales/dashboard/transactions/details` - Transaction details

### 8. PartnerPaymentControllerTest.php
Tests for payment management:
- GET `/api/partner/payments` - List payments
- GET `/api/partner/payments/{id}` - Show payment
- POST `/api/partner/payments/demand` - Create payment demand
- GET `/api/partner/payments/statistics/summary` - Payment statistics

### 9. PartnerRequestControllerTest.php
Tests for partner requests:
- GET `/api/partner/partner-requests` - List requests
- GET `/api/partner/partner-requests/{id}` - Show request
- POST `/api/partner/partner-requests` - Create request
- PUT `/api/partner/partner-requests/{id}` - Update request

### 10. PlanLabelPartnerControllerTest.php
Tests for plan labels:
- GET `/api/partner/plan-label` - List plan labels

### 11. UserPartnerControllerTest.php
Tests for user management:
- POST `/api/partner/users/add-role` - Add role to user
- GET `/api/partner/users/platforms` - Get partner platforms

## Running Tests

### Run All Partner API Tests
```bash
php artisan test --testsuite=Feature --filter Partner
```

### Run Specific Test File
```bash
php artisan test tests/Feature/Api/Partner/DealPartnerControllerTest.php
```

### Run Specific Test Method
```bash
php artisan test --filter test_can_list_deals_for_partner
```

### Run with Coverage
```bash
php artisan test --coverage --testsuite=Feature --filter Partner
```

## Test Structure

All tests follow these patterns:

1. **DatabaseTransactions**: Uses database transactions to rollback changes after each test
2. **setUp()**: Creates necessary test data (user, platform, etc.)
3. **IP Mocking**: Sets REMOTE_ADDR to '127.0.0.1' to pass check.url middleware
4. **Assertions**: Tests response status, JSON structure, and data integrity

## Common Test Scenarios

Each test file includes tests for:
- ✅ Successful operations (list, show, create, update)
- ✅ Pagination support
- ✅ Filtering and search
- ✅ Validation failures (missing required fields)
- ✅ Authentication failures (missing user_id)
- ✅ Authorization failures (invalid IP address)
- ✅ Edge cases and error handling

## Prerequisites

Before running tests, ensure:
1. Database is configured and migrations are run
2. Required factories exist for models
3. Test database is separate from development database

## Environment Setup

Add to `.env.testing`:
```env
DB_CONNECTION=mysql
DB_DATABASE=2earn_testing
APP_ENV=testing
```

## Notes

- All partner API routes use the `check.url` middleware
- Tests mock the IP address to '127.0.0.1' for valid access
- Tests use factories for creating test data
- DatabaseTransactions trait ensures test isolation
- User authentication is simulated via user_id parameter

## Coverage Goals

Target coverage for each test file:
- Routes: 100%
- Controller methods: 90%+
- Business logic: 85%+
- Error handling: 100%
