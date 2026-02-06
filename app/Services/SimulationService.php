<?php

namespace App\Services;

use App\Models\SimulationOrder;
use Illuminate\Support\Facades\Log;

class SimulationService
{
    private const LOG_PREFIX = '[SimulationService] ';

    /**
     * Compare current simulation with last saved simulation
     *
     * @param int $orderId
     * @param array $currentSimulation
     * @return array ['matches' => bool, 'details' => array]
     */
    public function compareWithLastSimulation(int $orderId, array $currentSimulation): array
    {
        $lastSimulation = SimulationOrder::getLatestForOrder($orderId);

        if (!$lastSimulation || !$lastSimulation->simulation_data) {
            Log::info(self::LOG_PREFIX . 'No previous simulation found for comparison', [
                'order_id' => $orderId
            ]);
            return ['matches' => true, 'details' => []];
        }

        // Compare key fields
        $currentFinalAmount = $currentSimulation['order']->amount_after_discount ?? null;
        $lastFinalAmount = $lastSimulation->simulation_data['order']['amount_after_discount'] ?? null;

        if ($currentFinalAmount !== $lastFinalAmount) {
            Log::error(self::LOG_PREFIX . 'Simulation mismatch detected', [
                'order_id' => $orderId,
                'current_final_amount' => $currentFinalAmount,
                'last_final_amount' => $lastFinalAmount,
                'current_simulation' => $currentSimulation,
                'last_simulation' => $lastSimulation->simulation_data
            ]);

            return [
                'matches' => false,
                'details' => [
                    'current_final_amount' => $currentFinalAmount,
                    'last_final_amount' => $lastFinalAmount,
                    'last_simulation_date' => $lastSimulation->created_at->toIso8601String()
                ]
            ];
        }

        Log::info(self::LOG_PREFIX . 'Simulation matches last saved simulation', [
            'order_id' => $orderId,
            'final_amount' => $currentFinalAmount
        ]);

        return ['matches' => true, 'details' => []];
    }
}
