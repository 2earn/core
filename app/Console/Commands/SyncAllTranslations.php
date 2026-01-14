<?php

namespace App\Console\Commands;

use App\Jobs\TranslationDatabaseToFiles;
use App\Jobs\TranslationFilesToDatabase;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncAllTranslations extends Command
{
    const SEPARATION = ' : ';

    protected $signature = 'translate:sync-all {--skip-sync : Skip sync-tabs step} {--skip-merge : Skip merge-all step} {--skip-clean : Skip clean-unused step}';

    protected $description = 'Sync all translations: sync-tabs, merge-all, clean-unused, update database, seed missing keys, and export to files';

    public function handle(): int
    {
        $this->info('═══════════════════════════════════════════════════');
        $this->info('        FULL TRANSLATION SYNCHRONIZATION           ');
        $this->info('═══════════════════════════════════════════════════');
        $this->newLine();

        $startTimeTotal = microtime(true);
        $steps = [];
        $hasErrors = false;

        if (!$this->option('skip-sync')) {
            $this->info('📝 Step 1/6: Syncing translation keys from code...');
            $this->line('   Command: translate:sync-tabs');
            $this->newLine();

            $startTime = microtime(true);
            $exitCode = $this->call('translate:sync-tabs');
            $endTime = microtime(true);
            $executionTime = $this->formatTime($endTime - $startTime);

            if ($exitCode === 0) {
                $this->info("   ✅ Sync completed in {$executionTime}");
                $steps[] = ['step' => 'Sync Keys', 'status' => 'success', 'time' => $executionTime];
            } else {
                $this->error("   ❌ Sync failed");
                $steps[] = ['step' => 'Sync Keys', 'status' => 'failed', 'time' => $executionTime];
                $hasErrors = true;
            }
            $this->newLine();
        } else {
            $this->warn('⏭️  Step 1/6: Skipped (--skip-sync)');
            $this->newLine();
        }

        if (!$this->option('skip-merge')) {
            $this->info('🔄 Step 2/6: Merging all translation files...');
            $this->line('   Command: translate:merge-all');
            $this->newLine();

            $startTime = microtime(true);
            $exitCode = $this->call('translate:merge-all');
            $endTime = microtime(true);
            $executionTime = $this->formatTime($endTime - $startTime);

            if ($exitCode === 0) {
                $this->info("   ✅ Merge completed in {$executionTime}");
                $steps[] = ['step' => 'Merge All', 'status' => 'success', 'time' => $executionTime];
            } else {
                $this->error("   ❌ Merge failed");
                $steps[] = ['step' => 'Merge All', 'status' => 'failed', 'time' => $executionTime];
                $hasErrors = true;
            }
            $this->newLine();
        } else {
            $this->warn('⏭️  Step 2/6: Skipped (--skip-merge)');
            $this->newLine();
        }

        if (!$this->option('skip-clean')) {
            $this->info('🧹 Step 3/6: Cleaning unused translation keys...');
            $this->line('   Command: translate:clean-unused');
            $this->newLine();

            $startTime = microtime(true);
            $exitCode = $this->call('translate:clean-unused', ['--backup' => true]);
            $endTime = microtime(true);
            $executionTime = $this->formatTime($endTime - $startTime);

            if ($exitCode === 0) {
                $this->info("   ✅ Cleanup completed in {$executionTime}");
                $steps[] = ['step' => 'Clean Unused', 'status' => 'success', 'time' => $executionTime];
            } else {
                $this->error("   ❌ Cleanup failed");
                $steps[] = ['step' => 'Clean Unused', 'status' => 'failed', 'time' => $executionTime];
                $hasErrors = true;
            }
            $this->newLine();
        } else {
            $this->warn('⏭️  Step 3/6: Skipped (--skip-clean)');
            $this->newLine();
        }

        $this->info('💾 Step 4/6: Updating database from files...');
        $this->line('   Job: TranslationFilesToDatabase');
        $this->newLine();

        try {
            $startTime = microtime(true);
            $job = new TranslationFilesToDatabase();
            $job->handle();
            $endTime = microtime(true);
            $executionTime = $this->formatTime($endTime - $startTime);

            Log::info(TranslationFilesToDatabase::class . self::SEPARATION . $executionTime);

            $this->info("   ✅ Database update completed in {$executionTime}");
            $steps[] = ['step' => 'Update Database', 'status' => 'success', 'time' => $executionTime];
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            $this->error("   ❌ Database update failed: " . $exception->getMessage());
            $steps[] = ['step' => 'Update Database', 'status' => 'failed', 'time' => '0s'];
            $hasErrors = true;
        }
        $this->newLine();

        $this->info('🌐 Step 5/6: Seeding missing translation keys...');
        $this->line('   Seeder: MissingTranslateTabsSeeder');
        $this->newLine();

        try {
            $startTime = microtime(true);
            $exitCode = $this->call('db:seed', ['--class' => 'MissingTranslateTabsSeeder']);
            $endTime = microtime(true);
            $executionTime = $this->formatTime($endTime - $startTime);

            if ($exitCode === 0) {
                $this->info("   ✅ Missing keys seeded in {$executionTime}");
                $steps[] = ['step' => 'Seed Missing Keys', 'status' => 'success', 'time' => $executionTime];
            } else {
                $this->error("   ❌ Seeding failed");
                $steps[] = ['step' => 'Seed Missing Keys', 'status' => 'failed', 'time' => $executionTime];
                $hasErrors = true;
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            $this->error("   ❌ Seeding failed: " . $exception->getMessage());
            $steps[] = ['step' => 'Seed Missing Keys', 'status' => 'failed', 'time' => '0s'];
            $hasErrors = true;
        }
        $this->newLine();

        // Step 6: Export database to files
        $this->info('📤 Step 6/6: Exporting database translations to files...');
        $this->line('   Job: TranslationDatabaseToFiles');
        $this->newLine();

        try {
            $startTime = microtime(true);
            $job = new TranslationDatabaseToFiles();
            $job->handle();
            $endTime = microtime(true);
            $executionTime = $this->formatTime($endTime - $startTime);

            Log::info(TranslationDatabaseToFiles::class . self::SEPARATION . $executionTime);

            $this->info("   ✅ Export to files completed in {$executionTime}");
            $steps[] = ['step' => 'Export to Files', 'status' => 'success', 'time' => $executionTime];
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            $this->error("   ❌ Export to files failed: " . $exception->getMessage());
            $steps[] = ['step' => 'Export to Files', 'status' => 'failed', 'time' => '0s'];
            $hasErrors = true;
        }
        $this->newLine();

        $endTimeTotal = microtime(true);
        $totalTime = $this->formatTime($endTimeTotal - $startTimeTotal);

        // Cleanup backup files
        $this->cleanupBackupFiles();

        $this->displaySummary($steps, $totalTime, $hasErrors);

        return $hasErrors ? self::FAILURE : self::SUCCESS;
    }

    /**
     * Delete all .backup files from resources/lang directory
     */
    protected function cleanupBackupFiles(): void
    {
        $this->newLine();
        $this->info('🧹 Cleaning up backup files...');

        $langPath = resource_path('lang');
        $backupFiles = glob($langPath . '/*.backup*');

        $deletedCount = 0;
        foreach ($backupFiles as $file) {
            if (file_exists($file) && is_file($file)) {
                unlink($file);
                $deletedCount++;
                $this->line("   Deleted: " . basename($file));
            }
        }

        if ($deletedCount > 0) {
            $this->info("   ✅ Deleted {$deletedCount} backup file(s)");
        } else {
            $this->line("   ℹ️  No backup files found");
        }
        $this->newLine();
    }

    protected function displaySummary(array $steps, string $totalTime, bool $hasErrors): void
    {
        $this->info('═══════════════════════════════════════════════════');
        $this->info('                   SUMMARY                         ');
        $this->info('═══════════════════════════════════════════════════');
        $this->newLine();

        $tableData = [];
        foreach ($steps as $step) {
            $statusIcon = $step['status'] === 'success' ? '✅' : '❌';
            $statusText = $step['status'] === 'success' ? '<fg=green>Success</>' : '<fg=red>Failed</>';

            $tableData[] = [
                $statusIcon,
                $step['step'],
                $statusText,
                $step['time']
            ];
        }

        $this->table(
            ['', 'Step', 'Status', 'Time'],
            $tableData
        );

        $this->newLine();
        $this->info("⏱️  Total execution time: {$totalTime}");
        $this->newLine();

        if ($hasErrors) {
            $this->error('⚠️  Some steps failed. Check the output above for details.');
        } else {
            $this->info('🎉 All translation synchronization steps completed successfully!');
        }

        $this->info('═══════════════════════════════════════════════════');
    }

    protected function formatTime(float $seconds): string
    {
        if ($seconds < 1) {
            return round($seconds * 1000) . 'ms';
        }
        return formatSolde($seconds, 3) . 's';
    }
}

