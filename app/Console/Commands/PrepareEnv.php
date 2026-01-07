<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class PrepareEnv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prepare:env';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prepare local environment by running migrations, seeders and clearing caches';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting environment preparation...');
        $this->newLine();

        // Run migrations
        $this->info('Running migrations...');
        Artisan::call('migrate');
        $this->line(Artisan::output());

        // Run main seeder
        $this->info('Seeding database...');
        Artisan::call('db:seed');
        $this->line(Artisan::output());

        // For local envs - specific seeders
        $this->info('Seeding OrderingSeeder...');
        Artisan::call('db:seed', ['--class' => 'OrderingSeeder']);
        $this->line(Artisan::output());

        $this->info('Seeding OrdersTableSeeder...');
        Artisan::call('db:seed', ['--class' => 'OrdersTableSeeder']);
        $this->line(Artisan::output());

        $this->info('Seeding OrdersTablePaymentSeeder...');
        Artisan::call('db:seed', ['--class' => 'OrdersTablePaymentSeeder']);
        $this->line(Artisan::output());

        // Sync translations
        $this->info('Syncing translations...');
        Artisan::call('translate:sync-all');
        $this->line(Artisan::output());

        $this->info('Updating translation model...');
        Artisan::call('translate:update-model');
        $this->line(Artisan::output());

        // Clear caches
        $this->info('Clearing caches...');
        Artisan::call('clear-caches');
        $this->line(Artisan::output());

        $this->newLine();
        $this->info('Environment preparation completed successfully!');

        return Command::SUCCESS;
    }
}

