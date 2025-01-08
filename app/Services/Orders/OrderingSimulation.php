<?php

namespace App\Services\Orders;

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use Core\Services\BalancesManager;
use Database\Seeders\AddCashSeeder;
use Faker\Factory;
use Illuminate\Support\Facades\Log;

class OrderingSimulation
{
    public function __construct(private BalancesManager $balancesManager)
    {
    }

    public static function createItem($faker)
    {
        $unit_price = mt_rand(200, 500) / 100;
        $discount = mt_rand(100, 200) / 100;
        $itemName = $faker->name();
        $description = $faker->name();
        $reference = $faker->randomNumber();
        if (Item::where('ref', $reference)->exists()) {
            $item = Item::where('ref', $reference)->first()->update([
                'name' => $itemName,
                'price' => $unit_price,
                'discount' => $discount,
                'description' => $description,
            ]);
        } else {
            $item = Item::create([
                'name' => $itemName,
                'price' => $unit_price,
                'description' => $description,
                'ref' => $reference,
                'discount' => $discount,
            ]);
        }
        return $item;
    }

    public static function createOrderItems(Order $order, $orderItemsNumber, $faker)
    {
        for ($i = 1; $i <= $orderItemsNumber; $i++) {
            $item = OrderingSimulation::createItem($faker);
            $qty = rand(1, 5);
            $order->orderDetails()->create([
                'qty' => $qty,
                'unit_price' => $item->price,
                'total_amount' => $qty * $item->price,
                'item_id' => $item->id,
            ]);
        }
    }
    public static function simulate(): bool
    {
        try {
            $BuyerId = AddCashSeeder::USERS_IDS[array_rand(AddCashSeeder::USERS_IDS)];
            $Buyer = User::where('idUser', $BuyerId)->first();
            $orderItemsNumber = rand(1, 5);

            $faker = Factory::create();
            $order = Order::create([
                'user_id' => $Buyer->id,
                'note' => $faker->text(),
            ]);
            OrderingSimulation::createOrderItems($order, $orderItemsNumber, $faker);
            return true;
        } catch (\Exception $exception) {
            Log::alert($exception->getMessage());
        }
        return false;

    }
}
