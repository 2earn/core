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
        $validated = $request->validate([
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

        $platform = Platform::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Platform created successfully',
            'data' => $platform
        ], Response::HTTP_CREATED);
    }

    public function show(Platform $platform)
    {
        return response()->json([
            'status' => true,
            'data' => $platform
        ]);
    }

    public function update(Request $request, Platform $platform)
    {
        $validated = $request->validate([
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

        $platform->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Platform updated successfully',
            'data' => $platform
        ]);
    }
}
