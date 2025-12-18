<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FillOrderPlatformIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Fills the platform_id field in orders table based on the platform_id
     * of the deals associated with the items in the order.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info("Starting to fill platform_id for orders...");

        // Get all orders that don't have a platform_id set
        $ordersWithoutPlatform = Order::whereNull('platform_id')
            ->orWhere('platform_id', 0)
            ->with(['OrderDetails.item.deal'])
            ->get();

        if ($ordersWithoutPlatform->isEmpty()) {
            $this->command->info("No orders found without platform_id. All orders are already configured.");
            return;
        }

        $this->command->info("Found {$ordersWithoutPlatform->count()} orders without platform_id");

        $updatedCount = 0;
        $skippedCount = 0;
        $errors = [];

        foreach ($ordersWithoutPlatform as $order) {
            try {
                // Get the first order detail with a valid item and deal
                $orderDetail = $order->OrderDetails()
                    ->with(['item.deal'])
                    ->whereHas('item.deal', function ($query) {
                        $query->whereNotNull('platform_id');
                    })
                    ->first();

                if (!$orderDetail) {
                    $skippedCount++;
                    $errors[] = "Order ID {$order->id}: No order details with valid items/deals found";
                    continue;
                }

                $item = $orderDetail->item;
                if (!$item || !$item->deal) {
                    $skippedCount++;
                    $errors[] = "Order ID {$order->id}: Item or deal not found";
                    continue;
                }

                $platformId = $item->deal->platform_id;

                if (!$platformId) {
                    $skippedCount++;
                    $errors[] = "Order ID {$order->id}: Deal has no platform_id";
                    continue;
                }

                // Verify all items in the order belong to the same platform
                $allItemsFromSamePlatform = true;
                $platformIds = [];

                foreach ($order->OrderDetails as $detail) {
                    if ($detail->item && $detail->item->deal && $detail->item->deal->platform_id) {
                        $platformIds[] = $detail->item->deal->platform_id;
                    }
                }

                $uniquePlatformIds = array_unique($platformIds);

                if (count($uniquePlatformIds) > 1) {
                    $this->command->warn("Order ID {$order->id}: Contains items from multiple platforms: " . implode(', ', $uniquePlatformIds));
                    // Use the most common platform_id
                    $platformId = array_count_values($platformIds);
                    arsort($platformId);
                    $platformId = array_key_first($platformId);
                }

                // Update the order
                $order->platform_id = $platformId;
                $order->save();

                $updatedCount++;

                if ($updatedCount % 50 == 0) {
                    $this->command->info("Progress: {$updatedCount} orders updated...");
                }

            } catch (\Exception $e) {
                $skippedCount++;
                $errors[] = "Order ID {$order->id}: {$e->getMessage()}";
            }
        }

        $this->command->info("=== Summary ===");
        $this->command->info("Successfully updated: {$updatedCount} orders");

        if ($skippedCount > 0) {
            $this->command->warn("Skipped: {$skippedCount} orders");
        }

        if (!empty($errors)) {
            $this->command->warn("\nErrors/Warnings:");
            foreach ($errors as $error) {
                $this->command->warn("  - {$error}");
            }
        }

        // Show platform distribution
        $this->command->info("\n=== Platform Distribution ===");
        $distribution = Order::whereNotNull('platform_id')
            ->select('platform_id', DB::raw('count(*) as count'))
            ->groupBy('platform_id')
            ->orderBy('count', 'desc')
            ->get();

        foreach ($distribution as $item) {
            $this->command->info("Platform ID {$item->platform_id}: {$item->count} orders");
        }
    }
}

