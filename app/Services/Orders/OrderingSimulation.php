<?php

namespace App\Services\Orders;

use App\Models\Deal;
use Core\Models\Platform;
use App\Models\Item;
use App\Models\Order;
use App\Models\User;
use Core\Enum\OrderEnum;
use Database\Seeders\AddCashSeeder;
use Faker\Generator;
use Illuminate\Support\Facades\Log;

class OrderingSimulation
{

    public static function createOrGetItem($platformId, $faker)
    {
        $dealsIds = Deal::where('platform_id', $platformId)->pluck('id')->toArray();
        $dealsId = $dealsIds[array_rand($dealsIds)];
        $itemsIds = Item::where('deal_id', $dealsId)->where('ref' ,'!=' , '#0001')->pluck('id')->toArray();
        $getFromItems = (bool)rand(0, 1);
        if ($getFromItems && !empty($itemsIds)) {
            return Item::find($itemsIds[array_rand($itemsIds)]);
        }
        return self::getItem($platformId, $faker);
    }

    public static function createItem($platformId, $faker)
    {
        $unit_price = mt_rand(1000, 3000) / 100;
        $itemName = $faker->word();
        $description = $faker->text();
        $reference = $faker->randomNumber(4);

        $hasDeal = (bool)rand(0, 2);
        $dealsIds = Deal::where('platform_id', $platformId)->pluck('id')->toArray();
        $dealsId = $dealsIds[array_rand($dealsIds)];
        $deal = Deal::find($dealsId);

        $discount = mt_rand(0, intval($deal->initial_commission - $deal->discount / 2));
        $discount2earn = mt_rand(0, intval($deal->initial_commission - $deal->discount / 2));

        $params = [
            'name' => $itemName,
            'price' => $unit_price,
            'discount' => $discount,
            'discount_2earn' => $discount2earn,
            'description' => $description,
            'platform_id' => $platformId,
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
        $orderItems = [];
        for ($i = 1; $i <= $orderItemsNumber; $i++) {
            $item = OrderingSimulation::createOrGetItem($platformId, $faker);
            $shipping = mt_rand(500, 2000) / 100;
            $qty = rand(1, 5);
            if (!isset($orderItems[$item->id])) {
                $orderItems[$item->id] = [
                    'qty' => $qty,
                    'unit_price' => $item->price,
                    'shipping' => $shipping,
                    'total_amount' => $qty * $item->price,
                    'item_id' => $item->id,
                ];
            } else {
                $orderItems[$item->id]['qty'] += $qty;
                $orderItems[$item->id]['total_amount'] += $qty * $item->price;
            }
        }
        Log::info("orderItems " . json_encode($orderItems));
        foreach ($orderItems as $orderItem) {
            $order->orderDetails()->create($orderItem);
        }
    }


    public static function getItem($platformId, $faker): bool|Item
    {
        return OrderingSimulation::createItem($platformId, $faker);
    }

    public static function validate($orderId)
    {
        try {
            $order = Order::find($orderId);
            $order->updateStatus(OrderEnum::Ready);
            $simulation = Ordering::simulate($order);

            if ($simulation) {
                Ordering::run($simulation);
            }

            $order = Order::find($orderId);
            return $order->status->name;
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

            $faker = app(Generator::class);
            $order = Order::create(['user_id' => $Buyer->id, 'note' => $faker->text()]);
            OrderingSimulation::createOrderItems($order, $orderItemsNumber, $platformId, $faker);
            return true;
        } catch (\Exception $exception) {
            Log::alert($exception->getMessage());
        }
        return false;
    }
}
