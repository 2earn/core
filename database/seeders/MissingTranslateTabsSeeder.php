<?php

namespace Database\Seeders;

use App\Jobs\TranslationDatabaseToFiles;
use App\Models\translatetabs;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MissingTranslateTabsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param bool $exportToFiles Whether to export translations to files after seeding
     * @return void
     */
    public function run($exportToFiles = false)
    {
        $jsonPath = base_path('new trans/missing_translate_tabs.json');

        if (!file_exists($jsonPath)) {
            $this->command->error("Translation file not found at: {$jsonPath}");
            return;
        }

        $jsonContent = file_get_contents($jsonPath);
        $translations = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('Error parsing JSON file: ' . json_last_error_msg());
            return;
        }

        if (!is_array($translations)) {
            $this->command->error('Invalid translations format in JSON file.');
            return;
        }

        $this->command->info("Loading " . count($translations) . " translations from JSON file...");

        foreach ($translations as $translation) {
            translatetabs::updateOrCreate(
                ['name' => $translation['name']],
                $translation
            );
        }

        $this->command->info('Missing translation keys have been successfully seeded!');

        // Optionally export to files after seeding
        if ($exportToFiles) {
            $this->command->info('Exporting translations to files...');
            try {
                $startTime = microtime(true);
                $job = new TranslationDatabaseToFiles();
                $job->handle();
                $endTime = microtime(true);
                $executionTime = round($endTime - $startTime, 3);

                Log::info('TranslationDatabaseToFiles executed after MissingTranslateTabsSeeder : ' . $executionTime . 's');
                $this->command->info("Export to files completed in {$executionTime}s");
            } catch (\Exception $e) {
                Log::error('Failed to export translations to files: ' . $e->getMessage());
                $this->command->error('Failed to export translations to files: ' . $e->getMessage());
            }
        }
    }
}

