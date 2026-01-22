<?php

namespace App\Services\Targeting;

use App\Models\Condition;
use Illuminate\Support\Facades\Log;

class ConditionService
{
    /**
     * Get condition by ID
     *
     * @param int $id
     * @return Condition|null
     */
    public function getById(int $id): ?Condition
    {
        try {
            return Condition::find($id);
        } catch (\Exception $e) {
            Log::error('Error fetching condition by ID: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get condition by ID or fail
     *
     * @param int $id
     * @return Condition
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getByIdOrFail(int $id): Condition
    {
        return Condition::findOrFail($id);
    }

    /**
     * Create a new condition
     *
     * @param array $data
     * @return Condition|null
     */
    public function create(array $data): ?Condition
    {
        try {
            return Condition::create($data);
        } catch (\Exception $e) {
            Log::error('Error creating condition: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update a condition
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        try {
            return Condition::where('id', $id)->update($data);
        } catch (\Exception $e) {
            Log::error('Error updating condition: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a condition
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        try {
            $condition = Condition::findOrFail($id);
            return $condition->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting condition: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get operators from Condition model
     *
     * @return array
     */
    public function getOperators(): array
    {
        return Condition::$operators;
    }

    /**
     * Get operands from Condition model
     *
     * @return array
     */
    public function getOperands(): array
    {
        return Condition::operands();
    }
}

