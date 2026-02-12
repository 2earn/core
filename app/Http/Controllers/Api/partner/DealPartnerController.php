<?php

namespace App\Http\Controllers\Api\partner;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDealRequest;
use App\Http\Requests\UpdateDealRequest;
use App\Models\Deal;
use App\Models\DealChangeRequest;
use App\Models\DealValidationRequest;
use App\Services\Deals\DealService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DealPartnerController extends Controller
{
    private const LOG_PREFIX = '[DealPartnerController] ';
    private const PAGINATION_LIMIT = 5;

    private DealService $dealService;

    public function __construct(DealService $dealService)
    {
        $this->dealService = $dealService;
        $this->middleware('check.url');
    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'platform_ids' => 'nullable|array',
            'platform_ids.*' => 'integer|exists:platforms,id',
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1',
            'search' => 'nullable|string|max:255'
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
        $platformIds = $request->input('platform_ids');
        $page = $request->input('page');
        $limit = $request->input('limit') ?? 8;
        $search = $request->input('search');

        $deals = $this->dealService->getPartnerDeals(
            $userId,
            $platformIds,
            $search,
            $page,
            $limit
        );

        $totalCount = $this->dealService->getPartnerDealsCount($userId, $platformIds, $search);

        $this->dealService->enrichDealsWithRequests($deals);

        return response()->json([
            'status' => true,
            'data' => $deals,
            'total' => $totalCount
        ]);
    }

    public function store(StoreDealRequest $request)
    {
        $validatedData = $request->validated();

        $userId = $request->input('user_id');
        $createdBy = $request->input('created_by');

        $validatedData['created_by_id'] = $userId;
        $validatedData['validated'] = false;
        $validatedData['current_turnover'] = $request->input('current_turnover', 0);

        try {
            DB::beginTransaction();

            $deal = $this->dealService->create($validatedData);
            $validationRequest = $this->dealService->createValidationRequest(
                $deal->id,
                $createdBy,
                $request->input('notes', 'Deal validation request created automatically')
            );

            DB::commit();

            Log::info(self::LOG_PREFIX . 'Deal and validation request created successfully', [
                'deal_id' => $deal->id,
                'user_id' => $userId
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Deal created successfully and validation request submitted',
                'data' => ['deal' => $deal, 'validation_request' => $validationRequest]
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(self::LOG_PREFIX . 'Failed to create deal and validation request', [
                'error' => $e->getMessage(),
                'user_id' => $userId
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to create deal: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function validateRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'deal_id' => 'required|exists:deals,id',
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Deal validation request validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $validatedData = $validator->validated();
        $dealId = $validatedData['deal_id'];
        $userId = $request->input('user_id');

        $deal = $this->dealService->getPartnerDealById($dealId, $userId);

        if (!$deal) {
            Log::error(self::LOG_PREFIX . 'Deal not found', ['deal_id' => $dealId, 'user_id' => $userId]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Deal not found'
            ], Response::HTTP_NOT_FOUND);
        }

        if ($deal->validated) {
            Log::warning(self::LOG_PREFIX . 'Deal is already validated', [
                'deal_id' => $dealId,
                'user_id' => $userId
            ]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Deal is already validated'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $existingRequest = DealValidationRequest::where('deal_id', $dealId)
            ->where('status', DealValidationRequest::STATUS_PENDING)
            ->first();

        if ($existingRequest) {
            Log::warning(self::LOG_PREFIX . 'Pending validation request already exists', [
                'deal_id' => $dealId,
                'request_id' => $existingRequest->id
            ]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'A pending validation request already exists for this deal'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            DB::beginTransaction();

            $validationRequest = $this->dealService->createValidationRequest(
                $dealId,
                $userId,
                $request->input('notes', 'Deal validation request created')
            );

            DB::commit();

            Log::info(self::LOG_PREFIX . 'Validation request created successfully', [
                'deal_id' => $dealId,
                'validation_request_id' => $validationRequest->id,
                'user_id' => $userId
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Validation request submitted successfully',
                'data' => [
                    'deal_id' => $dealId,
                    'validation_request' => $validationRequest
                ]
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(self::LOG_PREFIX . 'Failed to create validation request', [
                'error' => $e->getMessage(),
                'deal_id' => $dealId,
                'user_id' => $userId
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to create validation request: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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

        $deal = $this->dealService->getPartnerDealById($dealId, $userId);

        if (!$deal) {
            Log::error(self::LOG_PREFIX . 'Deal not found', ['deal_id' => $dealId, 'user_id' => $userId]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Failed to fetch deal'
            ], Response::HTTP_NOT_FOUND);
        }

        $changeRequests = $this->dealService->getDealChangeRequests($dealId);
        $validationRequests = $this->dealService->getDealValidationRequests($dealId);

        return response()->json([
            'status' => true,
            'data' => [
                'deal' => $deal,
                'validation_requests' => $validationRequests,
                'change_requests' => $changeRequests
            ]
        ]);
    }

    public function update(UpdateDealRequest $request, Deal $deal)
    {
        $validatedData = $request->validated();

        if (array_key_exists('current_turnover', $validatedData)) {
            $validatedData['current_turnover'] = $validatedData['current_turnover'] ?? 0;
        }

        $requestedBy = $request->input('requested_by') ?? $request->input('user_id');

        $changes = [];
        foreach ($validatedData as $field => $value) {
            if ($field === 'requested_by') {
                continue;
            }

            if ($deal->{$field} != $value) {
                $changes[$field] = [
                    'old' => $deal->{$field},
                    'new' => $value
                ];
            }
        }

        if (empty($changes)) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'No changes detected'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $changeRequest = $this->dealService->createChangeRequest(
            $deal->id,
            $changes,
            $requestedBy
        );
        Log::info(self::LOG_PREFIX . 'Deal change request created', [
            'deal_id' => $deal->id,
            'change_request_id' => $changeRequest->id,
            'requested_by' => $requestedBy
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Deal change request submitted successfully. Awaiting approval.',
            'data' => [
                'deal' => $deal,
                'change_request' => $changeRequest
            ]
        ], Response::HTTP_CREATED);
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

        if (!$this->dealService->userHasPermission($deal, $userId)) {
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

    public function cancelValidationRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'validation_request_id' => 'required|integer|exists:deal_validation_requests,id',
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Cancel validation request validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $validationRequestId = $request->input('validation_request_id');

        $validationRequest = DealValidationRequest::find($validationRequestId);

        if (!$validationRequest) {
            Log::error(self::LOG_PREFIX . 'Validation request not found', ['validation_request_id' => $validationRequestId]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation request not found'
            ], Response::HTTP_NOT_FOUND);
        }

        if (!$validationRequest->canBeCancelled()) {
            Log::warning(self::LOG_PREFIX . 'Validation request cannot be canceled', [
                'validation_request_id' => $validationRequestId,
                'current_status' => $validationRequest->status
            ]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Only pending validation requests can be cancelled. Current status: ' . $validationRequest->status
            ], Response::HTTP_FORBIDDEN);
        }

        $validationRequest->status = DealValidationRequest::STATUS_CANCELLED;
        $validationRequest->save();

        Log::info(self::LOG_PREFIX . 'Validation request canceled', [
            'validation_request_id' => $validationRequestId,
            'deal_id' => $validationRequest->deal_id
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Validation request cancelled successfully',
            'data' => $validationRequest
        ], Response::HTTP_OK);
    }

    public function cancelChangeRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'change_request_id' => 'required|integer|exists:deal_change_requests,id',
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Cancel change request validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $changeRequestId = $request->input('change_request_id');

        $changeRequest = DealChangeRequest::find($changeRequestId);

        if (!$changeRequest) {
            Log::error(self::LOG_PREFIX . 'Change request not found', ['change_request_id' => $changeRequestId]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Change request not found'
            ], Response::HTTP_NOT_FOUND);
        }

        if (!$changeRequest->canBeCancelled()) {
            Log::warning(self::LOG_PREFIX . 'Change request cannot be canceled', [
                'change_request_id' => $changeRequestId,
                'current_status' => $changeRequest->status
            ]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Only pending change requests can be cancelled. Current status: ' . $changeRequest->status
            ], Response::HTTP_FORBIDDEN);
        }

        $changeRequest->status = DealChangeRequest::STATUS_CANCELLED;
        $changeRequest->save();

        Log::info(self::LOG_PREFIX . 'Change request canceled', [
            'change_request_id' => $changeRequestId,
            'deal_id' => $changeRequest->deal_id
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Change request cancelled successfully',
            'data' => $changeRequest
        ], Response::HTTP_OK);
    }

    public function dashboardIndicators(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'platform_ids' => 'nullable|array',
            'platform_ids.*' => 'integer|exists:platforms,id',
            'deal_id' => 'nullable|integer|exists:deals,id'
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Dashboard indicators validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $userId = $request->input('user_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $platformIds = $request->input('platform_ids');
        $dealId = $request->input('deal_id');

        try {
            $indicators = $this->dealService->getDashboardIndicators(
                $userId,
                $startDate,
                $endDate,
                $platformIds,
                $dealId
            );

            Log::info(self::LOG_PREFIX . 'Dashboard indicators retrieved successfully', [
                'user_id' => $userId,
                'filters' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'platform_ids' => $platformIds,
                    'deal_id' => $dealId
                ]
            ]);

            return response()->json([
                'status' => true,
                'data' => $indicators
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Failed to retrieve dashboard indicators', [
                'error' => $e->getMessage(),
                'user_id' => $userId
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve dashboard indicators: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function performanceChart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'deal_id' => 'required|integer|exists:deals,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'view_mode' => 'nullable|string|in:daily,weekly,monthly'
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Performance chart validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $userId = $request->input('user_id');
        $dealId = $request->input('deal_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $viewMode = $request->input('view_mode', 'daily');

        try {
            $performanceData = $this->dealService->getDealPerformanceChart(
                $userId,
                $dealId,
                $startDate,
                $endDate,
                $viewMode
            );

            Log::info(self::LOG_PREFIX . 'Deal performance chart retrieved successfully', [
                'deal_id' => $dealId,
                'user_id' => $userId,
                'view_mode' => $viewMode
            ]);

            return response()->json([
                'status' => true,
                'data' => $performanceData
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Failed to retrieve deal performance chart', [
                'error' => $e->getMessage(),
                'deal_id' => $dealId,
                'user_id' => $userId
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve deal performance chart: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
