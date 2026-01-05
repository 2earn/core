<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Enums\OrderEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class OrdersTablePaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates orders without simulation or payment processing.
     *
     * @return void
     */
    public function run()
    {
        $allItems = Item::with(['deal', 'deal.platform'])->where('ref', '!=', '#0001')->get();

        // Group items by deal_id, filter items that have deals with platforms
        $itemsByDeal = $allItems
            ->filter(function ($item) {
                // Only keep items that have a deal with a platform
                return !is_null($item->deal_id)
                    && !is_null($item->deal)
                    && !is_null($item->deal->platform_id);
            })
            ->groupBy('deal_id')
            ->filter(function ($items, $dealId) {
                $firstItem = $items->first();
                $hasDeal = !is_null($firstItem->deal);

                if ($hasDeal) {
                    $dealType = $firstItem->deal->type;
                    $itemsCount = $items->count();
                    return $itemsCount >= 2;
                }

                return false;
            });


        if ($itemsByDeal->isEmpty()) {
            $this->command->error("No deals found with at least 2 items. Please ensure deals have multiple items and are assigned to platforms.");
            $this->command->warn("Tip: Each deal needs at least 2 items and must be assigned to a platform.");
            return;
        }

        // Group deals by platform_id
        $dealsByPlatform = $itemsByDeal->groupBy(function ($items) {
            return $items->first()->deal->platform_id;
        });

        if ($dealsByPlatform->isEmpty()) {
            $this->command->error("No platforms found with deals. Please ensure deals are assigned to platforms.");
            return;
        }

        $this->command->info("Found " . $dealsByPlatform->count() . " platforms with deals.");
        foreach ($dealsByPlatform as $platformId => $deals) {
            $this->command->info("Platform ID {$platformId}: " . $deals->count() . " deals");
        }

        $userIds = [2, 213, 325, 384, 3716, 3786];

        $ordersNumber = 10;

        $this->command->info("Creating {$ordersNumber} orders without simulation or payment...");

        for ($i = 0; $i < $ordersNumber; $i++) {

            $userId = $userIds[array_rand($userIds)];

            // Randomly select a platform
            $selectedPlatformId = $dealsByPlatform->keys()->random();
            $platformDeals = $dealsByPlatform->get($selectedPlatformId);

            // Randomly select one deal from this platform's deals
            $selectedDeal = $platformDeals->random();

            // Get the platform_id from the first item's deal
            $platformId = $selectedDeal->first()->deal->platform_id;

            $order = Order::create([
                'user_id' => $userId,
                'platform_id' => $platformId,
                'status' => OrderEnum::Ready,
                'note' => 'Sample order (no payment) ' . ($i + 1) . ' for platform ' . $platformId,
                'out_of_deal_amount' => 0,
                'deal_amount_before_discount' => 0,
                'total_order' => 0,
                'total_order_quantity' => 0,
                'deal_amount_after_discounts' => 0,
                'amount_after_discount' => 0,
                'paid_cash' => 0,
                'commission_2_earn' => 0,
                'deal_amount_for_partner' => 0,
                'commission_for_camembert' => 0,
                'total_final_discount' => 0,
                'total_final_discount_percentage' => 0,
                'total_lost_discount' => 0,
                'total_lost_discount_percentage' => 0,
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);

            $totalOrder = 0;
            $totalQuantity = 0;
            $dealStartDate = null;
            $dealEndDate = null;


            // Get items from that deal
            $dealItems = $selectedDeal;

            // Randomly select 2-5 items from this deal's products
            $numberOfItems = rand(2, min(5, $dealItems->count()));
            $selectedItems = $dealItems->random($numberOfItems);

            foreach ($selectedItems as $item) {
                $quantity = rand(1, 10);
                $unitPrice = $item->price;
                $totalAmount = $quantity * $unitPrice;

                OrderDetail::create([
                    'order_id' => $order->id,
                    'item_id' => $item->id,
                    'qty' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_amount' => $totalAmount,
                    'shipping' => 0,
                    'partner_discount_percentage' => 0,
                    'partner_discount' => 0,
                    'amount_after_partner_discount' => $totalAmount,
                    'earn_discount_percentage' => 0,
                    'earn_discount' => 0,
                    'amount_after_earn_discount' => $totalAmount,
                    'deal_discount_percentage' => 0,
                    'deal_discount' => 0,
                    'amount_after_deal_discount' => $totalAmount,
                    'total_discount' => 0,
                    'note' => 'Sample item (no payment)',
                    'created_by' => $userId,
                    'updated_by' => $userId,
                ]);

                $totalOrder += $totalAmount;
                $totalQuantity += $quantity;

                if (is_null($dealStartDate) && $item->deal) {
                    $dealStartDate = $item->deal->start_date;
                    $dealEndDate = $item->deal->end_date;
                }
            }

            $randomDate = null;
            if (!is_null($dealStartDate) && !is_null($dealEndDate)) {
                $startTimestamp = strtotime($dealStartDate);
                $endTimestamp = strtotime($dealEndDate);
                $randomTimestamp = rand($startTimestamp, $endTimestamp);
                $randomDate = date('Y-m-d H:i:s', $randomTimestamp);
            }

            $updateData = [
                'total_order' => $totalOrder,
                'total_order_quantity' => $totalQuantity,
                'deal_amount_before_discount' => $totalOrder,
                'amount_after_discount' => $totalOrder,
            ];

            if ($randomDate) {
                $updateData['created_at'] = $randomDate;
                Log::notice("Order created with date: " . $randomDate);
            }

            $order->update($updateData);

            if (($i + 1) % 10 == 0) {
                $this->command->info("Created " . ($i + 1) . " orders...");
            }
        }

        $this->command->info("Successfully created {$ordersNumber} orders without simulation or payment.");
        $this->command->warn("Note: These orders are in 'Ready' status and have not been simulated or paid.");
    }
}

