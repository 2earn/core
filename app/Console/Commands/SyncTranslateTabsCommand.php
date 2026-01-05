<?php

namespace App\Console\Commands;

use App\Jobs\TranslationDatabaseToFiles;
use App\Models\translatetabs;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncTranslateTabsCommand extends Command
{
    protected $signature = 'translate:sync-tabs';

    protected $description = 'Scan Blade views for translation keys, sync them with translatetabs, and regenerate translation files.';

    public function handle(): int
    {
        $this->info('Scanning for translation keys...');

        $this->info('- Scanning Blade views...');
        $viewsPath = resource_path('views');
        $viewKeys = $this->getUsedKeysFromViews($viewsPath);
        $this->info("  Found {$viewKeys->count()} unique keys in views");

        $this->info('- Scanning Livewire components...');
        $livewirePath = app_path('Livewire');
        $livewireKeys = $this->getUsedKeysFromPhp($livewirePath);
        $this->info("  Found {$livewireKeys->count()} unique keys in Livewire");

        $this->info('- Scanning Controllers...');
        $controllersPath = app_path('Http/Controllers');
        $controllerKeys = $this->getUsedKeysFromPhp($controllersPath);
        $this->info("  Found {$controllerKeys->count()} unique keys in Controllers");

        $this->info('- Scanning Models...');
        $modelsPath = app_path('Models');
        $modelKeys = $this->getUsedKeysFromPhp($modelsPath);
        $this->info("  Found {$modelKeys->count()} unique keys in Models");

        $this->info('- Scanning Services...');
        $servicesPath = app_path('Services');
        $serviceKeys = $this->getUsedKeysFromPhp($servicesPath);
        $this->info("  Found {$serviceKeys->count()} unique keys in Services");

        $this->info('- Scanning Notifications...');
        $notificationsPath = app_path('Notifications');
        $notificationKeys = $this->getUsedKeysFromPhp($notificationsPath);
        $this->info("  Found {$notificationKeys->count()} unique keys in Notifications");

        $usedKeys = $viewKeys
            ->merge($livewireKeys)
            ->merge($controllerKeys)
            ->merge($modelKeys)
            ->merge($serviceKeys)
            ->merge($notificationKeys)
            ->unique()
            ->values()
            ->toArray();
        $this->info("Total unique translation keys found: " . count($usedKeys));

        $tabKeys = translatetabs::pluck('name')->toArray();

        $missing = array_diff($usedKeys, $tabKeys);

        $added = 0;
        foreach ($missing as $key) {
            $record = translatetabs::create([
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
            Log::info("Added to translatetab: {$key} :: {$record->id}");
        }

        $totalMissing = count($missing);
        $this->info("Total missing keys: {$totalMissing}");
        $this->info("Total added to translatetab: {$added}");

        if ($added === 0) {
            $this->info('No new keys were added to translatetab.');
        } else {
            $this->info("{$added} missing translation keys added to translatetab.");

            $startTime = microtime(true);
            $job = new TranslationDatabaseToFiles();
            $job->handle();
            $endTime = microtime(true);

            $executionTime = formatSolde(($endTime - $startTime), 3);
            $this->info(TranslationDatabaseToFiles::class . ' : ' . $executionTime);
        }

        return self::SUCCESS;
    }

    private function getUsedKeysFromViews(string $viewsPath): \Illuminate\Support\Collection
    {
        $usedKeys = collect();

        if (!is_dir($viewsPath)) {
            return $usedKeys;
        }

        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($viewsPath));
        foreach ($iterator as $file) {
            if ($file->isFile() && preg_match('/\\.blade\\.php$/', $file->getFilename())) {
                $content = file_get_contents($file->getPathname());
                if (preg_match_all('/__\(["\']([^"\')]+)["\']\)/', $content, $matches)) {
                    foreach ($matches[1] as $key) {
                        $usedKeys->push($key);
                    }
                }
            }
        }

        return $usedKeys;
    }

    private function getUsedKeysFromPhp(string $phpPath): \Illuminate\Support\Collection
    {
        $usedKeys = collect();

        if (!is_dir($phpPath)) {
            return $usedKeys;
        }

        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($phpPath));
        foreach ($iterator as $file) {
            if ($file->isFile() && preg_match('/\\.php$/', $file->getFilename())) {
                $content = file_get_contents($file->getPathname());

                if (preg_match_all('/__\(["\']([^"\')]+)["\']\)/', $content, $matches)) {
                    foreach ($matches[1] as $key) {
                        $usedKeys->push($key);
                    }
                }

                if (preg_match_all('/trans\(["\']([^"\')]+)["\']\)/', $content, $matches)) {
                    foreach ($matches[1] as $key) {
                        $usedKeys->push($key);
                    }
                }

                if (preg_match_all('/@lang\(["\']([^"\')]+)["\']\)/', $content, $matches)) {
                    foreach ($matches[1] as $key) {
                        $usedKeys->push($key);
                    }
                }

                if (preg_match_all('/Lang::get\(["\']([^"\')]+)["\']\)/', $content, $matches)) {
                    foreach ($matches[1] as $key) {
                        $usedKeys->push($key);
                    }
                }
            }
        }

        return $usedKeys;
    }
}

