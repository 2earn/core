<?php

namespace App\Services;

use App\Models\SharesBalances;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class SharesService
{
    /**
     * Get paginated shares data for a user with search and sorting
     *
     * @param int $userId
     * @param string|null $search
     * @param string $sortField
     * @param string $sortDirection
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getSharesData(
        int $userId,
        ?string $search = null,
        string $sortField = 'id',
        string $sortDirection = 'desc',
        int $perPage = 10
    ): LengthAwarePaginator {
        try {
            $query = SharesBalances::query()
                ->where('beneficiary_id', $userId)
                ->select('*');

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('id', 'like', '%' . $search . '%')
                      ->orWhere('created_at', 'like', '%' . $search . '%')
                      ->orWhere('value', 'like', '%' . $search . '%');
                });
            }

            $query->orderBy($sortField, $sortDirection);

            return $query->paginate($perPage);
        } catch (\Exception $e) {
            Log::error('Error fetching shares data: ' . $e->getMessage(), [
                'user_id' => $userId,
                'search' => $search,
                'sort_field' => $sortField,
                'sort_direction' => $sortDirection
            ]);
            throw $e;
        }
    }

    /**
     * Get user's total sold shares value
     *
     * @param int $userId
     * @param int $balanceOperationId
     * @return float
     */
    public function getUserSoldSharesValue(int $userId, int $balanceOperationId = 44): float
    {
        try {
            return (float) SharesBalances::where('balance_operation_id', $balanceOperationId)
                ->where('beneficiary_id', $userId)
                ->selectRaw('SUM(value) as total_sum')
                ->first()
                ->total_sum ?? 0;
        } catch (\Exception $e) {
            Log::error('Error fetching user sold shares value: ' . $e->getMessage(), [
                'user_id' => $userId,
                'balance_operation_id' => $balanceOperationId
            ]);
            return 0;
        }
    }

    /**
     * Get user's total paid amount for shares
     *
     * @param int $userId
     * @param int $balanceOperationId
     * @return float
     */
    public function getUserTotalPaid(int $userId, int $balanceOperationId = 44): float
    {
        try {
            return (float) SharesBalances::where('balance_operation_id', $balanceOperationId)
                ->where('beneficiary_id', $userId)
                ->selectRaw('SUM(total_amount) as total_sum')
                ->first()
                ->total_sum ?? 0;
        } catch (\Exception $e) {
            Log::error('Error fetching user total paid: ' . $e->getMessage(), [
                'user_id' => $userId,
                'balance_operation_id' => $balanceOperationId
            ]);
            return 0;
        }
    }
}

