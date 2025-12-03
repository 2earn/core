<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class UserService
{
    /**
     * Get paginated users with joins and filters.
     *
     * @param string|null $search
     * @param string $sortBy
     * @param string $sortDirection
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getUsers(?string $search, string $sortBy, string $sortDirection, int $perPage): LengthAwarePaginator
    {
        $query = User::select(
            'countries.apha2',
            'countries.name as country',
            'users.id',
            'users.status',
            'users.idUser',
            'idUplineRegister',
            DB::raw('CONCAT(COALESCE(meta.arFirstName, meta.enFirstName), " ", COALESCE(meta.arLastName, meta.enLastName)) AS name'),
            'users.mobile',
            'users.created_at',
            'OptActivation',
            'activationCodeValue',
            'pass',
            DB::raw('IFNULL(`vip`.`flashCoefficient`,"##") as coeff'),
            DB::raw('IFNULL(`vip`.`flashDeadline`,"##") as periode'),
            DB::raw('IFNULL(`vip`.`flashNote`,"##") as note'),
            DB::raw('IFNULL(`vip`.`flashMinAmount`,"##") as minshares'),
            DB::raw('IFNULL(`vip`.`dateFNS`,"##") as date'),
        )
            ->join('metta_users as meta', 'meta.idUser', '=', 'users.idUser')
            ->join('countries', 'countries.id', '=', 'users.idCountry')
            ->leftJoin('vip', function ($join) {
                $join->on('vip.idUser', '=', 'users.idUser')->where('vip.closed', '=', 0);
            });

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('users.mobile', 'like', '%' . $search . '%')
                    ->orWhere('users.idUser', 'like', '%' . $search . '%')
                    ->orWhere(DB::raw('CONCAT(COALESCE(meta.arFirstName, meta.enFirstName), " ", COALESCE(meta.arLastName, meta.enLastName))'), 'like', '%' . $search . '%');
            });
        }

        return $query->orderBy($sortBy, $sortDirection)->paginate($perPage);
    }
}

