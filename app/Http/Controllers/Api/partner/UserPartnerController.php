<?php

namespace App\Http\Controllers\Api\partner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Partner\AddRoleRequest;
use App\Http\Requests\Api\Partner\UpdateRoleRequest;
use App\Http\Requests\Api\Partner\GetPartnerPlatformsRequest;
use App\Models\AssignPlatformRole;
use App\Models\User;
use App\Models\Platform;
use App\Services\EntityRole\EntityRoleService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserPartnerController extends Controller
{
    private const LOG_PREFIX = '[UserPartnerController] ';

    protected EntityRoleService $entityRoleService;

    public function __construct(EntityRoleService $entityRoleService)
    {
        $this->middleware('check.url');
        $this->entityRoleService = $entityRoleService;
    }

    // ...existing code...

    public function getPartnerPlatforms(GetPartnerPlatformsRequest $request)
    {
        $validated = $request->validated();
        $userId = $validated['user_id'];

        try {
            // Use EntityRoleService to get platforms with roles for the user
            $platforms = $this->entityRoleService->getPlatformsWithRolesForUser($userId);

            // Transform the data
            $platformsData = $platforms->map(function ($platform) {
                // Extract role names from the loaded roles relationship
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
                    'roles' => $roles, // Array of role names for this user on this platform
                    'role_details' => $platform->roles->map(function ($role) use ($platform) {
                        return [
                            'id' => $role->id,
                            'name' => $role->name,
                            'platform_name' => $platform->name,
                            'created_at' => $role->created_at,
                            'updated_at' => $role->updated_at,
                        ];
                    }),
                    'created_at' => $platform->created_at,
                    'updated_at' => $platform->updated_at,
                ];
            });

            Log::info(self::LOG_PREFIX . 'Successfully retrieved partner platforms with EntityRole', [
                'user_id' => $userId,
                'platforms_count' => $platformsData->count()
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Platforms retrieved successfully',
                'data' => [
                    'platforms' => $platformsData,
                    'total' => $platformsData->count()
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

    public function addRole(AddRoleRequest $request)
    {
        $validated = $request->validated();

        try {
            // Check if role already exists for this user on this platform
            $existingRole = $this->entityRoleService->getRoleByUserPlatformAndName(
                $validated['user_id'],
                $validated['platform_id'],
                $validated['role']
            );

            if ($existingRole) {
                Log::warning(self::LOG_PREFIX . 'Role already exists', [
                    'user_id' => $validated['user_id'],
                    'platform_id' => $validated['platform_id'],
                    'role' => $validated['role']
                ]);

                return response()->json([
                    'status' => false,
                    'message' => 'This role is already assigned to the user for this platform',
                    'data' => [
                        'existing_role' => [
                            'id' => $existingRole->id,
                            'name' => $existingRole->name,
                            'created_at' => $existingRole->created_at
                        ]
                    ]
                ], Response::HTTP_CONFLICT);
            }

            // Create the role using EntityRoleService
            $roleData = [
                'name' => $validated['role'],
                'user_id' => $validated['user_id'],
                'created_by' => auth()->id() ?? $validated['user_id'],
                'updated_by' => auth()->id() ?? $validated['user_id'],
            ];

            $role = $this->entityRoleService->createPlatformRole(
                $validated['platform_id'],
                $roleData
            );

            // Load relationships for response
            $role->load(['user:id,name,email', 'roleable:id,name']);

            Log::info(self::LOG_PREFIX . 'Role assigned successfully', [
                'role_id' => $role->id,
                'user_id' => $validated['user_id'],
                'platform_id' => $validated['platform_id'],
                'role_name' => $validated['role'],
                'created_by' => $roleData['created_by']
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Role assigned successfully',
                'data' => [
                    'role' => [
                        'id' => $role->id,
                        'name' => $role->name,
                        'user' => [
                            'id' => $role->user->id,
                            'name' => $role->user->name,
                            'email' => $role->user->email
                        ],
                        'platform' => [
                            'id' => $role->roleable->id,
                            'name' => $role->roleable->name
                        ],
                        'created_at' => $role->created_at,
                        'updated_at' => $role->updated_at
                    ]
                ]
            ], Response::HTTP_CREATED);

        } catch (\Throwable $e) {
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

    public function updateRole(UpdateRoleRequest $request)
    {
        $validated = $request->validated();

        try {
            // Get the existing role
            $existingRole = $this->entityRoleService->getRoleById($validated['role_id']);

            if (!$existingRole) {
                Log::warning(self::LOG_PREFIX . 'Role not found', [
                    'role_id' => $validated['role_id']
                ]);

                return response()->json([
                    'status' => false,
                    'message' => 'Role not found'
                ], Response::HTTP_NOT_FOUND);
            }

            // Update the role using EntityRoleService
            $updateData = [
                'name' => $validated['role_name'],
                'updated_by' => auth()->id() ?? $existingRole->user_id,
            ];

            $role = $this->entityRoleService->updateRole(
                $validated['role_id'],
                $updateData
            );

            // Load relationships for response
            $role->load(['user:id,name,email', 'roleable:id,name']);

            Log::info(self::LOG_PREFIX . 'Role updated successfully', [
                'role_id' => $role->id,
                'old_name' => $existingRole->name,
                'new_name' => $validated['role_name'],
                'updated_by' => $updateData['updated_by']
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Role updated successfully',
                'data' => [
                    'role' => [
                        'id' => $role->id,
                        'name' => $role->name,
                        'user' => [
                            'id' => $role->user->id,
                            'name' => $role->user->name,
                            'email' => $role->user->email
                        ],
                        'platform' => [
                            'id' => $role->roleable->id,
                            'name' => $role->roleable->name
                        ],
                        'created_at' => $role->created_at,
                        'updated_at' => $role->updated_at
                    ]
                ]
            ], Response::HTTP_OK);

        } catch (\Throwable $e) {
            Log::error(self::LOG_PREFIX . 'Failed to update role', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payload' => $validated
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to update role: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

