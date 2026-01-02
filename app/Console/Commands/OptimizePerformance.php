<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class OptimizePerformance extends Command
{
    protected $signature = 'app:optimize-performance {--clear : Clear all optimizations instead}';

    protected $description = 'Run all performance optimization commands for the application';

    public function handle()
    {
        $clear = $this->option('clear');

        if ($clear) {
            $this->info('🧹 Clearing all optimizations...');
            $this->newLine();

            $this->clearOptimizations();

            $this->newLine();
            $this->info('✅ All optimizations cleared successfully!');
            $this->info('💡 Use this during development when you need to see config/route changes immediately.');

            return 0;
        }

        $this->info('🚀 Starting performance optimization...');
        $this->newLine();

        $startTime = microtime(true);
        $startMemory = memory_get_usage(true);

        // Step 1: Composer autoload optimization
        $this->step('Optimizing Composer autoloader', function () {
            $this->call('optimize');
            exec('cd ' . base_path() . ' && composer dump-autoload -o 2>&1', $output, $returnCode);
            return $returnCode === 0;
        });

        // Step 2: Config cache
        $this->step('Caching configuration files', function () {
            Artisan::call('config:cache');
            return true;
        });

        // Step 3: Route cache
        $this->step('Caching routes', function () {
            Artisan::call('route:cache');
            return true;
        });

        // Step 4: Event cache
        $this->step('Caching events', function () {
            Artisan::call('event:cache');
            return true;
        });

        // Step 5: View cache
        $this->step('Caching views', function () {
            Artisan::call('view:cache');
            return true;
        });

        $endTime = microtime(true);
        $endMemory = memory_get_usage(true);

        $this->newLine();
        $this->info('✅ Performance optimization complete!');
        $this->newLine();

        // Show statistics
        $this->showStats($startTime, $endTime, $startMemory, $endMemory);

        // Show recommendations
        $this->showRecommendations();

        return 0;
    }

    protected function clearOptimizations()
    {
        $this->step('Clearing config cache', function () {
            Artisan::call('config:clear');
            return true;
        });

        $this->step('Clearing route cache', function () {
            Artisan::call('route:clear');
            return true;
        });

        $this->step('Clearing event cache', function () {
            Artisan::call('event:clear');
            return true;
        });

        $this->step('Clearing view cache', function () {
            Artisan::call('view:clear');
            return true;
        });

        $this->step('Clearing all cache', function () {
            Artisan::call('cache:clear');
            return true;
        });
    }

    protected function step(string $message, callable $callback)
    {
        $this->line("⏳ {$message}...");

        try {
            $result = $callback();
            if ($result) {
                $this->line("   ✓ {$message} complete");
            } else {
                $this->warn("   ⚠ {$message} completed with warnings");
            }
        } catch (\Exception $e) {
            $this->error("   ✗ {$message} failed: {$e->getMessage()}");
        }
    }

    protected function showStats($startTime, $endTime, $startMemory, $endMemory)
    {
        $executionTime = round(($endTime - $startTime) * 1000, 2);
        $memoryUsed = round(($endMemory - $startMemory) / 1024 / 1024, 2);

        $this->table(
            ['Metric', 'Value'],
            [
                ['Execution Time', "{$executionTime}ms"],
                ['Memory Used', "{$memoryUsed} MB"],
                ['Peak Memory', round(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB'],
            ]
        );
    }

    protected function showRecommendations()
    {
        $this->newLine();
        $this->info('📊 Additional Recommendations:');
        $this->newLine();

        $recommendations = [
            '1. Enable OPcache in production (php.ini)',
            '2. Consider Laravel Octane for persistent workers',
            '3. Use Redis/Memcached for session and cache drivers',
            '4. Enable HTTP/2 on your web server',
            '5. Use a CDN for static assets',
            '6. Monitor with APM tools (New Relic, Datadog, etc.)',
        ];

        foreach ($recommendations as $recommendation) {
            $this->line("   💡 {$recommendation}");
        }

        $this->newLine();
        $this->comment('📖 See PERFORMANCE_OPTIMIZATION.md for detailed information');
    }
}

