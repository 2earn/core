<?php

namespace App\Services\Orders;

use App\Models\Order;
use App\Models\User;
use Core\Services\BalancesManager;
use Database\Seeders\AddCashSeeder;
use Illuminate\Support\Facades\Log;

class OrderingSimulation
{
    public function __construct(private BalancesManager $balancesManager)
    {
    }

    public static function simulate(): bool
    {
        try {
            $BuyerId = AddCashSeeder::USERS_IDS[array_rand(AddCashSeeder::USERS_IDS)];
            $Buyer = User::where('idUser', $BuyerId)->first();
            $orderItemsNumber = rand(1, 9);
            Order::create([
                'user_id' => $Buyer->id,
            ]);
            for ($i = 1; $i <= $orderItemsNumber; $i++) {
            }
            return true;
        } catch (\Exception $exception) {
            Log::alert($exception->getMessage());
        }
        return false;

    }
}
