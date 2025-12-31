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

    /**
     * Get public users for financial requests
     *
     * @param int $excludeUserId User ID to exclude from results
     * @param int $countryId Country ID filter
     * @param int $minStatus Minimum status value
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPublicUsers(int $excludeUserId, int $countryId, int $minStatus)
    {
        return User::where('is_public', 1)
            ->where('idUser', '<>', $excludeUserId)
            ->where('idCountry', $countryId)
            ->where('status', '>', $minStatus)
            ->get();
    }

    /**
     * Find user by ID
     *
     * @param int $id
     * @return User|null
     */
    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * Update user OTP activation
     *
     * @param int $userId
     * @param string $optCode
     * @return int Number of rows updated
     */
    public function updateOptActivation(int $userId, string $optCode): int
    {
        return User::where('id', $userId)->update(['OptActivation' => $optCode]);
    }

    /**
     * Update user with custom data
     *
     * @param User $user
     * @param array $data
     * @return bool
     */
    public function updateUser(User $user, array $data): bool
    {
        foreach ($data as $key => $value) {
            $user->$key = $value;
        }
        return $user->save();
    }

    /**
     * Find user by idUser (business ID)
     *
     * @param string $idUser
     * @return object|null
     */
    public function findByIdUser(string $idUser): ?object
    {
        return DB::table('users')->where('idUser', $idUser)->first();
    }

    /**
     * Update user password by user ID
     *
     * @param int $userId
     * @param string $hashedPassword
     * @return int Number of rows updated
     */
    public function updatePassword(int $userId, string $hashedPassword): int
    {
        return DB::table('users')->where('id', $userId)->update(['password' => $hashedPassword]);
    }

    /**
     * Update user fields by user ID
     *
     * @param int $userId
     * @param array $data
     * @return int Number of rows updated
     */
    public function updateById(int $userId, array $data): int
    {
        return DB::table('users')->where('id', $userId)->update($data);
    }
}

