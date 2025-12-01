<?php

namespace App\Http\Controllers\Api\partner;

use App\Http\Controllers\Controller;
use App\Models\PlatformChangeRequest;
use App\Models\PlatformTypeChangeRequest;
use App\Models\PlatformValidationRequest;
use App\Services\Platform\PlatformService;
use Core\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PlatformPartnerController extends Controller
{
    private const LOG_PREFIX = '[PlatformPartnerController] ';

    protected $platformService;

    public function __construct(PlatformService $platformService)
    {
        $this->middleware('check.url');
        $this->platformService = $platformService;
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
            $platform->type_change_requests_count = PlatformTypeChangeRequest::where('platform_id', $platform->id)->count();
            $platform->validation_requests_count = PlatformValidationRequest::where('platform_id', $platform->id)->count();
            $platform->change_requests_count = PlatformChangeRequest::where('platform_id', $platform->id)->count();

            $platform->typeChangeRequests = PlatformTypeChangeRequest::where('platform_id', $platform->id)
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();
            $platform->validationRequests = PlatformValidationRequest::where('platform_id', $platform->id)
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();
            $platform->changeRequests = PlatformChangeRequest::where('platform_id', $platform->id)
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
            'status' => 'pending'
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


        $validationRequest = PlatformValidationRequest::create([
            'platform_id' => $data['platform_id'],
            'status' => 'pending'
        ]);

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

        $typeChangeRequests = PlatformTypeChangeRequest::where('platform_id', $platformId)
            ->orderBy('created_at', 'desc')
            ->get();

        $validationRequests = PlatformValidationRequest::where('platform_id', $platformId)
            ->orderBy('created_at', 'desc')
            ->get();

        $changeRequests = PlatformChangeRequest::where('platform_id', $platformId)
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
        $changeRequest = PlatformChangeRequest::create([
            'platform_id' => $platform->id,
            'changes' => $changes,
            'status' => 'pending',
            'requested_by' => $updatedBy
        ]);

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

        $changeRequest = PlatformTypeChangeRequest::create([
            'platform_id' => $platformId,
            'old_type' => $oldTypeId,
            'new_type' => $newTypeId,
            'status' => 'pending'
        ]);

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

        $validationRequest = PlatformValidationRequest::find($validationRequestId);

        if (!$validationRequest) {
            Log::error(self::LOG_PREFIX . 'Validation request not found', ['validation_request_id' => $validationRequestId]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation request not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $validationRequest->status = PlatformValidationRequest::STATUS_CANCELLED;
        $validationRequest->rejection_reason = $rejectionReason;
        $validationRequest->save();

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

        $changeRequest = PlatformChangeRequest::find($changeRequestId);

        if (!$changeRequest) {
            Log::error(self::LOG_PREFIX . 'Change request not found', ['change_request_id' => $changeRequestId]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Change request not found'
            ], Response::HTTP_NOT_FOUND);
        }

        if (!$changeRequest->canBeCancelled()) {
            Log::warning(self::LOG_PREFIX . 'Change request cannot be cancelled', [
                'change_request_id' => $changeRequestId,
                'current_status' => $changeRequest->status
            ]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Only pending change requests can be cancelled. Current status: ' . $changeRequest->status
            ], Response::HTTP_FORBIDDEN);
        }

        $changeRequest->status = PlatformChangeRequest::STATUS_CANCELLED;
        $changeRequest->save();

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

}
