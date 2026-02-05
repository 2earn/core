# Service Tests Runner - Command Line Guide

## Overview

The service tests runner has been transformed into a **command-line tool** with support for generating HTML reports.

## Installation

No installation required! Just use the PowerShell script directly.

## Usage

### Basic Syntax

```powershell
.\test-runner.ps1 [action] [options]
```

## Available Actions

### 1. Run All Unit Tests
```powershell
.\test-runner.ps1 all
```
Runs all unit tests in the application.

### 2. Run Service Tests Only
```powershell
.\test-runner.ps1 services
```
Runs only the service tests from `tests/Unit/Services/`.

### 3. Run Complete Tests (Exclude Incomplete)
```powershell
.\test-runner.ps1 complete
```
Runs only fully implemented tests, excluding stub tests marked as incomplete.

### 4. Run Specific Service Test
```powershell
.\test-runner.ps1 specific -Service AmountServiceTest
.\test-runner.ps1 specific -Service Items/ItemServiceTest
.\test-runner.ps1 specific -Service UserGuide/UserGuideServiceTest
```
Runs a specific service test file.

### 5. Run Tests with Coverage
```powershell
.\test-runner.ps1 coverage
```
Runs tests with code coverage analysis (requires Xdebug).

### 6. Run Tests in Parallel
```powershell
.\test-runner.ps1 parallel
```
Runs tests in parallel for faster execution.

### 7. List All Test Files
```powershell
.\test-runner.ps1 list
```
Lists all service test files in the project.

### 8. Show Implementation Status
```powershell
.\test-runner.ps1 status
```
Shows which tests are fully implemented and which are stubs.

### 9. Generate HTML Report
```powershell
# Generate HTML report only
.\test-runner.ps1 html

# Generate and open HTML report in browser
.\test-runner.ps1 html -Open
```
Generates a beautiful HTML test report with statistics, charts, and detailed results.

### 10. Show Help
```powershell
.\test-runner.ps1 help
.\test-runner.ps1
```
Shows the help message with all available commands.

## HTML Report Features

The HTML report includes:

- **📊 Visual Statistics**
  - Total tests count
  - Passed/Failed/Skipped counts
  - Execution time
  - Pass rate percentage

- **📈 Progress Bar**
  - Visual representation of test success rate

- **📋 Detailed Test Results**
  - Test name and class
  - Status (Passed/Failed/Skipped)
  - Execution time for each test
  - Error messages for failed tests

- **💅 Beautiful Design**
  - Responsive layout
  - Color-coded status indicators
  - Modern gradient headers
  - Professional styling

### Report Location

Reports are saved in: `tests/reports/`

- HTML Report: `test-report-[timestamp].html`
- JUnit XML: `junit-[timestamp].xml`

## Examples

### Example 1: Quick Test Run
```powershell
.\test-runner.ps1 services
```

### Example 2: Test Specific Service
```powershell
.\test-runner.ps1 specific -Service AmountServiceTest
```

### Example 3: Generate Report and View
```powershell
.\test-runner.ps1 html -Open
```

### Example 4: Run Complete Tests Only
```powershell
.\test-runner.ps1 complete
```

### Example 5: Check Status
```powershell
.\test-runner.ps1 status
```

## Quick Reference

| Command | Action |
|---------|--------|
| `all` | Run all unit tests |
| `services` | Run service tests |
| `complete` | Run complete tests only |
| `specific -Service <name>` | Run specific test |
| `coverage` | Run with coverage |
| `parallel` | Run in parallel |
| `list` | List all tests |
| `status` | Show status |
| `html` | Generate HTML report |
| `html -Open` | Generate & open report |
| `help` | Show help |

## Output Examples

### Success Output
```
Running Service Tests...
========================================

   PASS  Tests\Unit\Services\AmountServiceTest
  ✓ get by id returns amount when exists
  ✓ get by id returns null when not exists
  ✓ get paginated returns paginated results
  ...

  Tests:  8 passed (76 assertions)
  Duration: 1.23s
```

### HTML Report Output
```
========================================
Report Generated Successfully!
========================================

JUnit XML Report: tests/reports/junit-2026-01-26_143052.xml
HTML Report: tests/reports/test-report-2026-01-26_143052.html

To open in browser, use: .\test-runner.ps1 html -Open
```

## HTML Report Screenshot

The HTML report includes:

```
┌─────────────────────────────────────────────┐
│  📊 Service Tests Report                    │
│  Generated on January 26, 2026 14:30:52    │
└─────────────────────────────────────────────┘

┌──────────────┬──────────────┬──────────────┐
│ Total Tests  │   Passed     │   Failed     │
│     83       │     76       │      7       │
└──────────────┴──────────────┴──────────────┘

[████████████████████░░░░] 91.6%

Test Results Table:
┌────────────────────────────┬─────────┬────────┐
│ Test Name                  │ Status  │  Time  │
├────────────────────────────┼─────────┼────────┤
│ test_get_by_id...         │ PASSED  │ 0.01s  │
│ test_create_success...    │ PASSED  │ 0.02s  │
│ test_update_fails...      │ FAILED  │ 0.03s  │
└────────────────────────────┴─────────┴────────┘
```

## Tips & Tricks

### Tip 1: Quick Status Check
Before running tests, check what's implemented:
```powershell
.\test-runner.ps1 status
```

### Tip 2: Fast Development Cycle
Use specific tests during development:
```powershell
.\test-runner.ps1 specific -Service YourServiceTest
```

### Tip 3: Weekly Reports
Generate HTML reports for team meetings:
```powershell
.\test-runner.ps1 html -Open
```

### Tip 4: CI/CD Integration
For continuous integration, use:
```powershell
.\test-runner.ps1 services
# Exit code indicates success/failure
```

### Tip 5: Coverage Analysis
Check test coverage periodically:
```powershell
.\test-runner.ps1 coverage
```

## Troubleshooting

### Issue: "Cannot be loaded because running scripts is disabled"

**Solution:**
```powershell
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
```

### Issue: "Test file not found"

**Solution:**
```powershell
# Check available tests first
.\test-runner.ps1 list

# Use correct path
.\test-runner.ps1 specific -Service Items/ItemServiceTest
```

### Issue: "Xdebug not found" (for coverage)

**Solution:**
Install Xdebug or use regular test run:
```powershell
.\test-runner.ps1 services
```

## Advanced Usage

### Chaining Commands
```powershell
# Run tests and generate report
.\test-runner.ps1 services; .\test-runner.ps1 html -Open
```

### Automated Reporting
Create a scheduled task to generate reports:
```powershell
# Save this as run-daily-tests.ps1
.\test-runner.ps1 services
.\test-runner.ps1 html -Open
```

### Custom Workflows
```powershell
# Development workflow
.\test-runner.ps1 specific -Service YourServiceTest
# If pass, run all
.\test-runner.ps1 services
# Generate report
.\test-runner.ps1 html -Open
```

## Comparison: Old vs New

### Old Way (Interactive Menu)
```powershell
.\run-service-tests.ps1
# Wait for menu
# Press 2
# Wait for tests
# Press 9 for report
# ...
```

### New Way (Command Line)
```powershell
.\test-runner.ps1 services
.\test-runner.ps1 html -Open
# Done!
```

## Integration with CI/CD

### GitHub Actions Example
```yaml
- name: Run Service Tests
  run: pwsh test-runner.ps1 services

- name: Generate Report
  run: pwsh test-runner.ps1 html
  
- name: Upload Report
  uses: actions/upload-artifact@v2
  with:
    name: test-report
    path: tests/reports/*.html
```

### GitLab CI Example
```yaml
test:
  script:
    - pwsh test-runner.ps1 services
    - pwsh test-runner.ps1 html
  artifacts:
    paths:
      - tests/reports/
```

## Summary

✅ **Command-line interface** for all test operations  
✅ **HTML report generation** with beautiful design  
✅ **One-line commands** for quick testing  
✅ **Auto-open in browser** option for reports  
✅ **CI/CD ready** for automated testing  
✅ **Professional reports** for stakeholders  

The new command-line interface makes testing faster, easier, and more professional!

## Related Files

- **Script**: `test-runner.ps1` - Main command-line tool
- **Documentation**: `SERVICE_TESTS_STATUS.md` - Implementation status
- **Guide**: `QUICK_START_TESTING.md` - Setup guide
- **Tests**: `tests/Unit/Services/` - Test files directory

---

**Need help?** Run `.\test-runner.ps1 help`
