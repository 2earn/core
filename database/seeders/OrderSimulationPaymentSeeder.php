<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Enums\OrderEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class OrderSimulationPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Updates orders from ID 1 to 5 with simulation and payment details based on their status.
     *
     * @return void
     */
    public function run()
    {
        Log::notice('Starting OrderSimulationPaymentSeeder');

        // Get orders from ID 1 to 5
        $orders = Order::whereBetween('id', [1, 5])->get();

        if ($orders->isEmpty()) {
            Log::warning('No orders found with IDs between 1 and 5.');
            return;
        }

        foreach ($orders as $order) {
            $this->updateOrderByStatus($order);
        }

        Log::notice('OrderSimulationPaymentSeeder completed successfully');
    }

    /**
     * Update order based on its status
     *
     * @param Order $order
     * @return void
     */
    private function updateOrderByStatus(Order $order)
    {
        // Status 5: Failed - Simulation Failed
        if ($order->status->value == OrderEnum::Failed->value) {
            $order->update([
                'simulation_result' => 0,
                'simulation_details' => 'Simulation failed due to insufficient funds in the account balances',
                'simulation_datetime' => $order->updated_at,
            ]);

            Log::info("Updated Order ID: {$order->id} - Status 5 (Failed) - Simulation failed");
        }

        // Status 6: Dispatched - Payment Successful
        if ($order->status->value == OrderEnum::Dispatched->value) {
            $order->update([
                'simulation_result' => 1,
                'simulation_datetime' => $order->updated_at,
                'payment_result' => 1,
                'payment_details' => 'Payment successful',
                'payment_datetime' => $order->updated_at,
            ]);

            Log::info("Updated Order ID: {$order->id} - Status 6 (Dispatched) - Payment successful");
        }
    }
}

