<?php

namespace App\Services\Balances;

use App\Models\CashBalances;
use Carbon\Carbon;

class CashBalancesService
{
    /**
     * Get total sales for today for a specific user
     *
     * @param int $beneficiaryId User ID
     * @param int $operationId Balance operation ID (default: 42 for sales)
     * @return float|null
     */
    public function getTodaySales(int $beneficiaryId, int $operationId = 42): ?float
    {
        $today = Carbon::now()->format('Y-m-d');

        return CashBalances::where('balance_operation_id', $operationId)
            ->where('beneficiary_id', $beneficiaryId)
            ->whereDate('created_at', '=', $today)
            ->selectRaw('SUM(value) as total_sum')
            ->first()
            ->total_sum;
    }

    /**
     * Get total sales for all time for a specific user
     *
     * @param int $beneficiaryId User ID
     * @param int $operationId Balance operation ID (default: 42 for sales)
     * @return float|null
     */
    public function getTotalSales(int $beneficiaryId, int $operationId = 42): ?float
    {
        return CashBalances::where('balance_operation_id', $operationId)
            ->where('beneficiary_id', $beneficiaryId)
            ->selectRaw('SUM(value) as total_sum')
            ->first()
            ->total_sum;
    }

    /**
     * Get sales data for a specific user
     *
     * @param int $beneficiaryId User ID
     * @param int $operationId Balance operation ID (default: 42 for sales)
     * @return array Returns array with 'today' and 'total' keys
     */
    public function getSalesData(int $beneficiaryId, int $operationId = 42): array
    {
        return [
            'today' => $this->getTodaySales($beneficiaryId, $operationId),
            'total' => $this->getTotalSales($beneficiaryId, $operationId),
        ];
    }
}

