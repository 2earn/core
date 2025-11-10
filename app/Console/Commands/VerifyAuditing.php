<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class VerifyAuditing extends Command
{
    protected $signature = 'auditing:verify';
    protected $description = 'Verify that the auditing system is properly configured';

    public function handle()
    {
        $this->info('=== Verifying Auditing System ===');
        $this->newLine();

        // Test 1: Check trait
        $this->info('Test 1: Checking if HasAuditing trait exists');
        if (trait_exists(\App\Traits\HasAuditing::class)) {
            $this->line('✅ HasAuditing trait found');
        } else {
            $this->error('❌ HasAuditing trait not found');
        }
        $this->newLine();

        // Test 2: Check a few models
        $this->info('Test 2: Checking models for HasAuditing trait');
        $modelsToCheck = ['Deal', 'Order', 'Item', 'News', 'Survey', 'Event', 'User'];
        foreach ($modelsToCheck as $model) {
            $className = "App\\Models\\{$model}";
            if (class_exists($className)) {
                $reflection = new \ReflectionClass($className);
                $traits = $reflection->getTraitNames();
                if (in_array('App\Traits\HasAuditing', $traits)) {
                    $this->line("  ✅ {$model} - Has trait");
                } else {
                    $this->line("  ❌ {$model} - Missing trait");
                }
            }
        }
        $this->newLine();

        // Test 3: Check database columns
        $this->info('Test 3: Checking database columns');
        $tablesToCheck = ['deals', 'orders', 'items', 'news', 'surveys', 'events'];
        foreach ($tablesToCheck as $table) {
            if (Schema::hasTable($table)) {
                $hasCreatedBy = Schema::hasColumn($table, 'created_by');
                $hasUpdatedBy = Schema::hasColumn($table, 'updated_by');

                if ($hasCreatedBy && $hasUpdatedBy) {
                    $this->line("  ✅ {$table} - Has both columns");
                } else {
                    $missing = [];
                    if (!$hasCreatedBy) $missing[] = 'created_by';
                    if (!$hasUpdatedBy) $missing[] = 'updated_by';
                    $this->line("  ❌ {$table} - Missing: " . implode(', ', $missing));
                }
            } else {
                $this->line("  ⚠️  {$table} - Table not found");
            }
        }
        $this->newLine();

        // Test 4: Count all models
        $this->info('Test 4: Scanning all models');
        $modelsPath = app_path('Models');
        $files = glob($modelsPath . '/*.php');
        $withTrait = 0;
        $withoutTrait = [];

        foreach ($files as $file) {
            $filename = basename($file, '.php');
            $className = "App\\Models\\{$filename}";

            try {
                if (class_exists($className)) {
                    $reflection = new \ReflectionClass($className);
                    $traits = $reflection->getTraitNames();

                    if (in_array('App\Traits\HasAuditing', $traits)) {
                        $withTrait++;
                    } else {
                        $withoutTrait[] = $filename;
                    }
                }
            } catch (\Exception $e) {
                // Skip
            }
        }

        $this->info("Models WITH HasAuditing trait: {$withTrait}");
        if (!empty($withoutTrait)) {
            $this->warn("Models WITHOUT trait (" . count($withoutTrait) . "): " . implode(', ', $withoutTrait));
        } else {
            $this->line('✅ All models have the trait!');
        }

        $this->newLine();
        $this->info('=== Verification Complete ===');
        $this->newLine();

        $this->comment('To test in practice:');
        $this->line('1. php artisan tinker');
        $this->line('2. Auth::loginUsingId(1)');
        $this->line('3. $deal = Deal::create(["name" => "Test", "description" => "Test"])');
        $this->line('4. $deal->created_by (should show user ID)');
        $this->line('5. $deal->creator->name (should show user name)');

        return 0;
    }
}

