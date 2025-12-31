<?php

namespace App\Services\Targeting;

use App\Models\Group;
use Illuminate\Support\Facades\Log;

class GroupService
{
    /**
     * Get group by ID or fail
     *
     * @param int $id
     * @return Group
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getByIdOrFail(int $id): Group
    {
        return Group::findOrFail($id);
    }

    /**
     * Get group by ID
     *
     * @param int $id
     * @return Group|null
     */
    public function getById(int $id): ?Group
    {
        try {
            return Group::find($id);
        } catch (\Exception $e) {
            Log::error('Error fetching group by ID: ' . $e->getMessage(), ['id' => $id]);
            return null;
        }
    }

    /**
     * Create a new group
     *
     * @param array $data
     * @return Group|null
     */
    public function create(array $data): ?Group
    {
        try {
            return Group::create($data);
        } catch (\Exception $e) {
            Log::error('Error creating group: ' . $e->getMessage(), ['data' => $data]);
            return null;
        }
    }

    /**
     * Update a group
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        try {
            return Group::where('id', $id)->update($data);
        } catch (\Exception $e) {
            Log::error('Error updating group: ' . $e->getMessage(), ['id' => $id, 'data' => $data]);
            return false;
        }
    }

    /**
     * Delete a group
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        try {
            $group = Group::findOrFail($id);
            return $group->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting group: ' . $e->getMessage(), ['id' => $id]);
            return false;
        }
    }
}

