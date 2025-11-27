<?php

namespace App\Services\Balances;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ShareBalanceService
{
    /**
     * Get shares sold data with pagination, search, and sorting
     *
     * @param string $search Search term for filtering
     * @param int $perPage Number of records per page
     * @param string $sortField Field to sort by
     * @param string $sortDirection Sort direction (asc/desc)
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getSharesSoldesData(
        string $search = '',
        int $perPage = 100,
        string $sortField = 'created_at',
        string $sortDirection = 'desc'
    ) {
        $query = DB::table('shares_balances')
            ->select(
                'current_balance',
                'payed',
                'countries.apha2',
                'shares_balances.id',
                DB::raw('CONCAT(nvl( meta.arFirstName,meta.enFirstName), \' \' ,nvl( meta.arLastName,meta.enLastName)) AS Name'),
                'user.mobile',
                DB::raw('CAST(value AS DECIMAL(10,0)) AS value'),
                'shares_balances.value as raw_value',
                DB::raw('CAST(shares_balances.unit_price AS DECIMAL(10,2)) AS unit_price'),
                'shares_balances.created_at',
                'shares_balances.payed as payed',
                'shares_balances.beneficiary_id'
            )
            ->join('users as user', 'user.idUser', '=', 'shares_balances.beneficiary_id')
            ->join('metta_users as meta', 'meta.idUser', '=', 'user.idUser')
            ->join('countries', 'countries.id', '=', 'user.idCountry')
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('user.mobile', 'like', '%' . $search . '%')
                      ->orWhere(DB::raw('CONCAT(nvl( meta.arFirstName,meta.enFirstName), \' \' ,nvl( meta.arLastName,meta.enLastName))'), 'like', '%' . $search . '%');
                });
            })
            ->orderBy($sortField, $sortDirection);

        return $query->paginate($perPage);
    }

    /**
     * Update share balance
     *
     * @param int $id Share balance ID
     * @param float $amount New balance amount
     * @return bool
     */
    public function updateShareBalance(int $id, float $amount): bool
    {
        try {
            DB::table('shares_balances')
                ->where('id', $id)
                ->update([
                    'current_balance' => $amount,
                    'payed' => 1,
                ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error updating share balance: ' . $e->getMessage());
            return false;
        }
    }
}

