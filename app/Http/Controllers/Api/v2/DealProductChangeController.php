<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Services\Deals\DealProductChangeService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class DealProductChangeController extends Controller
{
    private DealProductChangeService $dealProductChangeService;

    public function __construct(DealProductChangeService $dealProductChangeService)
    {
        $this->dealProductChangeService = $dealProductChangeService;
    }

    /**
     * Get filtered product changes
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'deal_id' => 'nullable|integer|exists:deals,id',
            'item_id' => 'nullable|integer|exists:items,id',
            'action' => 'nullable|in:added,removed',
            'changed_by' => 'nullable|integer|exists:users,id',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $changes = $this->dealProductChangeService->getFilteredChanges(
            $request->only(['deal_id', 'item_id', 'action', 'changed_by', 'from_date', 'to_date']),
            $request->input('per_page', 15)
        );

        return response()->json([
            'status' => true,
            'data' => $changes->items(),
            'pagination' => [
                'current_page' => $changes->currentPage(),
                'per_page' => $changes->perPage(),
                'total' => $changes->total(),
                'last_page' => $changes->lastPage()
            ]
        ]);
    }

    /**
     * Get product change by ID
     */
    public function show(int $id)
    {
        $change = $this->dealProductChangeService->getChangeById($id);

        if (!$change) {
            return response()->json([
                'status' => false,
                'message' => 'Product change not found'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => true,
            'data' => $change
        ]);
    }

    /**
     * Get statistics
     */
    public function getStatistics(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'deal_id' => 'nullable|integer|exists:deals,id',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $statistics = $this->dealProductChangeService->getStatistics(
            $request->only(['deal_id', 'from_date', 'to_date'])
        );

        return response()->json([
            'status' => true,
            'data' => $statistics
        ]);
    }

    /**
     * Create product change
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'deal_id' => 'required|integer|exists:deals,id',
            'item_id' => 'required|integer|exists:items,id',
            'action' => 'required|in:added,removed',
            'changed_by' => 'nullable|integer|exists:users,id',
            'note' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $change = $this->dealProductChangeService->createChange($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Product change created successfully',
            'data' => $change
        ], Response::HTTP_CREATED);
    }

    /**
     * Create bulk product changes
     */
    public function createBulk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'deal_id' => 'required|integer|exists:deals,id',
            'item_ids' => 'required|array',
            'item_ids.*' => 'integer|exists:items,id',
            'action' => 'required|in:added,removed',
            'changed_by' => 'nullable|integer|exists:users,id',
            'note' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $count = $this->dealProductChangeService->createBulkChanges(
            $request->input('deal_id'),
            $request->input('item_ids'),
            $request->input('action'),
            $request->input('changed_by'),
            $request->input('note')
        );

        return response()->json([
            'status' => true,
            'message' => 'Bulk product changes created successfully',
            'created_count' => $count
        ], Response::HTTP_CREATED);
    }
}

