<?php

namespace App\Http\Controllers\Api\partner;

use App\Http\Controllers\Controller;
use App\Models\PlatformValidationRequest;
use App\Services\Dashboard\SalesDashboardService;
use App\Services\Platform\PlatformChangeRequestService;
use App\Services\Platform\PlatformService;
use App\Services\Platform\PlatformTypeChangeRequestService;
use App\Services\Platform\PlatformValidationRequestService;
use App\Models\Platform;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PlatformPartnerController extends Controller
{
    private const LOG_PREFIX = '[PlatformPartnerController] ';

    protected $platformService;
    protected $platformValidationRequestService;
    protected $platformChangeRequestService;
    protected $platformTypeChangeRequestService;
    protected $salesDashboardService;

    public function __construct(
        PlatformService                  $platformService,
        PlatformValidationRequestService $platformValidationRequestService,
        PlatformChangeRequestService     $platformChangeRequestService,
        PlatformTypeChangeRequestService $platformTypeChangeRequestService,
        SalesDashboardService            $salesDashboardService
    )
    {
        $this->middleware('check.url');
        $this->platformService = $platformService;
        $this->platformValidationRequestService = $platformValidationRequestService;
        $this->platformChangeRequestService = $platformChangeRequestService;
        $this->platformTypeChangeRequestService = $platformTypeChangeRequestService;
        $this->salesDashboardService = $salesDashboardService;
    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1',
            'search' => 'nullable|string|max:255'
        ]);

        $userId = $request->input('user_id');
        $page = $request->input('page');
        $limit = $request->input('limit') ?? 8;
        $search = $request->input('search');

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $result = $this->platformService->getPlatformsForPartner($userId, $page, $search, $limit);
        $platforms = $result['platforms'];
        $totalCount = $result['total_count'];

        $platforms->load(['validationRequest' => function ($query) {
            $query->latest();
        }]);

        $platforms->each(function ($platform) {
            // Use services to get counts
            $platform->type_change_requests_count = $this->platformTypeChangeRequestService
                ->getFilteredQuery(null, null)
                ->where('platform_id', $platform->id)
                ->count();

            $platform->validation_requests_count = $this->platformValidationRequestService
                ->getFilteredQuery(null, null)
                ->where('platform_id', $platform->id)
                ->count();

            $platform->change_requests_count = $this->platformChangeRequestService
                ->getFilteredQuery(null, null)
                ->where('platform_id', $platform->id)
                ->count();

            // Get recent requests using services
            $platform->typeChangeRequests = $this->platformTypeChangeRequestService
                ->getFilteredQuery(null, null)
                ->where('platform_id', $platform->id)
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();

            $platform->validationRequests = $this->platformValidationRequestService
                ->getFilteredQuery(null, null)
                ->where('platform_id', $platform->id)
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();

            $platform->changeRequests = $this->platformChangeRequestService
                ->getFilteredQuery(null, null)
                ->where('platform_id', $platform->id)
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();
        });


        return response()->json([
            'status' => true,
            'data' => $platforms,
            'total_platforms' => $totalCount
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string',
            'link' => 'sometimes|url',
            'show_profile' => 'boolean',
            'image_link' => 'nullable|string',
            'owner_id' => 'required|exists:users,id',
            'created_by' => 'required|exists:users,id',
            'marketing_manager_id' => 'nullable|exists:users,id',
            'financial_manager_id' => 'nullable|exists:users,id',
            'business_sector_id' => 'nullable|exists:business_sectors,id'
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Platform creation validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        $data['enabled'] = false;

        $platform = Platform::create($data);

        $validationRequest = PlatformValidationRequest::create([
            'platform_id' => $platform->id,
            'status' => PlatformValidationRequest::STATUS_PENDING,
            'requested_by' => $data['created_by']
        ]);

        Log::info(self::LOG_PREFIX . 'Platform created with validation request', [
            'platform_id' => $platform->id,
            'validation_request_id' => $validationRequest->id
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Platform created successfully. Awaiting validation.',
            'data' => [
                'platform' => $platform,
                'validation_request' => $validationRequest
            ]
        ], Response::HTTP_CREATED);
    }

    public function validateRequest(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'owner_id' => 'required|exists:users,id',
            'platform_id' => 'required|exists:platforms,id'
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Platform creation validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        $data['enabled'] = false;


        $validationRequest = $this->platformValidationRequestService->createRequest(
            $data['platform_id'],
            $data['owner_id']
        );

        Log::info(self::LOG_PREFIX . 'Validation request created', [
            'platform_id' => $data['platform_id'],
            'validation_request_id' => $validationRequest->id
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Validation request created. Awaiting validation.',
            'data' => [
                'platform' => $data['platform_id'],
                'validation_request' => $validationRequest
            ]
        ], Response::HTTP_CREATED);
    }

    public function show(Request $request, $platformId)
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

        $platform = $this->platformService->getPlatformForPartner($platformId, $userId);
        if (!$platform) {
            Log::error(self::LOG_PREFIX . 'Platform not found', ['platform_id' => $platformId, 'user_id' => $userId]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Failed to fetch platform'
            ], Response::HTTP_NOT_FOUND);
        }

        // Use services to get requests
        $typeChangeRequests = $this->platformTypeChangeRequestService
            ->getFilteredQuery(null, null)
            ->where('platform_id', $platformId)
            ->orderBy('created_at', 'desc')
            ->get();

        $validationRequests = $this->platformValidationRequestService
            ->getFilteredQuery(null, null)
            ->where('platform_id', $platformId)
            ->orderBy('created_at', 'desc')
            ->get();

        $changeRequests = $this->platformChangeRequestService
            ->getFilteredQuery(null, null)
            ->where('platform_id', $platformId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => [
                'platform' => $platform,
                'type_change_requests' => $typeChangeRequests,
                'validation_requests' => $validationRequests,
                'change_requests' => $changeRequests
            ]
        ]);
    }

    public function update(Request $request, Platform $platform)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'link' => 'sometimes|url',
            'enabled' => 'sometimes|boolean',
            'show_profile' => 'sometimes|boolean',
            'image_link' => 'nullable|string',
            'updated_by' => 'required|exists:users,id',
            'owner_id' => 'sometimes|exists:users,id',
            'marketing_manager_id' => 'nullable|exists:users,id',
            'financial_manager_id' => 'nullable|exists:users,id',
            'business_sector_id' => 'nullable|exists:business_sectors,id'
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Platform update validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $validatedData = $validator->validated();
        $updatedBy = $validatedData['updated_by'];
        unset($validatedData['updated_by']);

        // Filter out only the fields that are actually changing
        $changes = [];
        foreach ($validatedData as $field => $value) {
            if ($platform->{$field} != $value) {
                $changes[$field] = [
                    'old' => $platform->{$field},
                    'new' => $value
                ];
            }
        }

        // If no changes, return early
        if (empty($changes)) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'No changes detected'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Create a change request
        $changeRequest = $this->platformChangeRequestService->createRequest(
            $platform->id,
            $changes,
            $updatedBy
        );

        Log::info(self::LOG_PREFIX . 'Platform change request created', [
            'platform_id' => $platform->id,
            'change_request_id' => $changeRequest->id,
            'requested_by' => $updatedBy
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Platform change request submitted successfully. Awaiting approval.',
            'data' => [
                'platform' => $platform,
                'change_request' => $changeRequest
            ]
        ], Response::HTTP_CREATED);
    }

    public function changePlatformType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'platform_id' => 'required|integer|exists:platforms,id',
            'type_id' => 'required|integer|in:1,2,3',
            'updated_by' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Platform type change validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $platformId = $request->input('platform_id');
        $newTypeId = $request->input('type_id');
        $updatedBy = $request->input('updated_by');

        $platform = Platform::find($platformId);

        if (!$platform) {
            Log::error(self::LOG_PREFIX . 'Platform not found', ['platform_id' => $platformId]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Platform not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $oldTypeId = $platform->type;

        $allowedTransitions = [
            3 => [1, 2],
            2 => [1],
            1 => []
        ];

        if ($oldTypeId == 1) {
            Log::warning(self::LOG_PREFIX . 'Type 1 platforms cannot change type', [
                'platform_id' => $platformId,
                'current_type' => $oldTypeId
            ]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Type 1 (Full) platforms cannot change their type'
            ], Response::HTTP_FORBIDDEN);
        }

        if (!isset($allowedTransitions[$oldTypeId]) || !in_array($newTypeId, $allowedTransitions[$oldTypeId])) {
            Log::warning(self::LOG_PREFIX . 'Invalid type transition', [
                'platform_id' => $platformId,
                'old_type' => $oldTypeId,
                'new_type' => $newTypeId
            ]);
            return response()->json([
                'status' => 'Failed',
                'message' => "Type {$oldTypeId} platforms can only change to types: " . implode(', ', $allowedTransitions[$oldTypeId] ?? [])
            ], Response::HTTP_FORBIDDEN);
        }

        if ($oldTypeId == $newTypeId) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'New type cannot be the same as current type'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $changeRequest = $this->platformTypeChangeRequestService->createRequest(
            $platformId,
            $oldTypeId,
            $newTypeId,
            $updatedBy
        );

        Log::info(self::LOG_PREFIX . 'Platform type change request created', [
            'request_id' => $changeRequest->id,
            'platform_id' => $platformId,
            'old_type' => $oldTypeId,
            'new_type' => $newTypeId
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Platform type change request created successfully',
            'data' => $changeRequest
        ], Response::HTTP_CREATED);
    }

    public function cancelValidationRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'validation_request_id' => 'required|integer|exists:platform_validation_requests,id',
            'cancelled_by' => 'required|exists:users,id',
            'rejection_reason' => 'required|string|max:500'
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
        $rejectionReason = $request->input('rejection_reason');
        $cancelled_by = $request->input('cancelled_by');

        try {
            $validationRequest = $this->platformValidationRequestService->cancelRequest(
                $validationRequestId,
                $cancelled_by,
                $rejectionReason,
            );
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Validation request not found', ['validation_request_id' => $validationRequestId]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation request not found'
            ], Response::HTTP_NOT_FOUND);
        }

        Log::info(self::LOG_PREFIX . 'Validation request cancelled', [
            'validation_request_id' => $validationRequestId,
            'platform_id' => $validationRequest->platform_id,
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
            'change_request_id' => 'required|integer|exists:platform_change_requests,id',
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

        try {
            $changeRequest = $this->platformChangeRequestService->cancelRequest($changeRequestId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error(self::LOG_PREFIX . 'Change request not found', ['change_request_id' => $changeRequestId]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Change request not found'
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            Log::warning(self::LOG_PREFIX . 'Change request cannot be cancelled', [
                'change_request_id' => $changeRequestId,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'status' => 'Failed',
                'message' => $e->getMessage()
            ], Response::HTTP_FORBIDDEN);
        }


        Log::info(self::LOG_PREFIX . 'Change request cancelled', [
            'change_request_id' => $changeRequestId,
            'platform_id' => $changeRequest->platform_id
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Change request cancelled successfully',
            'data' => $changeRequest
        ], Response::HTTP_OK);
    }

    /**
     * Get top-selling platforms chart data
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getTopSellingPlatforms(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Validation failed for top-selling platforms', [
                'errors' => $validator->errors()
            ]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $userId = $request->input('user_id');

        try {
            $filters = [
                'user_id' => $userId,
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'limit' => $request->input('limit', 10),
            ];

            $filters = array_filter($filters, function ($value) {
                return !is_null($value);
            });

            $topPlatforms = $this->salesDashboardService->getTopSellingPlatforms($filters);

            Log::info(self::LOG_PREFIX . 'Top-selling platforms retrieved successfully', [
                'user_id' => $userId,
                'filters' => $filters,
                'count' => count($topPlatforms)
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Top-selling platforms retrieved successfully',
                'data' => [
                    'top_platforms' => $topPlatforms
                ]
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error retrieving top-selling platforms: ' . $e->getMessage(), [
                'user_id' => $userId,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'Failed',
                'message' => 'Error retrieving top-selling platforms',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
