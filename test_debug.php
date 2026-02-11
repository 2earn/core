<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Get a balance operation with relationships
$operation = \App\Models\BalanceOperation::with(['parent', 'opeartionCategory'])->first();

if ($operation) {
    echo "Operation found:\n";
    echo "ID: " . $operation->id . "\n";
    echo "Operation: " . $operation->operation . "\n";
    echo "Parent Operation ID: " . $operation->parent_operation_id . "\n";
    echo "Operation Category ID: " . $operation->operation_category_id . "\n";
    echo "\nRelationships:\n";
    echo "Parent: " . ($operation->parent ? "Loaded (ID: " . $operation->parent->id . ")" : "NULL") . "\n";
    echo "Category: " . ($operation->opeartionCategory ? "Loaded (ID: " . $operation->opeartionCategory->id . ")" : "NULL") . "\n";
    echo "\nJSON:\n";
    echo json_encode($operation, JSON_PRETTY_PRINT);
} else {
    echo "No operations found in database\n";
}

