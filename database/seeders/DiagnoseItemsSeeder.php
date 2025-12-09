<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class DiagnoseItemsSeeder extends Seeder
{
    /**
     * Diagnose items and deals setup
     *
     * @return void
     */
    public function run()
    {
        $this->command->info("=== DIAGNOSTIC REPORT ===\n");

        // Check total items
        $totalItems = Item::count();
        $this->command->info("Total items in database: {$totalItems}");

        // Check items excluding #0001
        $filteredItems = Item::where('ref', '!=', '#0001')->count();
        $this->command->info("Items excluding ref '#0001': {$filteredItems}");

        // Check items with deal_id
        $itemsWithDealId = Item::where('ref', '!=', '#0001')->whereNotNull('deal_id')->count();
        $this->command->info("Items with deal_id: {$itemsWithDealId}");

        // Get all items with deals loaded
        $allItems = Item::with('deal')->where('ref', '!=', '#0001')->get();

        // Check items that actually have a deal relationship loaded
        $itemsWithDealLoaded = $allItems->filter(function ($item) {
            return !is_null($item->deal);
        })->count();
        $this->command->info("Items with deal relationship loaded: {$itemsWithDealLoaded}");

        // Group by deal_id and show details
        $this->command->info("\n=== DEALS BREAKDOWN ===");

        $groupedByDeal = $allItems->filter(function ($item) {
            return !is_null($item->deal_id) && !is_null($item->deal);
        })->groupBy('deal_id');

        if ($groupedByDeal->isEmpty()) {
            $this->command->error("No items found with valid deal relationships!");

            // Show sample items to understand the issue
            $this->command->warn("\n=== SAMPLE ITEMS (first 5) ===");
            $sampleItems = Item::where('ref', '!=', '#0001')->limit(5)->get();
            foreach ($sampleItems as $item) {
                $this->command->line("Item ID: {$item->id}, Ref: {$item->ref}, Deal ID: " . ($item->deal_id ?? 'NULL'));
            }

            return;
        }

        foreach ($groupedByDeal as $dealId => $items) {
            $firstItem = $items->first();
            $deal = $firstItem->deal;
            $itemsCount = $items->count();

            $this->command->line("Deal ID: {$dealId}");
            $this->command->line("  - Deal Name: {$deal->name}");
            $this->command->line("  - Deal Type: {$deal->type} (" . gettype($deal->type) . ")");
            $this->command->line("  - Items Count: {$itemsCount}");
            $this->command->line("  - Start Date: {$deal->start_date}");
            $this->command->line("  - End Date: {$deal->end_date}");

            if ($itemsCount < 2) {
                $this->command->warn("  ⚠ Warning: This deal has less than 2 items (seeder requires 2-5 items per order)");
            }

            $this->command->line("");
        }

        // Count deals with at least 2 items
        $dealsWithEnoughItems = $groupedByDeal->filter(function ($items) {
            return $items->count() >= 2;
        });

        $this->command->info("\n=== SUMMARY ===");
        $this->command->info("Total deals with items: {$groupedByDeal->count()}");
        $this->command->info("Deals with 2+ items (usable for seeding): {$dealsWithEnoughItems->count()}");

        if ($dealsWithEnoughItems->isEmpty()) {
            $this->command->warn("\nNo deals found with at least 2 items!");
            $this->command->warn("The OrdersTableSeeder requires deals to have 2-5 items.");
            $this->command->warn("Please add more items to your deals or create new deals with multiple items.");
        } else {
            $this->command->info("\n✓ {$dealsWithEnoughItems->count()} deal(s) are available for order seeding!");
        }
    }
}

