<?php

namespace Database\Seeders;

use App\Jobs\TranslationDatabaseToFiles;
use Core\Models\translatetabs;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class SyncTranslateTabSeeder extends Seeder
{

    public function run(): void
    {
        $viewsPath = base_path('resources/views');
        $usedKeys = $this->getUsedKeys($viewsPath);
        $tabKeys = translatetabs::pluck('name')->toArray();

        $missing = array_diff($usedKeys, $tabKeys);

        $added = 0;
        foreach ($missing as $key) {
            $re = translatetabs::create([
                'name' => $key,
                'value' => $key,
                'valueEn' => $key . ' EN',
                'valueFr' => $key . ' FR',
                'valueTr' => $key . ' TR',
                'valueEs' => $key . ' ES',
                'valueRu' => $key . ' RU',
                'valueDe' => $key . ' DE',
            ]);
            $added++;
            Log::info("Added to translatetab: $key :: " . $re);
        }
        $totalMissing = count($missing);
        $this->command->info("Total missing keys: $totalMissing");
        $this->command->info("Total added to translatetab: $added");
        if ($added === 0) {
            $this->command->info('No new keys were added to translatetab.');
        } else {
            $this->command->info("$added missing translation keys added to translatetab.");

            $start_time = microtime(true);
            $job = new TranslationDatabaseToFiles();
            $job->handle();
            $end_time = microtime(true);
            $execution_time = formatSolde(($end_time - $start_time), 3);
            $this->command->info(TranslationDatabaseToFiles::class .' : ' . $execution_time);
        }
    }

    private function getUsedKeys($viewsPath): array
    {
        $usedKeys = [];
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($viewsPath));
        foreach ($iterator as $file) {
            if ($file->isFile() && preg_match('/\.blade\.php$/', $file->getFilename())) {
                $content = file_get_contents($file->getPathname());
                if (preg_match_all('/__\(["\']([^"\')]+)["\']\)/', $content, $matches)) {
                    foreach ($matches[1] as $key) {
                        $usedKeys[$key] = true;
                    }
                }
            }
        }
        return array_keys($usedKeys);
    }
}
