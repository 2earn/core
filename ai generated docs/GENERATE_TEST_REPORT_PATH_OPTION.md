# GenerateTestReport Command - Path Option Enhancement

## Overview
Enhanced the `GenerateTestReport` command to support the `--path` option, allowing you to run and generate reports for specific test directories.

## New Feature: --path Option

### Usage

#### Run tests for a specific directory:
```bash
php artisan test:report --path=tests/Feature/Api/v2
```

#### Run only API v2 tests and open report:
```bash
php artisan test:report --path=tests/Feature/Api/v2 --open
```

#### Run specific tests with exclusions:
```bash
php artisan test:report --path=tests/Feature/Api/v2 --exclude-group=slow
```

#### Skip test execution, generate report from existing results:
```bash
php artisan test:report --path=tests/Feature/Api/v2 --skip-tests
```

## Command Options

| Option | Description | Example |
|--------|-------------|---------|
| `--path=` | Specify test path to run | `--path=tests/Feature/Api/v2` |
| `--skip-tests` | Skip running tests, use existing results | `--skip-tests` |
| `--open` | Open report in browser after generation | `--open` |
| `--timeout=` | Maximum execution time in seconds (default: 1800) | `--timeout=3600` |
| `--exclude-group=` | Exclude test groups (can be used multiple times) | `--exclude-group=slow` |

## Examples

### 1. Test Only API v2 Controllers
```bash
php artisan test:report --path=tests/Feature/Api/v2
```

**Output:**
```
🧪 Test Report Generator

📝 Running tests...
   Test path: tests/Feature/Api/v2

📊 34 tests will be executed

[Test execution output...]

✅ Test report generated successfully!

┌────────────────┬──────┐
│ Metric         │ Value│
├────────────────┼──────┤
│ Total Tests    │ 34   │
│ Passed         │ 32   │
│ Failed         │ 2    │
│ Errors         │ 0    │
│ Skipped        │ 0    │
│ Success Rate   │ 94.12%│
│ Total Time     │ 45.3s│
└────────────────┴──────┘

📁 Report location: /path/to/tests/reports/test-report.html
```

### 2. Test Specific Feature with Auto-open
```bash
php artisan test:report --path=tests/Feature/Api/v2 --open
```
This will run the tests and automatically open the HTML report in your default browser.

### 3. Test Unit Tests Only
```bash
php artisan test:report --path=tests/Unit
```

### 4. Test Multiple Specific Files
```bash
php artisan test:report --path=tests/Feature/Api/v2/DealControllerTest.php
```

### 5. Combine with Other Options
```bash
# Test API v2, exclude slow tests, open report, timeout after 30 minutes
php artisan test:report \
    --path=tests/Feature/Api/v2 \
    --exclude-group=slow \
    --exclude-group=integration \
    --timeout=1800 \
    --open
```

## How It Works

1. **Command Parsing**: The command reads the `--path` option value
2. **Test Count**: Counts tests in the specified path using `php artisan test --list-tests`
3. **Test Execution**: Runs tests only from the specified path
4. **Report Generation**: Generates HTML report based on JUnit XML output
5. **Display Results**: Shows statistics and opens browser if requested

## Implementation Details

### Changes Made

#### 1. Command Signature Update
Added `--path` option to the command signature:
```php
protected $signature = 'test:report
                        {--skip-tests : Skip running tests and use existing results}
                        {--open : Open the report in browser after generation}
                        {--timeout=1800 : Maximum execution time in seconds}
                        {--exclude-group=* : Exclude test groups}
                        {--path= : Specify test path to run (e.g., tests/Feature/Api/v2)}';
```

#### 2. Test Path Handling
The command now:
- Retrieves the path option: `$testPath = $this->option('path');`
- Displays the path being tested
- Adds the path to both count and execution commands
- Properly handles path in conjunction with other options

### Code Flow

```php
// Retrieve path option
$testPath = $this->option('path');

// Display path info
if ($testPath) {
    $this->comment('   Test path: ' . $testPath);
}

// Count tests in path
$countCommand = ['php', 'artisan', 'test', '--list-tests'];
if ($testPath) {
    $countCommand[] = $testPath;
}

// Run tests from path
$testCommand = ['php', 'artisan', 'test'];
// ... add other options ...
if ($testPath) {
    $testCommand[] = $testPath;
}
```

## Benefits

### 1. **Focused Testing**
Run tests only for the code you're working on:
- API v2 controllers only
- Specific feature areas
- Individual test files

### 2. **Faster Feedback**
- Reduced test execution time
- Quicker iteration during development
- Faster CI/CD pipelines for specific modules

### 3. **Better Reports**
- Reports focus on relevant test suites
- Easier to identify issues in specific areas
- Cleaner, more targeted reports

### 4. **Flexible Workflows**
Combine with other options:
- `--exclude-group` to skip slow tests
- `--timeout` for longer-running test suites
- `--open` for immediate feedback

## Use Cases

### Development Workflow
```bash
# While developing API v2 features
php artisan test:report --path=tests/Feature/Api/v2 --open
```

### Code Review
```bash
# Test only changed feature area
php artisan test:report --path=tests/Feature/SpecificFeature
```

### CI/CD Pipeline
```bash
# Run different test suites in parallel
php artisan test:report --path=tests/Unit
php artisan test:report --path=tests/Feature/Api/v2
php artisan test:report --path=tests/Feature/Livewire
```

### Debugging
```bash
# Focus on failing test suite
php artisan test:report --path=tests/Feature/Api/v2/DealControllerTest.php --open
```

## Path Examples

Valid path formats:
```bash
--path=tests/Feature                          # All feature tests
--path=tests/Feature/Api                      # All API tests
--path=tests/Feature/Api/v2                   # API v2 tests only
--path=tests/Feature/Api/v2/DealControllerTest.php  # Single test file
--path=tests/Unit                             # All unit tests
--path=tests/Unit/Services                    # All service tests
```

## Report Output

The generated HTML report will include:
- Only tests from the specified path
- Statistics specific to that path
- Test suite breakdown for that area
- Groups from those tests
- Pass/fail details for each test

## Notes

1. **Path is Optional**: If no path is specified, all tests run (default behavior)
2. **Relative to Project Root**: Paths are relative to the Laravel project root
3. **Works with All Options**: The `--path` option works seamlessly with:
   - `--exclude-group`
   - `--timeout`
   - `--open`
   - `--skip-tests`
4. **JUnit XML**: The report still uses the same JUnit XML format
5. **Multiple Paths**: Currently supports one path per command execution

## Future Enhancements

Potential improvements:
- Support multiple paths: `--path=tests/Feature/Api/v2 --path=tests/Unit/Services`
- Path wildcards: `--path=tests/Feature/*/v2`
- Path validation with suggestions
- Path history and favorites

## Troubleshooting

### Path Not Found
```bash
⚠️  Could not determine total test count
```
**Solution**: Verify the path exists and contains test files

### No Tests Found
```bash
📊 0 tests will be executed
```
**Solution**: Check that the path contains PHP test files with test methods

### Report Empty
**Solution**: Ensure tests were actually run and JUnit XML was generated

## Date
February 10, 2026

