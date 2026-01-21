<?php

namespace App\Services\EntityRole;

use App\Models\EntityRole;
use App\Models\Platform;
use App\Models\Partner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EntityRoleService
{
    /**
     * Get all roles
     */
    public function getAllRoles()
    {
        return EntityRole::with(['roleable', 'creator', 'updater'])->get();
    }

    /**
     * Get role by ID
     */
    public function getRoleById($id)
    {
        return EntityRole::with(['roleable', 'creator', 'updater'])->find($id);
    }

    /**
     * Get all platform roles
     */
    public function getPlatformRoles()
    {
        return EntityRole::platformRoles()
            ->with(['roleable', 'creator', 'updater'])
            ->get();
    }

    /**
     * Get all partner roles
     */
    public function getPartnerRoles()
    {
        return EntityRole::partnerRoles()
            ->with(['roleable', 'creator', 'updater'])
            ->get();
    }

    /**
     * Get roles for a specific platform
     */
    public function getRolesForPlatform($platformId, $paginate = false, $perPage = 10)
    {
        $query = EntityRole::where('roleable_type', Platform::class)
            ->where('roleable_id', $platformId)
            ->with(['user', 'creator', 'updater'])
            ->orderBy('created_at', 'desc');

        return $paginate ? $query->paginate($perPage) : $query->get();
    }

    /**
     * Get roles for a specific partner
     */
    public function getRolesForPartner($partnerId, $paginate = false, $perPage = 10)
    {
        $query = EntityRole::where('roleable_type', Partner::class)
            ->where('roleable_id', $partnerId)
            ->with(['user', 'creator', 'updater'])
            ->orderBy('created_at', 'desc');

        return $paginate ? $query->paginate($perPage) : $query->get();
    }

    /**
     * Create a new role for a platform
     */
    public function createPlatformRole($platformId, $data)
    {
        try {
            DB::beginTransaction();

            $roleData = [
                'name' => $data['name'],
                'roleable_id' => $platformId,
                'roleable_type' => Platform::class,
                'user_id' => $data['user_id'] ?? null,
                'created_by' => $data['created_by'] ?? null,
                'updated_by' => $data['updated_by'] ?? null,
            ];

            $role = EntityRole::create($roleData);

            DB::commit();
            return $role;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating platform role: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create a new role for a partner
     */
    public function createPartnerRole($partnerId, $data)
    {
        try {
            DB::beginTransaction();

            $roleData = [
                'name' => $data['name'],
                'roleable_id' => $partnerId,
                'roleable_type' => Partner::class,
                'user_id' => $data['user_id'] ?? null,
                'created_by' => $data['created_by'] ?? null,
                'updated_by' => $data['updated_by'] ?? null,
            ];

            $role = EntityRole::create($roleData);

            DB::commit();
            return $role;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating partner role: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update a role
     */
    public function updateRole($id, $data)
    {
        try {
            DB::beginTransaction();

            $role = EntityRole::findOrFail($id);

            $updateData = [
                'name' => $data['name'],
                'user_id' => $data['user_id'] ?? $role->user_id,
                'updated_by' => $data['updated_by'] ?? null,
            ];

            $role->update($updateData);

            DB::commit();
            return $role;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating role: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete a role
     */
    public function deleteRole($id)
    {
        try {
            DB::beginTransaction();

            $role = EntityRole::findOrFail($id);
            $role->delete();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting role: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Search roles by name
     */
    public function searchRolesByName($name)
    {
        return EntityRole::searchByName($name)
            ->with(['roleable', 'creator', 'updater'])
            ->get();
    }

    /**
     * Get paginated roles with search and filters
     */
    public function getFilteredRoles($searchTerm = null, $type = null, $perPage = 15)
    {
        $query = EntityRole::with(['roleable', 'creator', 'updater']);

        if ($searchTerm) {
            $query->where('name', 'like', '%' . $searchTerm . '%');
        }

        if ($type === 'platform') {
            $query->where('roleable_type', Platform::class);
        } elseif ($type === 'partner') {
            $query->where('roleable_type', Partner::class);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Check if a role name exists for a specific roleable
     */
    public function roleNameExistsForRoleable($name, $roleableId, $roleableType, $excludeId = null)
    {
        $query = EntityRole::where('name', $name)
            ->where('roleable_id', $roleableId)
            ->where('roleable_type', $roleableType);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Check if a user has any role in platforms
     *
     * @param int $userId
     * @return bool
     */
    public function userHasPlatformRole(int $userId): bool
    {
        return EntityRole::where('user_id', $userId)
            ->where('roleable_type', Platform::class)
            ->exists();
    }

    /**
     * Check if a user has any role in partners
     *
     * @param int $userId
     * @return bool
     */
    public function userHasPartnerRole(int $userId): bool
    {
        return EntityRole::where('user_id', $userId)
            ->where('roleable_type', Partner::class)
            ->exists();
    }

    /**
     * Get all platform IDs where user has a role
     *
     * @param int $userId
     * @return array
     */
    public function getUserPlatformIds(int $userId): array
    {
        return EntityRole::where('user_id', $userId)
            ->where('roleable_type', Platform::class)
            ->pluck('roleable_id')
            ->toArray();
    }

    /**
     * Get all partner IDs where user has a role
     *
     * @param int $userId
     * @return array
     */
    public function getUserPartnerIds(int $userId): array
    {
        return EntityRole::where('user_id', $userId)
            ->where('roleable_type', Partner::class)
            ->pluck('roleable_id')
            ->toArray();
    }

    /**
     * Get all user IDs that have platform roles
     *
     * @return array
     */
    public function getAllPlatformPartnerUserIds(): array
    {
        return EntityRole::where('roleable_type', Platform::class)
            ->distinct()
            ->pluck('user_id')
            ->toArray();
    }

    /**
     * Get all platforms with roles for a specific user
     *
     * @param int $userId
     * @param array $with Additional relationships to eager load
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPlatformsWithRolesForUser(int $userId, array $with = [])
    {
        $defaultWith = ['businessSector', 'logoImage'];
        $with = array_merge($defaultWith, $with);

        return Platform::whereHas('roles', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->with(array_merge($with, ['roles' => function ($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->select('id', 'user_id', 'name', 'roleable_id', 'roleable_type', 'created_at', 'updated_at');
        }]))
        ->get();
    }

    /**
     * Get role names for a user on a specific platform
     *
     * @param int $userId
     * @param int $platformId
     * @return array
     */
    public function getUserRolesForPlatform(int $userId, int $platformId): array
    {
        return EntityRole::where('user_id', $userId)
            ->where('roleable_id', $platformId)
            ->where('roleable_type', Platform::class)
            ->pluck('name')
            ->toArray();
    }

    /**
     * Get a specific role by user, platform, and role name
     *
     * @param int $userId
     * @param int $platformId
     * @param string $roleName
     * @return EntityRole|null
     */
    public function getRoleByUserPlatformAndName(int $userId, int $platformId, string $roleName): ?EntityRole
    {
        return EntityRole::where('user_id', $userId)
            ->where('roleable_id', $platformId)
            ->where('roleable_type', Platform::class)
            ->where('name', $roleName)
            ->first();
    }
}
