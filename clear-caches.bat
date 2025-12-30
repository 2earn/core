@echo off
REM Service Provider Optimization - Cache Clear Script
REM Run this to fix "Target class [Balances] does not exist" error

echo ========================================
echo  Service Provider Optimization
echo  Cache Clear Script
echo ========================================
echo.

echo [1/5] Clearing configuration cache...
php artisan config:clear
if %errorlevel% neq 0 (
    echo ERROR: Failed to clear config cache!
    pause
    exit /b 1
)
echo ✓ Configuration cache cleared
echo.

echo [2/5] Clearing application cache...
php artisan cache:clear
if %errorlevel% neq 0 (
    echo ERROR: Failed to clear application cache!
    pause
    exit /b 1
)
echo ✓ Application cache cleared
echo.

echo [3/5] Clearing route cache...
php artisan route:clear
if %errorlevel% neq 0 (
    echo ERROR: Failed to clear route cache!
    pause
    exit /b 1
)
echo ✓ Route cache cleared
echo.

echo [4/5] Clearing compiled views...
php artisan view:clear
if %errorlevel% neq 0 (
    echo ERROR: Failed to clear view cache!
    pause
    exit /b 1
)
echo ✓ Compiled views cleared
echo.

echo [5/5] Regenerating autoloader...
composer dump-autoload
if %errorlevel% neq 0 (
    echo ERROR: Failed to regenerate autoloader!
    pause
    exit /b 1
)
echo ✓ Autoloader regenerated
echo.

echo ========================================
echo  ✓ ALL CACHES CLEARED SUCCESSFULLY!
echo ========================================
echo.
echo The service provider optimization is now active.
echo You can now test the /buy-action route.
echo.
echo Press any key to exit...
pause >nul

