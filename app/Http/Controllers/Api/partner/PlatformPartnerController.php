<?php

namespace App\Http\Controllers\Api\partner;

use App\Http\Controllers\Controller;
use App\Models\PlatformTypeChangeRequest;
use Core\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PlatformPartnerController extends Controller
{
    private const LOG_PREFIX = '[PlatformPartnerController] ';
    private const PAGINATION_LIMIT = 5;

    public function __construct()
    {
        $this->middleware('check.url');
    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'page' => 'nullable|integer|min:1',
            'search' => 'nullable|string|max:255'
        ]);

        $userId = $request->input('user_id');
        $page = $request->input('page');
        $search = $request->input('search');

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $query = Platform::where('marketing_manager_id', $userId)
            ->orWhere('financial_manager_id', $userId)
            ->orWhere('owner_id', $userId);

        if (!is_null($search) && $search !== '') {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $totalCount = $query->count();
        $platforms = !is_null($page) ? $query->paginate(self::PAGINATION_LIMIT, ['*'], 'page', $page) : $query->get();

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

        return response()->json([
            'status' => true,
            'message' => 'Platform created successfully',
            'data' => $platform
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

        $platform = Platform::where('id', $platformId)
            ->where(function ($q) use ($userId) {
                $q->where('marketing_manager_id', $userId)
                    ->orWhere('financial_manager_id', $userId)
                    ->orWhere('owner_id', $userId);
            })
            ->first();

        if (!$platform) {
            Log::error(self::LOG_PREFIX . 'Platform not found', ['platform_id' => $platformId, 'user_id' => $userId]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Failed to fetch platform'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => true,
            'data' => $platform
        ]);
    }

    public function update(Request $request, Platform $platform)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'link' => 'sometimes|url',
            'enabled' => 'sometimes|boolean',
            'type' => 'sometimes|string',
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

        $platform->update($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Platform updated successfully',
            'data' => $platform
        ]);
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

        // Find the platform
        $platform = Platform::find($platformId);

        if (!$platform) {
            Log::error(self::LOG_PREFIX . 'Platform not found', ['platform_id' => $platformId]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Platform not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $oldTypeId = $platform->type;

        // Validate type transitions
        $allowedTransitions = [
            3 => [1, 2], // Type 3 (Paiement) can change to 1 (Full) or 2 (Hybrid)
            2 => [1],    // Type 2 (Hybrid) can change only to 1 (Full)
            1 => []      // Type 1 (Full) cannot change
        ];

        // Check if current type is 1 (cannot change)
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

        // Check if the transition is allowed
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

        // Check if the new type is the same as old type
        if ($oldTypeId == $newTypeId) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'New type cannot be the same as current type'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Create the change request
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
}
