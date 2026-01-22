<?php

namespace App\Services;

use App\Models\action_historys;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class ActionHistorysService
{
    /**
     * Get action history by ID
     *
     * @param int $id
     * @return action_historys|null
     */
    public function getById(int $id): ?action_historys
    {
        try {
            return action_historys::find($id);
        } catch (\Exception $e) {
            Log::error('Error fetching action history by ID: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get paginated action histories with search
     *
     * @param string|null $search
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginated(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        try {
            return action_historys::query()
                ->when($search, function ($query) use ($search) {
                    $query->where('title', 'like', '%' . $search . '%');
                })
                ->paginate($perPage);
        } catch (\Exception $e) {
            Log::error('Error fetching paginated action histories: ' . $e->getMessage());
            return action_historys::paginate($perPage);
        }
    }

    /**
     * Get all action histories
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        try {
            return action_historys::all();
        } catch (\Exception $e) {
            Log::error('Error fetching all action histories: ' . $e->getMessage());
            return new \Illuminate\Database\Eloquent\Collection();
        }
    }

    /**
     * Update an action history
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        try {
            $actionHistory = action_historys::find($id);
            if (!$actionHistory) {
                return false;
            }

            foreach ($data as $key => $value) {
                $actionHistory->$key = $value;
            }

            return $actionHistory->save();
        } catch (\Exception $e) {
            Log::error('Error updating action history: ' . $e->getMessage());
            return false;
        }
    }
}

