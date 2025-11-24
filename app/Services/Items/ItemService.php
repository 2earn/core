<?php

namespace App\Services\Items;

use App\Models\Item;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ItemService
{
    /**
     * Get items with optional search filter
     *
     * @param string|null $search
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getItems(?string $search = null, int $perPage = 5): LengthAwarePaginator
    {
        $query = Item::query();

        // Apply search filter
        if (!is_null($search) && !empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Order by created date descending
        $query->orderBy('created_at', 'desc');

        return $query->paginate($perPage);
    }

    /**
     * Delete an item by ID
     *
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function deleteItem(int $id): bool
    {
        $item = Item::findOrFail($id);
        return $item->delete();
    }
}

