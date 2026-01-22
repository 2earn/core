<?php

namespace App\Console\Commands;

use App\Services\Translation\TranslationMergeService;
use Illuminate\Console\Command;

class MergeEnTranslations extends Command
{
    protected $signature = 'translate:merge-en {source? : Path to the source JSON file to merge}';

    protected $description = 'Merge English translations from a source JSON file into resources/lang/en.json';

    protected TranslationMergeService $translationService;

    public function __construct(TranslationMergeService $translationService)
    {
        parent::__construct();
        $this->translationService = $translationService;
    }

    public function handle(): int
    {
        $languageCode = 'en';
        $languageName = $this->translationService->getLanguageName($languageCode);

        $this->info("Starting {$languageName} translation merge...");

        $sourcePath = $this->argument('source')
            ?? $this->translationService->getDefaultSourcePath($languageCode);

        if (!file_exists($sourcePath)) {
            $this->error("Source file not found: {$sourcePath}");
            if (!$this->argument('source')) {
                $this->warn("Tip: Default source path is '{$sourcePath}'");
                $this->warn("You can specify a different path: php artisan translate:merge-en path/to/file.json");
            }
            return self::FAILURE;
        }

        $result = $this->translationService->mergeTranslations($sourcePath, $languageCode);

        if (!$result['success']) {
            $this->error("Error: {$result['message']}");
            return self::FAILURE;
        }

        if (isset($result['backupPath']) && $result['backupPath']) {
            $this->info("Backup created: {$result['backupPath']}");
        }

        $this->info("Current translations loaded: {$result['currentCount']}");
        $this->info("New translations loaded: {$result['newCount']}");
        $this->info("After merge: {$result['mergedCount']} translations");

        $this->newLine();
        $this->info('✓ Translation merge completed successfully!');
        $this->info("✓ Total translations: {$result['mergedCount']}");
        $this->info("✓ File updated: {$result['targetPath']}");
        if (isset($result['backupPath']) && $result['backupPath']) {
            $this->info('✓ Backup saved: ' . basename($result['backupPath']));
        }

        $this->newLine();
        $this->line('Sample translations:');
        foreach ($result['sample'] as $key => $value) {
            $this->line("  '{$key}' => '{$value}'");
        }

        return self::SUCCESS;
    }
}

