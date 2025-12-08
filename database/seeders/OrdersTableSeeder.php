<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Services\Orders\Ordering;
use Core\Enum\OrderEnum;
use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = Item::where('ref', '!=', '#0001')->take(5)->get();
        $userId = 384;
        $ordersNumber = 20;
        $CreatedOrders = [];
        for ($i = 0; $i < $ordersNumber; $i++) {
            $order = Order::create([
                'user_id' => $userId,
                'status' => OrderEnum::Ready,
                'note' => 'Sample order ' . ($i + 1),
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
            ]);

            $totalOrder = 0;
            $totalQuantity = 0;

            foreach ($items as $item) {
                $quantity = rand(1, 3);
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
                    'note' => 'Sample item',
                ]);

                $totalOrder += $totalAmount;
                $totalQuantity += $quantity;
            }

            $order->update([
                'total_order' => $totalOrder,
                'total_order_quantity' => $totalQuantity,
                'deal_amount_before_discount' => $totalOrder,
                'amount_after_discount' => $totalOrder,
            ]);

            $CreatedOrders[] = $order;
        }
        foreach ($CreatedOrders as $CreatedOrder) {

            $simulation = Ordering::simulate($CreatedOrder);
            if ($simulation) {
                Ordering::run($simulation);
            }

        }
    }
}
