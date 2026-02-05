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

### Test Report Generator

The project includes a powerful **Test Report Generator** command that runs PHPUnit tests and generates a beautiful, interactive HTML report with detailed statistics and test results.

#### Command Overview
```bash
php artisan test:report
```

This command will:
1. 🧪 Execute all PHPUnit tests (excluding slow tests by default)
2. 📊 Parse the JUnit XML test results
3. 🎨 Generate a beautiful HTML report
4. 📁 Save the report to `tests/reports/test-report.html`

---

#### Command Options

**Basic Usage:**
```bash
# Run tests and generate report
php artisan test:report

# Generate report from existing test results (skip test execution)
php artisan test:report --skip-tests

# Generate report and open in browser automatically
php artisan test:report --open
```

**Advanced Options:**

```bash
# Include slow tests (by default they are excluded)
php artisan test:report --include-slow

# Exclude specific test groups
php artisan test:report --exclude-group=integration --exclude-group=api

# Set custom timeout (default: 1800 seconds / 30 minutes)
php artisan test:report --timeout=3600

# Combine multiple options
php artisan test:report --include-slow --open --timeout=600
```

---

#### Command Options Reference

| Option | Description | Default Value |
|--------|-------------|---------------|
| `--skip-tests` | Skip running tests and use existing JUnit XML results | `false` |
| `--open` | Automatically open the HTML report in your default browser | `false` |
| `--timeout=N` | Maximum execution time in seconds for test suite | `1800` (30 min) |
| `--exclude-group=GROUP` | Exclude specific test groups (can be used multiple times) | `slow` |
| `--include-slow` | Include slow tests (removes "slow" from exclude list) | `false` |

---

#### Report Features

The generated HTML report includes:

**📊 Test Statistics Dashboard:**
- Total tests executed
- Passed tests count
- Failed tests count
- Skipped tests count
- Success rate percentage
- Total execution time

**🎯 Test Suite Breakdown:**
- Organized by test class
- Individual test case results
- Execution time per test
- Readable test names (formatted from method names)
- Full error messages for failed tests

**🎨 Visual Design:**
- Modern, responsive design
- Color-coded test results (green, red, yellow)
- Expandable test suite sections
- Easy navigation
- Mobile-friendly layout

---

#### Usage Examples

**Example 1: Quick Report Generation**
```bash
# Run all tests (except slow) and generate report
php artisan test:report
```
**Output:**
```
🧪 Test Report Generator

📝 Running tests...
   Excluding groups: slow

📊 108 tests will be executed

  PASS  Tests\Feature\Controllers\ApiControllerTest
  ✓ test_user_is_authenticated
  ...

📊 Parsing test results...
🎨 Generating HTML report...
✅ Test report generated successfully!

+--------------+--------+
| Metric       | Value  |
+--------------+--------+
| Total Tests  | 1404   |
| Passed       | 1401   |
| Failed       | 1      |
| Skipped      | 2      |
| Success Rate | 99.79% |
| Total Time   | 89.31s |
+--------------+--------+


📁 Report location: C:\laragon\www\2earn\tests\reports\test-report.html
```

---

**Example 2: Quick Check Without Re-running Tests**
```bash
# Use existing test results to regenerate report
php artisan test:report --skip-tests --open
```
**Use case:** You already ran tests and just want to view the report or regenerate it with updated styling.

---

**Example 3: Complete Test Suite (Including Slow Tests)**
```bash
# Run all tests including slow ones
php artisan test:report --include-slow --timeout=3600
```
**Use case:** CI/CD pipelines, comprehensive testing before production deployment.

---

**Example 4: Custom Test Groups**
```bash
# Exclude multiple test groups
php artisan test:report --exclude-group=integration --exclude-group=external --exclude-group=slow
```
**Use case:** Running only unit tests for rapid feedback during development.

---

**Example 5: Developer Workflow**
```bash
# Quick feedback loop during development
php artisan test:report --open --timeout=300
```
**Use case:** Fast test execution with immediate visual feedback in the browser.

---

#### Report Location

The HTML report is saved to:
```
tests/reports/test-report.html
```

You can open it manually in any browser or use the `--open` flag to open it automatically.

---

#### Requirements

The test report generator requires:
- ✅ PHPUnit configured with JUnit XML logging
- ✅ `tests/reports/` directory (created automatically)
- ✅ Laravel Blade view: `resources/views/test-report.blade.php`
- ✅ Symfony Process component (included in Laravel)

**PHPUnit Configuration** (`phpunit.xml`):
```xml
<logging>
    <junit outputFile="tests/reports/junit.xml"/>
</logging>
```

---

#### Troubleshooting

**Error: JUnit XML file not found**
```
❌ JUnit XML file not found at: tests/reports/junit.xml
   Please run tests first or check your phpunit.xml configuration.
```
**Solution:** Ensure your `phpunit.xml` has JUnit logging configured:
```xml
<logging>
    <junit outputFile="tests/reports/junit.xml"/>
</logging>
```

---

**Error: Test execution timed out**
```
❌ Test execution timed out or failed
⚠️  Attempting to generate report from partial results...
```
**Solution:** Increase the timeout value:
```bash
php artisan test:report --timeout=3600
```

---

**Error: View not found**
```
❌ View [test-report] not found
```
**Solution:** Ensure the Blade view exists at:
```
resources/views/test-report.blade.php
```

---

#### Benefits

**🚀 Development Benefits:**
- Visual test results for better understanding
- Historical test data tracking
- Easy sharing with team members
- Beautiful presentation for stakeholders

**⏱️ Time Savings:**
- No need to parse console output
- Quick identification of failing tests
- Instant access to error messages
- Fast navigation between test suites

**📈 Quality Assurance:**
- Track test coverage trends
- Monitor test execution time
- Identify slow tests easily
- Maintain test health visibility

---

#### Integration with CI/CD

Add to your CI/CD pipeline:

**GitHub Actions:**
```yaml
- name: Run Tests and Generate Report
  run: php artisan test:report --skip-tests
  
- name: Upload Test Report
  uses: actions/upload-artifact@v3
  with:
    name: test-report
    path: tests/reports/test-report.html
```

**GitLab CI:**
```yaml
test:
  script:
    - php artisan test:report
  artifacts:
    paths:
      - tests/reports/test-report.html
    expire_in: 1 week
```

---

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
