<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CleanUnusedTranslations extends Command
{
    
    protected $signature = 'translate:clean-unused
                            {--dry-run : Show what would be removed without actually removing}
                            {--lang= : Specific language to clean (ar, fr, en, etc.)}
                            {--backup : Create backup before cleaning}';

    protected $description = 'Remove unused translation keys from language files';

    protected array $scanDirectories = [
        'resources/views',
        'app/Livewire',
        'app/Http/Controllers',
        'app',
    ];

    protected array $patterns = [
        '/__\([\'"]([^\'"]+)[\'"]\)/',           
        '/trans\([\'"]([^\'"]+)[\'"]\)/',        
        '/@lang\([\'"]([^\'"]+)[\'"]\)/',        
        '/\{\{\s*__\([\'"]([^\'"]+)[\'"]\)/',   
        '/\{\{\s*trans\([\'"]([^\'"]+)[\'"]\)/', 
    ];

    public function handle(): int
    {
        $this->info('🧹 Starting cleanup of unused translation keys...');
        $this->newLine();

        $this->info('📂 Scanning codebase for translation usage...');
        $usedKeys = $this->findUsedTranslationKeys();
        $this->info("✅ Found " . count($usedKeys) . " unique translation keys in use");
        $this->newLine();

        $languages = $this->getLanguagesToProcess();

        if (empty($languages)) {
            $this->error('❌ No language files found to process');
            return self::FAILURE;
        }

        $totalRemoved = 0;
        $results = [];

        foreach ($languages as $lang => $filePath) {
            $this->line("🔍 Processing {$lang}.json...");

            $result = $this->cleanLanguageFile($filePath, $lang, $usedKeys);
            $results[$lang] = $result;
            $totalRemoved += $result['removed'];

            $this->info("   ✓ {$result['removed']} unused keys found");
        }

        $this->newLine();
        $this->displaySummary($results, $usedKeys, $totalRemoved);

        return self::SUCCESS;
    }

    protected function findUsedTranslationKeys(): array
    {
        $usedKeys = [];
        $progressBar = $this->output->createProgressBar();

        foreach ($this->scanDirectories as $directory) {
            $path = base_path($directory);

            if (!File::exists($path)) {
                continue;
            }

            $files = File::allFiles($path);
            $progressBar->setMaxSteps(count($files));

            foreach ($files as $file) {
                $content = File::get($file->getPathname());

                foreach ($this->patterns as $pattern) {
                    if (preg_match_all($pattern, $content, $matches)) {
                        foreach ($matches[1] as $key) {
                            $usedKeys[$key] = true;
                        }
                    }
                }

                $progressBar->advance();
            }
        }

        $progressBar->finish();
        $this->newLine();

        return array_keys($usedKeys);
    }

    protected function getLanguagesToProcess(): array
    {
        $langPath = resource_path('lang');
        $languages = [];

        $specificLang = $this->option('lang');

        if ($specificLang) {
            $filePath = "{$langPath}/{$specificLang}.json";
            if (File::exists($filePath)) {
                $languages[$specificLang] = $filePath;
            } else {
                $this->warn("⚠️  Language file not found: {$specificLang}.json");
            }
        } else {
            
            $files = File::glob("{$langPath}/*.json");
            foreach ($files as $file) {
                $lang = pathinfo($file, PATHINFO_FILENAME);
                $languages[$lang] = $file;
            }
        }

        return $languages;
    }

    protected function cleanLanguageFile(string $filePath, string $lang, array $usedKeys): array
    {
        
        $content = File::get($filePath);
        $translations = json_decode($content, true);

        if (!is_array($translations)) {
            return [
                'total' => 0,
                'removed' => 0,
                'kept' => 0,
                'unusedKeys' => []
            ];
        }

        $totalKeys = count($translations);
        $unusedKeys = [];
        $cleanedTranslations = [];

        foreach ($translations as $key => $value) {
            if (in_array($key, $usedKeys)) {
                $cleanedTranslations[$key] = $value;
            } else {
                $unusedKeys[] = $key;
            }
        }

        $removedCount = count($unusedKeys);
        $keptCount = count($cleanedTranslations);

        if ($this->option('backup') && $removedCount > 0 && !$this->option('dry-run')) {
            $this->createBackup($filePath);
        }

        if (!$this->option('dry-run') && $removedCount > 0) {
            ksort($cleanedTranslations);
            $json = json_encode($cleanedTranslations, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
            File::put($filePath, $json);
        }

        return [
            'total' => $totalKeys,
            'removed' => $removedCount,
            'kept' => $keptCount,
            'unusedKeys' => $unusedKeys
        ];
    }

    protected function createBackup(string $filePath): void
    {
        $backupPath = $filePath . '.backup_' . date('YmdHis');
        File::copy($filePath, $backupPath);
        $this->info("   💾 Backup created: " . basename($backupPath));
    }

    protected function displaySummary(array $results, array $usedKeys, int $totalRemoved): void
    {
        $this->info('═══════════════════════════════════════════════════');
        $this->info('           CLEANUP SUMMARY                        ');
        $this->info('═══════════════════════════════════════════════════');
        $this->newLine();

        $tableData = [];
        foreach ($results as $lang => $result) {
            $percentage = $result['total'] > 0
                ? round(($result['removed'] / $result['total']) * 100, 1)
                : 0;

            $tableData[] = [
                strtoupper($lang),
                $result['total'],
                $result['kept'],
                $result['removed'],
                "{$percentage}%"
            ];
        }

        $this->table(
            ['Language', 'Total Keys', 'Kept', 'Removed', '% Removed'],
            $tableData
        );

        $this->newLine();
        $this->info("📊 Keys in use across codebase: " . count($usedKeys));
        $this->info("🗑️  Total unused keys found: {$totalRemoved}");

        if ($this->option('dry-run')) {
            $this->newLine();
            $this->warn('🔍 DRY RUN MODE - No files were modified');
            $this->info('Run without --dry-run to actually remove unused keys');
        } else if ($totalRemoved > 0) {
            $this->newLine();
            $this->info('✅ Cleanup completed successfully!');
            if ($this->option('backup')) {
                $this->info('💾 Backups were created for all modified files');
            }
        } else {
            $this->newLine();
            $this->info('✨ No unused keys found - all translations are in use!');
        }

        if ($this->option('dry-run') && $totalRemoved > 0) {
            $this->newLine();
            $this->info('Sample of unused keys (first 10):');
            $sampleCount = 0;
            foreach ($results as $lang => $result) {
                if ($sampleCount >= 10) break;
                foreach (array_slice($result['unusedKeys'], 0, 10 - $sampleCount) as $key) {
                    $this->line("  • {$key}");
                    $sampleCount++;
                    if ($sampleCount >= 10) break;
                }
            }
        }

        $this->info('═══════════════════════════════════════════════════');
    }
}

