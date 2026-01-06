<?php

namespace App\Services\Role;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleService
{
    /**
     * Get a Role by ID
     *
     * @param int $id
     * @return Role|null
     */
    public function getById(int $id): ?Role
    {
        return Role::find($id);
    }

    /**
     * Get a Role by ID or fail
     *
     * @param int $id
     * @return Role
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getByIdOrFail(int $id): Role
    {
        return Role::findOrFail($id);
    }

    /**
     * Get paginated Roles with search
     *
     * @param string|null $search
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginated(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        $query = Role::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('guard_name', 'like', '%' . $search . '%');
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Get all Roles
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Role::orderBy('created_at', 'desc')->get();
    }

    /**
     * Create a new Role
     *
     * @param array $data
     * @return Role
     */
    public function create(array $data): Role
    {
        return Role::create($data);
    }

    /**
     * Update a Role
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $role = Role::findOrFail($id);
        return $role->update($data);
    }

    /**
     * Delete a Role
     *
     * @param int $id
     * @return bool|null
     * @throws \Exception
     */
    public function delete(int $id): ?bool
    {
        if ($id <= 4) {
            throw new \Exception('This Role cannot be deleted!');
        }

        $role = Role::findOrFail($id);
        return $role->delete();
    }

    /**
     * Check if a role can be deleted
     *
     * @param int $id
     * @return bool
     */
    public function canDelete(int $id): bool
    {
        return $id > 4;
    }

    /**
     * Get paginated user roles with search functionality
     *
     * @param string $search
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getUserRoles(string $search = '', int $perPage = 10): LengthAwarePaginator
    {
        return DB::table('users')
            ->leftjoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftjoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->leftjoin('countries', 'users.idCountry', '=', 'countries.id')
            ->selectRaw('users.id, users.idUser, users.name,users.mobile,users.idCountry,ifnull(model_has_roles.model_id,0) as idrole, ifnull(roles.name,\'sansRole\') role ,countries.name countrie ,countries.apha2 apha2')
            ->where('users.name', 'like', '%' . $search . '%')
            ->orWhere('users.mobile', 'like', '%' . $search . '%')
            ->orWhere('countries.name', 'like', '%' . $search . '%')
            ->paginate($perPage);
    }
}

