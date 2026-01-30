# PowerShell script to run BalanceOperationServiceTest
Write-Host "Running BalanceOperationServiceTest..." -ForegroundColor Green
Write-Host ""

# Navigate to project directory
Set-Location "C:\laragon\www\2earn"

# Run the test
php artisan test tests/Unit/Services/Balances/BalanceOperationServiceTest.php --testdox

Write-Host ""
Write-Host "Test execution completed." -ForegroundColor Green
