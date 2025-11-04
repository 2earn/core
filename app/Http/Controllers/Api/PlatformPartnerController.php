<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Core\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PlatformPartnerController extends Controller
{
    private const LOG_PREFIX = '[PlatformPartnerController] ';

    public function __construct()
    {
        $this->middleware('check.url');
    }

    public function index(Request $request)
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
            ], 422);
        }

        $platforms = Platform::where('marketing_manager_id', $userId)
            ->orWhere('financial_manager_id', $userId)
            ->orWhere('owner_id', $userId)
            ->paginate(10);

        return response()->json([
            'status' => true,
            'data' => $platforms
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'link' => 'required|url',
            'enabled' => 'required|boolean',
            'type' => 'required|string',
            'show_profile' => 'boolean',
            'image_link' => 'nullable|string',
            'owner_id' => 'required|exists:users,id',
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

        $platform = Platform::create($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Platform created successfully',
            'data' => $platform
        ], Response::HTTP_CREATED);
    }

    public function show(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'platform_id' => 'required|integer|exists:platform,id'
        ]);

        $userId = $request->input('user_id');
        $platformId = $request->input('platform_id');

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $platform = Platform::where('marketing_manager_id', $userId)
            ->orWhere('financial_manager_id', $userId)
            ->orWhere('owner_id', $userId)
            ->andWhere('id', $platformId)
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
}
