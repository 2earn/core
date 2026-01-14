<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class FindModelsForAudit extends Command
{

    protected $signature = 'auditing:find-models {--missing : Show only models missing the trait}';

    protected $description = 'Find all models and check which ones have the HasAuditing trait';

    protected $auditingTables = [
        'user_contacts' => 'UserContact',
        'vip' => 'vip',
        'user_earns' => 'user_earn',
        'user_balances' => 'user_balance',
        'usercontactnumber' => 'UserContactNumber',
        'translatetab' => 'translatetabs',
        'transactions' => null,
        'targetables' => null,
        'states' => null,
        'sms_balances' => 'SMSBalances',
        'settings' => 'Setting',
        'role_has_permissions' => null,
        'roles' => null,
        'representatives' => null,
        'pool' => 'Pool',
        'platforms' => 'Platform',
        'metta_users' => 'MettaUser',
        'financial_request' => 'FinancialRequest',
        'detail_financial_request' => 'detail_financial_request',
        'countries' => 'countrie',
        'balance_operations' => 'BalanceOperation',
    ];

    public function handle()
    {
        $this->info('=== Scanning Models for HasAuditing Trait ===');
        $this->newLine();

        $results = [
            'app' => $this->scanDirectory(app_path('Models'), 'App\\Models'),
        ];

        $showMissingOnly = $this->option('missing');

        $this->info('📁 App\Models Directory:');
        $this->displayResults($results['app'], $showMissingOnly);
        $this->newLine();

        $totalApp = count($results['app']);
        $withTraitApp = count(array_filter($results['app'], fn($r) => $r['has_trait']));

        $this->info('📊 Summary:');
        $this->line("  App\\Models: {$withTraitApp}/{$totalApp} models have HasAuditing trait");

        $this->newLine();

        $this->info('📋 Tables Without Models:');
        $missingModels = array_filter($this->auditingTables, fn($model) => $model === null);
        if (!empty($missingModels)) {
            foreach (array_keys($missingModels) as $table) {
                $this->line("  ⚠️  {$table} - No model found (may be package-managed)");
            }
        } else {
            $this->line('  ✅ All auditing tables have models');
        }

        $this->newLine();
        $this->comment('Tip: Use --missing flag to show only models without the trait');
        $this->comment('Example: php artisan auditing:find-models --missing');

        return 0;
    }

    protected function scanDirectory($path, $namespace)
    {
        $results = [];

        if (!File::exists($path)) {
            return $results;
        }

        $files = File::files($path);

        foreach ($files as $file) {
            $filename = $file->getFilename();
            $modelName = pathinfo($filename, PATHINFO_FILENAME);
            $className = $namespace . '\\' . $modelName;

            try {

                if (@class_exists($className, false) || @class_exists($className)) {
                    $reflection = new \ReflectionClass($className);

                    if (!$reflection->isSubclassOf('Illuminate\Database\Eloquent\Model')) {
                        continue;
                    }

                    $traits = $this->getAllTraits($reflection);
                    $hasTrait = in_array('App\Traits\HasAuditing', $traits);
                    $hasTimestamps = $this->checkTimestamps($reflection);

                    $results[$modelName] = [
                        'class' => $className,
                        'file' => $filename,
                        'has_trait' => $hasTrait,
                        'timestamps' => $hasTimestamps,
                    ];
                }
            } catch (\Throwable $e) {

                continue;
            }
        }

        return $results;
    }

    protected function getAllTraits(\ReflectionClass $class)
    {
        $traits = [];

        do {
            $traits = array_merge($traits, $class->getTraitNames());
        } while ($class = $class->getParentClass());

        return array_unique($traits);
    }

    protected function checkTimestamps(\ReflectionClass $reflection)
    {
        try {
            if ($reflection->hasProperty('timestamps')) {
                $property = $reflection->getProperty('timestamps');
                $property->setAccessible(true);
                $instance = $reflection->newInstanceWithoutConstructor();
                $value = $property->getValue($instance);
                return $value ? 'enabled' : 'disabled';
            }
        } catch (\Throwable $e) {

        }

        return 'unknown';
    }

    protected function displayResults($results, $showMissingOnly)
    {
        if (empty($results)) {
            $this->line('  No models found');
            return;
        }

        foreach ($results as $modelName => $info) {
            if ($showMissingOnly && $info['has_trait']) {
                continue;
            }

            $icon = $info['has_trait'] ? '✅' : '❌';
            $timestampInfo = $info['timestamps'] === 'enabled' ? 'TS:✓' :
                           ($info['timestamps'] === 'disabled' ? 'TS:✗' : 'TS:?');

            if ($info['has_trait']) {
                $this->line("  {$icon} <info>{$modelName}</info> [{$timestampInfo}]");
            } else {
                $this->line("  {$icon} <comment>{$modelName}</comment> [{$timestampInfo}] - Missing HasAuditing trait");
            }
        }
    }
}

