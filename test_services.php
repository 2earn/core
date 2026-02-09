<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing UserCurrentBalanceHorisontalService...\n";
try {
    $service = app(\App\Services\UserBalances\UserCurrentBalanceHorisontalService::class);
    echo "SUCCESS: UserCurrentBalanceHorisontalService loaded successfully\n";
    echo "Class: " . get_class($service) . "\n\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n\n";
}

echo "Testing UserCurrentBalanceVerticalService...\n";
try {
    $service = app(\App\Services\UserBalances\UserCurrentBalanceVerticalService::class);
    echo "SUCCESS: UserCurrentBalanceVerticalService loaded successfully\n";
    echo "Class: " . get_class($service) . "\n\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n\n";
}

echo "Testing UserBalancesHelper...\n";
try {
    $service = app(\App\Services\UserBalances\UserBalancesHelper::class);
    echo "SUCCESS: UserBalancesHelper loaded successfully\n";
    echo "Class: " . get_class($service) . "\n\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n\n";
}

echo "All tests completed!\n";

