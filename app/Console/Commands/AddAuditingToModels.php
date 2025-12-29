<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class AddAuditingToModels extends Command
{
    
    protected $signature = 'auditing:add-trait {--dry-run : Show what would be changed without making changes}';

    protected $description = 'Add HasAuditing trait to all models that extend Illuminate\Database\Eloquent\Model';

    public function handle()
    {
        $dryRun = $this->option('dry-run');

        $directories = [
            'App\Models' => app_path('Models'),
            'Core\Models' => base_path('Core/Models'),
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

                if (str_contains($content, 'use HasAuditing') || str_contains($content, 'HasAuditing;')) {
                    $this->line("⏭️  Skipping {$file->getFilename()} - Already has HasAuditing trait");
                    $skipped++;
                    continue;
                }

                if (!str_contains($content, 'extends Model') &&
                    !str_contains($content, 'extends Authenticatable')) {
                    $this->line("⏭️  Skipping {$file->getFilename()} - Does not extend Model");
                    $skipped++;
                    continue;
                }

                try {
                    $newContent = $this->addAuditingTrait($content);

                    if ($newContent !== $content) {
                        if (!$dryRun) {
                            File::put($file->getPathname(), $newContent);
                            $this->info("✅ Updated {$file->getFilename()}");
                        } else {
                            $this->info("🔍 Would update {$file->getFilename()}");
                        }
                        $updated++;
                    } else {
                        $this->line("⏭️  Skipping {$file->getFilename()} - No changes needed");
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

    protected function addAuditingTrait(string $content): string
    {
        
        if (!str_contains($content, "use App\Traits\HasAuditing;")) {
            
            $pattern = '/(namespace\s+[^;]+;)/';
            if (preg_match($pattern, $content)) {
                $content = preg_replace(
                    $pattern,
                    "$1\n\nuse App\Traits\HasAuditing;",
                    $content,
                    1
                );
            }
        }

        if (!preg_match('/use\s+[^;]*HasAuditing/', $content)) {
            
            if (preg_match('/(class\s+\w+[^{]*\{[^}]*?use\s+HasFactory)(;)/s', $content)) {
                $content = preg_replace(
                    '/(class\s+\w+[^{]*\{[^}]*?use\s+HasFactory)(;)/s',
                    '$1, HasAuditing$2',
                    $content,
                    1
                );
            }
            
            else if (preg_match('/(class\s+\w+[^{]*\{[^}]*?use\s+[^;]+)(;)/s', $content)) {
                $content = preg_replace(
                    '/(class\s+\w+[^{]*\{[^}]*?use\s+[^;]+)(;)/s',
                    '$1, HasAuditing$2',
                    $content,
                    1
                );
            }
            
            else if (preg_match('/(class\s+\w+[^{]*\{)/', $content)) {
                $content = preg_replace(
                    '/(class\s+\w+[^{]*\{)/',
                    "$1\n    use HasAuditing;\n",
                    $content,
                    1
                );
            }
        }

        return $content;
    }
}

