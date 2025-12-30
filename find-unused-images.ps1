#!/usr/bin/env pwsh
# Script to find unused images in the project

Write-Host "=== Finding Unused Images ===" -ForegroundColor Cyan
Write-Host ""

# Define image directories to check
$imagePaths = @(
    "resources\images",
    "resources\img",
    "public"
)

# Define file extensions to check
$imageExtensions = @("*.png", "*.jpg", "*.jpeg", "*.gif", "*.svg", "*.webp", "*.ico")

# Define directories and files to search for image references
$searchPaths = @(
    "resources\views",
    "resources\js",
    "resources\css",
    "resources\sass",
    "resources\scss",
    "app",
    "public"
)

$excludePaths = @(
    "node_modules",
    "vendor",
    "storage",
    "public\build",
    "public\uploads"
)

Write-Host "Scanning for images..." -ForegroundColor Yellow

# Get all images
$allImages = @()
foreach ($path in $imagePaths) {
    if (Test-Path $path) {
        foreach ($ext in $imageExtensions) {
            $images = Get-ChildItem -Path $path -Filter $ext -Recurse -File -ErrorAction SilentlyContinue
            $allImages += $images
        }
    }
}

Write-Host "Found $($allImages.Count) total images" -ForegroundColor Green
Write-Host ""

# Get all code files to search
Write-Host "Searching for image references in code..." -ForegroundColor Yellow
$codeFiles = @()
foreach ($path in $searchPaths) {
    if (Test-Path $path) {
        $files = Get-ChildItem -Path $path -Include *.php,*.blade.php,*.js,*.jsx,*.ts,*.tsx,*.css,*.scss,*.sass,*.vue -Recurse -File -ErrorAction SilentlyContinue
        $codeFiles += $files
    }
}

Write-Host "Searching in $($codeFiles.Count) code files..." -ForegroundColor Green
Write-Host ""

# Track unused images
$unusedImages = @()
$usedImages = @()
$processedCount = 0

foreach ($image in $allImages) {
    $processedCount++

    # Show progress every 50 images
    if ($processedCount % 50 -eq 0) {
        Write-Host "Progress: $processedCount / $($allImages.Count)" -ForegroundColor Gray
    }

    $imageName = $image.Name
    $imageBaseName = [System.IO.Path]::GetFileNameWithoutExtension($imageName)
    $isUsed = $false

    # Search for references to this image
    foreach ($file in $codeFiles) {
        try {
            $content = Get-Content -Path $file.FullName -Raw -ErrorAction SilentlyContinue

            if ($content -match [regex]::Escape($imageName) -or
                $content -match [regex]::Escape($imageBaseName)) {
                $isUsed = $true
                break
            }
        }
        catch {
            # Skip files that can't be read
        }
    }

    if ($isUsed) {
        $usedImages += $image
    }
    else {
        $unusedImages += $image
    }
}

Write-Host ""
Write-Host "=== Results ===" -ForegroundColor Cyan
Write-Host ""
Write-Host "Total Images: $($allImages.Count)" -ForegroundColor White
Write-Host "Used Images: $($usedImages.Count)" -ForegroundColor Green
Write-Host "Unused Images: $($unusedImages.Count)" -ForegroundColor Red
Write-Host ""

if ($unusedImages.Count -gt 0) {
    Write-Host "=== Unused Images ===" -ForegroundColor Yellow
    Write-Host ""

    # Group by directory
    $groupedImages = $unusedImages | Group-Object { $_.DirectoryName }

    foreach ($group in $groupedImages) {
        $relativePath = $group.Name.Replace((Get-Location).Path, ".")
        Write-Host "Directory: $relativePath" -ForegroundColor Cyan
        foreach ($img in $group.Group) {
            $size = [math]::Round($img.Length / 1KB, 2)
            Write-Host "  - $($img.Name) ($size KB)" -ForegroundColor Gray
        }
        Write-Host ""
    }

    # Calculate total size
    $totalSize = ($unusedImages | Measure-Object -Property Length -Sum).Sum
    $totalSizeMB = [math]::Round($totalSize / 1MB, 2)

    Write-Host "Total unused images size: $totalSizeMB MB" -ForegroundColor Yellow
    Write-Host ""

    # Export to file
    $reportPath = "unused-images-report.txt"
    $unusedImages | Select-Object FullName, @{Name='Size(KB)';Expression={[math]::Round($_.Length/1KB,2)}} |
        Out-File -FilePath $reportPath -Encoding UTF8

    Write-Host "Report saved to: $reportPath" -ForegroundColor Green
    Write-Host ""

    # Ask for confirmation to delete
    $response = Read-Host "Do you want to delete these unused images? (yes/no)"

    if ($response -eq "yes") {
        Write-Host ""
        Write-Host "Creating backup..." -ForegroundColor Yellow

        # Create backup directory
        $backupDir = "unused-images-backup-$(Get-Date -Format 'yyyyMMdd-HHmmss')"
        New-Item -ItemType Directory -Path $backupDir -Force | Out-Null

        # Copy unused images to backup
        foreach ($img in $unusedImages) {
            $relativePath = $img.FullName.Replace((Get-Location).Path + "\", "")
            $backupPath = Join-Path $backupDir $relativePath
            $backupFolder = Split-Path $backupPath -Parent

            New-Item -ItemType Directory -Path $backupFolder -Force -ErrorAction SilentlyContinue | Out-Null
            Copy-Item $img.FullName $backupPath -Force
        }

        Write-Host "Backup created: $backupDir" -ForegroundColor Green
        Write-Host ""
        Write-Host "Deleting unused images..." -ForegroundColor Yellow

        # Delete unused images
        $deletedCount = 0
        foreach ($img in $unusedImages) {
            try {
                Remove-Item $img.FullName -Force
                $deletedCount++
            }
            catch {
                Write-Host "Failed to delete: $($img.Name)" -ForegroundColor Red
            }
        }

        Write-Host ""
        Write-Host "Deleted $deletedCount images ($totalSizeMB MB freed)" -ForegroundColor Green
        Write-Host "Backup location: $backupDir" -ForegroundColor Cyan
    }
    else {
        Write-Host "No images deleted. Run the script again if you want to delete them later." -ForegroundColor Yellow
    }
}
else {
    Write-Host "All images are being used! 🎉" -ForegroundColor Green
}

Write-Host ""
Write-Host "=== Complete ===" -ForegroundColor Cyan

