<?php

namespace App\Http\Controllers\Api\partner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Partner\AddRoleRequest;
use App\Http\Requests\Api\Partner\GetPartnerPlatformsRequest;
use App\Models\AssignPlatformRole;
use App\Models\User;
use App\Models\Platform;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserPartnerController extends Controller
{
    private const LOG_PREFIX = '[UserPartnerController] ';

    public function __construct()
    {
        $this->middleware('check.url');
    }

    public function addRole(AddRoleRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            $user = User::findOrFail($validated['user_id']);
            $platform = Platform::findOrFail($validated['platform_id']);

            $assignPlatformRole = AssignPlatformRole::updateOrCreate(
                [
                    'platform_id' => $validated['platform_id'],
                    'user_id' => $validated['user_id'],
                    'role' => $validated['role']
                ],
                [
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]
            );

            Log::info(self::LOG_PREFIX . 'Role assign request sent successfully, waiting for approval', [
                'assignment_id' => $assignPlatformRole->id,
                'user_id' => $validated['user_id'],
                'platform_id' => $validated['platform_id'],
                'role' => $validated['role'],
                'assigned_by' => auth()->id()
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Role assign request sent successfully, waiting for approval',
                'data' => [
                    'id' => $assignPlatformRole->id,
                    'user_id' => $user->id,
                    'platform_id' => $platform->id,
                    'role' => $validated['role']
                ]
            ], Response::HTTP_OK);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error(self::LOG_PREFIX . 'Failed to assign role', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payload' => $validated
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to assign role: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getPartnerPlatforms(GetPartnerPlatformsRequest $request)
    {
        $validated = $request->validated();
        $userId = $validated['user_id'];

        try {

            $platforms = Platform::whereHas('roles', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->with(['businessSector', 'logoImage', 'roles' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }])
            ->get()
            ->map(function ($platform) use ($userId) {

                $roles = $platform->roles->pluck('name')->toArray();

                return [
                    'id' => $platform->id,
                    'name' => $platform->name,
                    'description' => $platform->description,
                    'type' => $platform->type,
                    'link' => $platform->link,
                    'enabled' => $platform->enabled,
                    'show_profile' => $platform->show_profile,
                    'image_link' => $platform->image_link,
                    'business_sector' => $platform->businessSector ? [
                        'id' => $platform->businessSector->id,
                        'name' => $platform->businessSector->name ?? null,
                    ] : null,
                    'logo' => $platform->logoImage ? [
                        'id' => $platform->logoImage->id,
                        'path' => $platform->logoImage->path ?? null,
                    ] : null,
                    'roles' => $roles,
                    'created_at' => $platform->created_at,
                    'updated_at' => $platform->updated_at,
                ];
            });

            Log::info(self::LOG_PREFIX . 'Successfully retrieved partner platforms', [
                'user_id' => $userId,
                'platforms_count' => $platforms->count()
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Platforms retrieved successfully',
                'data' => [
                    'platforms' => $platforms,
                    'total' => $platforms->count()
                ]
            ], Response::HTTP_OK);

        } catch (\Throwable $e) {
            Log::error(self::LOG_PREFIX . 'Failed to retrieve partner platforms', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $userId
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve platforms: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

