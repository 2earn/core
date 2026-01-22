<?php

namespace App\Services\Platform;

use App\Models\Platform;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class PlatformService
{
    /**
     * Get all platforms
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        try {
            return Platform::all();
        } catch (\Exception $e) {
            Log::error('Error fetching all platforms: ' . $e->getMessage());
            return new Collection();
        }
    }

    /**
     * Get platform by ID
     *
     * @param int $id
     * @return Platform|null
     */
    public function getById(int $id): ?Platform
    {
        try {
            return Platform::find($id);
        } catch (\Exception $e) {
            Log::error('Error fetching platform by ID: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get platforms with user purchase history
     *
     * @param int $userId
     * @return Collection
     */
    public function getPlatformsWithUserPurchases(int $userId): Collection
    {
        try {
            return Platform::whereHas('items.orderDetails.order', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
                ->distinct()
                ->get();
        } catch (\Exception $e) {
            Log::error('Error fetching platforms with user purchases: ' . $e->getMessage(), [
                'user_id' => $userId
            ]);
            return new Collection();
        }
    }

    /**
     * Find platform by ID or fail
     *
     * @param int $id
     * @return Platform
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): Platform
    {
        return Platform::findOrFail($id);
    }

    /**
     * Create a new platform
     *
     * @param array $data
     * @return Platform|null
     */
    public function create(array $data): ?Platform
    {
        try {
            return Platform::create($data);
        } catch (\Exception $e) {
            Log::error('Error creating platform: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update a platform
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        try {
            $platform = Platform::findOrFail($id);
            return $platform->update($data);
        } catch (\Exception $e) {
            Log::error('Error updating platform: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a platform
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        try {
            $platform = Platform::findOrFail($id);
            return $platform->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting platform: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get platforms with active deals for a business sector
     *
     * @param int $businessSectorId
     * @return Collection
     */
    public function getPlatformsWithActiveDeals(int $businessSectorId)
    {
        try {
            return Platform::where('business_sector_id', $businessSectorId)
                ->where('enabled', true)
                ->whereHas('deals', function ($query) {
                    $query->where('status', 2)
                        ->where('validated', true);
                })
                ->with(['deals' => function ($query) {
                    $query->where('status', 2)
                        ->where('validated', true);
                }])
                ->get();
        } catch (\Exception $e) {
            Log::error('Error fetching platforms with active deals: ' . $e->getMessage(), [
                'business_sector_id' => $businessSectorId
            ]);
            return new Collection();
        }
    }

    /**
     * Get items from enabled platforms for a business sector
     *
     * @param int $businessSectorId
     * @return \Illuminate\Support\Collection
     */
    public function getItemsFromEnabledPlatforms(int $businessSectorId): \Illuminate\Support\Collection
    {
        try {
            return Platform::where('business_sector_id', $businessSectorId)
                ->where('enabled', true)
                ->with(['items' => function ($query) {
                    $query->whereHas('deal', function ($dealQuery) {
                        $dealQuery->where('status', 2)
                            ->where('validated', true);
                    });
                }])
                ->get()
                ->pluck('items')
                ->flatten();
        } catch (\Exception $e) {
            Log::error('Error fetching items from enabled platforms: ' . $e->getMessage(), [
                'business_sector_id' => $businessSectorId
            ]);
            return collect();
        }
    }

    /**
     * Get paginated platforms with filters
     *
     * @param string $search
     * @param int $perPage
     * @param array $businessSectors
     * @param array $types
     * @param array $enabled
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginatedPlatforms(
        string $search = '',
        int $perPage = 15,
        array $businessSectors = [],
        array $types = [],
        array $enabled = []
    ) {
        try {
            $query = Platform::with(['businessSector', 'deals']);

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%')
                        ->orWhere('url', 'like', '%' . $search . '%');
                });
            }

            if (!empty($businessSectors)) {
                $query->whereIn('business_sector_id', $businessSectors);
            }

            if (!empty($types)) {
                $query->whereIn('type', $types);
            }

            if (!empty($enabled)) {
                $enabledValues = array_map(function ($val) {
                    return $val === 'true' || $val === '1' || $val === 1;
                }, $enabled);
                $query->whereIn('enabled', $enabledValues);
            }

            return $query->orderBy('created_at', 'desc')->paginate($perPage);
        } catch (\Exception $e) {
            Log::error('Error fetching paginated platforms: ' . $e->getMessage(), [
                'search' => $search,
                'per_page' => $perPage,
                'business_sectors' => $businessSectors,
                'types' => $types,
                'enabled' => $enabled
            ]);
            return Platform::paginate($perPage);
        }
    }

    /**
     * Get all enabled platforms
     *
     * @return Collection
     */
    public function getEnabledPlatforms(): Collection
    {
        try {
            return Platform::where('enabled', true)
                ->orderBy('name')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error fetching enabled platforms: ' . $e->getMessage());
            return new Collection();
        }
    }

    /**
     * Get platforms managed by a specific user
     *
     * @param int $userId
     * @param bool $onlyEnabled
     * @return Collection
     */
    public function getPlatformsManagedByUser(int $userId, bool $onlyEnabled = true): Collection
    {
        try {
            $query = Platform::whereHas('managers', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            });

            if ($onlyEnabled) {
                $query->where('enabled', true);
            }

            return $query->orderBy('name')->get();
        } catch (\Exception $e) {
            Log::error('Error fetching platforms managed by user: ' . $e->getMessage(), [
                'user_id' => $userId,
                'only_enabled' => $onlyEnabled
            ]);
            return new Collection();
        }
    }

    /**
     * Get platforms for a partner (owner, marketing manager, or financial manager)
     *
     * @param int $userId
     * @param int|null $page
     * @param string|null $search
     * @param int $limit
     * @return array
     */
    public function getPlatformsForPartner(int $userId, ?int $page = 1, ?string $search = null, int $limit = 8): array
    {
        try {
            $query = Platform::whereHas('roles', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            });

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%')
                        ->orWhere('link', 'like', '%' . $search . '%');
                });
            }

            $totalCount = $query->count();

            if ($page !== null && $page > 0) {
                $offset = ($page - 1) * $limit;
                $query->skip($offset)->take($limit);
            }

            $platforms = $query->with([
                'businessSector',
                'deals',
                'logoImage'
            ])
                ->orderBy('created_at', 'desc')
                ->get();

            return [
                'platforms' => $platforms,
                'total_count' => $totalCount
            ];
        } catch (\Exception $e) {
            Log::error('Error fetching platforms for partner: ' . $e->getMessage(), [
                'user_id' => $userId,
                'page' => $page,
                'search' => $search,
                'limit' => $limit
            ]);
            return [
                'platforms' => new Collection(),
                'total_count' => 0
            ];
        }
    }

    /**
     * Check if a user has a role in a platform (owner, marketing manager, or financial manager)
     *
     * @param int $userId
     * @param int $platformId
     * @return bool
     */
    public function userHasRoleInPlatform(int $userId, int $platformId): bool
    {
        try {
            return Platform::where('id', $platformId)
                ->whereHas('roles', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                })
                ->exists();
        } catch (\Exception $e) {
            Log::error('Error checking if user has role in platform: ' . $e->getMessage(), [
                'user_id' => $userId,
                'platform_id' => $platformId
            ]);
            return false;
        }
    }

    /**
     * Get a single platform for a partner if user has a role in it
     *
     * @param int $platformId
     * @param int $userId
     * @return Platform|null
     */
    public function getPlatformForPartner(int $platformId, int $userId): ?Platform
    {
        try {
            return Platform::where('id', $platformId)
                ->whereHas('roles', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                })
                ->with([
                    'businessSector',
                    'deals',
                    'logoImage',
                    'validationRequest',
                    'pendingValidationRequest',
                    'pendingTypeChangeRequest',
                    'pendingChangeRequest'
                ])
                ->first();
        } catch (\Exception $e) {
            Log::error('Error fetching platform for partner: ' . $e->getMessage(), [
                'platform_id' => $platformId,
                'user_id' => $userId
            ]);
            return null;
        }
    }
}

