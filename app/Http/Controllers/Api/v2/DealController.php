<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Services\Deals\DealService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class DealController extends Controller
{
    private DealService $dealService;

    public function __construct(DealService $dealService)
    {
        $this->dealService = $dealService;
    }

    /**
     * Get filtered deals
     */
    public function index(Request $request)
    {
        // Normalize boolean-like query values so Validator::boolean accepts them
        $input = $request->all();

        if (array_key_exists('is_super_admin', $input) && is_string($input['is_super_admin'])) {
            $val = strtolower(trim($input['is_super_admin']));
            if ($val === 'true') {
                $input['is_super_admin'] = 1;
            } elseif ($val === 'false') {
                $input['is_super_admin'] = 0;
            }
            // leave numeric '1'/'0' or other values untouched
        }

        $validator = Validator::make($input, [
            'is_super_admin' => 'required|boolean',
            'user_id' => 'nullable|integer|exists:users,id',
            'keyword' => 'nullable|string',
            'statuses' => 'nullable|array',
            'types' => 'nullable|array',
            'platforms' => 'nullable|array',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Use the normalized value if present, otherwise fallback to Request parsing
        $isSuperAdmin = array_key_exists('is_super_admin', $input)
            ? (bool) $input['is_super_admin']
            : $request->boolean('is_super_admin');

        $deals = $this->dealService->getFilteredDeals(
            $isSuperAdmin,
            $request->input('user_id'),
            $request->input('keyword'),
            $request->input('statuses', []),
            $request->input('types', []),
            $request->input('platforms', []),
            $request->input('start_date_from'),
            $request->input('start_date_to'),
            $request->input('end_date_from'),
            $request->input('end_date_to'),
            $request->input('per_page')
        );

        return response()->json([
            'status' => true,
            'data' => $deals
        ]);
    }

    /**
     * Get all deals
     */
    public function all()
    {
        $deals = $this->dealService->getAllDeals();
        return response()->json(['status' => true, 'data' => $deals]);
    }

    /**
     * Get partner deals
     */
    public function getPartnerDeals(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'platform_ids' => 'nullable|array',
            'search' => 'nullable|string',
            'page' => 'nullable|integer',
            'per_page' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $deals = $this->dealService->getPartnerDeals(
            $request->input('user_id'),
            $request->input('platform_ids'),
            $request->input('search'),
            $request->input('page'),
            $request->input('per_page', 5)
        );

        return response()->json(['status' => true, 'data' => $deals]);
    }

    /**
     * Get available deals for user
     */
    public function getAvailableDeals(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'is_super_admin' => 'nullable|boolean',
            'platform_id' => 'nullable|integer|exists:platforms,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $deals = $this->dealService->getAvailableDeals(
            $request->input('user_id'),
            $request->boolean('is_super_admin', false),
            $request->input('platform_id')
        );

        return response()->json(['status' => true, 'data' => $deals]);
    }

    /**
     * Get deals with user purchases
     */
    public function getDealsWithPurchases(int $userId)
    {
        $deals = $this->dealService->getDealsWithUserPurchases($userId);
        return response()->json(['status' => true, 'data' => $deals]);
    }

    /**
     * Get deal by ID
     */
    public function show(int $id)
    {
        $deal = $this->dealService->findById($id);

        if (!$deal) {
            return response()->json(['status' => false, 'message' => 'Deal not found'], 404);
        }

        return response()->json(['status' => true, 'data' => $deal]);
    }

    /**
     * Get partner deal by ID
     */
    public function getPartnerDeal(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $deal = $this->dealService->getPartnerDealById($id, $request->input('user_id'));

        if (!$deal) {
            return response()->json(['status' => false, 'message' => 'Deal not found or access denied'], 404);
        }

        return response()->json(['status' => true, 'data' => $deal]);
    }

    /**
     * Get dashboard indicators
     */
    public function getDashboardIndicators(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'platform_ids' => 'nullable|array',
            'deal_id' => 'nullable|integer|exists:deals,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $indicators = $this->dealService->getDashboardIndicators(
            $request->input('user_id'),
            $request->input('start_date'),
            $request->input('end_date'),
            $request->input('platform_ids'),
            $request->input('deal_id')
        );

        return response()->json(['status' => true, 'data' => $indicators]);
    }

    /**
     * Get deal performance chart
     */
    public function getPerformanceChart(Request $request, int $dealId)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'view_mode' => 'nullable|in:daily,weekly,monthly'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $chartData = $this->dealService->getDealPerformanceChart(
                $request->input('user_id'),
                $dealId,
                $request->input('start_date'),
                $request->input('end_date'),
                $request->input('view_mode', 'daily')
            );

            return response()->json(['status' => true, 'data' => $chartData]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 404);
        }
    }

    /**
     * Get deal change requests
     */
    public function getChangeRequests(int $dealId)
    {
        $requests = $this->dealService->getDealChangeRequests($dealId);
        return response()->json(['status' => true, 'data' => $requests]);
    }

    /**
     * Get deal validation requests
     */
    public function getValidationRequests(int $dealId)
    {
        $requests = $this->dealService->getDealValidationRequests($dealId);
        return response()->json(['status' => true, 'data' => $requests]);
    }

    /**
     * Create deal
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'platform_id' => 'required|integer|exists:platforms,id',
            'final_commission' => 'nullable|numeric',
            'target_turnover' => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $deal = $this->dealService->create($request->all());
        return response()->json(['status' => true, 'data' => $deal], 201);
    }

    /**
     * Update deal
     */
    public function update(Request $request, int $id)
    {
        $deal = $this->dealService->findById($id);

        if (!$deal) {
            return response()->json(['status' => false, 'message' => 'Deal not found'], 404);
        }

        $success = $this->dealService->update($id, $request->all());
        return response()->json(['status' => $success, 'message' => $success ? 'Updated successfully' : 'Update failed']);
    }

    /**
     * Delete deal
     */
    public function destroy(int $id)
    {
        try {
            $success = $this->dealService->delete($id);
            return response()->json(['status' => true, 'message' => 'Deal deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 404);
        }
    }

    /**
     * Create validation request
     */
    public function createValidationRequest(Request $request, int $dealId)
    {
        $validator = Validator::make($request->all(), [
            'requested_by_id' => 'required|integer|exists:users,id',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $validationRequest = $this->dealService->createValidationRequest(
            $dealId,
            $request->input('requested_by_id'),
            $request->input('notes')
        );

        return response()->json(['status' => true, 'data' => $validationRequest], 201);
    }

    /**
     * Create change request
     */
    public function createChangeRequest(Request $request, int $dealId)
    {
        $validator = Validator::make($request->all(), [
            'changes' => 'required|array',
            'requested_by' => 'required|integer|exists:users,id',
            'status' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $changeRequest = $this->dealService->createChangeRequest(
            $dealId,
            $request->input('changes'),
            $request->input('requested_by'),
            $request->input('status', 'pending')
        );

        return response()->json(['status' => true, 'data' => $changeRequest], 201);
    }

    /**
     * Get archived deals
     */
    public function getArchivedDeals(Request $request)
    {
        $deals = $this->dealService->getArchivedDeals(
            $request->input('search'),
            $request->boolean('is_super_admin', false)
        );

        return response()->json(['status' => true, 'data' => $deals]);
    }
}

