<?php

namespace Database\Seeders;

use App\Models\CommissionBreakDown;
use App\Models\Order;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FillCommissionBreakdownPlatformIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Fills the platform_id field in commission_break_downs table based on
     * the platform_id of the associated order.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info("Starting to fill platform_id for commission breakdowns...");

        // Get all commission breakdowns that don't have a platform_id set
        $breakdownsWithoutPlatform = CommissionBreakDown::whereNull('platform_id')
            ->orWhere('platform_id', 0)
            ->with('order')
            ->get();

        if ($breakdownsWithoutPlatform->isEmpty()) {
            $this->command->info("No commission breakdowns found without platform_id. All records are already configured.");
            return;
        }

        $this->command->info("Found {$breakdownsWithoutPlatform->count()} commission breakdowns without platform_id");

        $updatedCount = 0;
        $skippedCount = 0;
        $errors = [];

        foreach ($breakdownsWithoutPlatform as $breakdown) {
            try {
                // Get the order
                $order = $breakdown->order;

                if (!$order) {
                    $skippedCount++;
                    $errors[] = "Commission Breakdown ID {$breakdown->id}: Order not found (order_id: {$breakdown->order_id})";
                    continue;
                }

                $platformId = $order->platform_id;

                if (!$platformId) {
                    $skippedCount++;
                    $errors[] = "Commission Breakdown ID {$breakdown->id}: Order {$order->id} has no platform_id";
                    continue;
                }

                // Update the commission breakdown
                $breakdown->platform_id = $platformId;
                $breakdown->save();

                $updatedCount++;

                if ($updatedCount % 100 == 0) {
                    $this->command->info("Progress: {$updatedCount} commission breakdowns updated...");
                }

            } catch (\Exception $e) {
                $skippedCount++;
                $errors[] = "Commission Breakdown ID {$breakdown->id}: {$e->getMessage()}";
            }
        }

        $this->command->info("\n=== Summary ===");
        $this->command->info("Successfully updated: {$updatedCount} commission breakdowns");

        if ($skippedCount > 0) {
            $this->command->warn("Skipped: {$skippedCount} commission breakdowns");
        }

        if (!empty($errors)) {
            $this->command->warn("\nErrors/Warnings:");
            $maxErrorsToShow = 20;
            $errorCount = count($errors);

            foreach (array_slice($errors, 0, $maxErrorsToShow) as $error) {
                $this->command->warn("  - {$error}");
            }

            if ($errorCount > $maxErrorsToShow) {
                $this->command->warn("  ... and " . ($errorCount - $maxErrorsToShow) . " more errors");
            }
        }

        // Show platform distribution
        $this->command->info("\n=== Platform Distribution ===");
        $distribution = CommissionBreakDown::whereNotNull('platform_id')
            ->select('platform_id', DB::raw('count(*) as count'))
            ->groupBy('platform_id')
            ->orderBy('count', 'desc')
            ->get();

        foreach ($distribution as $item) {
            $this->command->info("Platform ID {$item->platform_id}: {$item->count} commission breakdowns");
        }

        // Cross-check with orders
        $this->command->info("\n=== Verification ===");
        $mismatchCount = CommissionBreakDown::join('orders', 'commission_break_downs.order_id', '=', 'orders.id')
            ->whereColumn('commission_break_downs.platform_id', '!=', 'orders.platform_id')
            ->whereNotNull('commission_break_downs.platform_id')
            ->whereNotNull('orders.platform_id')
            ->count();

        if ($mismatchCount > 0) {
            $this->command->error("Warning: Found {$mismatchCount} commission breakdowns with platform_id different from their order's platform_id");
        } else {
            $this->command->info("✓ All commission breakdowns have matching platform_id with their orders");
        }
    }
}

