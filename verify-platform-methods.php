<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Verifying PlatformService Methods ===\n\n";

$platformService = app(\App\Services\Platform\PlatformService::class);

// Check all three methods exist
$methods = ['getPlatformsForPartner', 'userHasRoleInPlatform', 'getPlatformForPartner'];
foreach ($methods as $method) {
    if (method_exists($platformService, $method)) {
        echo "✓ {$method}() - EXISTS\n";
    } else {
        echo "✗ {$method}() - MISSING\n";
        exit(1);
    }
}

echo "\n--- Testing Methods ---\n\n";

// Test getPlatformsForPartner
try {
    $result = $platformService->getPlatformsForPartner(1, 1, null, 10);
    echo "✓ getPlatformsForPartner() - CALLABLE (returned " . $result['platforms']->count() . " platforms)\n";
} catch (\Exception $e) {
    echo "✗ getPlatformsForPartner() - ERROR: " . $e->getMessage() . "\n";
    exit(1);
}

// Test userHasRoleInPlatform
try {
    $result = $platformService->userHasRoleInPlatform(1, 1);
    echo "✓ userHasRoleInPlatform() - CALLABLE (returned " . ($result ? 'true' : 'false') . ")\n";
} catch (\Exception $e) {
    echo "✗ userHasRoleInPlatform() - ERROR: " . $e->getMessage() . "\n";
    exit(1);
}

// Test getPlatformForPartner
try {
    $result = $platformService->getPlatformForPartner(1, 1);
    echo "✓ getPlatformForPartner() - CALLABLE (returned " . ($result ? 'Platform object' : 'null') . ")\n";
} catch (\Exception $e) {
    echo "✗ getPlatformForPartner() - ERROR: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n=== All Methods Verified Successfully! ===\n";
echo "All three methods are now available and working in PlatformService.\n";

