<?php

namespace App\Services\Platform;

use Core\Models\Platform;
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
}

