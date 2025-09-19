<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class FindUnusedBladeFilesSeeder extends Seeder
{
    public function run(): void
    {
        $bladeFiles = collect(File::allFiles(resource_path('views')))
            ->filter(fn($file) => $file->getExtension() === 'php' && str_ends_with($file->getFilename(), '.blade.php'))
            ->map(fn($file) => str_replace(['/', '\\'], '.',
                trim(str_replace(resource_path('views'), '', $file->getPathname()), '/\\')
            ))
            ->map(fn($path) => preg_replace('/\.blade\.php$/', '', $path))
            ->values();

        $usedViews = collect();

        $searchPaths = [
            base_path('routes'),
            app_path('Http/Controllers'),
        ];
        $pattern = '/view\s*\(\s*[\'\"]([^\'\"]+)[\'\"]/i';
        foreach ($searchPaths as $dir) {
            foreach (File::allFiles($dir) as $file) {
                $content = File::get($file->getPathname());
                if (preg_match_all($pattern, $content, $matches)) {
                    foreach ($matches[1] as $view) {
                        $usedViews->push($view);
                    }
                }
                if (preg_match_all("/Route::view\s*\(\s*[^,]+,\s*[\'\"]([^\'\"]+)[\'\"]/i", $content, $matches2)) {
                    foreach ($matches2[1] as $view) {
                        $usedViews->push($view);
                    }
                }
            }
        }
        $usedViews = $usedViews->unique()->values();

        $unused = $bladeFiles->filter(function ($blade) use ($usedViews) {
            return !$usedViews->contains(function ($used) use ($blade) {
                return strtolower($used) === strtolower($blade);
            });
        })->values();

        if ($unused->isEmpty()) {
            $this->command->info('No unused Blade files found.');
            Log::info('No unused Blade files found.');
        } else {
            $this->command->warn('Unused Blade files:');
            foreach ($unused as $file) {
                $this->command->line($file);
                Log::warning('Unused Blade file: ' . $file);
            }
            $this->command->info('Total unused Blade files: ' . $unused->count());
        }
    }
}
