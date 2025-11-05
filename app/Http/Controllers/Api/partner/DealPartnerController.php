<?php

namespace App\Http\Controllers\Api\partner;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DealPartnerController extends Controller
{
    private const LOG_PREFIX = '[DealPartnerController] ';
    private const PAGINATION_LIMIT = 5;

    public function __construct()
    {
        $this->middleware('check.url');
    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'platform_id' => 'nullable|integer|exists:platforms,id',
            'page' => 'nullable|integer|min:1'
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $userId = $request->input('user_id');
        $platformId = $request->input('business_sector_id');
        $page = $request->input('page', 1);

        $query = Deal::with('platform')
            ->whereHas('platform', function ($query) use ($userId, $platformId) {
                $query->where(function ($q) use ($userId) {
                    $q->where('marketing_manager_id', $userId)
                      ->orWhere('financial_manager_id', $userId)
                      ->orWhere('owner_id', $userId);
                });

                if ($platformId) {
                    $query->where('platform_id', $platformId);
                }
            });

        $totalCount = $query->count();
        $deals = $query->paginate(self::PAGINATION_LIMIT, ['*'], 'page', $page);

        return response()->json([
            'status' => true,
            'data' => $deals,
            'total' => $totalCount
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'validated' => 'required|boolean',
            'type' => 'required|string',
            'status' => 'required|string',
            'current_turnover' => 'nullable|numeric',
            'is_turnover' => 'nullable|boolean',
            'discount' => 'nullable|numeric',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'initial_commission' => 'nullable|numeric',
            'final_commission' => 'nullable|numeric',
            'earn_profit' => 'nullable|numeric',
            'jackpot' => 'nullable|numeric',
            'tree_remuneration' => 'nullable|numeric',
            'proactive_cashback' => 'nullable|numeric',
            'total_commission_value' => 'nullable|numeric',
            'total_unused_cashback_value' => 'nullable|numeric',
            'platform_id' => 'required|exists:platforms,id',
            'cash_company_profit' => 'nullable|numeric',
            'cash_jackpot' => 'nullable|numeric',
            'cash_tree' => 'nullable|numeric',
            'cash_cashback' => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Deal creation validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $validatedData = $validator->validated();
        $validatedData['created_by_id'] = $request->input('user_id');
        $validatedData['current_turnover'] = $request->input('current_turnover', 0);

        $deal = Deal::create($validatedData);

        return response()->json([
            'status' => true,
            'message' => 'Deal created successfully',
            'data' => $deal
        ], Response::HTTP_CREATED);
    }

    public function show(Request $request, $dealId)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id'
        ]);

        $userId = $request->input('user_id');

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $deal = Deal::with('platform')
            ->where('id', $dealId)
            ->whereHas('platform', function ($query) use ($userId) {
                $query->where(function ($q) use ($userId) {
                    $q->where('marketing_manager_id', $userId)
                      ->orWhere('financial_manager_id', $userId)
                      ->orWhere('owner_id', $userId);
                });
            })
            ->first();

        if (!$deal) {
            Log::error(self::LOG_PREFIX . 'Deal not found', ['deal_id' => $dealId, 'user_id' => $userId]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Failed to fetch deal'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => true,
            'data' => $deal
        ]);
    }

    public function update(Request $request, Deal $deal)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'validated' => 'sometimes|required|boolean',
            'type' => 'sometimes|required|string',
            'status' => 'sometimes|required|string',
            'target_turnover' => 'sometimes|required|numeric',
            'current_turnover' => 'nullable|numeric',
            'is_turnover' => 'nullable|boolean',
            'discount' => 'nullable|numeric',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'initial_commission' => 'nullable|numeric',
            'final_commission' => 'nullable|numeric',
            'earn_profit' => 'nullable|numeric',
            'jackpot' => 'nullable|numeric',
            'tree_remuneration' => 'nullable|numeric',
            'proactive_cashback' => 'nullable|numeric',
            'total_commission_value' => 'nullable|numeric',
            'total_unused_cashback_value' => 'nullable|numeric',
            'platform_id' => 'sometimes|exists:platforms,id',
            'cash_company_profit' => 'nullable|numeric',
            'cash_jackpot' => 'nullable|numeric',
            'cash_tree' => 'nullable|numeric',
            'cash_cashback' => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Deal update validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $validatedData = $validator->validated();
        if (array_key_exists('current_turnover', $validatedData)) {
            $validatedData['current_turnover'] = $validatedData['current_turnover'] ?? 0;
        }

        $deal->update($validatedData);

        return response()->json([
            'status' => true,
            'message' => 'Deal updated successfully',
            'data' => $deal
        ]);
    }

    public function changeStatus(Request $request, Deal $deal)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|integer',
            'user_id' => 'required|integer|exists:users,id'
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Deal status change validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $userId = $request->input('user_id');

        // Check if user has permission through platform
        $hasPermission = $deal->platform()
            ->where(function ($query) use ($userId) {
                $query->where('marketing_manager_id', $userId)
                    ->orWhere('financial_manager_id', $userId)
                    ->orWhere('owner_id', $userId);
            })
            ->exists();

        if (!$hasPermission) {
            Log::error(self::LOG_PREFIX . 'User does not have permission to change deal status', [
                'deal_id' => $deal->id,
                'user_id' => $userId
            ]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'You do not have permission to change this deal status'
            ], Response::HTTP_FORBIDDEN);
        }

        $deal->status = $request->input('status');
        $deal->save();

        return response()->json([
            'status' => true,
            'message' => 'Deal status updated successfully',
            'data' => $deal
        ]);
    }
}
