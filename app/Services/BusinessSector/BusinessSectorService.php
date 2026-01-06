<?php

namespace App\Services\BusinessSector;

use App\Models\BusinessSector;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class BusinessSectorService
{
    /**
     * Get all business sectors
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        try {
            return BusinessSector::all();
        } catch (\Exception $e) {
            Log::error('Error fetching all business sectors: ' . $e->getMessage());
            return new Collection();
        }
    }

    /**
     * Get business sectors with filters, pagination and relations
     *
     * @param array $params
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|Collection
     */
    public function getBusinessSectors(array $params = [])
    {
        try {
            $query = BusinessSector::query();

            if (isset($params['with']) && is_array($params['with'])) {
                $query->with($params['with']);
            }

            if (isset($params['search']) && !empty($params['search'])) {
                $search = $params['search'];
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            }

            if (isset($params['order_by'])) {
                $orderDirection = $params['order_direction'] ?? 'asc';
                $query->orderBy($params['order_by'], $orderDirection);
            }

            if (isset($params['PAGE_SIZE'])) {
                $page = $params['page'] ?? 1;
                return $query->paginate($params['PAGE_SIZE'], ['*'], 'page', $page);
            }

            return $query->get();
        } catch (\Exception $e) {
            Log::error('Error fetching business sectors: ' . $e->getMessage(), [
                'params' => $params
            ]);
            return new Collection();
        }
    }

    /**
     * Get business sector by ID
     *
     * @param int $id
     * @return BusinessSector|null
     */
    public function getById(int $id): ?BusinessSector
    {
        try {
            return BusinessSector::find($id);
        } catch (\Exception $e) {
            Log::error('Error fetching business sector by ID: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get business sector with images by ID
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
            Log::error('Error fetching business sector with images: ' . $e->getMessage(), [
                'id' => $id
            ]);
            return null;
        }
    }

    /**
     * Get business sectors with user purchase history
     *
     * @param int $userId
     * @return Collection
     */
    public function getSectorsWithUserPurchases(int $userId): Collection
    {
        try {
            return BusinessSector::whereHas('platforms.items.orderDetails.order', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
                ->distinct()
                ->get();
        } catch (\Exception $e) {
            Log::error('Error fetching business sectors with user purchases: ' . $e->getMessage(), [
                'user_id' => $userId
            ]);
            return new Collection();
        }
    }

    /**
     * Find business sector by ID or fail
     *
     * @param int $id
     * @return BusinessSector
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): BusinessSector
    {
        return BusinessSector::findOrFail($id);
    }

    /**
     * Create a new business sector
     *
     * @param array $data
     * @return BusinessSector|null
     */
    public function create(array $data): ?BusinessSector
    {
        try {
            return BusinessSector::create($data);
        } catch (\Exception $e) {
            Log::error('Error creating business sector: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update a business sector
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        try {
            $sector = BusinessSector::findOrFail($id);
            return $sector->update($data);
        } catch (\Exception $e) {
            Log::error('Error updating business sector: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a business sector
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        try {
            $sector = BusinessSector::findOrFail($id);
            return $sector->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting business sector: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a business sector (alias for delete method)
     *
     * @param int $id
     * @return bool
     */
    public function deleteBusinessSector(int $id): bool
    {
        return $this->delete($id);
    }
}
