<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MergeAllTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translate:merge-all {--path= : Base path containing translation files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Merge all translation files (ar, fr, en, es, tr, de, ru) at once';

    /**
     * Supported languages.
     *
     * @var array
     */
    protected array $languages = [
        'ar' => 'Arabic',
        'fr' => 'French',
        'en' => 'English',
        'es' => 'Spanish',
        'tr' => 'Turkish',
        'de' => 'German',
        'ru' => 'Russian',
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🚀 Starting merge for all translation files...');
        $this->newLine();

        $basePath = $this->option('path') ?? base_path('new trans');
        $totalSuccess = 0;
        $totalFailed = 0;
        $results = [];

        // Process each language
        foreach ($this->languages as $code => $name) {
            $sourcePath = $basePath . "/{$code}.json";

            $this->line("📝 Processing {$name} ({$code})...");

            // Check if source file exists
            if (!file_exists($sourcePath)) {
                $this->warn("   ⚠️  Source file not found: {$sourcePath}");
                $this->warn("   ⏭️  Skipping {$name}");
                $results[$code] = [
                    'name' => $name,
                    'status' => 'skipped',
                    'reason' => 'Source file not found'
                ];
                $totalFailed++;
                $this->newLine();
                continue;
            }

            // Call the specific language merge command
            $exitCode = $this->call("translate:merge-{$code}", [
                'source' => $sourcePath
            ]);

            if ($exitCode === 0) {
                $this->info("   ✅ {$name} merged successfully!");
                $results[$code] = [
                    'name' => $name,
                    'status' => 'success'
                ];
                $totalSuccess++;
            } else {
                $this->error("   ❌ {$name} merge failed!");
                $results[$code] = [
                    'name' => $name,
                    'status' => 'failed'
                ];
                $totalFailed++;
            }

            $this->newLine();
        }

        // Display summary
        $this->displaySummary($results, $totalSuccess, $totalFailed);

        // Return appropriate exit code
        return $totalFailed === 0 ? self::SUCCESS : self::FAILURE;
    }

    /**
     * Display summary of merge operations.
     *
     * @param array $results
     * @param int $totalSuccess
     * @param int $totalFailed
     * @return void
     */
    protected function displaySummary(array $results, int $totalSuccess, int $totalFailed): void
    {
        $this->info('═══════════════════════════════════════════════════');
        $this->info('                 MERGE SUMMARY                     ');
        $this->info('═══════════════════════════════════════════════════');
        $this->newLine();

        // Display results table
        $tableData = [];
        foreach ($results as $code => $result) {
            $statusIcon = match($result['status']) {
                'success' => '✅',
                'failed' => '❌',
                'skipped' => '⏭️',
                default => '❓'
            };

            $statusText = match($result['status']) {
                'success' => '<fg=green>Success</>',
                'failed' => '<fg=red>Failed</>',
                'skipped' => '<fg=yellow>Skipped</>',
                default => 'Unknown'
            };

            $tableData[] = [
                $statusIcon,
                strtoupper($code),
                $result['name'],
                $statusText,
                $result['reason'] ?? '-'
            ];
        }

        $this->table(
            ['', 'Code', 'Language', 'Status', 'Note'],
            $tableData
        );

        $this->newLine();
        $this->info("📊 Total Languages: " . count($this->languages));
        $this->info("✅ Successfully Merged: {$totalSuccess}");

        if ($totalFailed > 0) {
            $this->error("❌ Failed/Skipped: {$totalFailed}");
        }

        $this->newLine();

        if ($totalFailed === 0) {
            $this->info('🎉 All translations merged successfully!');
        } else {
            $this->warn('⚠️  Some translations were not merged. Check the details above.');
        }

        $this->info('═══════════════════════════════════════════════════');
    }
}
