<?php

namespace App\Http\Controllers\Api\partner;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\Item;
use App\Services\Deals\DealService;
use App\Services\Items\ItemService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
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
}
