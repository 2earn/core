<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\File;

$modelsPath = app_path('Models');
$coreModelsPath = base_path('Core/Models');

$tableToModelMap = [
    'user_contacts' => 'ContactUser',
    'vip' => 'vip',
    'user_earns' => null, // Need to check
    'user_balances' => null, // Need to check
    'usercontactnumber' => null, // Need to check
    'translatetab' => null, // Need to check
    'transactions' => null, // Need to check
    'targetables' => null, // Need to check
    'states' => null, // Need to check in Core
    'sms_balances' => 'SMSBalances',
    'settings' => null, // Need to check in Core
    'role_has_permissions' => null, // Likely no model (pivot)
    'roles' => null, // Need to check in Core
    'representatives' => null, // Need to check in Core
    'pool' => 'Pool',
    'platforms' => null, // Need to check in Core
    'metta_users' => null, // Need to check
    'financial_request' => null, // Need to check
    'detail_financial_request' => null, // Need to check
    'countries' => null, // Need to check in Core
    'balance_operations' => null, // Need to check
];

echo "Searching for models that need auditing fields...\n\n";

// Check App\Models
$appModels = File::files($modelsPath);
foreach ($appModels as $file) {
    $filename = $file->getFilename();
    echo "Found: app/Models/{$filename}\n";
}

echo "\n";

// Check Core\Models
if (File::exists($coreModelsPath)) {
    $coreModels = File::files($coreModelsPath);
    foreach ($coreModels as $file) {
        $filename = $file->getFilename();
        echo "Found: Core/Models/{$filename}\n";
    }
}

echo "\n\nModels that likely need updating:\n";
echo "================================\n\n";

$needsUpdate = [
    'ContactUser.php' => 'Already has trait, add fillable fields',
    'vip.php' => '✓ UPDATED - timestamps enabled, fillable updated',
    'Pool.php' => '✓ UPDATED - fillable updated',
    'SMSBalances.php' => 'Add fillable fields (created_by, updated_by)',
];

foreach ($needsUpdate as $model => $status) {
    echo "  - {$model}: {$status}\n";
}

echo "\n\nModels to search for:\n";
echo "====================\n";
$toFind = [
    'user_earns',
    'user_balances',
    'usercontactnumber',
    'translatetab',
    'transactions',
    'targetables',
    'states',
    'settings',
    'roles',
    'representatives',
    'platforms',
    'metta_users',
    'financial_request',
    'detail_financial_request',
    'countries',
    'balance_operations',
];

foreach ($toFind as $table) {
    echo "  - {$table}\n";
}

