<?php

// Test script to verify commission formula active filter
// Run with: php artisan tinker < test_commission_filter.php

use App\Models\CommissionFormula;
use App\Services\Commission\CommissionFormulaService;

echo "=== Commission Formula Filter Test ===" . PHP_EOL . PHP_EOL;

// Check total formulas
$total = CommissionFormula::count();
echo "Total formulas in database: {$total}" . PHP_EOL;

// Check with different query methods
$activeCount1 = CommissionFormula::where('is_active', 1)->count();
echo "Active formulas (is_active = 1): {$activeCount1}" . PHP_EOL;

$activeCount2 = CommissionFormula::where('is_active', true)->count();
echo "Active formulas (is_active = true): {$activeCount2}" . PHP_EOL;

$activeCount3 = CommissionFormula::where('is_active', '=', 1)->count();
echo "Active formulas (is_active = '1'): {$activeCount3}" . PHP_EOL;

$inactiveCount = CommissionFormula::where('is_active', false)->count();
echo "Inactive formulas (is_active = false): {$inactiveCount}" . PHP_EOL;

// Show first 3 formulas
echo PHP_EOL . "First 3 formulas:" . PHP_EOL;
CommissionFormula::take(3)->get(['id', 'name', 'is_active'])->each(function($f) {
    $type = gettype($f->is_active);
    $value = $f->is_active ? 'true' : 'false';
    echo "  ID: {$f->id}, Name: {$f->name}, Active: {$value} (type: {$type})" . PHP_EOL;
});

// Test with service
echo PHP_EOL . "=== Testing CommissionFormulaService ===" . PHP_EOL;
$service = app(CommissionFormulaService::class);

// Test with active = true
$result1 = $service->getPaginatedFormulas(['is_active' => true]);
echo "getPaginatedFormulas(['is_active' => true]): {$result1['total']} results" . PHP_EOL;

// Test with active = false
$result2 = $service->getPaginatedFormulas(['is_active' => false]);
echo "getPaginatedFormulas(['is_active' => false]): {$result2['total']} results" . PHP_EOL;

// Test with active = 1
$result3 = $service->getPaginatedFormulas(['is_active' => 1]);
echo "getPaginatedFormulas(['is_active' => 1]): {$result3['total']} results" . PHP_EOL;

// Test with active = 0
$result4 = $service->getPaginatedFormulas(['is_active' => 0]);
echo "getPaginatedFormulas(['is_active' => 0]): {$result4['total']} results" . PHP_EOL;

// Test getActiveFormulas
$activeFormulas = $service->getActiveFormulas();
echo "getActiveFormulas(): {$activeFormulas->count()} results" . PHP_EOL;

echo PHP_EOL . "=== Test Complete ===" . PHP_EOL;

