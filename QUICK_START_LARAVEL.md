# Quick Start - Laravel Service Tests Command

## Installation Complete! ✅

The Laravel Artisan command is ready to use. No installation needed!

## Quick Start in 3 Steps

### 1️⃣ Run the Command

```bash
php artisan test:services
```

### 2️⃣ Choose from Menu

```
╔════════════════════════════════════════╗
║     Service Tests Runner - Menu        ║
╚════════════════════════════════════════╝

Select an action:
  [0] Run ALL Unit Tests
  [1] Run ONLY Service Tests  ← Start here!
  [2] Run Complete Tests (exclude incomplete)
  [3] Run Specific Service Test
  [4] Run Tests with Coverage
  [5] Run Tests in Parallel
  [6] List All Service Tests
  [7] Show Implementation Status
  [8] Generate HTML Report  ← Generate reports here!
  [9] Exit
```

### 3️⃣ View Your Results

Tests run immediately with live output!

## Most Common Commands

### Run Service Tests
```bash
php artisan test:services services
```

### Generate HTML Report
```bash
php artisan test:services html --open
```

### Check Status
```bash
php artisan test:services status
```

### Run Specific Test
```bash
php artisan test:services specific --service=AmountServiceTest
```

## Command Cheat Sheet

| What You Want | Command |
|---------------|---------|
| Interactive menu | `php artisan test:services` |
| Run all service tests | `php artisan test:services services` |
| Run specific test | `php artisan test:services specific --service=NAME` |
| Generate HTML report | `php artisan test:services html --open` |
| Check implementation status | `php artisan test:services status` |
| List all test files | `php artisan test:services list` |
| Run with coverage | `php artisan test:services coverage` |
| Run in parallel (faster) | `php artisan test:services parallel` |

## HTML Report Preview

When you run `php artisan test:services html --open`, you get:

```
Generating HTML Test Report...

Running tests...
Creating HTML report...

✓ Reports generated successfully!

HTML Report: /path/to/tests/reports/service-tests-2026-01-26_14-30-45.html
JUnit XML:   /path/to/tests/reports/junit-2026-01-26_14-30-45.xml

Opening HTML report in browser...
```

Then your browser opens with a beautiful report showing:
- 📊 Statistics dashboard (total, passed, failed, skipped)
- 📈 Visual progress bar
- ✅ List of implemented tests
- 📋 Complete test output
- 💻 Usage examples
- 📁 File locations

## Examples for Different Scenarios

### Before Committing Code
```bash
php artisan test:services services
```

### Working on Specific Service
```bash
php artisan test:services specific --service=YourServiceTest
```

### Weekly Team Report
```bash
php artisan test:services html --open
```

### CI/CD Pipeline
```bash
php artisan test:services services
```

### Check What's Done
```bash
php artisan test:services status
```

## Tips

### 💡 Tip 1: Create an Alias
Add to `~/.bashrc` or `~/.zshrc`:
```bash
alias test='php artisan test:services services'
alias test-report='php artisan test:services html --open'
```

Then just type: `test` or `test-report`

### 💡 Tip 2: Use in Git Hooks
`.git/hooks/pre-commit`:
```bash
#!/bin/bash
php artisan test:services services || exit 1
```

### 💡 Tip 3: Quick Status Check
```bash
php artisan test:services status
```

Shows you immediately what's implemented vs what needs work.

### 💡 Tip 4: Watch Mode
Use with nodemon for auto-rerun on file changes:
```bash
nodemon --exec "php artisan test:services services" --ext php
```

### 💡 Tip 5: Filter Output
```bash
php artisan test:services services | grep "PASS"
```

## Troubleshooting

### Command Not Found?
```bash
php artisan config:clear
php artisan list | grep test
```

### Tests Not Running?
```bash
# Check database
php artisan migrate --env=testing

# Check status
php artisan test:services status
```

### Report Not Opening?
```bash
# Generate without opening
php artisan test:services html

# Then manually open the file shown
```

## Cross-Platform

Works everywhere:
- ✅ Windows (CMD, PowerShell, Git Bash)
- ✅ macOS (Terminal, iTerm2)
- ✅ Linux (Bash, Zsh, Fish)

## Comparison with PowerShell

### Old Way (Windows Only)
```powershell
.\run-service-tests.ps1 -Action services
.\run-service-tests.ps1 -Action html -OpenReport
```

### New Way (All Platforms)
```bash
php artisan test:services services
php artisan test:services html --open
```

Same functionality, works everywhere! 🎉

## What's Available

### Test Status
- ✅ 7 fully implemented test files
- ✅ 76+ test methods working
- ⏳ 76+ stub files awaiting implementation

### Implemented Tests
1. AmountServiceTest (8 tests)
2. CountryServiceTest (4 tests)
3. UserGuide/UserGuideServiceTest (20 tests)
4. Items/ItemServiceTest (17 tests)
5. EventServiceTest (13 tests)
6. CashServiceTest (5 tests)
7. CommentServiceTest (9 tests)

## Next Steps

1. ✅ **Try it now**: `php artisan test:services`
2. 📊 **Generate a report**: `php artisan test:services html --open`
3. 📖 **Read full guide**: `LARAVEL_COMMAND_GUIDE.md`
4. 💻 **Start testing**: Pick a service and implement tests!

## Help & Documentation

### Get Command Help
```bash
php artisan test:services --help
```

### Full Documentation
- **Complete Guide**: `LARAVEL_COMMAND_GUIDE.md`
- **Test Status**: `SERVICE_TESTS_STATUS.md`
- **Comparison**: `SCRIPTS_COMPARISON.md`
- **Testing Guide**: `tests/Unit/Services/README.md`

## Summary

🎯 **One Command to Rule Them All:**
```bash
php artisan test:services
```

✨ **Features:**
- Interactive menu
- Command-line interface
- Beautiful HTML reports
- Cross-platform support
- Native Laravel integration
- Auto-open in browser
- JUnit XML export

🚀 **Start Now:**
```bash
php artisan test:services
```

That's it! You're ready to go! 🎉
