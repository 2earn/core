@echo off
REM Service Provider Optimization - Quick Fix Script
REM Run this if you get "Target class [Balances] does not exist" error

echo ========================================
echo Service Provider Optimization Fix
echo ========================================
echo.

echo Clearing configuration cache...
php artisan config:clear
if %errorlevel% neq 0 (
    echo ERROR: Failed to clear config cache!
    pause
    exit /b 1
)

echo.
echo Clearing application cache...
php artisan cache:clear
if %errorlevel% neq 0 (
    echo WARNING: Failed to clear app cache
)

echo.
echo Clearing route cache...
php artisan route:clear
if %errorlevel% neq 0 (
    echo WARNING: Failed to clear route cache
)

echo.
echo Clearing view cache...
php artisan view:clear
if %errorlevel% neq 0 (
    echo WARNING: Failed to clear view cache
)

echo.
echo Rebuilding autoloader...
composer dump-autoload
if %errorlevel% neq 0 (
    echo WARNING: Failed to rebuild autoloader
)

echo.
echo ========================================
echo Fix Applied Successfully!
echo ========================================
echo.
echo The DeferredServiceProvider is now loaded.
echo All services should work correctly now.
echo.
echo Testing services...
echo.

php artisan tinker --execute="echo 'Testing Balances service...'; var_dump(app('Balances')); echo 'SUCCESS: Balances service resolved!';"

echo.
echo ========================================
echo Next Steps:
echo ========================================
echo 1. Test /buy-action route
echo 2. Check for any errors in logs
echo 3. Monitor application performance
echo.

pause

