@echo off
echo ========================================
echo  Building 2Earn Modern Design System
echo ========================================
echo.

cd /d "%~dp0"

echo [1/2] Running npm build...
call npm run build

if %ERRORLEVEL% NEQ 0 (
    echo.
    echo ❌ Build failed! Check errors above.
    pause
    exit /b %ERRORLEVEL%
)

echo.
echo ========================================
echo  ✅ BUILD SUCCESSFUL!
echo ========================================
echo.
echo Next steps:
echo 1. Open your browser
echo 2. Press Ctrl + Shift + R to hard refresh
echo 3. Enjoy the modern design!
echo.
echo The site now has:
echo   ✨ Rounded cards with beautiful shadows
echo   ✨ Gradient buttons with hover effects
echo   ✨ Modern form inputs
echo   ✨ Smooth animations
echo   ✨ Dark mode support
echo   ✨ RTL support
echo.
pause

