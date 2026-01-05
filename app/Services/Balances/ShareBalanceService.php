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
     * Get share balances list for a specific user
     *
     * @param int $idUser User ID
     * @return \Illuminate\Support\Collection
     */
    public function getShareBalancesList(int $idUser)
    {
        return DB::table('shares_balances as u')
            ->select(
                'u.reference',
                'u.created_at',
                DB::raw("CASE WHEN b.direction = 'IN' THEN u.value ELSE -u.value END AS value"),
                'u.beneficiary_id',
                'u.balance_operation_id',
                'u.real_amount',
                'u.current_balance',
                'u.unit_price',
                'u.total_amount'
            )
            ->join('balance_operations as b', 'u.balance_operation_id', '=', 'b.id')
            ->join('users as s', 'u.beneficiary_id', '=', 's.idUser')
            ->where('u.beneficiary_id', $idUser)
            ->orderBy('u.created_at')
            ->get();
    }

    /**
     * Get shares soldes query
     *
     * @return \Illuminate\Support\Collection
     */
    public function getSharesSoldesQuery()
    {
        return DB::table('shares_balances')
            ->select(
                'current_balance',
                'payed',
                'countries.apha2',
                'shares_balances.id',
                DB::raw('CONCAT(nvl( meta.arFirstName,meta.enFirstName), \' \' ,nvl( meta.arLastName,meta.enLastName)) AS Name'),
                'user.mobile',
                DB::raw('CAST(value AS DECIMAL(10,0)) AS value'),
                'value',
                DB::raw('CAST(shares_balances.unit_price AS DECIMAL(10,2)) AS unit_price'),
                'shares_balances.created_at',
                'shares_balances.payed as payed',
                'shares_balances.beneficiary_id'
            )
            ->join('users as user', 'user.idUser', '=', 'shares_balances.beneficiary_id')
            ->join('metta_users as meta', 'meta.idUser', '=', 'user.idUser')
            ->join('countries', 'countries.id', '=', 'user.idCountry')
            ->orderBy('created_at')
            ->get();
    }

    /**
     * Get share price evolution by date query
     *
     * @return \Illuminate\Support\Collection
     */
    public function getSharePriceEvolutionDateQuery()
    {
        return DB::table('shares_balances')
            ->select(DB::raw('DATE(created_at) as x'), DB::raw('SUM(value) as y'))
            ->where('balance_operation_id', 20)
            ->groupBy('x')
            ->get();
    }

    /**
     * Get share price evolution by week query
     *
     * @return \Illuminate\Support\Collection
     */
    public function getSharePriceEvolutionWeekQuery()
    {
        return DB::table('shares_balances')
            ->select(DB::raw(' concat(year(created_at),\'-\',WEEK(created_at, 1)) as x'), DB::raw('SUM(value) as y'), DB::raw(' WEEK(created_at, 1) as z'))
            ->where('balance_operation_id', 44)
            ->groupBy('x', 'z')
            ->orderBy('z')
            ->get();
    }

    /**
     * Get share price evolution by month query
     *
     * @return \Illuminate\Support\Collection
     */
    public function getSharePriceEvolutionMonthQuery()
    {
        return DB::table('shares_balances')
            ->select(DB::raw('DATE_FORMAT(created_at, \'%Y-%m\') as x'), DB::raw('SUM(value) as y'))
            ->where('balance_operation_id', 44)
            ->groupBy('x')
            ->get();
    }

    /**
     * Get share price evolution by day query
     *
     * @return \Illuminate\Support\Collection
     */
    public function getSharePriceEvolutionDayQuery()
    {
        return DB::table('shares_balances')
            ->select(DB::raw('DAYNAME(created_at) as x'), DB::raw('SUM(value) as y'), DB::raw('DAYOFWEEK(created_at) as z'))
            ->where('balance_operation_id', 44)
            ->groupBy('x', 'z')
            ->orderBy('z')
            ->get();
    }

    /**
     * Get share price evolution query
     *
     * @return \Illuminate\Support\Collection
     */
    public function getSharePriceEvolutionQuery()
    {
        return DB::table('shares_balances')
            ->select(
                DB::raw('CAST(SUM(value) OVER (ORDER BY id) AS DECIMAL(10,0))AS x'),
                DB::raw('CAST(unit_price AS DECIMAL(10,2)) AS y')
            )
            ->where('balance_operation_id', 44)
            ->orderBy('created_at')
            ->get();
    }

    /**
     * Get shares solde query for a specific user
     *
     * @param int $beneficiaryId User ID
     * @return \Illuminate\Database\Query\Builder
     */
    public function getSharesSoldeQuery(int $beneficiaryId)
    {
        return DB::table('shares_balances')
            ->where('beneficiary_id', $beneficiaryId)
            ->orderBy('id', 'desc');
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

    /**
     * Get user balances for delayed sponsorship within time window
     *
     * @param int $balanceOperationId Balance operation ID (e.g., OLD_ID_44)
     * @param int $beneficiaryId Beneficiary user ID
     * @param int $retardatifReservation Hours threshold for delayed reservation
     * @param int $saleCount Limit of records to retrieve
     * @return \Illuminate\Support\Collection
     */
    public function getUserBalancesForDelayedSponsorship(
        int $balanceOperationId,
        int $beneficiaryId,
        int $retardatifReservation,
        int $saleCount
    ) {
        try {
            return DB::table('shares_balances')
                ->where('balance_operation_id', $balanceOperationId)
                ->where('beneficiary_id', $beneficiaryId)
                ->whereRaw('TIMESTAMPDIFF(HOUR, created_at, NOW()) < ?', [$retardatifReservation])
                ->orderBy('created_at', 'ASC')
                ->limit($saleCount)
                ->get();
        } catch (\Exception $e) {
            Log::error('Error fetching user balances for delayed sponsorship: ' . $e->getMessage(), [
                'balance_operation_id' => $balanceOperationId,
                'beneficiary_id' => $beneficiaryId,
                'retardatif_reservation' => $retardatifReservation,
                'sale_count' => $saleCount
            ]);
            return collect([]);
        }
    }
}

