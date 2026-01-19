# Partner API Test Runner
# Quick test execution script for PowerShell

Write-Host "==================================" -ForegroundColor Cyan
Write-Host "Partner API Test Suite Runner" -ForegroundColor Cyan
Write-Host "==================================" -ForegroundColor Cyan
Write-Host ""

# Function to run tests
function Run-Tests {
    param (
        [string]$TestFile = "",
        [string]$Filter = "",
        [switch]$Coverage = $false,
        [switch]$Testdox = $false
    )

    $command = "php artisan test"

    if ($TestFile) {
        $command += " $TestFile"
    } else {
        $command += " tests/Feature/Api/Partner"
    }

    if ($Filter) {
        $command += " --filter=$Filter"
    }

    if ($Coverage) {
        $command += " --coverage"
    }

    if ($Testdox) {
        $command += " --testdox"
    }

    Write-Host "Running: $command" -ForegroundColor Yellow
    Write-Host ""
    Invoke-Expression $command
}

# Main menu
Write-Host "Select an option:" -ForegroundColor Green
Write-Host "1. Run all Partner API tests" -ForegroundColor White
Write-Host "2. Run tests with detailed output (testdox)" -ForegroundColor White
Write-Host "3. Run tests with coverage" -ForegroundColor White
Write-Host "4. Run specific test file" -ForegroundColor White
Write-Host "5. Run Platform tests" -ForegroundColor White
Write-Host "6. Run Deal tests" -ForegroundColor White
Write-Host "7. Run Order tests" -ForegroundColor White
Write-Host "8. Run Payment tests" -ForegroundColor White
Write-Host "9. Quick check (one test file)" -ForegroundColor White
Write-Host "0. Exit" -ForegroundColor White
Write-Host ""

$choice = Read-Host "Enter your choice (0-9)"

switch ($choice) {
    "1" {
        Write-Host "Running all Partner API tests..." -ForegroundColor Green
        Run-Tests
    }
    "2" {
        Write-Host "Running tests with detailed output..." -ForegroundColor Green
        Run-Tests -Testdox
    }
    "3" {
        Write-Host "Running tests with coverage report..." -ForegroundColor Green
        Run-Tests -Coverage
    }
    "4" {
        Write-Host ""
        Write-Host "Available test files:" -ForegroundColor Yellow
        Write-Host "  - DealPartnerControllerTest.php" -ForegroundColor Gray
        Write-Host "  - OrderPartnerControllerTest.php" -ForegroundColor Gray
        Write-Host "  - PlatformPartnerControllerTest.php" -ForegroundColor Gray
        Write-Host "  - ItemsPartnerControllerTest.php" -ForegroundColor Gray
        Write-Host "  - SalesDashboardControllerTest.php" -ForegroundColor Gray
        Write-Host "  - PartnerPaymentControllerTest.php" -ForegroundColor Gray
        Write-Host ""
        $filename = Read-Host "Enter test filename"
        Run-Tests -TestFile "tests/Feature/Api/Partner/$filename"
    }
    "5" {
        Write-Host "Running Platform tests..." -ForegroundColor Green
        Run-Tests -TestFile "tests/Feature/Api/Partner/PlatformPartnerControllerTest.php" -Testdox
    }
    "6" {
        Write-Host "Running Deal tests..." -ForegroundColor Green
        Run-Tests -TestFile "tests/Feature/Api/Partner/DealPartnerControllerTest.php" -Testdox
    }
    "7" {
        Write-Host "Running Order tests..." -ForegroundColor Green
        Run-Tests -TestFile "tests/Feature/Api/Partner/OrderPartnerControllerTest.php" -Testdox
    }
    "8" {
        Write-Host "Running Payment tests..." -ForegroundColor Green
        Run-Tests -TestFile "tests/Feature/Api/Partner/PartnerPaymentControllerTest.php" -Testdox
    }
    "9" {
        Write-Host "Quick check - Running PlanLabelPartnerControllerTest..." -ForegroundColor Green
        Run-Tests -TestFile "tests/Feature/Api/Partner/PlanLabelPartnerControllerTest.php"
    }
    "0" {
        Write-Host "Goodbye!" -ForegroundColor Cyan
        exit
    }
    default {
        Write-Host "Invalid choice. Please run the script again." -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "==================================" -ForegroundColor Cyan
Write-Host "Test execution completed!" -ForegroundColor Cyan
Write-Host "==================================" -ForegroundColor Cyan
