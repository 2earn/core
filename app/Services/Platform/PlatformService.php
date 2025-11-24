<?php

namespace App\Services\Platform;

use Core\Models\Platform;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class PlatformService
{
    /**
     * Get enabled platforms with optional filters
     *
     * @param array $filters
     * @return EloquentCollection
     */
    public function getEnabledPlatforms(array $filters = []): EloquentCollection
    {
        try {
            $query = Platform::query()->where('enabled', true);

            // Apply business sector filter
            if (!empty($filters['business_sector_id'])) {
                $query->where('business_sector_id', $filters['business_sector_id']);
            }

            // Apply type filter
            if (!empty($filters['type'])) {
                $query->where('type', $filters['type']);
            }

            // Apply owner filter
            if (!empty($filters['owner_id'])) {
                $query->where('owner_id', $filters['owner_id']);
            }

            // Apply search filter
            if (!empty($filters['search'])) {
                $query->where(function($q) use ($filters) {
                    $q->where('name', 'like', '%' . $filters['search'] . '%')
                      ->orWhere('description', 'like', '%' . $filters['search'] . '%');
                });
            }

            // Load relationships if specified
            if (!empty($filters['with'])) {
                $query->with($filters['with']);
            }

            // Apply ordering
            $orderBy = $filters['order_by'] ?? 'created_at';
            $orderDirection = $filters['order_direction'] ?? 'asc';
            $query->orderBy($orderBy, $orderDirection);

            return $query->get();
        } catch (\Exception $e) {
            Log::error('Error fetching enabled platforms: ' . $e->getMessage());
            return new EloquentCollection();
        }
    }

    /**
     * Get platforms with active deals for a business sector
     *
     * @param int $businessSectorId
     * @return EloquentCollection
     */
    public function getPlatformsWithActiveDeals(int $businessSectorId): EloquentCollection
    {
        try {
            return Platform::with([
                'logoImage',
                'deals' => function($query) {
                    $query->where('start_date', '<=', now())
                          ->where('end_date', '>=', now());
                },
                'deals.items' => function($query) {
                    $query->where('ref', '!=', '#0001');
                },
                'deals.items.thumbnailsImage'
            ])
            ->where('enabled', true)
            ->where('business_sector_id', $businessSectorId)
            ->orderBy('created_at')
            ->get();
        } catch (\Exception $e) {
            Log::error('Error fetching platforms with active deals: ' . $e->getMessage());
            return new EloquentCollection();
        }
    }

    /**
     * Get all items from enabled platforms in a business sector
     *
     * @param int $businessSectorId
     * @return Collection
     */
    public function getItemsFromEnabledPlatforms(int $businessSectorId): Collection
    {
        try {
            return Platform::where('enabled', true)
                ->where('business_sector_id', $businessSectorId)
                ->with('deals.items')
                ->get()
                ->pluck('deals')
                ->flatten()
                ->pluck('items')
                ->flatten();
        } catch (\Exception $e) {
            Log::error('Error fetching items from enabled platforms: ' . $e->getMessage());
            return new Collection();
        }
    }

    /**
     * Get platform statistics
     *
     * @param int|null $businessSectorId
     * @return array
     */
    public function getStatistics(?int $businessSectorId = null): array
    {
        try {
            $query = Platform::query();

            if ($businessSectorId) {
                $query->where('business_sector_id', $businessSectorId);
            }

            return [
                'total' => (clone $query)->count(),
                'enabled' => (clone $query)->where('enabled', true)->count(),
                'disabled' => (clone $query)->where('enabled', false)->count(),
                'with_deals' => (clone $query)->whereHas('deals')->count(),
                'with_active_deals' => (clone $query)->whereHas('deals', function($q) {
                    $q->where('start_date', '<=', now())
                      ->where('end_date', '>=', now());
                })->count(),
            ];
        } catch (\Exception $e) {
            Log::error('Error getting platform statistics: ' . $e->getMessage());
            return [
                'total' => 0,
                'enabled' => 0,
                'disabled' => 0,
                'with_deals' => 0,
                'with_active_deals' => 0,
            ];
        }
    }

    /**
     * Get platform by ID with relationships
     *
     * @param int $id
     * @param array $with
     * @return Platform|null
     */
    public function getPlatformById(int $id, array $with = []): ?Platform
    {
        try {
            $query = Platform::query();

            if (!empty($with)) {
                $query->with($with);
            }

            return $query->find($id);
        } catch (\Exception $e) {
            Log::error('Error fetching platform by ID: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Toggle platform enabled status
     *
     * @param int $id
     * @return bool
     */
    public function toggleEnabled(int $id): bool
    {
        try {
            $platform = Platform::findOrFail($id);
            $platform->enabled = !$platform->enabled;
            return $platform->save();
        } catch (\Exception $e) {
            Log::error('Error toggling platform enabled status: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get platforms managed by a user
     *
     * @param int $userId
     * @param bool $onlyEnabled
     * @return EloquentCollection
     */
    public function getPlatformsManagedByUser(int $userId, bool $onlyEnabled = true): EloquentCollection
    {
        try {
            $query = Platform::where(function ($q) use ($userId) {
                $q->where('owner_id', $userId)
                  ->orWhere('marketing_manager_id', $userId)
                  ->orWhere('financial_manager_id', $userId);
            });

            if ($onlyEnabled) {
                $query->where('enabled', true);
            }

            return $query->with(['logoImage', 'businessSector'])
                         ->orderBy('created_at', 'desc')
                         ->get();
        } catch (\Exception $e) {
            Log::error('Error fetching platforms managed by user: ' . $e->getMessage());
            return new EloquentCollection();
        }
    }

    /**
     * Get platforms by business sector with counts
     *
     * @param int $businessSectorId
     * @return EloquentCollection
     */
    public function getPlatformsWithCounts(int $businessSectorId): EloquentCollection
    {
        try {
            return Platform::where('business_sector_id', $businessSectorId)
                ->where('enabled', true)
                ->withCount([
                    'deals',
                    'deals as active_deals_count' => function($query) {
                        $query->where('start_date', '<=', now())
                              ->where('end_date', '>=', now());
                    },
                    'items',
                    'coupons'
                ])
                ->with('logoImage')
                ->orderBy('created_at')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error fetching platforms with counts: ' . $e->getMessage());
            return new EloquentCollection();
        }
    }

    /**
     * Get platforms for partner with pagination and search
     *
     * @param int $userId
     * @param int|null $page
     * @param string|null $search
     * @param int $limit
     * @return array ['platforms' => Collection|LengthAwarePaginator, 'total_count' => int]
     */
    public function getPlatformsForPartner(int $userId, ?int $page = null, ?string $search = null, int $limit = 5): array
    {
        try {
            $query = Platform::where('marketing_manager_id', $userId)
                ->orWhere('financial_manager_id', $userId)
                ->orWhere('owner_id', $userId);

            if (!is_null($search) && $search !== '') {
                $query->where('name', 'like', '%' . $search . '%');
            }

            $totalCount = $query->count();
            $platforms = !is_null($page) ? $query->paginate($limit, ['*'], 'page', $page) : $query->get();

            return [
                'platforms' => $platforms,
                'total_count' => $totalCount
            ];
        } catch (\Exception $e) {
            Log::error('Error fetching platforms for partner: ' . $e->getMessage());
            return [
                'platforms' => new EloquentCollection(),
                'total_count' => 0
            ];
        }
    }

    /**
     * Check if platform can be accessed by user
     *
     * @param int $platformId
     * @param int $userId
     * @return bool
     */
    public function canUserAccessPlatform(int $platformId, int $userId): bool
    {
        try {
            return Platform::where('id', $platformId)
                ->where(function ($query) use ($userId) {
                    $query->where('owner_id', $userId)
                          ->orWhere('marketing_manager_id', $userId)
                          ->orWhere('financial_manager_id', $userId);
                })
                ->exists();
        } catch (\Exception $e) {
            Log::error('Error checking platform access: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get a single platform for a specific partner user
     *
     * @param int $platformId
     * @param int $userId
     * @return Platform|null
     */
    public function getPlatformForPartner(int $platformId, int $userId): ?Platform
    {
        try {
            return Platform::where('id', $platformId)
                ->where(function ($q) use ($userId) {
                    $q->where('marketing_manager_id', $userId)
                        ->orWhere('financial_manager_id', $userId)
                        ->orWhere('owner_id', $userId);
                })
                ->first();
        } catch (\Exception $e) {
            Log::error('Error fetching platform for partner: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update platform data
     *
     * @param int $id
     * @param array $data
     * @return Platform|null
     */
    public function updatePlatform(int $id, array $data): ?Platform
    {
        try {
            $platform = Platform::findOrFail($id);
            $platform->update($data);
            return $platform->fresh();
        } catch (\Exception $e) {
            Log::error('Error updating platform: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create a new platform
     *
     * @param array $data
     * @return Platform|null
     */
    public function createPlatform(array $data): ?Platform
    {
        try {
            return Platform::create($data);
        } catch (\Exception $e) {
            Log::error('Error creating platform: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete platform
     *
     * @param int $id
     * @return bool
     */
    public function deletePlatform(int $id): bool
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
     * Get paginated platforms with search for admin index
     *
     * @param string|null $search
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginatedPlatforms(?string $search = null, int $perPage = 10)
    {
        try {
            return Platform::with([
                'businessSector',
                'pendingTypeChangeRequest',
                'pendingValidationRequest',
                'pendingChangeRequest'
            ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('type', 'like', '%' . $search . '%')
                      ->orWhere('id', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
        } catch (\Exception $e) {
            Log::error('Error fetching paginated platforms: ' . $e->getMessage());
            return Platform::paginate(0); // Return empty paginator
        }
    }

    /**
     * Get platform for show page with relationships and counts
     *
     * @param int $id
     * @return Platform|null
     */
    public function getPlatformForShow(int $id): ?Platform
    {
        try {
            return Platform::with(['businessSector', 'logoImage', 'deals', 'items', 'coupons'])
                ->withCount(['deals', 'items', 'coupons'])
                ->findOrFail($id);
        } catch (\Exception $e) {
            Log::error('Error fetching platform for show: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if platform is enabled
     *
     * @param int $id
     * @return bool
     */
    public function isPlatformEnabled(int $id): bool
    {
        try {
            $platform = Platform::find($id);
            return $platform ? $platform->enabled : false;
        } catch (\Exception $e) {
            Log::error('Error checking if platform is enabled: ' . $e->getMessage());
            return false;
        }
    }
}

