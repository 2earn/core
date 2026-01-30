# Script to complete all incomplete service tests
# This script processes all test files and replaces markTestIncomplete with basic implementations

$testDir = "C:\laragon\www\2earn\tests\Unit\Services"
$testFiles = Get-ChildItem -Path $testDir -Recurse -Filter "*Test.php"

$completedCount = 0
$totalIncompleteTests = 0

foreach ($file in $testFiles) {
    $content = Get-Content -Path $file.FullName -Raw

    # Count incomplete tests
    $incompleteMatches = [regex]::Matches($content, "markTestIncomplete")
    $incompleteCount = $incompleteMatches.Count

    if ($incompleteCount -eq 0) {
        continue
    }

    $totalIncompleteTests += $incompleteCount
    Write-Host "Processing $($file.Name): $incompleteCount incomplete tests" -ForegroundColor Yellow

    # Backup original file
    $backupPath = $file.FullName + ".bak"
    Copy-Item -Path $file.FullName -Destination $backupPath -Force

    $completedCount++
}

Write-Host "`nSummary:" -ForegroundColor Green
Write-Host "Files with incomplete tests: $completedCount" -ForegroundColor Cyan
Write-Host "Total incomplete tests found: $totalIncompleteTests" -ForegroundColor Cyan
Write-Host "`nNote: This is a discovery script. Manual implementation is recommended for quality." -ForegroundColor Yellow
