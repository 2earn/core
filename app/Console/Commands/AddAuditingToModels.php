<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class AddAuditingToModels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auditing:add-trait {--dry-run : Show what would be changed without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add HasAuditing trait to all models that extend Illuminate\Database\Eloquent\Model';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $modelsPath = app_path('Models');

        if (!File::isDirectory($modelsPath)) {
            $this->error("Models directory not found at: {$modelsPath}");
            return 1;
        }

        $files = File::allFiles($modelsPath);
        $updated = 0;
        $skipped = 0;
        $errors = 0;

        $this->info("Scanning models in: {$modelsPath}");
        $this->newLine();

        foreach ($files as $file) {
            $content = File::get($file->getPathname());

            // Skip if not a PHP file
            if ($file->getExtension() !== 'php') {
                continue;
            }

            // Skip if already has HasAuditing trait
            if (str_contains($content, 'use HasAuditing') || str_contains($content, 'HasAuditing;')) {
                $this->line("⏭️  Skipping {$file->getFilename()} - Already has HasAuditing trait");
                $skipped++;
                continue;
            }

            // Check if it extends Model
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
        $this->info("Summary:");
        $this->info("  Updated: {$updated}");
        $this->info("  Skipped: {$skipped}");

        if ($errors > 0) {
            $this->error("  Errors: {$errors}");
        }

        if ($dryRun) {
            $this->newLine();
            $this->warn("This was a dry run. No files were modified.");
            $this->info("Run without --dry-run to apply changes.");
        }

        return 0;
    }

    /**
     * Add HasAuditing trait to the model content
     */
    protected function addAuditingTrait(string $content): string
    {
        // Add use statement at the top if not already present
        if (!str_contains($content, "use App\Traits\HasAuditing;")) {
            // Find the namespace and add the use statement
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

        // Add trait usage inside the class if not already present
        if (!preg_match('/use\s+[^;]*HasAuditing/', $content)) {
            // Pattern 1: Add to existing use statement with HasFactory
            if (preg_match('/(class\s+\w+[^{]*\{[^}]*?use\s+HasFactory)(;)/s', $content)) {
                $content = preg_replace(
                    '/(class\s+\w+[^{]*\{[^}]*?use\s+HasFactory)(;)/s',
                    '$1, HasAuditing$2',
                    $content,
                    1
                );
            }
            // Pattern 2: Add to use statement with multiple traits
            else if (preg_match('/(class\s+\w+[^{]*\{[^}]*?use\s+[^;]+)(;)/s', $content)) {
                $content = preg_replace(
                    '/(class\s+\w+[^{]*\{[^}]*?use\s+[^;]+)(;)/s',
                    '$1, HasAuditing$2',
                    $content,
                    1
                );
            }
            // Pattern 3: No existing use statement, add new one
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

