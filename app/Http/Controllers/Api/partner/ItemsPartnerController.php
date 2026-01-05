<?php

namespace App\Http\Controllers\Api\partner;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\DealProductChange;
use App\Models\Item;
use App\Models\User;
use App\Notifications\ItemsAddedToDeal;
use App\Notifications\ItemsRemovedFromDeal;
use App\Services\Deals\DealService;
use App\Services\Items\ItemService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class ItemsPartnerController extends Controller
{
    private const LOG_PREFIX = '[ItemsPartnerController] ';

    protected ItemService $itemService;
    protected DealService $dealService;

    public function __construct(ItemService $itemService, DealService $dealService)
    {
        $this->itemService = $itemService;
        $this->dealService = $dealService;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'ref' => 'required|string|unique:items,ref',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'photo_link' => 'nullable|string',
            'discount' => 'nullable|numeric|min:0',
            'discount_2earn' => 'nullable|numeric|min:0',
            'platform_id' => 'nullable|exists:platforms,id',
            'deal_id' => 'nullable|exists:deals,id',
            'created_by' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = $request->all();
        if (!isset($data['stock'])) {
            $data['stock'] = 0;
        }
        $item = Item::create($data);

        Log::info(self::LOG_PREFIX . 'Item created successfully', ['id' => $item->id]);
        return response()->json([
            'status' => 'Success',
            'message' => 'Item created successfully',
            'data' => $item
        ], Response::HTTP_CREATED);
    }

    public function update(Request $request, $itemId)
    {
        $item = Item::find($itemId);
        if (!$item) {
            Log::error(self::LOG_PREFIX . 'Item not found', ['id' => $itemId]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Item not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'ref' => 'sometimes|string|unique:items,ref,' . $itemId,
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'description' => 'nullable|string',
            'photo_link' => 'nullable|string',
            'discount' => 'nullable|numeric|min:0',
            'discount_2earn' => 'nullable|numeric|min:0',
            'platform_id' => 'sometimes|exists:platforms,id',
            'deal_id' => 'nullable|exists:deals,id',
            'updated_by' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = $request->all();
        $item->update($data);

        Log::info(self::LOG_PREFIX . 'Item updated successfully', ['id' => $itemId]);
        return response()->json([
            'status' => 'Success',
            'message' => 'Item updated successfully',
            'data' => $item
        ], Response::HTTP_OK);
    }

    public function listItemsForDeal($dealId)
    {
        $deal = $this->dealService->find($dealId);

        if (!$deal) {
            Log::error(self::LOG_PREFIX . 'Deal not found', ['id' => $dealId]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Deal not found'
            ], Response::HTTP_NOT_FOUND);
        }

        if (!$deal->validated) {
            Log::warning(self::LOG_PREFIX . 'Deal is not active', ['id' => $dealId, 'status' => $deal->status]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Deal is not validated'
            ], Response::HTTP_BAD_REQUEST);
        }

        $items = $this->itemService->getItemsForDeal($dealId);
        $productsCount = $items->count();

        $products = $items->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'ref' => $item->ref,
                'price' => $item->price,
                'discount' => $item->discount,
                'discount_2earn' => $item->discount_2earn,
                'photo_link' => $item->photo_link,
                'description' => $item->description,
                'stock' => $item->stock,
                'platform_id' => $item->platform_id,
            ];
        });

        Log::info(self::LOG_PREFIX . 'Items retrieved for deal', ['deal_id' => $dealId, 'count' => $productsCount]);

        return response()->json([
            'deal_id' => $deal->id,
            'deal_name' => $deal->name,
            'products_count' => $productsCount,
            'products' => $products
        ], Response::HTTP_OK);
    }

    public function addItemsToDeal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'deal_id' => 'required|integer|exists:deals,id',
            'product_ids' => 'required|array',
            'product_ids.*' => 'integer|exists:items,id',
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $dealId = $request->input('deal_id');
        $productIds = $request->input('product_ids');

        $deal = $this->dealService->find($dealId);

        if (!$deal) {
            Log::error(self::LOG_PREFIX . 'Deal not found', ['id' => $dealId]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Deal not found'
            ], Response::HTTP_NOT_FOUND);
        }

        if (!$deal->validated) {
            Log::warning(self::LOG_PREFIX . 'Deal is not validated', ['id' => $dealId]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Deal is not validated'
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $updatedCount = $this->itemService->bulkUpdateDeal($productIds, $dealId);

            foreach ($productIds as $productId) {
                DealProductChange::create([
                    'deal_id' => $dealId,
                    'item_id' => $productId,
                    'action' => 'added',
                    'changed_by' => auth()->id(),
                    'note' => 'Product added to deal via API'
                ]);
            }

            Log::info(self::LOG_PREFIX . 'Products added to deal', [
                'deal_id' => $dealId,
                'product_ids' => $productIds,
                'updated_count' => $updatedCount
            ]);

            $this->notifyPlatformUsers($deal, $updatedCount, $productIds, 'added');

            return response()->json([
                'status' => 'success',
                'message' => 'Products added to deal successfully',
                'deal_id' => $dealId,
                'product_ids' => $productIds
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Failed to add products to deal', [
                'deal_id' => $dealId,
                'product_ids' => $productIds,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'Failed',
                'message' => 'Failed to add products to deal',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function removeItemsFromDeal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'deal_id' => 'required|integer|exists:deals,id',
            'product_ids' => 'required|array',
            'product_ids.*' => 'integer|exists:items,id',
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $dealId = $request->input('deal_id');
        $productIds = $request->input('product_ids');

        $deal = $this->dealService->find($dealId);

        if (!$deal) {
            Log::error(self::LOG_PREFIX . 'Deal not found', ['id' => $dealId]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Deal not found'
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $removedCount = $this->itemService->bulkRemoveFromDeal($productIds, $dealId);

            foreach ($productIds as $productId) {
                DealProductChange::create([
                    'deal_id' => $dealId,
                    'item_id' => $productId,
                    'action' => 'removed',
                    'changed_by' => auth()->id(),
                    'note' => 'Product removed from deal via API'
                ]);
            }

            Log::info(self::LOG_PREFIX . 'Products removed from deal', [
                'deal_id' => $dealId,
                'product_ids' => $productIds,
                'removed_count' => $removedCount
            ]);

            $this->notifyPlatformUsers($deal, $removedCount, $productIds, 'removed');

            return response()->json([
                'status' => 'success',
                'message' => 'Products removed from deal successfully',
                'deal_id' => $dealId,
                'removed_product_ids' => $productIds
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Failed to remove products from deal', [
                'deal_id' => $dealId,
                'product_ids' => $productIds,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'Failed',
                'message' => 'Failed to remove products from deal',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function notifyPlatformUsers(Deal $deal, int $itemsCount, array $productIds, string $action): void
    {
        try {
            $platform = $deal->platform;

            if (!$platform) {
                Log::warning(self::LOG_PREFIX . 'Platform not found for deal', [
                    'deal_id' => $deal->id,
                    'platform_id' => $deal->platform_id
                ]);
                return;
            }

            $platformUsers = $platform->getPlatformRoleUsers();

            if ($platformUsers->isEmpty()) {
                Log::info(self::LOG_PREFIX . 'No users found for platform notification', [
                    'platform_id' => $deal->platform_id
                ]);
                return;
            }

            $notificationClass = $action === 'added' ? ItemsAddedToDeal::class : ItemsRemovedFromDeal::class;

            Notification::send($platformUsers, new $notificationClass($deal, $itemsCount, $productIds));

            Log::info(self::LOG_PREFIX . 'Notifications sent to platform users', [
                'deal_id' => $deal->id,
                'platform_id' => $deal->platform_id,
                'action' => $action,
                'users_count' => $platformUsers->count(),
                'user_ids' => $platformUsers->pluck('id')->toArray()
            ]);
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Failed to send notifications to platform users', [
                'deal_id' => $deal->id,
                'platform_id' => $deal->platform_id,
                'action' => $action,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
