# Service Tests Runner Script for PowerShell
# Provides command-line options to run PHPUnit tests for services
#
# Usage:
#   .\run-service-tests.ps1 [action] [options]
#
# Actions:
#   all          - Run all unit tests
#   services     - Run only service tests
#   complete     - Run complete tests (exclude incomplete)
#   specific     - Run a specific service test
#   coverage     - Run tests with coverage report
#   parallel     - Run tests in parallel
#   list         - List all service test files
#   status       - Show test implementation status
#   html         - Generate HTML test report
#
# Options:
#   -Service     - Specify service name for 'specific' action
#   -Open        - Open HTML report in browser (for 'html' action)
#
# Examples:
#   .\run-service-tests.ps1 all
#   .\run-service-tests.ps1 specific -Service AmountServiceTest
#   .\run-service-tests.ps1 html -Open
#   .\run-service-tests.ps1 services

param(
    [Parameter(Position=0)]
    [ValidateSet('all', 'services', 'complete', 'specific', 'coverage', 'parallel', 'list', 'status', 'html', 'help')]
    [string]$Action = 'help',

    [Parameter()]
    [string]$Service = '',

    [Parameter()]
    [switch]$Open
)

function Show-Help {
    Write-Host ""
    Write-Host "========================================" -ForegroundColor Cyan
    Write-Host "    Service Tests Runner - Help" -ForegroundColor Cyan
    Write-Host "========================================" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "USAGE:" -ForegroundColor Yellow
    Write-Host "  .\run-service-tests.ps1 [action] [options]"
    Write-Host ""
    Write-Host "ACTIONS:" -ForegroundColor Yellow
    Write-Host "  all          Run all unit tests" -ForegroundColor Green
    Write-Host "  services     Run only service tests" -ForegroundColor Green
    Write-Host "  complete     Run complete tests (exclude incomplete)" -ForegroundColor Green
    Write-Host "  specific     Run a specific service test" -ForegroundColor Green
    Write-Host "  coverage     Run tests with coverage report" -ForegroundColor Green
    Write-Host "  parallel     Run tests in parallel (faster)" -ForegroundColor Green
    Write-Host "  list         List all service test files" -ForegroundColor Green
    Write-Host "  status       Show test implementation status" -ForegroundColor Green
    Write-Host "  html         Generate HTML test report" -ForegroundColor Green
    Write-Host "  help         Show this help message" -ForegroundColor Green
    Write-Host ""
    Write-Host "OPTIONS:" -ForegroundColor Yellow
    Write-Host "  -Service <name>    Specify service name for 'specific' action"
    Write-Host "  -Open              Open HTML report in browser (for 'html' action)"
    Write-Host ""
    Write-Host "EXAMPLES:" -ForegroundColor Yellow
    Write-Host "  .\run-service-tests.ps1 all" -ForegroundColor Cyan
    Write-Host "  .\run-service-tests.ps1 services" -ForegroundColor Cyan
    Write-Host "  .\run-service-tests.ps1 specific -Service AmountServiceTest" -ForegroundColor Cyan
    Write-Host "  .\run-service-tests.ps1 specific -Service Items/ItemServiceTest" -ForegroundColor Cyan
    Write-Host "  .\run-service-tests.ps1 html -Open" -ForegroundColor Cyan
    Write-Host "  .\run-service-tests.ps1 coverage" -ForegroundColor Cyan
    Write-Host ""
}

function Run-AllTests {
    Write-Host "`nRunning ALL Unit Tests..." -ForegroundColor Cyan
    Write-Host "========================================" -ForegroundColor Cyan
    php artisan test --testsuite=Unit
}

function Run-ServiceTests {
    Write-Host "`nRunning Service Tests..." -ForegroundColor Cyan
    Write-Host "========================================" -ForegroundColor Cyan
    php artisan test tests/Unit/Services/
}

function Run-CompleteTests {
    Write-Host "`nRunning Complete Service Tests (excluding incomplete)..." -ForegroundColor Cyan
    Write-Host "========================================" -ForegroundColor Cyan
    php artisan test tests/Unit/Services/ --exclude-group incomplete
}

function Run-SpecificTest {
    param([string]$ServiceName)

    if ([string]::IsNullOrWhiteSpace($ServiceName)) {
        Write-Host ""
        Write-Host "Available Services (examples):" -ForegroundColor Yellow
        Write-Host "  - AmountServiceTest"
        Write-Host "  - CountryServiceTest"
        Write-Host "  - UserGuide/UserGuideServiceTest"
        Write-Host "  - Items/ItemServiceTest"
        Write-Host "  - EventServiceTest"
        Write-Host "  - CashServiceTest"
        Write-Host "  - CommentServiceTest"
        Write-Host ""
        Write-Host "Usage: .\run-service-tests.ps1 specific -Service <ServiceName>" -ForegroundColor Cyan
        Write-Host "Example: .\run-service-tests.ps1 specific -Service AmountServiceTest" -ForegroundColor Cyan
        Write-Host ""
        return
    }

    $testPath = "tests/Unit/Services/$ServiceName"
    if (-not ($testPath -like "*.php")) {
        $testPath += ".php"
    }

    if (-not (Test-Path $testPath)) {
        Write-Host ""
        Write-Host "Error: Test file not found: $testPath" -ForegroundColor Red
        Write-Host ""
        return
    }

    Write-Host "`nRunning $testPath..." -ForegroundColor Cyan
    Write-Host "========================================" -ForegroundColor Cyan
    php artisan test $testPath
}

function Run-CoverageTests {
    Write-Host "`nRunning Tests with Coverage..." -ForegroundColor Cyan
    Write-Host "Note: Requires Xdebug to be installed" -ForegroundColor Yellow
    Write-Host "========================================" -ForegroundColor Cyan
    php artisan test tests/Unit/Services/ --coverage --min=70
}

function Run-ParallelTests {
    Write-Host "`nRunning Tests in Parallel (Faster)..." -ForegroundColor Cyan
    Write-Host "========================================" -ForegroundColor Cyan
    php artisan test tests/Unit/Services/ --parallel
}

function List-Tests {
    Write-Host "`nListing All Service Test Files..." -ForegroundColor Cyan
    Write-Host "========================================" -ForegroundColor Cyan
    Write-Host ""

    $testFiles = Get-ChildItem -Path "tests\Unit\Services" -Filter "*Test.php" -Recurse

    $testFiles | ForEach-Object {
        $relativePath = $_.FullName.Replace((Get-Location).Path + "\tests\Unit\Services\", "")
        Write-Host "  $relativePath" -ForegroundColor Green
    }

    Write-Host ""
    Write-Host "Total: $($testFiles.Count) test files" -ForegroundColor Yellow
    Write-Host ""
}

function Show-Status {
    Write-Host "`nTest Implementation Status" -ForegroundColor Cyan
    Write-Host "========================================" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "Fully Implemented Tests:" -ForegroundColor Green
    Write-Host "  ✓ AmountServiceTest.php (8 tests)" -ForegroundColor Green
    Write-Host "  ✓ CountryServiceTest.php (4 tests)" -ForegroundColor Green
    Write-Host "  ✓ UserGuide\UserGuideServiceTest.php (20 tests)" -ForegroundColor Green
    Write-Host "  ✓ Items\ItemServiceTest.php (17 tests)" -ForegroundColor Green
    Write-Host "  ✓ EventServiceTest.php (13 tests)" -ForegroundColor Green
    Write-Host "  ✓ CashServiceTest.php (5 tests)" -ForegroundColor Green
    Write-Host "  ✓ CommentServiceTest.php (9 tests)" -ForegroundColor Green
    Write-Host ""
    Write-Host "Statistics:" -ForegroundColor Yellow
    Write-Host "  Total Test Files: 83+" -ForegroundColor White
    Write-Host "  Implemented: 7 (76+ test methods)" -ForegroundColor Green
    Write-Host "  Remaining: 76+" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "For detailed status, see: SERVICE_TESTS_STATUS.md" -ForegroundColor Cyan
    Write-Host ""
}

function Generate-HtmlReport {
    param([bool]$OpenInBrowser = $false)

    Write-Host "`nGenerating HTML Test Report..." -ForegroundColor Cyan
    Write-Host "========================================" -ForegroundColor Cyan
    Write-Host ""

    $reportDir = "tests/reports"
    $timestamp = Get-Date -Format "yyyy-MM-dd_HHmmss"
    $htmlReport = "$reportDir/test-report-$timestamp.html"
    $junitReport = "$reportDir/junit-$timestamp.xml"

    # Create reports directory if it doesn't exist
    if (-not (Test-Path $reportDir)) {
        New-Item -ItemType Directory -Path $reportDir -Force | Out-Null
        Write-Host "Created reports directory: $reportDir" -ForegroundColor Green
    }

    Write-Host "Running tests and generating reports..." -ForegroundColor Yellow
    Write-Host ""

    # Run tests with JUnit XML output
    php artisan test tests/Unit/Services/ --log-junit="$junitReport"

    # Generate HTML report from JUnit XML
    Write-Host ""
    Write-Host "Generating HTML report from test results..." -ForegroundColor Yellow

    # Create HTML report
    $htmlContent = Generate-HtmlContent -JunitFile $junitReport
    Set-Content -Path $htmlReport -Value $htmlContent -Encoding UTF8

    Write-Host ""
    Write-Host "========================================" -ForegroundColor Green
    Write-Host "Report Generated Successfully!" -ForegroundColor Green
    Write-Host "========================================" -ForegroundColor Green
    Write-Host ""
    Write-Host "JUnit XML Report: $junitReport" -ForegroundColor Cyan
    Write-Host "HTML Report: $htmlReport" -ForegroundColor Cyan
    Write-Host ""

    # Open in browser if requested
    if ($OpenInBrowser) {
        Write-Host "Opening report in default browser..." -ForegroundColor Yellow
        Start-Process $htmlReport
    } else {
        Write-Host "To open in browser, use: .\run-service-tests.ps1 html -Open" -ForegroundColor Yellow
    }
    Write-Host ""
}

function Generate-HtmlContent {
    param([string]$JunitFile)

    $stats = @{
        Total = 0
        Passed = 0
        Failed = 0
        Skipped = 0
        Time = 0
    }

    $testResults = @()

    # Parse JUnit XML if it exists
    if (Test-Path $JunitFile) {
        [xml]$junit = Get-Content $JunitFile

        if ($junit.testsuites) {
            $stats.Total = [int]$junit.testsuites.tests
            $stats.Failed = [int]$junit.testsuites.failures + [int]$junit.testsuites.errors
            $stats.Skipped = [int]$junit.testsuites.skipped
            $stats.Passed = $stats.Total - $stats.Failed - $stats.Skipped
            $stats.Time = [decimal]$junit.testsuites.time

            foreach ($testsuite in $junit.testsuites.testsuite) {
                foreach ($testcase in $testsuite.testcase) {
                    $result = @{
                        Name = $testcase.name
                        Class = $testcase.classname
                        Time = $testcase.time
                        Status = if ($testcase.failure) { "Failed" } elseif ($testcase.skipped) { "Skipped" } else { "Passed" }
                        Message = if ($testcase.failure) { $testcase.failure.'#text' } else { "" }
                    }
                    $testResults += $result
                }
            }
        }
    }

    $timestamp = Get-Date -Format "MMMM dd, yyyy HH:mm:ss"
    $passRate = if ($stats.Total -gt 0) { [math]::Round(($stats.Passed / $stats.Total) * 100, 2) } else { 0 }

    $html = @"
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Tests Report - $timestamp</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 1400px; margin: 0 auto; background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 8px 8px 0 0; }
        .header h1 { font-size: 32px; margin-bottom: 10px; }
        .header .timestamp { opacity: 0.9; font-size: 14px; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; padding: 30px; }
        .stat-card { background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #667eea; }
        .stat-card.passed { border-left-color: #28a745; }
        .stat-card.failed { border-left-color: #dc3545; }
        .stat-card.skipped { border-left-color: #ffc107; }
        .stat-card h3 { color: #666; font-size: 14px; margin-bottom: 10px; text-transform: uppercase; }
        .stat-card .value { font-size: 36px; font-weight: bold; color: #333; }
        .content { padding: 30px; }
        .section-title { font-size: 24px; margin-bottom: 20px; color: #333; border-bottom: 2px solid #667eea; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        thead { background: #f8f9fa; }
        th { text-align: left; padding: 15px; font-weight: 600; color: #666; border-bottom: 2px solid #dee2e6; }
        td { padding: 12px 15px; border-bottom: 1px solid #dee2e6; }
        tr:hover { background: #f8f9fa; }
        .status { padding: 4px 12px; border-radius: 4px; font-size: 12px; font-weight: 600; display: inline-block; }
        .status.passed { background: #d4edda; color: #155724; }
        .status.failed { background: #f8d7da; color: #721c24; }
        .status.skipped { background: #fff3cd; color: #856404; }
        .progress-bar { height: 30px; background: #e9ecef; border-radius: 15px; overflow: hidden; margin: 20px 0; }
        .progress-fill { height: 100%; background: linear-gradient(90deg, #28a745 0%, #20c997 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; transition: width 0.3s ease; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 14px; border-top: 1px solid #dee2e6; }
        .error-message { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 10px; border-radius: 4px; margin-top: 10px; font-size: 12px; max-height: 200px; overflow-y: auto; white-space: pre-wrap; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 Service Tests Report</h1>
            <div class="timestamp">Generated on $timestamp</div>
        </div>

        <div class="stats">
            <div class="stat-card">
                <h3>Total Tests</h3>
                <div class="value">$($stats.Total)</div>
            </div>
            <div class="stat-card passed">
                <h3>Passed</h3>
                <div class="value">$($stats.Passed)</div>
            </div>
            <div class="stat-card failed">
                <h3>Failed</h3>
                <div class="value">$($stats.Failed)</div>
            </div>
            <div class="stat-card skipped">
                <h3>Skipped</h3>
                <div class="value">$($stats.Skipped)</div>
            </div>
            <div class="stat-card">
                <h3>Execution Time</h3>
                <div class="value">$([math]::Round($stats.Time, 2))s</div>
            </div>
            <div class="stat-card">
                <h3>Pass Rate</h3>
                <div class="value">$passRate%</div>
            </div>
        </div>

        <div class="content">
            <div class="progress-bar">
                <div class="progress-fill" style="width: $passRate%">$passRate%</div>
            </div>

            <h2 class="section-title">Test Results</h2>
            <table>
                <thead>
                    <tr>
                        <th>Test Name</th>
                        <th>Class</th>
                        <th>Status</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
"@

    foreach ($test in $testResults) {
        $statusClass = $test.Status.ToLower()
        $escapedName = [System.Web.HttpUtility]::HtmlEncode($test.Name)
        $escapedClass = [System.Web.HttpUtility]::HtmlEncode($test.Class)

        $html += @"
                    <tr>
                        <td>$escapedName</td>
                        <td>$escapedClass</td>
                        <td><span class="status $statusClass">$($test.Status)</span></td>
                        <td>$([math]::Round([decimal]$test.Time, 3))s</td>
                    </tr>
"@
        if ($test.Status -eq "Failed" -and $test.Message) {
            $escapedMessage = [System.Web.HttpUtility]::HtmlEncode($test.Message)
            $html += @"
                    <tr>
                        <td colspan="4">
                            <div class="error-message">$escapedMessage</div>
                        </td>
                    </tr>
"@
        }
    }

    $html += @"
                </tbody>
            </table>
        </div>

        <div class="footer">
            <p>Service Tests Report - 2earn Platform</p>
            <p>For more information, see SERVICE_TESTS_STATUS.md</p>
        </div>
    </div>
</body>
</html>
"@

    return $html
}

# Main execution
switch ($Action.ToLower()) {
    'all' { Run-AllTests }
    'services' { Run-ServiceTests }
    'complete' { Run-CompleteTests }
    'specific' { Run-SpecificTest -ServiceName $Service }
    'coverage' { Run-CoverageTests }
    'parallel' { Run-ParallelTests }
    'list' { List-Tests }
    'status' { Show-Status }
    'html' { Generate-HtmlReport -OpenInBrowser $Open }
    'help' { Show-Help }
    default { Show-Help }
}
