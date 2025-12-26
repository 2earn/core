<?php

namespace App\Console\Commands;

use App\Services\Translation\TranslationMergeService;
use Illuminate\Console\Command;

class MergeDeTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translate:merge-de {source? : Path to the source JSON file to merge}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Merge German translations from a source JSON file into resources/lang/de.json';

    /**
     * Translation merge service.
     *
     * @var TranslationMergeService
     */
    protected TranslationMergeService $translationService;

    /**
     * Create a new command instance.
     */
    public function __construct(TranslationMergeService $translationService)
    {
        parent::__construct();
        $this->translationService = $translationService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $languageCode = 'de';
        $languageName = $this->translationService->getLanguageName($languageCode);

        $this->info("Starting {$languageName} translation merge...");

        // Get source file path or use default
        $sourcePath = $this->argument('source')
            ?? $this->translationService->getDefaultSourcePath($languageCode);

        if (!file_exists($sourcePath)) {
            $this->error("Source file not found: {$sourcePath}");
            if (!$this->argument('source')) {
                $this->warn("Tip: Default source path is '{$sourcePath}'");
                $this->warn("You can specify a different path: php artisan translate:merge-de path/to/file.json");
            }
            return self::FAILURE;
        }

        // Perform merge using service
        $result = $this->translationService->mergeTranslations($sourcePath, $languageCode);

        // Handle result
        if (!$result['success']) {
            $this->error("Error: {$result['message']}");
            return self::FAILURE;
        }

        // Display progress information
        if (isset($result['backupPath']) && $result['backupPath']) {
            $this->info("Backup created: {$result['backupPath']}");
        }

        $this->info("Current translations loaded: {$result['currentCount']}");
        $this->info("New translations loaded: {$result['newCount']}");
        $this->info("After merge: {$result['mergedCount']} translations");

        // Display success message
        $this->newLine();
        $this->info('✓ Translation merge completed successfully!');
        $this->info("✓ Total translations: {$result['mergedCount']}");
        $this->info("✓ File updated: {$result['targetPath']}");
        if (isset($result['backupPath']) && $result['backupPath']) {
            $this->info('✓ Backup saved: ' . basename($result['backupPath']));
        }

        // Show sample of updated translations
        $this->newLine();
        $this->line('Sample translations:');
        foreach ($result['sample'] as $key => $value) {
            $this->line("  '{$key}' => '{$value}'");
        }

        return self::SUCCESS;
    }
}

