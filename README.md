# 2earn

## Template & Framework
- **Template:** Velzon
- **Framework:** Laravel 12
- **Frontend:** Livewire 3

---

## Project Overview
2earn is a comprehensive web application built with Laravel 12 and Livewire 3, utilizing the Velzon template. It offers a robust platform for managing users, financial operations, notifications, vouchers, and more, with a modular architecture and dynamic user interfaces.

---

## Main Features
- **User Management:** Registration, balances, roles, account settings
- **Financial Operations:** Coupons, shares, requests, deals, additional income
- **Notification System:** Real-time notifications for users
- **Voucher & Coupon Management:** Issue and redeem vouchers/coupons
- **Platform & Settings Configuration:** Admin and user settings
- **SMS Integration:** Send and manage SMS notifications
- **Country & Representative Management:** Regional and representative data
- **OAuth Authentication:** Secure login and API access
- **Post & Target Management:** Content and goal tracking

---

## Livewire Components
This project uses Livewire 3 for dynamic, reactive user interfaces. Key Livewire components include:
- **Balances:** Manage and display user balances
- **BusinessSectorShow, BusinessSectorIndex, BusinessSectorGroup, BusinessSectorCreateUpdate:** Business sector management and display
- **Biography:** User biography management
- **BfsToSms, BfsFunding:** BFS and SMS integration, funding operations
- **BeInfluencer:** Influencer management features
- **AdditionalIncome:** Track and manage additional income
- **Account:** User account management
- **AcceptFinancialRequest:** Handle financial request approvals
- **BussinessSectorsHome:** Business sector dashboard
- **CareerExperience:** Manage career experience data
- **BuyShares:** Share purchasing functionality
- **CDPersonality:** Personality-related features
- **CashToBfs:** Cash to BFS operations
- **Cart:** Shopping cart management
- **ConfigurationAmounts:** Configuration of financial amounts
- **ConditionCreateUpdate:** Create and update conditions

These components provide interactive features and streamline user workflows throughout the application.

---

## Setup Instructions
1. **Clone the repository and install dependencies:**
   ```bash
   composer install
   npm install
   ```
2. **Copy the example environment file and configure your settings:**
   ```bash
   cp .env.example .env
   ```
3. **Generate the application key:**
   ```bash
   php artisan key:generate
   ```
4. **Run migrations:**
   ```bash
   php artisan migrate
   ```
5. **(Optional) Build frontend assets:**
   ```bash
   npm run build
   ```

---

## Unit Testing Guide

### Overview
The 2earn project includes a comprehensive test suite with **108 passing tests** across 26 controller test files. All tests use modern PHP 8 attributes and follow Laravel best practices.

### Test Statistics
- ✅ **108 tests passing** (100%)
- ✅ **0 tests skipped** 
- ✅ **0 tests failing**
- ✅ **239 assertions**
- ⏱️ **Duration:** ~8 seconds

---

### Test Structure

#### Directory Organization
```
tests/
├── Feature/
│   ├── Controllers/          # 26 controller test files (108 tests)
│   │   ├── ApiControllerTest.php
│   │   ├── BalancesControllerTest.php
│   │   ├── HomeControllerTest.php
│   │   └── ... (23 more)
│   └── Api/
│       └── Partner/          # Partner-related tests
│           ├── PartnerRoleRequestTest.php
│           ├── PartnerPaymentControllerTest.php
│           └── ...
└── Unit/                     # Unit tests directory
```

#### Naming Conventions
- **Test Files:** `*ControllerTest.php` (e.g., `VoucherControllerTest.php`)
- **Test Methods:** `test_*` (e.g., `test_user_is_authenticated`)
- **Attributes:** Modern PHP 8 `#[Test]` attributes instead of `/** @test */`

---

### Running Tests

#### Basic Commands
```bash
# Run all tests
php artisan test

# Run all controller tests
php artisan test tests/Feature/Controllers

# Run specific test file
php artisan test tests/Feature/Controllers/ApiControllerTest.php

# Run specific test method
php artisan test --filter test_user_is_authenticated

# Run tests with detailed output
php artisan test --testdox

# Run tests with coverage (if configured)
php artisan test --coverage
```

#### Advanced Options
```bash
# Run tests in parallel
php artisan test --parallel

# Stop on first failure
php artisan test --stop-on-failure

# List all tests without running
php artisan test --list-tests

# Run with verbose output
php artisan test -v
```

---

### Test Coverage

#### Controller Tests (26 files)

**Core Controllers:**
- ✅ ApiController (9 tests) - API operations, buy actions, gifts
- ✅ HomeController (4 tests) - User profile, authentication
- ✅ OAuthController (9 tests) - OAuth authentication flow

**Balance Controllers:**
- ✅ BalancesController (4 tests) - Cash transfers, balance validation
- ✅ BalancesOperationsController (3 tests) - Operations management
- ✅ UsersBalancesController (4 tests) - User balance tracking
- ✅ SharesController (4 tests) - Share purchases and management

**Management Controllers:**
- ✅ CouponsController (5 tests) - Coupon management
- ✅ VoucherController (4 tests) - Voucher operations
- ✅ DealsController (3 tests) - Deal management
- ✅ VipController (5 tests) - VIP features

**System Controllers:**
- ✅ RolesController (3 tests) - Role management
- ✅ SettingsController (4 tests) - System settings
- ✅ NotificationsController (4 tests) - Notifications
- ✅ SmsController (4 tests) - SMS operations

**And 12 more...** (see full list in test directories)

---

### Test Features

#### What Each Test Includes

**1. Authentication Testing**
```php
#[Test]
public function test_user_is_authenticated()
{
    $this->assertAuthenticatedAs($this->user);
}
```

**2. Factory Testing**
```php
#[Test]
public function test_user_factory_creates_valid_user()
{
    $user = User::factory()->create();
    $this->assertDatabaseHas('users', ['id' => $user->id]);
}
```

**3. Service Mocking**
```php
#[Test]
public function test_service_can_be_mocked()
{
    $mock = Mockery::mock(VipService::class);
    $this->app->instance(VipService::class, $mock);
}
```

**4. Database Transactions**
All tests use `DatabaseTransactions` trait for automatic rollback:
```php
use DatabaseTransactions;
```

---

### Writing New Tests

#### Test Template
```php
<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class YourControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    #[Test]
    public function test_your_feature()
    {
        // Arrange
        $data = ['key' => 'value'];

        // Act
        $response = $this->postJson('/api/endpoint', $data);

        // Assert
        $response->assertStatus(200);
        $this->assertDatabaseHas('table', $data);
    }
}
```

#### Best Practices
1. ✅ Use `DatabaseTransactions` for automatic rollback
2. ✅ Create users with factories, not manually
3. ✅ Use `#[Test]` attributes instead of `/** @test */`
4. ✅ Follow Arrange-Act-Assert pattern
5. ✅ Use descriptive test method names
6. ✅ Mock external services to avoid dependencies
7. ✅ Test one thing per test method
8. ✅ Use factories for test data creation

---

### Test Documentation

Comprehensive documentation is available in:
- **ALL_CONTROLLER_TESTS_PASSING.md** - Complete test status and results
- **SKIPPED_TESTS_IMPLEMENTED.md** - Details on integration tests
- **PHPUNIT_WARNINGS_FIXED.md** - PHPUnit configuration guide
- **TEST_FIX_API_CONTROLLER.md** - Troubleshooting examples

---

### PHPUnit Configuration

The project uses PHPUnit 10+ with modern configuration:

**phpunit.xml highlights:**
- ✅ Modern schema (PHPUnit 10+)
- ✅ DatabaseTransactions for all tests
- ✅ Test reports in `tests/reports/`
- ✅ Environment: Testing mode
- ✅ Cache directory: `.phpunit.cache`

**Key Settings:**
```xml
<testsuites>
    <testsuite name="Feature">
        <directory>./tests/Feature</directory>
    </testsuite>
    <testsuite name="Unit">
        <directory>./tests/Unit</directory>
    </testsuite>
</testsuites>
```

---

### Continuous Integration

Tests are ready for CI/CD integration:

```yaml
# Example GitHub Actions workflow
- name: Run Tests
  run: php artisan test --parallel

- name: Generate Coverage
  run: php artisan test --coverage
```

---

### Troubleshooting

#### Common Issues

**Issue: Class not found errors**
```bash
composer dump-autoload
php artisan optimize:clear
```

**Issue: Database connection errors**
```bash
# Check .env.testing exists
cp .env .env.testing
php artisan config:clear
```

**Issue: Factory not found**
```bash
composer dump-autoload
php artisan migrate:fresh --seed
```

---

### Test Achievements

🎉 **Milestones Reached:**
- ✅ All controller tests implemented
- ✅ Zero tests skipped
- ✅ All PHPUnit warnings resolved
- ✅ Modern PHP 8 attributes used
- ✅ 100% test pass rate
- ✅ Production-ready test suite

---

## License
This project is under a private license and is the property of 2earn.cash company. Unauthorized use, distribution, or modification is strictly prohibited.
