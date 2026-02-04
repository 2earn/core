# Find the specific test that's causing the hang
Write-Host "🔍 Testing 'get international image works' test..." -ForegroundColor Cyan

# Try to run the specific test with a timeout
$testName = "get international image works"

Write-Host "`n1️⃣ Searching for the test..." -ForegroundColor Yellow
php artisan test --list-tests | Select-String -Pattern "international" -Context 2

Write-Host "`n2️⃣ Trying to run with filter (30 second timeout)..." -ForegroundColor Yellow

# Create a job to run the test with timeout
$job = Start-Job -ScriptBlock {
    Set-Location "C:\laragon\www\2earn"
    php artisan test --filter="international"
}

# Wait for 30 seconds
$completed = Wait-Job $job -Timeout 30

if ($completed) {
    Write-Host "✅ Test completed within timeout!" -ForegroundColor Green
    Receive-Job $job
} else {
    Write-Host "⚠️ Test is hanging! Stopping after 30 seconds..." -ForegroundColor Red
    Stop-Job $job
    Remove-Job $job

    Write-Host "`n💡 Recommendation: Check the test for:" -ForegroundColor Yellow
    Write-Host "  - External API calls without timeout" -ForegroundColor White
    Write-Host "  - Missing HTTP client mocks" -ForegroundColor White
    Write-Host "  - Infinite loops or recursion" -ForegroundColor White
    Write-Host "  - Database transactions not rolling back" -ForegroundColor White
}

Write-Host "`n3️⃣ You can also try running specific test files:" -ForegroundColor Yellow
Write-Host "php artisan test tests/Feature/YourTestFile.php --filter='international'" -ForegroundColor Cyan
