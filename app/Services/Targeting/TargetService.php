<?php

namespace App\Services\Targeting;

use App\Models\Target;
use Illuminate\Support\Facades\Log;

class TargetService
{
    /**
     * Get a target by ID
     *
     * @param int $id
     * @return Target|null
     */
    public function getById(int $id): ?Target
    {
        try {
            return Target::find($id);
        } catch (\Exception $e) {
            Log::error('Error fetching target by ID: ' . $e->getMessage(), ['id' => $id]);
            return null;
        }
    }

    /**
     * Get a target by ID or fail
     *
     * @param int $id
     * @return Target
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getByIdOrFail(int $id): Target
    {
        return Target::findOrFail($id);
    }

    /**
     * Create a new target
     *
     * @param array $data
     * @return Target|null
     */
    public function create(array $data): ?Target
    {
        try {
            return Target::create($data);
        } catch (\Exception $e) {
            Log::error('Error creating target: ' . $e->getMessage(), ['data' => $data]);
            throw $e;
        }
    }

    /**
     * Update a target
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        try {
            return Target::where('id', $id)->update($data);
        } catch (\Exception $e) {
            Log::error('Error updating target: ' . $e->getMessage(), [
                'id' => $id,
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Delete a target
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        try {
            $target = Target::findOrFail($id);
            return $target->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting target: ' . $e->getMessage(), ['id' => $id]);
            throw $e;
        }
    }

    /**
     * Check if a target exists
     *
     * @param int $id
     * @return bool
     */
    public function exists(int $id): bool
    {
        try {
            return Target::where('id', $id)->exists();
        } catch (\Exception $e) {
            Log::error('Error checking target existence: ' . $e->getMessage(), ['id' => $id]);
            return false;
        }
    }

    /**
     * Get all targets
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        try {
            return Target::all();
        } catch (\Exception $e) {
            Log::error('Error fetching all targets: ' . $e->getMessage());
            return collect();
        }
    }
}

