<?php

namespace App\Services\Hashtag;

use App\Models\Hashtag;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class HashtagService
{
    /**
     * Get all hashtags with optional filters
     *
     * @param array $filters
     * @return EloquentCollection|\Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getHashtags(array $filters = [])
    {
        try {
            $query = Hashtag::query();

            if (!empty($filters['search'])) {
                $query->where(function($q) use ($filters) {
                    $q->where('name', 'like', '%' . $filters['search'] . '%')
                      ->orWhere('slug', 'like', '%' . $filters['search'] . '%');
                });
            }

            if (!empty($filters['with'])) {
                $query->with($filters['with']);
            }

            $orderBy = $filters['order_by'] ?? 'id';
            $orderDirection = $filters['order_direction'] ?? 'desc';
            $query->orderBy($orderBy, $orderDirection);

            if (!empty($filters['PAGE_SIZE'])) {
                return $query->paginate($filters['PAGE_SIZE']);
            }

            return $query->get();
        } catch (\Exception $e) {
            Log::error('Error fetching hashtags: ' . $e->getMessage());
            return new EloquentCollection();
        }
    }

    /**
     * Get hashtag by ID
     *
     * @param int $id
     * @param array $with
     * @return Hashtag|null
     */
    public function getHashtagById(int $id, array $with = []): ?Hashtag
    {
        try {
            $query = Hashtag::query();

            if (!empty($with)) {
                $query->with($with);
            }

            return $query->find($id);
        } catch (\Exception $e) {
            Log::error('Error fetching hashtag by ID: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create a new hashtag
     *
     * @param array $data
     * @return Hashtag|null
     */
    public function createHashtag(array $data): ?Hashtag
    {
        try {
            // Generate slug if not provided
            if (empty($data['slug']) && !empty($data['name'])) {
                $data['slug'] = Str::slug($data['name']);
            }

            return Hashtag::create($data);
        } catch (\Exception $e) {
            Log::error('Error creating hashtag: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update an existing hashtag
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateHashtag(int $id, array $data): bool
    {
        try {
            $hashtag = Hashtag::findOrFail($id);

            // Generate slug if name is updated
            if (!empty($data['name']) && empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
            }

            return $hashtag->update($data);
        } catch (\Exception $e) {
            Log::error('Error updating hashtag: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a hashtag
     *
     * @param int $id
     * @return bool
     */
    public function deleteHashtag(int $id): bool
    {
        try {
            $hashtag = Hashtag::findOrFail($id);
            return $hashtag->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting hashtag: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if hashtag name exists
     *
     * @param string $name
     * @param int|null $exceptId
     * @return bool
     */
    public function hashtagExists(string $name, ?int $exceptId = null): bool
    {
        try {
            $query = Hashtag::where('name', $name);

            if ($exceptId) {
                $query->where('id', '!=', $exceptId);
            }

            return $query->exists();
        } catch (\Exception $e) {
            Log::error('Error checking hashtag existence: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get hashtag by slug
     *
     * @param string $slug
     * @return Hashtag|null
     */
    public function getHashtagBySlug(string $slug): ?Hashtag
    {
        try {
            return Hashtag::where('slug', $slug)->first();
        } catch (\Exception $e) {
            Log::error('Error fetching hashtag by slug: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get all hashtags
     *
     * @return EloquentCollection
     */
    public function getAll(): EloquentCollection
    {
        try {
            return Hashtag::all();
        } catch (\Exception $e) {
            Log::error('Error fetching all hashtags: ' . $e->getMessage());
            return new EloquentCollection();
        }
    }
}

