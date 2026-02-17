<?php

namespace App\Services\Balances;

use App\Models\BalanceOperation;
use App\Models\OperationCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BalanceOperationService
{
    public function getFilteredOperations(
        ?string $search = null,
        int $perPage = 10
    ): LengthAwarePaginator {
        return BalanceOperation::with(['parent', 'operationCategory'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('id', 'like', '%' . $search . '%')
                        ->orWhere('operation', 'like', '%' . $search . '%')
                        ->orWhere('balance_id', 'like', '%' . $search . '%')
                        ->orWhere('parent_operation_id', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }

    public function getOperationById(int $id): ?BalanceOperation
    {
        return BalanceOperation::with(['parent', 'operationCategory'])->find($id);
    }

    public function getAllOperations()
    {
        return BalanceOperation::with(['parent', 'operationCategory'])
            ->orderBy('id', 'desc')
            ->get();
    }

    public function createOperation(array $data): BalanceOperation
    {
        return BalanceOperation::create($data);
    }

    public function updateOperation(int $id, array $data): bool
    {
        $operation = $this->getOperationById($id);

        if (!$operation) {
            return false;
        }

        return $operation->update($data);
    }

    public function deleteOperation(int $id): bool
    {
        $operation = $this->getOperationById($id);

        if (!$operation) {
            return false;
        }

        return $operation->delete();
    }

    public function getOperationCategoryName(?int $categoryId): string
    {
        if (!$categoryId) {
            return '-';
        }

        $category = OperationCategory::find($categoryId);
        return $category ? $category->name : '-';
    }
}

