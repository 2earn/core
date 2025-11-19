<?php

namespace App\Services\BusinessSector;

use App\Models\BusinessSector;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\Log;

class BusinessSectorService
{
    /**
     * Get all business sectors with optional filters
     *
     * @param array $filters
     * @return EloquentCollection
     */
    public function getBusinessSectors(array $filters = []): EloquentCollection
    {
        try {
            $query = BusinessSector::query();

            // Apply search filter
            if (!empty($filters['search'])) {
                $query->where(function($q) use ($filters) {
                    $q->where('name', 'like', '%' . $filters['search'] . '%')
                      ->orWhere('description', 'like', '%' . $filters['search'] . '%');
                });
            }

            // Apply color filter
            if (!empty($filters['color'])) {
                $query->where('color', $filters['color']);
            }

            // Load relationships if specified
            if (!empty($filters['with'])) {
                $query->with($filters['with']);
            }

            // Apply ordering
            $orderBy = $filters['order_by'] ?? 'created_at';
            $orderDirection = $filters['order_direction'] ?? 'desc';
            $query->orderBy($orderBy, $orderDirection);

            return $query->get();
        } catch (\Exception $e) {
            Log::error('Error fetching business sectors: ' . $e->getMessage());
            return new EloquentCollection();
        }
    }

    /**
     * Get business sector by ID with relationships
     *
     * @param int $id
     * @param array $with
     * @return BusinessSector|null
     */
    public function getBusinessSectorById(int $id, array $with = []): ?BusinessSector
    {
        try {
            $query = BusinessSector::query();

            if (!empty($with)) {
                $query->with($with);
            }

            return $query->find($id);
        } catch (\Exception $e) {
            Log::error('Error fetching business sector by ID: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get business sector with all images
     *
     * @param int $id
     * @return BusinessSector|null
     */
    public function getBusinessSectorWithImages(int $id): ?BusinessSector
    {
        try {
            return BusinessSector::with(['logoImage', 'thumbnailsImage', 'thumbnailsHomeImage'])
                ->find($id);
        } catch (\Exception $e) {
            Log::error('Error fetching business sector with images: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get business sectors with platform counts
     *
     * @return EloquentCollection
     */
    public function getBusinessSectorsWithCounts(): EloquentCollection
    {
        try {
            return BusinessSector::withCount([
                'platforms',
                'platforms as enabled_platforms_count' => function($query) {
                    $query->where('enabled', true);
                }
            ])
            ->with(['logoImage', 'thumbnailsImage'])
            ->orderBy('created_at', 'desc')
            ->get();
        } catch (\Exception $e) {
            Log::error('Error fetching business sectors with counts: ' . $e->getMessage());
            return new EloquentCollection();
        }
    }

    /**
     * Get business sectors with enabled platforms
     *
     * @return EloquentCollection
     */
    public function getBusinessSectorsWithEnabledPlatforms(): EloquentCollection
    {
        try {
            return BusinessSector::with([
                'logoImage',
                'thumbnailsImage',
                'platforms' => function($query) {
                    $query->where('enabled', true)
                          ->with('logoImage')
                          ->orderBy('created_at');
                }
            ])
            ->has('platforms')
            ->orderBy('created_at', 'desc')
            ->get();
        } catch (\Exception $e) {
            Log::error('Error fetching business sectors with enabled platforms: ' . $e->getMessage());
            return new EloquentCollection();
        }
    }

    /**
     * Get business sector statistics
     *
     * @param int|null $businessSectorId
     * @return array
     */
    public function getStatistics(?int $businessSectorId = null): array
    {
        try {
            $query = BusinessSector::query();

            if ($businessSectorId) {
                $query->where('id', $businessSectorId);
            }

            $totalSectors = $query->count();
            $sectorsWithPlatforms = (clone $query)->has('platforms')->count();
            $totalPlatforms = (clone $query)->withCount('platforms')->get()->sum('platforms_count');
            $enabledPlatforms = (clone $query)->withCount([
                'platforms as enabled_platforms_count' => function($q) {
                    $q->where('enabled', true);
                }
            ])->get()->sum('enabled_platforms_count');

            return [
                'total_sectors' => $totalSectors,
                'sectors_with_platforms' => $sectorsWithPlatforms,
                'total_platforms' => $totalPlatforms,
                'enabled_platforms' => $enabledPlatforms,
            ];
        } catch (\Exception $e) {
            Log::error('Error getting business sector statistics: ' . $e->getMessage());
            return [
                'total_sectors' => 0,
                'sectors_with_platforms' => 0,
                'total_platforms' => 0,
                'enabled_platforms' => 0,
            ];
        }
    }

    /**
     * Create a new business sector
     *
     * @param array $data
     * @return BusinessSector|null
     */
    public function createBusinessSector(array $data): ?BusinessSector
    {
        try {
            return BusinessSector::create($data);
        } catch (\Exception $e) {
            Log::error('Error creating business sector: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update business sector data
     *
     * @param int $id
     * @param array $data
     * @return BusinessSector|null
     */
    public function updateBusinessSector(int $id, array $data): ?BusinessSector
    {
        try {
            $businessSector = BusinessSector::findOrFail($id);
            $businessSector->update($data);
            return $businessSector->fresh();
        } catch (\Exception $e) {
            Log::error('Error updating business sector: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete business sector
     *
     * @param int $id
     * @return bool
     */
    public function deleteBusinessSector(int $id): bool
    {
        try {
            $businessSector = BusinessSector::findOrFail($id);
            return $businessSector->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting business sector: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if business sector has platforms
     *
     * @param int $id
     * @return bool
     */
    public function hasPlatforms(int $id): bool
    {
        try {
            return BusinessSector::where('id', $id)->has('platforms')->exists();
        } catch (\Exception $e) {
            Log::error('Error checking if business sector has platforms: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get business sectors for dropdown/select
     *
     * @return EloquentCollection
     */
    public function getForSelect(): EloquentCollection
    {
        try {
            return BusinessSector::select('id', 'name', 'color')
                ->orderBy('name')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error fetching business sectors for select: ' . $e->getMessage());
            return new EloquentCollection();
        }
    }

    /**
     * Search business sectors
     *
     * @param string $searchTerm
     * @param int $limit
     * @return EloquentCollection
     */
    public function search(string $searchTerm, int $limit = 10): EloquentCollection
    {
        try {
            return BusinessSector::where('name', 'like', '%' . $searchTerm . '%')
                ->orWhere('description', 'like', '%' . $searchTerm . '%')
                ->with(['logoImage'])
                ->limit($limit)
                ->get();
        } catch (\Exception $e) {
            Log::error('Error searching business sectors: ' . $e->getMessage());
            return new EloquentCollection();
        }
    }
}

