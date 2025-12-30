<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ClearCachesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear-caches
                            {--config : Clear only configuration cache}
                            {--cache : Clear only application cache}
                            {--route : Clear only route cache}
                            {--view : Clear only compiled views}
                            {--autoload : Regenerate only autoloader}
                            {--all : Clear all caches (default)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all application caches (config, cache, routes, views) and regenerate autoloader';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('========================================');
        $this->info(' Service Provider Optimization');
        $this->info(' Cache Clear Command');
        $this->info('========================================');
        $this->newLine();

        // Determine what to clear
        $clearAll = !$this->option('config')
                    && !$this->option('cache')
                    && !$this->option('route')
                    && !$this->option('view')
                    && !$this->option('autoload');

        $cleared = [];
        $failed = [];

        // Clear configuration cache
        if ($clearAll || $this->option('config')) {
            if ($this->clearConfigCache()) {
                $cleared[] = 'Configuration cache';
            } else {
                $failed[] = 'Configuration cache';
            }
        }

        // Clear application cache
        if ($clearAll || $this->option('cache')) {
            if ($this->clearApplicationCache()) {
                $cleared[] = 'Application cache';
            } else {
                $failed[] = 'Application cache';
            }
        }

        // Clear route cache
        if ($clearAll || $this->option('route')) {
            if ($this->clearRouteCache()) {
                $cleared[] = 'Route cache';
            } else {
                $failed[] = 'Route cache';
            }
        }

        // Clear compiled views
        if ($clearAll || $this->option('view')) {
            if ($this->clearViewCache()) {
                $cleared[] = 'Compiled views';
            } else {
                $failed[] = 'Compiled views';
            }
        }

        // Regenerate autoloader
        if ($clearAll || $this->option('autoload')) {
            if ($this->regenerateAutoloader()) {
                $cleared[] = 'Autoloader';
            } else {
                $failed[] = 'Autoloader';
            }
        }

        // Display results
        $this->newLine();
        $this->info('========================================');

        if (empty($failed)) {
            $this->info(' ✓ ALL CACHES CLEARED SUCCESSFULLY!');
            $this->info('========================================');
            $this->newLine();

            foreach ($cleared as $item) {
                $this->line(" ✓ {$item} cleared");
            }

            $this->newLine();
            $this->info('The service provider optimization is now active.');
            $this->info('You can now test the /buy-action route.');

            return Command::SUCCESS;
        } else {
            $this->warn(' ⚠ COMPLETED WITH WARNINGS');
            $this->info('========================================');
            $this->newLine();

            if (!empty($cleared)) {
                $this->line('Cleared successfully:');
                foreach ($cleared as $item) {
                    $this->line(" ✓ {$item}");
                }
                $this->newLine();
            }

            if (!empty($failed)) {
                $this->line('Failed to clear:');
                foreach ($failed as $item) {
                    $this->error(" ✗ {$item}");
                }
            }

            return Command::FAILURE;
        }
    }

    /**
     * Clear configuration cache.
     *
     * @return bool
     */
    protected function clearConfigCache(): bool
    {
        $this->line('[1/5] Clearing configuration cache...');

        try {
            Artisan::call('config:clear');
            $this->info('✓ Configuration cache cleared');
            $this->newLine();
            return true;
        } catch (\Exception $e) {
            $this->error('✗ Failed to clear config cache: ' . $e->getMessage());
            $this->newLine();
            return false;
        }
    }

    /**
     * Clear application cache.
     *
     * @return bool
     */
    protected function clearApplicationCache(): bool
    {
        $this->line('[2/5] Clearing application cache...');

        try {
            Artisan::call('cache:clear');
            $this->info('✓ Application cache cleared');
            $this->newLine();
            return true;
        } catch (\Exception $e) {
            $this->error('✗ Failed to clear application cache: ' . $e->getMessage());
            $this->newLine();
            return false;
        }
    }

    /**
     * Clear route cache.
     *
     * @return bool
     */
    protected function clearRouteCache(): bool
    {
        $this->line('[3/5] Clearing route cache...');

        try {
            Artisan::call('route:clear');
            $this->info('✓ Route cache cleared');
            $this->newLine();
            return true;
        } catch (\Exception $e) {
            $this->error('✗ Failed to clear route cache: ' . $e->getMessage());
            $this->newLine();
            return false;
        }
    }

    /**
     * Clear compiled views.
     *
     * @return bool
     */
    protected function clearViewCache(): bool
    {
        $this->line('[4/5] Clearing compiled views...');

        try {
            Artisan::call('view:clear');
            $this->info('✓ Compiled views cleared');
            $this->newLine();
            return true;
        } catch (\Exception $e) {
            $this->error('✗ Failed to clear view cache: ' . $e->getMessage());
            $this->newLine();
            return false;
        }
    }

    /**
     * Regenerate composer autoloader.
     *
     * @return bool
     */
    protected function regenerateAutoloader(): bool
    {
        $this->line('[5/5] Regenerating autoloader...');

        try {
            $process = new \Symfony\Component\Process\Process(
                ['composer', 'dump-autoload'],
                base_path()
            );
            $process->setTimeout(120);
            $process->run();

            if ($process->isSuccessful()) {
                $this->info('✓ Autoloader regenerated');
                $this->newLine();
                return true;
            } else {
                $this->error('✗ Failed to regenerate autoloader: ' . $process->getErrorOutput());
                $this->newLine();
                return false;
            }
        } catch (\Exception $e) {
            $this->error('✗ Failed to regenerate autoloader: ' . $e->getMessage());
            $this->newLine();
            return false;
        }
    }
}

