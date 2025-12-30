#!/usr/bin/env pwsh
# Benchmark script for Vite build performance

Write-Host "=== Vite Performance Benchmark ===" -ForegroundColor Cyan
Write-Host ""

# Clean build cache
Write-Host "Cleaning cache..." -ForegroundColor Yellow
if (Test-Path "node_modules/.vite") {
    Remove-Item -Recurse -Force "node_modules/.vite"
}
if (Test-Path "public/build") {
    Remove-Item -Recurse -Force "public/build"
}

Write-Host "Cache cleaned!" -ForegroundColor Green
Write-Host ""

# Benchmark production build
Write-Host "Testing production build (npm run build)..." -ForegroundColor Yellow
$buildTime = Measure-Command { npm run build }
Write-Host "Build completed in: $($buildTime.TotalSeconds) seconds" -ForegroundColor Green
Write-Host ""

# Clean for dev server test
Write-Host "Cleaning for dev server test..." -ForegroundColor Yellow
if (Test-Path "node_modules/.vite") {
    Remove-Item -Recurse -Force "node_modules/.vite"
}

# Note about dev server
Write-Host "To test dev server, run: npm run dev" -ForegroundColor Cyan
Write-Host "Dev server startup time will be shown in the terminal output" -ForegroundColor Cyan
Write-Host ""

Write-Host "=== Benchmark Complete ===" -ForegroundColor Cyan
Write-Host ""
Write-Host "Performance Improvements:" -ForegroundColor Green
Write-Host "- esbuild minifier (4x faster than terser)" -ForegroundColor White
Write-Host "- Dependency pre-bundling" -ForegroundColor White
Write-Host "- Manual code splitting" -ForegroundColor White
Write-Host "- Optimized asset copying" -ForegroundColor White
Write-Host "- Server warmup for dev" -ForegroundColor White

