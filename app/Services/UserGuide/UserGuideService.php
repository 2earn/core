<?php

namespace App\Services\UserGuide;

use App\Models\UserGuide;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class UserGuideService
{
    /**
     * Get a user guide by ID with relationships
     *
     * @param int $id
     * @return UserGuide|null
     */
    public function getById(int $id): ?UserGuide
    {
        return UserGuide::with('user')->find($id);
    }

    /**
     * Get a user guide by ID or fail
     *
     * @param int $id
     * @return UserGuide
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getByIdOrFail(int $id): UserGuide
    {
        return UserGuide::with('user')->findOrFail($id);
    }

    /**
     * Get paginated user guides with search
     *
     * @param string|null $search
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginated(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        $query = UserGuide::with('user');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Get all user guides
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return UserGuide::with('user')->latest()->get();
    }

    /**
     * Create a new user guide
     *
     * @param array $data
     * @return UserGuide
     */
    public function create(array $data): UserGuide
    {
        return UserGuide::create($data);
    }

    /**
     * Update a user guide
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $guide = UserGuide::findOrFail($id);
        return $guide->update($data);
    }

    /**
     * Delete a user guide
     *
     * @param int $id
     * @return bool|null
     * @throws \Exception
     */
    public function delete(int $id): ?bool
    {
        $guide = UserGuide::findOrFail($id);
        return $guide->delete();
    }

    /**
     * Search user guides by title or description
     *
     * @param string $search
     * @return Collection
     */
    public function search(string $search): Collection
    {
        return UserGuide::with('user')
            ->where(function($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->latest()
            ->get();
    }

    /**
     * Get user guides by route name
     *
     * @param string $routeName
     * @return Collection
     */
    public function getByRouteName(string $routeName): Collection
    {
        return UserGuide::with('user')
            ->whereJsonContains('routes', $routeName)
            ->latest()
            ->get();
    }

    /**
     * Get user guides created by a specific user
     *
     * @param int $userId
     * @return Collection
     */
    public function getByUserId(int $userId): Collection
    {
        return UserGuide::with('user')
            ->where('user_id', $userId)
            ->latest()
            ->get();
    }

    /**
     * Check if a user guide exists
     *
     * @param int $id
     * @return bool
     */
    public function exists(int $id): bool
    {
        return UserGuide::where('id', $id)->exists();
    }

    /**
     * Get total count of user guides
     *
     * @return int
     */
    public function count(): int
    {
        return UserGuide::count();
    }

    /**
     * Get recent user guides
     *
     * @param int $limit
     * @return Collection
     */
    public function getRecent(int $limit = 5): Collection
    {
        return UserGuide::with('user')
            ->latest()
            ->limit($limit)
            ->get();
    }
}

