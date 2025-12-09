<?php

namespace App\Services\Items;

use App\Models\Item;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

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
     * Find an item by ID
     *
     * @param int $id
     * @return Item|null
     */
    public function findItem(int $id): ?Item
    {
        return Item::find($id);
    }

    /**
     * Find an item by ID or fail
     *
     * @param int $id
     * @return Item
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findItemOrFail(int $id): Item
    {
        return Item::findOrFail($id);
    }

    /**
     * Create a new item
     *
     * @param array $data
     * @return Item
     */
    public function createItem(array $data): Item
    {
        return Item::create($data);
    }

    /**
     * Update an item
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateItem(int $id, array $data): bool
    {
        return Item::where('id', $id)->update($data);
    }

    /**
     * Handle image upload for item
     *
     * @param Item $item
     * @param TemporaryUploadedFile|null $image
     * @return void
     */
    public function handleImageUpload(Item $item, ?TemporaryUploadedFile $image): void
    {
        if (!$image) {
            return;
        }

        // Delete existing image if present
        if (!is_null($item->thumbnailsImage)) {
            Storage::disk('public2')->delete($item->thumbnailsImage->url);
            $item->thumbnailsImage()->delete();
        }

        // Store new image
        $imagePath = $image->store('business-sectors/' . Item::IMAGE_TYPE_THUMBNAILS, 'public2');
        $item->thumbnailsImage()->create([
            'url' => $imagePath,
            'type' => Item::IMAGE_TYPE_THUMBNAILS,
        ]);
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

    /**
     * Get items by deal ID
     *
     * @param int|null $dealId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getItemsByDeal(?int $dealId = null)
    {
        $query = Item::select('id', 'name', 'ref')
            ->where('ref', '!=', '#0001')
            ->orderBy('name');

        if ($dealId) {
            $query->where('deal_id', $dealId);
        }

        return $query->get();
    }
}
