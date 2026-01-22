<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class AddAuditingFieldsToFillable extends Command
{
    protected $signature = 'auditing:add-fillable {--dry-run : Show what would be changed without making changes}';
    protected $description = 'Add created_by and updated_by to $fillable array in all models with HasAuditing trait';

    public function handle()
    {
        $dryRun = $this->option('dry-run');

        $directories = [
            'App\Models' => app_path('Models'),
        ];

        $totalUpdated = 0;
        $totalSkipped = 0;
        $totalErrors = 0;

        foreach ($directories as $namespace => $path) {
            if (!File::isDirectory($path)) {
                $this->warn("⚠️  Directory not found: {$path}");
                continue;
            }

            $this->info("Scanning models in: {$namespace} ({$path})");
            $this->newLine();

            $files = File::allFiles($path);
            $updated = 0;
            $skipped = 0;
            $errors = 0;

            foreach ($files as $file) {
                $content = File::get($file->getPathname());

                if ($file->getExtension() !== 'php') {
                    continue;
                }

                if (!str_contains($content, 'use HasAuditing') && !str_contains($content, 'HasAuditing;')) {
                    $skipped++;
                    continue;
                }

                if (str_contains($content, "'created_by'") && str_contains($content, "'updated_by'")) {
                    $this->line("⏭️  Skipping {$file->getFilename()} - Already has auditing fields in fillable");
                    $skipped++;
                    continue;
                }

                try {
                    $newContent = $this->addAuditingFieldsToFillable($content, $file->getFilename());

                    if ($newContent !== $content) {
                        if (!$dryRun) {
                            File::put($file->getPathname(), $newContent);
                            $this->info("✅ Updated {$file->getFilename()}");
                        } else {
                            $this->info("🔍 Would update {$file->getFilename()}");
                        }
                        $updated++;
                    } else {
                        $this->line("⏭️  Skipping {$file->getFilename()} - No fillable array found or no changes needed");
                        $skipped++;
                    }
                } catch (\Exception $e) {
                    $this->error("❌ Error processing {$file->getFilename()}: {$e->getMessage()}");
                    $errors++;
                }
            }

            $this->newLine();
            $this->info("{$namespace} Summary:");
            $this->line("  Updated: {$updated}");
            $this->line("  Skipped: {$skipped}");
            if ($errors > 0) {
                $this->line("  Errors: {$errors}");
            }
            $this->newLine();

            $totalUpdated += $updated;
            $totalSkipped += $skipped;
            $totalErrors += $errors;
        }

        $this->info("=== Overall Summary ===");
        $this->info("  Total Updated: {$totalUpdated}");
        $this->info("  Total Skipped: {$totalSkipped}");

        if ($totalErrors > 0) {
            $this->error("  Total Errors: {$totalErrors}");
        }

        if ($dryRun) {
            $this->newLine();
            $this->warn("This was a dry run. No files were modified.");
            $this->info("Run without --dry-run to apply changes.");
        }

        return 0;
    }

    protected function addAuditingFieldsToFillable(string $content, string $filename): string
    {

        $pattern = '/(protected\s+\$fillable\s*=\s*\[)(.*?)(\];)/s';

        if (!preg_match($pattern, $content, $matches)) {
            return $content;
        }

        $fillableContent = $matches[2];

        if (str_contains($fillableContent, "'created_by'") || str_contains($fillableContent, '"created_by"')) {
            return $content;
        }

        $newFillableContent = rtrim($fillableContent);

        $newFillableContent = rtrim($newFillableContent, ",\n\r\t ");

        $newFillableContent .= ",\n        'created_by',\n        'updated_by',\n    ";

        $newContent = str_replace(
            $matches[1] . $matches[2] . $matches[3],
            $matches[1] . $newFillableContent . $matches[3],
            $content
        );

        return $newContent;
    }
}

