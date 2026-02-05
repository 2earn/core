# Test Runner Scripts - Summary

## Available Options

You now have **THREE ways** to run service tests:

### 1. **Laravel Artisan Command** - ⭐ RECOMMENDED
```bash
php artisan test:services
```
- Native Laravel integration
- Cross-platform (Windows, macOS, Linux)
- Interactive menu OR command-line
- Beautiful HTML reports

### 2. **run-service-tests.ps1** - PowerShell (Hybrid)
```powershell
.\run-service-tests.ps1 -Action services
```
- Interactive menu AND command-line arguments
- Windows PowerShell only
- More flexible for different use cases

### 3. **test-runner.ps1** - PowerShell (Command-Line)
```powershell
.\test-runner.ps1 services
```
- Pure command-line interface
- Windows PowerShell only
- Simpler syntax for automation

## Feature Comparison

| Feature | Laravel Command | run-service-tests.ps1 | test-runner.ps1 |
|---------|----------------|----------------------|-----------------|
| **Interactive Menu** | ✅ Yes | ✅ Yes | ❌ No |
| **Command Line** | ✅ Yes | ✅ Yes | ✅ Yes |
| **HTML Reports** | ✅ Yes | ✅ Yes | ✅ Yes |
| **Open in Browser** | ✅ Yes | ✅ Yes | ✅ Yes |
| **Cross-Platform** | ✅ Yes | ❌ Windows Only | ❌ Windows Only |
| **Native Laravel** | ✅ Yes | ❌ No | ❌ No |
| **Best For** | All use cases | Windows development | Windows automation |

## Usage Examples

### Laravel Artisan Command (RECOMMENDED)

**Interactive Mode:**
```bash
php artisan test:services
# Shows menu, select options
```

**Command-Line Mode:**
```bash
php artisan test:services services
php artisan test:services specific --service=AmountServiceTest
php artisan test:services html --open
php artisan test:services status
```

### run-service-tests.ps1 (Hybrid)

**Interactive Mode:**
```powershell
.\run-service-tests.ps1
# Shows menu, select options
```

**Command-Line Mode:**
```powershell
.\run-service-tests.ps1 -Action all
.\run-service-tests.ps1 -Action specific -Service AmountServiceTest
.\run-service-tests.ps1 -Action html -OpenReport
```

### test-runner.ps1 (Command-Line Only)

```powershell
.\test-runner.ps1 all
.\test-runner.ps1 specific -Service AmountServiceTest
.\test-runner.ps1 html -Open
```

## Which One to Use?

### Use **Laravel Artisan Command** (php artisan test:services) when:
- ✅ You want cross-platform support
- ✅ You prefer native Laravel integration
- ✅ You're working in a team with different OSes
- ✅ You want the most maintainable solution
- ✅ You're using CI/CD pipelines
- ✅ **RECOMMENDED for all use cases**

### Use **run-service-tests.ps1** when:
- ✅ You're on Windows and prefer PowerShell
- ✅ You want an interactive menu
- ✅ You prefer traditional menu-based interface
- ✅ You're doing exploratory testing on Windows

### Use **test-runner.ps1** when:
- ✅ You're on Windows
- ✅ You want pure command-line PowerShell
- ✅ You prefer shorter PowerShell commands
- ✅ You're automating tests on Windows only

## Command Syntax Comparison

### Running All Tests

**Laravel Artisan:**
```bash
php artisan test:services all
```
*(Works on all platforms)*

**run-service-tests.ps1:**
```powershell
.\run-service-tests.ps1 -Action all
```

**test-runner.ps1:**
```powershell
.\test-runner.ps1 all
```

### Running Service Tests

**Laravel Artisan:**
```bash
php artisan test:services services
```

**run-service-tests.ps1:**
```powershell
.\run-service-tests.ps1 -Action services
```

**test-runner.ps1:**
```powershell
.\test-runner.ps1 services
```

### Running Specific Test

**Laravel Artisan:**
```bash
php artisan test:services specific --service=AmountServiceTest
```
*(Clean Laravel syntax)*

**run-service-tests.ps1:**
```powershell
.\run-service-tests.ps1 -Action specific -Service "AmountServiceTest"
```

**test-runner.ps1:**
```powershell
.\test-runner.ps1 specific -Service AmountServiceTest
```

### Generating HTML Report

**Laravel Artisan:**
```bash
php artisan test:services html --open
```
*(Shortest and cleanest!)*

**run-service-tests.ps1:**
```powershell
.\run-service-tests.ps1 -Action html -OpenReport
```

**test-runner.ps1:**
```powershell
.\test-runner.ps1 html -Open
```

## All Available Actions

Both scripts support these actions:

| Action | Description |
|--------|-------------|
| `all` | Run all unit tests |
| `services` | Run service tests only |
| `complete` | Run complete tests (exclude stubs) |
| `specific` | Run a specific test file |
| `coverage` | Run with coverage report |
| `parallel` | Run tests in parallel |
| `list` | List all test files |
| `status` | Show implementation status |
| `html` | Generate HTML report |
| `help` | Show help message |

## HTML Report Feature

Both scripts generate identical HTML reports with:

### Features:
- 📊 **Visual Statistics**: Total, Passed, Failed, Skipped, Time, Pass Rate
- 📈 **Progress Bar**: Visual pass rate indicator
- 📋 **Detailed Results**: Test name, class, status, time
- ❌ **Error Messages**: Full stack traces for failures
- 🎨 **Beautiful Design**: Modern, professional styling
- 🔍 **Sortable Data**: Easy to navigate results

### Generate Report:

**run-service-tests.ps1:**
```powershell
.\run-service-tests.ps1 -Action html -OpenReport
```

**test-runner.ps1:**
```powershell
.\test-runner.ps1 html -Open
```

### Report Output:
```
========================================
Report Generated Successfully!
========================================

JUnit XML Report: tests/reports/junit-2026-01-26_143052.xml
HTML Report: tests/reports/test-report-2026-01-26_143052.html

Opening report in default browser...
```

## Quick Start

### For Interactive Use:
```powershell
.\run-service-tests.ps1
```
*(Shows menu, easy to use)*

### For Command Line:
```powershell
.\test-runner.ps1 services
```
*(Fast and direct)*

### For Automation/CI/CD:
```powershell
.\test-runner.ps1 services
if ($LASTEXITCODE -eq 0) {
    .\test-runner.ps1 html -Open
}
```

## Documentation

- **TEST_RUNNER_GUIDE.md** - Comprehensive guide for command-line interface
- **SERVICE_TESTS_STATUS.md** - Current implementation status
- **QUICK_START_TESTING.md** - Setup guide for running tests
- **tests/Unit/Services/README.md** - Testing best practices

## Recommendation

### For Development:
Use **either script** - whatever you prefer!
- Interactive? → `run-service-tests.ps1`
- Command-line? → `test-runner.ps1`

### For CI/CD:
Use **test-runner.ps1** for cleaner syntax:
```yaml
# GitHub Actions
- name: Run Tests
  run: pwsh test-runner.ps1 services
  
- name: Generate Report  
  run: pwsh test-runner.ps1 html
```

## Summary

✅ **Three powerful options** for running tests  
✅ **Laravel Artisan Command** - RECOMMENDED (cross-platform, native)  
✅ **PowerShell Scripts** - Available for Windows users  
✅ **Identical functionality** - Choose your preferred interface  
✅ **Beautiful HTML reports** with all options  
✅ **Well documented** - Guides for everything  
✅ **CI/CD ready** - Works in automated pipelines  

## Recommendation

### 🏆 For Most Users: Laravel Artisan Command
```bash
php artisan test:services
```
**Why?**
- ✅ Works on Windows, macOS, Linux
- ✅ Native Laravel integration
- ✅ No external scripts needed
- ✅ Easier to maintain
- ✅ Familiar Laravel syntax
- ✅ Better for teams with mixed OSes

### 🪟 For Windows-Only Teams: PowerShell Scripts
```powershell
# Interactive menu
.\run-service-tests.ps1

# Command line
.\test-runner.ps1 services
```
**Why?**
- ✅ Native PowerShell integration
- ✅ Windows-optimized
- ✅ Familiar for PowerShell users

## Quick Access

**Laravel Artisan (Recommended):**
```bash
# Interactive menu
php artisan test:services

# Command line
php artisan test:services services
php artisan test:services html --open

# Help
php artisan test:services --help
```

**PowerShell Interactive:**
```powershell
.\run-service-tests.ps1
```

**PowerShell Command-Line:**
```powershell
.\test-runner.ps1 services
```

## Documentation

- **Laravel Command Guide**: `LARAVEL_COMMAND_GUIDE.md` ⭐
- **PowerShell Commands**: `TESTING_COMMANDS.md`
- **Test Status**: `SERVICE_TESTS_STATUS.md`
- **Quick Start**: `QUICK_START_TESTING.md`

---

**🚀 Get Started:** `php artisan test:services`
