<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class PrepareLocal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // Command renamed to target preparing a Version 7 environment
    protected $signature = 'app:prepare-v7';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prepare version 7 environment (migrate, seed, translations, and caches).';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Running migrations...');
        Artisan::call('migrate', ['--force' => true]);
        $this->line(Artisan::output());

        $this->info('Seeding database...');
        Artisan::call('db:seed', ['--force' => true]);
        $this->line(Artisan::output());

        $this->info('Running extra seeders...');
        // Run additional (potentially destructive) seeders only on non-production environments
        if (! $this->laravel->environment('production')) {
            $extra = [
                'OrderingSeeder',
                'OrdersTableSeeder',
                'OrdersTablePaymentSeeder',
            ];
            foreach ($extra as $seeder) {
                $this->info("Seeding: $seeder");
                Artisan::call('db:seed', ['--class' => $seeder, '--force' => true]);
                $this->line(Artisan::output());
            }
        } else {
            $this->info('Skipping extra seeders on production environment.');
        }

        $this->info('Syncing translations...');
        Artisan::call('translate:sync-all');
        $this->line(Artisan::output());

        $this->info('Updating translation models...');
        Artisan::call('translate:update-model');
        $this->line(Artisan::output());

        $this->info('Clearing caches...');
        Artisan::call('clear-caches');
        $this->line(Artisan::output());

        $this->info('prepare-local completed.');
        return 0;
    }
}
