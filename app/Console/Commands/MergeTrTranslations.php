<?php
}
    }
        return self::SUCCESS;

        }
            $this->line("  '{$key}' => '{$value}'");
        foreach ($result['sample'] as $key => $value) {
        $this->line('Sample translations:');
        $this->newLine();

        }
            $this->info('✓ Backup saved: ' . basename($result['backupPath']));
        if (isset($result['backupPath']) && $result['backupPath']) {
        $this->info("✓ File updated: {$result['targetPath']}");
        $this->info("✓ Total translations: {$result['mergedCount']}");
        $this->info('✓ Translation merge completed successfully!');
        $this->newLine();

        $this->info("After merge: {$result['mergedCount']} translations");
        $this->info("New translations loaded: {$result['newCount']}");
        $this->info("Current translations loaded: {$result['currentCount']}");

        }
            $this->info("Backup created: {$result['backupPath']}");
        if (isset($result['backupPath']) && $result['backupPath']) {

        }
            return self::FAILURE;
            $this->error("Error: {$result['message']}");
        if (!$result['success']) {

        $result = $this->translationService->mergeTranslations($sourcePath, $languageCode);

        }
            return self::FAILURE;
            }
                $this->warn("You can specify a different path: php artisan translate:merge-tr path/to/file.json");
                $this->warn("Tip: Default source path is '{$sourcePath}'");
            if (!$this->argument('source')) {
            $this->error("Source file not found: {$sourcePath}");
        if (!file_exists($sourcePath)) {

            ?? $this->translationService->getDefaultSourcePath($languageCode);
        $sourcePath = $this->argument('source')

        $this->info("Starting {$languageName} translation merge...");

        $languageName = $this->translationService->getLanguageName($languageCode);
        $languageCode = 'tr';
    {
    public function handle(): int

    }
        $this->translationService = $translationService;
        parent::__construct();
    {
    public function __construct(TranslationMergeService $translationService)

    protected TranslationMergeService $translationService;

    protected $description = 'Merge Turkish translations from a source JSON file into resources/lang/tr.json';

    protected $signature = 'translate:merge-tr {source? : Path to the source JSON file to merge}';
{
class MergeTrTranslations extends Command

use Illuminate\Console\Command;
use App\Services\Translation\TranslationMergeService;

namespace App\Console\Commands;


