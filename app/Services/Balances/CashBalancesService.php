<?php

namespace App\Services\Balances;

use App\Models\CashBalances;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

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
        $today = Carbon::now()->format(config('app.date_format'));

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

    /**
     * Get transfer query for a specific user
     *
     * @param int $beneficiaryId User ID
     * @param int $operationId Balance operation ID
     * @return Builder
     */
    public function getTransfertQuery(int $beneficiaryId, int $operationId): Builder
    {
        return DB::table('cash_balances')
            ->select('value', 'description', 'created_at')
            ->where('balance_operation_id', $operationId)
            ->where('beneficiary_id', $beneficiaryId)
            ->whereNotNull('description')
            ->orderBy('created_at', 'DESC');
    }

    /**
     * Get paginated transactions for a specific user with search and sorting
     *
     * @param int $beneficiaryId User ID
     * @param int $operationId Balance operation ID (default: 42 for sales)
     * @param string|null $search Search term for description and value
     * @param string $sortField Field to sort by
     * @param string $sortDirection Sort direction (asc or desc)
     * @param int $perPage Items per page
     * @return LengthAwarePaginator
     */
    public function getTransactions(
        int $beneficiaryId,
        int $operationId = 42,
        ?string $search = null,
        string $sortField = 'created_at',
        string $sortDirection = 'desc',
        int $perPage = 10
    ): LengthAwarePaginator {
        return DB::table('cash_balances')
            ->select('value', 'description', 'created_at')
            ->where('balance_operation_id', $operationId)
            ->where('beneficiary_id', $beneficiaryId)
            ->whereNotNull('description')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('description', 'like', '%' . $search . '%')
                      ->orWhere('value', 'like', '%' . $search . '%');
                });
            })
            ->orderBy($sortField, $sortDirection)
            ->paginate($perPage);
    }
}

