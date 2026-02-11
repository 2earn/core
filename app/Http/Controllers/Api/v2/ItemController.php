<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Services\Items\ItemService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    private ItemService $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    /**
     * Get items with pagination and search
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $items = $this->itemService->getItems(
            $request->input('search'),
            $request->input('per_page', 5)
        );

        return response()->json([
            'status' => true,
            'data' => $items->items(),
            'pagination' => [
                'current_page' => $items->currentPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
                'last_page' => $items->lastPage()
            ]
        ]);
    }

    /**
     * Get items by platform
     */
    public function getByPlatform(Request $request, int $platformId)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $items = $this->itemService->getItemsByPlatform(
            $platformId,
            $request->input('search'),
            $request->input('per_page', 15)
        );

        return response()->json([
            'status' => true,
            'data' => $items->items(),
            'pagination' => [
                'current_page' => $items->currentPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
                'last_page' => $items->lastPage()
            ]
        ]);
    }

    /**
     * Get items by deal
     */
    public function getByDeal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'deal_id' => 'nullable|integer|exists:deals,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $items = $this->itemService->getItemsByDeal($request->input('deal_id'));

        return response()->json(['status' => true, 'data' => $items]);
    }

    /**
     * Get all items for a deal
     */
    public function getForDeal(int $dealId)
    {
        $items = $this->itemService->getItemsForDeal($dealId);

        return response()->json(['status' => true, 'data' => $items]);
    }

    /**
     * Get items with user purchases
     */
    public function getWithUserPurchases(int $userId)
    {
        $items = $this->itemService->getItemsWithUserPurchases($userId);

        return response()->json(['status' => true, 'data' => $items]);
    }

    /**
     * Get item by ID
     */
    public function show(int $id)
    {
        $item = $this->itemService->findItem($id);

        if (!$item) {
            return response()->json(['status' => false, 'message' => 'Item not found'], 404);
        }

        return response()->json(['status' => true, 'data' => $item]);
    }

    /**
     * Find item by ref and platform
     */
    public function findByRefAndPlatform(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ref' => 'required|string',
            'platform_id' => 'required|integer|exists:platforms,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $item = $this->itemService->findByRefAndPlatform(
            $request->input('ref'),
            $request->input('platform_id')
        );

        if (!$item) {
            return response()->json(['status' => false, 'message' => 'Item not found'], 404);
        }

        return response()->json(['status' => true, 'data' => $item]);
    }

    /**
     * Create item
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'ref' => 'required|string|max:255',
            'platform_id' => 'required|integer|exists:platforms,id',
            'deal_id' => 'nullable|integer|exists:deals,id',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $item = $this->itemService->createItem($request->all());

        return response()->json(['status' => true, 'data' => $item, 'message' => 'Item created successfully'], 201);
    }

    /**
     * Update item
     */
    public function update(Request $request, int $id)
    {
        $item = $this->itemService->findItem($id);

        if (!$item) {
            return response()->json(['status' => false, 'message' => 'Item not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'ref' => 'nullable|string|max:255',
            'deal_id' => 'nullable|integer|exists:deals,id',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $success = $this->itemService->updateItem($id, $request->all());

        return response()->json([
            'status' => $success,
            'message' => $success ? 'Item updated successfully' : 'Update failed'
        ]);
    }

    /**
     * Delete item
     */
    public function destroy(int $id)
    {
        try {
            $success = $this->itemService->deleteItem($id);
            return response()->json(['status' => true, 'message' => 'Item deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 404);
        }
    }

    /**
     * Bulk update items to assign to deal
     */
    public function bulkUpdateDeal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_ids' => 'required|array',
            'item_ids.*' => 'integer|exists:items,id',
            'deal_id' => 'required|integer|exists:deals,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $count = $this->itemService->bulkUpdateDeal(
            $request->input('item_ids'),
            $request->input('deal_id')
        );

        return response()->json([
            'status' => true,
            'message' => 'Items updated successfully',
            'updated_count' => $count
        ]);
    }

    /**
     * Bulk remove items from deal
     */
    public function bulkRemoveFromDeal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_ids' => 'required|array',
            'item_ids.*' => 'integer|exists:items,id',
            'deal_id' => 'required|integer|exists:deals,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $count = $this->itemService->bulkRemoveFromDeal(
            $request->input('item_ids'),
            $request->input('deal_id')
        );

        return response()->json([
            'status' => true,
            'message' => 'Items removed from deal successfully',
            'removed_count' => $count
        ]);
    }
}

