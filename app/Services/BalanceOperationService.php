<?php

namespace App\Services;

use App\Models\BalanceOperation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class BalanceOperationService
{
    /**
     * Get all balance operations
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return BalanceOperation::all();
    }

    /**
     * Find balance operation by ID
     *
     * @param int $id
     * @return BalanceOperation|null
     */
    public function findById(int $id): ?BalanceOperation
    {
        return BalanceOperation::find($id);
    }

    /**
     * Find balance operation by ID or fail
     *
     * @param int $id
     * @return BalanceOperation
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findByIdOrFail(int $id): BalanceOperation
    {
        return BalanceOperation::findOrFail($id);
    }

    /**
     * Update balance operation
     *
     * @param int $id
     * @param array $data
     * @return BalanceOperation|null
     */
    public function update(int $id, array $data): ?BalanceOperation
    {
        try {
            $balance = BalanceOperation::find($id);
            if (!$balance) {
                Log::error('Balance operation not found', ['id' => $id]);
                return null;
            }

            $balance->update($data);
            return $balance->fresh();
        } catch (\Exception $e) {
            Log::error('Error updating balance operation: ' . $e->getMessage(), [
                'id' => $id,
                'data' => $data
            ]);
            return null;
        }
    }

    /**
     * Create a new balance operation
     *
     * @param array $data
     * @return BalanceOperation|null
     */
    public function create(array $data): ?BalanceOperation
    {
        try {
            return BalanceOperation::create($data);
        } catch (\Exception $e) {
            Log::error('Error creating balance operation: ' . $e->getMessage(), ['data' => $data]);
            return null;
        }
    }

    /**
     * Delete balance operation
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        try {
            $balance = BalanceOperation::find($id);
            if (!$balance) {
                return false;
            }
            return $balance->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting balance operation: ' . $e->getMessage(), ['id' => $id]);
            return false;
        }
    }

    /**
     * Get multiplicator for balance operation
     *
     * @param int $balanceOperationId
     * @return int
     */
    public function getMultiplicator(int $balanceOperationId): int
    {
        return BalanceOperation::getMultiplicator($balanceOperationId);
    }
}

