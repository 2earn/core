<?php

namespace App\Services\Balances;

use App\Models\OperationCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OperationCategoryService
{
    public function getFilteredCategories(
        ?string $search = null,
        int $perPage = 10
    ): LengthAwarePaginator
    {
        return OperationCategory::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('code', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%')
                        ->orWhere('id', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }

    public function getCategoryById(int $id): ?OperationCategory
    {
        return OperationCategory::find($id);
    }

    public function getAllCategories()
    {
        return OperationCategory::orderBy('id', 'desc')->get();
    }

    /**
     * Get all categories (alias for getAllCategories for consistency)
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAll()
    {
        return OperationCategory::all();
    }

    public function createCategory(array $data): OperationCategory
    {
        return OperationCategory::create($data);
    }

    public function updateCategory(int $id, array $data): bool
    {
        $category = $this->getCategoryById($id);

        if (!$category) {
            return false;
        }

        return $category->update($data);
    }

    public function deleteCategory(int $id): bool
    {
        $category = $this->getCategoryById($id);

        if (!$category) {
            return false;
        }

        return $category->delete();
    }
}

