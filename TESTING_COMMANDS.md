# Service Tests - Command Line Reference

## Quick Start

The service tests can be run in two modes:
1. **Interactive Menu** - Double-click the script or run without parameters
2. **Command Line** - Use parameters for automation and CI/CD

## Command Line Usage

### PowerShell Script

```powershell
# Basic syntax
.\run-service-tests.ps1 -Action <action> [options]
```

### Available Actions

#### 1. Run All Unit Tests
```powershell
.\run-service-tests.ps1 -Action all
```

#### 2. Run Service Tests Only
```powershell
.\run-service-tests.ps1 -Action services
```

#### 3. Run Complete Tests (Exclude Incomplete)
```powershell
.\run-service-tests.ps1 -Action complete
```

#### 4. Run Specific Service Test
```powershell
# Simple service test
.\run-service-tests.ps1 -Action specific -Service AmountServiceTest

# Service test in subdirectory
.\run-service-tests.ps1 -Action specific -Service Items/ItemServiceTest

# With .php extension (optional)
.\run-service-tests.ps1 -Action specific -Service CountryServiceTest.php
```

#### 5. Run Tests with Coverage
```powershell
.\run-service-tests.ps1 -Action coverage
```
*Note: Requires Xdebug to be installed*

#### 6. Run Tests in Parallel (Faster)
```powershell
.\run-service-tests.ps1 -Action parallel
```

#### 7. List All Service Tests
```powershell
.\run-service-tests.ps1 -Action list
```

#### 8. Check Implementation Status
```powershell
.\run-service-tests.ps1 -Action status
```

#### 9. Generate HTML Report (Don't Open)
```powershell
.\run-service-tests.ps1 -Action report
```

#### 10. Generate HTML Report and Open in Browser
```powershell
.\run-service-tests.ps1 -Action html
```

### Interactive Menu Mode

Simply run without parameters:
```powershell
.\run-service-tests.ps1
```

## HTML Report Features

The HTML report includes:
- 📊 **Statistics Dashboard** - Total, Passed, Failed, Incomplete tests
- ✅ **Implemented Tests List** - All completed test files with test counts
- 📈 **Progress Bar** - Visual representation of pass rate
- 📋 **Test Output** - Full console output from test run
- 🎨 **Modern UI** - Beautiful gradient design with responsive layout
- 📁 **File Locations** - Quick access to JUnit XML and HTML reports

### Report Location

Reports are saved in: `tests/reports/`

Format: `service-tests-YYYY-MM-DD_HH-mm-ss.html`

Example: `service-tests-2026-01-26_14-30-45.html`

## Direct PHP Commands

### Using Laravel Artisan

```bash
# Run all unit tests
php artisan test --testsuite=Unit

# Run service tests
php artisan test tests/Unit/Services/

# Run specific test
php artisan test tests/Unit/Services/AmountServiceTest.php

# Run with coverage
php artisan test tests/Unit/Services/ --coverage

# Run in parallel
php artisan test tests/Unit/Services/ --parallel

# Exclude incomplete tests
php artisan test tests/Unit/Services/ --exclude-group incomplete

# Generate JUnit XML report
php artisan test tests/Unit/Services/ --log-junit=tests/reports/junit.xml
```

### Using PHPUnit Directly

```bash
# Run all tests
vendor/bin/phpunit

# Run specific test suite
vendor/bin/phpunit --testsuite Unit

# Run specific file
vendor/bin/phpunit tests/Unit/Services/AmountServiceTest.php

# With coverage
vendor/bin/phpunit --coverage-html tests/reports/coverage
```

## CI/CD Integration

### Example GitHub Actions

```yaml
name: Service Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: windows-latest
    
    steps:
      - uses: actions/checkout@v2
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.1'
          extensions: mbstring, pdo_mysql
          
      - name: Install Dependencies
        run: composer install
        
      - name: Run Service Tests
        run: php artisan test tests/Unit/Services/
        
      - name: Generate HTML Report
        run: |
          powershell -File run-service-tests.ps1 -Action report
          
      - name: Upload Report
        uses: actions/upload-artifact@v2
        with:
          name: test-reports
          path: tests/reports/
```

### Example GitLab CI

```yaml
test:
  script:
    - composer install
    - php artisan test tests/Unit/Services/
    - powershell -File run-service-tests.ps1 -Action report
  artifacts:
    paths:
      - tests/reports/
```

## Automation Examples

### Batch Script to Run Tests Before Commit

Create `pre-commit-tests.bat`:
```batch
@echo off
echo Running service tests...
powershell -File run-service-tests.ps1 -Action services
if %ERRORLEVEL% NEQ 0 (
    echo Tests failed! Commit aborted.
    exit /b 1
)
echo All tests passed!
```

### Daily Report Generation

Create scheduled task:
```powershell
# Create daily test report at 8 AM
$action = New-ScheduledTaskAction -Execute "PowerShell.exe" `
    -Argument "-File C:\path\to\run-service-tests.ps1 -Action html"
    
$trigger = New-ScheduledTaskTrigger -Daily -At 8am

Register-ScheduledTask -Action $action -Trigger $trigger `
    -TaskName "Daily Service Test Report"
```

## Tips & Tricks

### 1. Quick Test of Specific Service

```powershell
# Short form
.\run-service-tests.ps1 -Action specific -Service AmountServiceTest
```

### 2. Generate Report Without Running Tests

Use the `-Action report` if you already ran tests recently:
```powershell
.\run-service-tests.ps1 -Action report
```

### 3. Run Multiple Commands

```powershell
# Run services tests then generate report
.\run-service-tests.ps1 -Action services
.\run-service-tests.ps1 -Action html
```

### 4. Filter Test Output

```bash
# Run tests and filter output
php artisan test tests/Unit/Services/ | Select-String "PASS"
```

### 5. Watch Mode (Auto-rerun)

```bash
# Install nodemon globally
npm install -g nodemon

# Watch for changes and rerun tests
nodemon --exec "php artisan test tests/Unit/Services/" --ext php
```

## Keyboard Shortcuts (Interactive Mode)

When using interactive menu:
- `1` - All tests
- `2` - Service tests  
- `3` - Complete tests
- `4` - Specific test
- `5` - Coverage
- `6` - Parallel
- `7` - List tests
- `8` - Status
- `9` - Generate report
- `0` - Exit

## Environment Variables

Set these in `.env.testing`:

```env
DB_CONNECTION=mysql
DB_DATABASE=2earn_testing
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=array
SESSION_DRIVER=array
QUEUE_CONNECTION=sync
```

## Troubleshooting

### Script Execution Policy Error

```powershell
# Fix PowerShell execution policy
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
```

### Tests Not Found

```powershell
# Verify test files exist
.\run-service-tests.ps1 -Action list
```

### Database Errors

```bash
# Run migrations for test database
php artisan migrate --env=testing
```

### Report Not Opening

```powershell
# Manually open the latest report
$latest = Get-ChildItem tests/reports/*.html | Sort-Object LastWriteTime -Descending | Select-Object -First 1
Start-Process $latest.FullName
```

## Examples by Use Case

### For Developers

```powershell
# Before committing code
.\run-service-tests.ps1 -Action services

# Test specific service you modified
.\run-service-tests.ps1 -Action specific -Service YourServiceTest

# Check what's implemented
.\run-service-tests.ps1 -Action status
```

### For QA Team

```powershell
# Run full test suite
.\run-service-tests.ps1 -Action all

# Generate comprehensive report
.\run-service-tests.ps1 -Action html

# Check coverage
.\run-service-tests.ps1 -Action coverage
```

### For CI/CD

```powershell
# Quick validation (exclude incomplete)
.\run-service-tests.ps1 -Action complete

# Parallel execution for speed
.\run-service-tests.ps1 -Action parallel

# Generate report for artifacts
.\run-service-tests.ps1 -Action report
```

## Additional Resources

- **Full Documentation**: `tests/Unit/Services/README.md`
- **Status Report**: `SERVICE_TESTS_STATUS.md`
- **Quick Start**: `QUICK_START_TESTING.md`
- **Test Generator**: `generate-service-tests.php`

---

**Need help?** Run `.\run-service-tests.ps1` for interactive menu!
