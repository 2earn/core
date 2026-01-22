<?php

/*
 * Service Provider Optimization Test Script
 *
 * Run this to verify that all services are properly bound and working
 * after the optimization changes.
 *
 * Usage: php artisan tinker < test-services.php
 */

// Test 1: Check if services can be resolved from container
echo "Test 1: Resolving services from container...\n";
try {
    $sponsorship = app('Sponsorship');
    echo "✓ Sponsorship service resolved\n";
} catch (Exception $e) {
    echo "✗ Sponsorship failed: " . $e->getMessage() . "\n";
}

try {
    $targeting = app('Targeting');
    echo "✓ Targeting service resolved\n";
} catch (Exception $e) {
    echo "✗ Targeting failed: " . $e->getMessage() . "\n";
}

try {
    $communication = app('Communication');
    echo "✓ Communication service resolved\n";
} catch (Exception $e) {
    echo "✗ Communication failed: " . $e->getMessage() . "\n";
}

try {
    $balances = app('Balances');
    echo "✓ Balances service resolved\n";
} catch (Exception $e) {
    echo "✗ Balances failed: " . $e->getMessage() . "\n";
}

try {
    $userToken = app('UserToken');
    echo "✓ UserToken service resolved\n";
} catch (Exception $e) {
    echo "✗ UserToken failed: " . $e->getMessage() . "\n";
}

// Test 2: Check if services can be resolved by class name
echo "\nTest 2: Resolving services by class name...\n";
try {
    $sponsorship = app(\App\Services\Sponsorship\Sponsorship::class);
    echo "✓ Sponsorship class resolved\n";
} catch (Exception $e) {
    echo "✗ Sponsorship class failed: " . $e->getMessage() . "\n";
}

try {
    $targeting = app(\App\Services\Targeting\Targeting::class);
    echo "✓ Targeting class resolved\n";
} catch (Exception $e) {
    echo "✗ Targeting class failed: " . $e->getMessage() . "\n";
}

// Test 3: Verify singleton behavior (same instance)
echo "\nTest 3: Verifying singleton behavior...\n";
try {
    $instance1 = app('Sponsorship');
    $instance2 = app('Sponsorship');
    if ($instance1 === $instance2) {
        echo "✓ Sponsorship is singleton (same instance)\n";
    } else {
        echo "✗ Sponsorship is NOT singleton (different instances)\n";
    }
} catch (Exception $e) {
    echo "✗ Singleton test failed: " . $e->getMessage() . "\n";
}

// Test 4: Check deferred provider is registered
echo "\nTest 4: Checking provider registration...\n";
$providers = app()->getLoadedProviders();
if (isset($providers[\App\Providers\DeferredServiceProvider::class])) {
    echo "✓ DeferredServiceProvider is loaded\n";
} else {
    echo "ℹ DeferredServiceProvider not yet loaded (deferred until needed)\n";
}

echo "\n✓ All tests completed!\n";

