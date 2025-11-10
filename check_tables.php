<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;

$tables = [
    'user_contacts',
    'vip',
    'user_earns',
    'user_balances',
    'usercontactnumber',
    'translatetab',
    'transactions',
    'targetables',
    'states',
    'sms_balances',
    'settings',
    'role_has_permissions',
    'roles',
    'representatives',
    'pool',
    'platforms',
    'metta_users',
    'financial_request',
    'detail_financial_request',
    'countries',
    'balance_operations'
];

echo "Checking tables for auditing fields...\n\n";

foreach ($tables as $table) {
    echo "Table: $table\n";

    if (!Schema::hasTable($table)) {
        echo "  ❌ Table does not exist\n\n";
        continue;
    }

    $columns = Schema::getColumnListing($table);

    $hasCreatedAt = in_array('created_at', $columns);
    $hasUpdatedAt = in_array('updated_at', $columns);
    $hasCreatedBy = in_array('created_by', $columns);
    $hasUpdatedBy = in_array('updated_by', $columns);

    echo "  created_at: " . ($hasCreatedAt ? "✓" : "✗") . "\n";
    echo "  updated_at: " . ($hasUpdatedAt ? "✓" : "✗") . "\n";
    echo "  created_by: " . ($hasCreatedBy ? "✓" : "✗") . "\n";
    echo "  updated_by: " . ($hasUpdatedBy ? "✓" : "✗") . "\n";
    echo "  All columns: " . implode(', ', $columns) . "\n\n";
}

