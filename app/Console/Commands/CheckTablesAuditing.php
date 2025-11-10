<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class CheckTablesAuditing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auditing:check-tables {tables?* : Specific tables to check (optional)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if tables have all required auditing fields (created_at, updated_at, created_by, updated_by)';

    /**
     * Default tables to check if none specified
     *
     * @var array
     */
    protected $defaultTables = [
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
        'balance_operations',
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tablesToCheck = $this->argument('tables');

        if (empty($tablesToCheck)) {
            $tablesToCheck = $this->defaultTables;
            $this->info('Checking default tables for auditing fields...');
        } else {
            $this->info('Checking specified tables for auditing fields...');
        }

        $this->newLine();

        $results = [];
        $allGood = true;

        foreach ($tablesToCheck as $table) {
            if (!Schema::hasTable($table)) {
                $this->error("❌ Table '{$table}' does not exist");
                $allGood = false;
                continue;
            }

            $columns = Schema::getColumnListing($table);

            $hasCreatedAt = in_array('created_at', $columns);
            $hasUpdatedAt = in_array('updated_at', $columns);
            $hasCreatedBy = in_array('created_by', $columns);
            $hasUpdatedBy = in_array('updated_by', $columns);

            $allFieldsPresent = $hasCreatedAt && $hasUpdatedAt && $hasCreatedBy && $hasUpdatedBy;

            if ($allFieldsPresent) {
                $this->line("✅ <info>{$table}</info> - All auditing fields present");
            } else {
                $allGood = false;
                $missing = [];
                if (!$hasCreatedAt) $missing[] = 'created_at';
                if (!$hasUpdatedAt) $missing[] = 'updated_at';
                if (!$hasCreatedBy) $missing[] = 'created_by';
                if (!$hasUpdatedBy) $missing[] = 'updated_by';

                $this->line("❌ <error>{$table}</error> - Missing: " . implode(', ', $missing));
            }

            $results[$table] = [
                'created_at' => $hasCreatedAt,
                'updated_at' => $hasUpdatedAt,
                'created_by' => $hasCreatedBy,
                'updated_by' => $hasUpdatedBy,
                'all_present' => $allFieldsPresent,
            ];
        }

        $this->newLine();

        // Summary
        $totalTables = count($results);
        $compliantTables = count(array_filter($results, fn($r) => $r['all_present']));

        if ($allGood) {
            $this->info("🎉 All {$totalTables} tables have complete auditing fields!");
        } else {
            $this->warn("⚠️  {$compliantTables}/{$totalTables} tables have complete auditing fields");
        }

        // Detailed view option
        if ($this->option('verbose')) {
            $this->newLine();
            $this->info('Detailed column listing:');

            foreach ($tablesToCheck as $table) {
                if (Schema::hasTable($table)) {
                    $columns = Schema::getColumnListing($table);
                    $this->line("\n<comment>{$table}:</comment> " . implode(', ', $columns));
                }
            }
        }

        $this->newLine();
        $this->comment('Tip: Use -v flag for verbose output with all columns');
        $this->comment('Example: php artisan auditing:check-tables users deals orders');

        return $allGood ? 0 : 1;
    }
}

