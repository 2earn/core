<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Services\CommissionBreakDownService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommissionBreakDownController extends Controller
{
    private CommissionBreakDownService $commissionBreakDownService;

    public function __construct(CommissionBreakDownService $commissionBreakDownService)
    {
        $this->commissionBreakDownService = $commissionBreakDownService;
    }

    /**
     * Get commission breakdown by ID
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        try {
            $commission = $this->commissionBreakDownService->getById($id);

            if (!$commission) {
                return response()->json([
                    'status' => false,
                    'message' => 'Commission breakdown not found'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data' => $commission
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error fetching commission breakdown: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get commission breakdowns by deal ID
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByDeal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'deal_id' => 'required|integer',
            'order_by' => 'nullable|string|in:id,created_at,commission_value,camembert',
            'order_direction' => 'nullable|string|in:ASC,DESC'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $dealId = $request->input('deal_id');
            $orderBy = $request->input('order_by', 'id');
            $orderDirection = $request->input('order_direction', 'ASC');

            $commissions = $this->commissionBreakDownService->getByDealId($dealId, $orderBy, $orderDirection);

            return response()->json([
                'status' => true,
                'data' => $commissions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error fetching commission breakdowns: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate commission totals for a deal
     *
     * @param int $dealId
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateTotals(int $dealId)
    {
        try {
            $totals = $this->commissionBreakDownService->calculateTotals($dealId);

            return response()->json([
                'status' => true,
                'data' => $totals
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error calculating commission totals: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new commission breakdown
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'deal_id' => 'required|integer|exists:deals,id',
            'order_id' => 'nullable|integer|exists:orders,id',
            'type' => 'required|integer',
            'trigger' => 'nullable|integer',
            'new_turnover' => 'nullable|numeric',
            'old_turnover' => 'nullable|numeric',
            'purchase_value' => 'nullable|numeric',
            'commission_percentage' => 'nullable|numeric',
            'commission_value' => 'nullable|numeric',
            'camembert' => 'nullable|numeric',
            'cash_company_profit' => 'nullable|numeric',
            'cash_jackpot' => 'nullable|numeric',
            'cash_tree' => 'nullable|numeric',
            'cash_cashback' => 'nullable|numeric',
            'deal_paid_amount' => 'nullable|numeric',
            'additional_amount' => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $commission = $this->commissionBreakDownService->create($request->all());

            if (!$commission) {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to create commission breakdown'
                ], 500);
            }

            return response()->json([
                'status' => true,
                'data' => $commission,
                'message' => 'Commission breakdown created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error creating commission breakdown: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a commission breakdown
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'deal_id' => 'nullable|integer|exists:deals,id',
            'order_id' => 'nullable|integer|exists:orders,id',
            'type' => 'nullable|integer',
            'trigger' => 'nullable|integer',
            'new_turnover' => 'nullable|numeric',
            'old_turnover' => 'nullable|numeric',
            'purchase_value' => 'nullable|numeric',
            'commission_percentage' => 'nullable|numeric',
            'commission_value' => 'nullable|numeric',
            'camembert' => 'nullable|numeric',
            'cash_company_profit' => 'nullable|numeric',
            'cash_jackpot' => 'nullable|numeric',
            'cash_tree' => 'nullable|numeric',
            'cash_cashback' => 'nullable|numeric',
            'deal_paid_amount' => 'nullable|numeric',
            'additional_amount' => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $result = $this->commissionBreakDownService->update($id, $request->all());

            if (!$result) {
                return response()->json([
                    'status' => false,
                    'message' => 'Commission breakdown not found or update failed'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Commission breakdown updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error updating commission breakdown: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a commission breakdown
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        try {
            $result = $this->commissionBreakDownService->delete($id);

            if (!$result) {
                return response()->json([
                    'status' => false,
                    'message' => 'Commission breakdown not found or delete failed'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Commission breakdown deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error deleting commission breakdown: ' . $e->getMessage()
            ], 500);
        }
    }
}

