<?php

namespace Database\Seeders;

use Core\Models\translatetabs;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MissingTranslateTabsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
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
    }
}

