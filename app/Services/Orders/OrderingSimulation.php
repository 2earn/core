<?php

namespace App\Services\Orders;

use App\Models\Deal;
use Core\Models\Platform;
use App\Models\Item;
use App\Models\Order;
use App\Models\User;
use Core\Enum\OrderEnum;
use Core\Services\BalancesManager;
use Database\Seeders\AddCashSeeder;
use Faker\Factory;
use Illuminate\Support\Facades\Log;

class OrderingSimulation
{
    public function __construct(private BalancesManager $balancesManager)
    {
    }

    public static function createItem($platformId, $faker)
    {
        $unit_price = mt_rand(200, 500) / 100;
        $discount = mt_rand(1, 20);
        $itemName = $faker->name();
        $description = $faker->name();
        $reference = $faker->randomNumber(4);

        $hasDeal = (bool)rand(0, 2);
        $dealsIds = Deal::where('platform_id', $platformId)->pluck('id')->toArray();
        $dealsId = $dealsIds[array_rand($dealsIds)];

        $params = [
            'name' => $itemName,
            'price' => $unit_price,
            'discount' => $discount,
            'description' => $description,
        ];

        if ($hasDeal) {
            $params['deal_id'] = $dealsId;
        }
        if (Item::where('ref', $reference)->exists()) {
            $item = Item::where('ref', $reference)->first()->update($params);
        } else {
            $params['ref'] = $reference;
            $item = Item::create($params);
        }
        return $item;
    }

    public static function createOrderItems(Order $order, $orderItemsNumber, $platformId, $faker)
    {
        for ($i = 1; $i <= $orderItemsNumber; $i++) {
            $item = OrderingSimulation::createItem($platformId, $faker);
            $shipping = mt_rand(50, 120) / 100;
            $qty = rand(1, 3);
            $order->orderDetails()->create([
                'qty' => $qty,
                'unit_price' => $item->price,
                'shipping' => $shipping,
                'total_amount' => $qty * $item->price,
                'item_id' => $item->id,
            ]);
        }
    }

    public static function validate($orderId)
    {
        try {
            $order = Order::find($orderId);
            $order->updateStatus(OrderEnum::Ready);
            if (Ordering::simulate($order)) {
                Ordering::run($order);
            }
            $order = Order::find($orderId);
            return OrderEnum::tryFrom($order->status->value)->name;
        } catch (\Exception $exception) {
            Log::alert($exception->getMessage());
        }
        return false;
    }
    public static function simulate(): bool
    {
        try {
            $BuyerId = AddCashSeeder::USERS_IDS[array_rand(AddCashSeeder::USERS_IDS)];
            $Buyer = User::where('idUser', $BuyerId)->first();
            $orderItemsNumber = rand(1, 5);
            $platformsIds = Platform::all()->pluck('id')->toArray();
            $platformId = $platformsIds[array_rand($platformsIds)];
            $faker = Factory::create();
            $order = Order::create(['user_id' => $Buyer->id, 'note' => $faker->text()]);
            OrderingSimulation::createOrderItems($order, $orderItemsNumber, $platformId, $faker);
            return true;
        } catch (\Exception $exception) {
            Log::alert($exception->getMessage());
        }
        return false;

    }
}
