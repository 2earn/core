@echo off
REM Performance Optimization Script for 2earn Laravel Application
REM Run this script after deployment or when you need to optimize the application

echo ========================================
echo   2earn Performance Optimization
echo ========================================
echo.

echo [1/6] Clearing old cache...
php artisan optimize:clear
echo.

echo [2/6] Optimizing Composer autoloader...
composer dump-autoload -o
echo.

echo [3/6] Caching configuration...
php artisan config:cache
echo.

echo [4/6] Caching routes...
php artisan route:cache
echo.

echo [5/6] Caching events...
php artisan event:cache
echo.

echo [6/6] Caching views...
php artisan view:cache
echo.

echo ========================================
echo   Optimization Complete!
echo ========================================
echo.
echo Your application is now optimized.
echo Run "clear-caches.bat" if you need to clear optimizations during development.
echo.
pause

