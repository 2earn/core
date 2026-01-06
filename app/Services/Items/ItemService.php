<?php

namespace App\Services\Items;

use App\Models\Item;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
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

        if (!is_null($search) && !empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

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

        if (!is_null($item->thumbnailsImage)) {
            Storage::disk('public2')->delete($item->thumbnailsImage->url);
            $item->thumbnailsImage()->delete();
        }

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

    /**
     * Get all items for a specific deal with complete details
     *
     * @param int $dealId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getItemsForDeal(int $dealId)
    {
        return Item::where('deal_id', $dealId)
            ->where('ref', '!=', '#0001')
            ->get();
    }

    /**
     * Aggregate top-selling items from a query builder
     *
     * @param Builder $query Query builder with order_details joined to orders and items
     * @param int $limit Maximum number of items to return
     * @return array Array of products with name and sale count
     */
    public function aggregateTopSellingItems(Builder $query, int $limit = 10): array
    {
        $topProducts = $query
            ->select(
                'items.name as product_name',
                DB::raw('SUM(order_details.qty) as sale_count'),
                DB::raw('SUM(order_details.amount_after_partner_discount) as partner_benefit')
            )
            ->groupBy('items.id', 'items.name')
            ->orderByDesc('sale_count')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'product_name' => $item->product_name,
                    'sale_count' => (int) $item->sale_count,
                    'partner_benefit' => (float) $item->partner_benefit,
                ];
            });

        return $topProducts->toArray();
    }

    /**
     * Bulk update items to assign them to a deal
     *
     * @param array $itemIds Array of item IDs to update
     * @param int $dealId Deal ID to assign to the items
     * @return int Number of items updated
     */
    public function bulkUpdateDeal(array $itemIds, int $dealId): int
    {
        return Item::whereIn('id', $itemIds)->update(['deal_id' => $dealId]);
    }

    /**
     * Bulk remove items from a deal
     *
     * @param array $itemIds Array of item IDs to remove from the deal
     * @param int $dealId Deal ID to verify items belong to
     * @return int Number of items removed
     */
    public function bulkRemoveFromDeal(array $itemIds, int $dealId): int
    {
        return Item::whereIn('id', $itemIds)
            ->where('deal_id', $dealId)
            ->update(['deal_id' => null]);
    }

    /**
     * Find item by ref and platform_id
     *
     * @param string $ref
     * @param int $platformId
     * @return Item|null
     */
    public function findByRefAndPlatform(string $ref, int $platformId): ?Item
    {
        return Item::where('ref', $ref)
            ->where('platform_id', $platformId)
            ->first();
    }

    /**
     * Get items with user purchase history
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getItemsWithUserPurchases(int $userId): \Illuminate\Database\Eloquent\Collection
    {
        try {
            return Item::whereHas('OrderDetails.order', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
                ->distinct()
                ->get();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error fetching items with user purchases: ' . $e->getMessage(), [
                'user_id' => $userId
            ]);
            return new \Illuminate\Database\Eloquent\Collection();
        }
    }
}
