<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Services\EntityRole\EntityRoleService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class EntityRoleController extends Controller
{
    private EntityRoleService $entityRoleService;

    public function __construct(EntityRoleService $entityRoleService)
    {
        $this->entityRoleService = $entityRoleService;
    }

    /**
     * Get all roles
     */
    public function index()
    {
        $roles = $this->entityRoleService->getAllRoles();
        return response()->json(['status' => true, 'data' => $roles]);
    }

    /**
     * Get filtered roles with pagination
     */
    public function getFiltered(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string',
            'type' => 'nullable|in:platform,partner',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $roles = $this->entityRoleService->getFilteredRoles(
            $request->input('search'),
            $request->input('type'),
            $request->input('per_page', 15)
        );

        return response()->json([
            'status' => true,
            'data' => $roles->items(),
            'pagination' => [
                'current_page' => $roles->currentPage(),
                'per_page' => $roles->perPage(),
                'total' => $roles->total(),
                'last_page' => $roles->lastPage()
            ]
        ]);
    }

    /**
     * Get role by ID
     */
    public function show(int $id)
    {
        $role = $this->entityRoleService->getRoleById($id);

        if (!$role) {
            return response()->json(['status' => false, 'message' => 'Role not found'], 404);
        }

        return response()->json(['status' => true, 'data' => $role]);
    }

    /**
     * Get platform roles
     */
    public function getPlatformRoles()
    {
        $roles = $this->entityRoleService->getPlatformRoles();
        return response()->json(['status' => true, 'data' => $roles]);
    }

    /**
     * Get partner roles
     */
    public function getPartnerRoles()
    {
        $roles = $this->entityRoleService->getPartnerRoles();
        return response()->json(['status' => true, 'data' => $roles]);
    }

    /**
     * Get roles for platform
     */
    public function getRolesForPlatform(Request $request, int $platformId)
    {
        $roles = $this->entityRoleService->getRolesForPlatform(
            $platformId,
            $request->boolean('paginate', false),
            $request->input('per_page', 10)
        );

        return response()->json(['status' => true, 'data' => $roles]);
    }

    /**
     * Get roles for partner
     */
    public function getRolesForPartner(Request $request, int $partnerId)
    {
        $roles = $this->entityRoleService->getRolesForPartner(
            $partnerId,
            $request->boolean('paginate', false),
            $request->input('per_page', 10)
        );

        return response()->json(['status' => true, 'data' => $roles]);
    }

    /**
     * Get entity roles keyed by name
     */
    public function getEntityRolesKeyedByName(int $platformId)
    {
        $roles = $this->entityRoleService->getEntityRolesKeyedByName($platformId);
        return response()->json(['status' => true, 'data' => $roles]);
    }

    /**
     * Get platforms with roles for user
     */
    public function getPlatformsWithRolesForUser(Request $request, int $userId)
    {
        $platforms = $this->entityRoleService->getPlatformsWithRolesForUser(
            $userId,
            $request->input('with', [])
        );

        return response()->json(['status' => true, 'data' => $platforms]);
    }

    /**
     * Get user roles for platform
     */
    public function getUserRolesForPlatform(int $userId, int $platformId)
    {
        $roles = $this->entityRoleService->getUserRolesForPlatform($userId, $platformId);
        return response()->json(['status' => true, 'data' => $roles]);
    }

    /**
     * Get user platform IDs
     */
    public function getUserPlatformIds(int $userId)
    {
        $platformIds = $this->entityRoleService->getUserPlatformIds($userId);
        return response()->json(['status' => true, 'data' => $platformIds]);
    }

    /**
     * Get user partner IDs
     */
    public function getUserPartnerIds(int $userId)
    {
        $partnerIds = $this->entityRoleService->getUserPartnerIds($userId);
        return response()->json(['status' => true, 'data' => $partnerIds]);
    }

    /**
     * Check if user has platform role
     */
    public function checkUserHasPlatformRole(int $userId)
    {
        $hasRole = $this->entityRoleService->userHasPlatformRole($userId);
        return response()->json(['status' => true, 'has_role' => $hasRole]);
    }

    /**
     * Check if user has partner role
     */
    public function checkUserHasPartnerRole(int $userId)
    {
        $hasRole = $this->entityRoleService->userHasPartnerRole($userId);
        return response()->json(['status' => true, 'has_role' => $hasRole]);
    }

    /**
     * Get platform owner role
     */
    public function getPlatformOwnerRole(int $platformId)
    {
        $role = $this->entityRoleService->getPlatformOwnerRole($platformId);

        if (!$role) {
            return response()->json(['status' => false, 'message' => 'Owner role not found'], 404);
        }

        return response()->json(['status' => true, 'data' => $role]);
    }

    /**
     * Create platform role
     */
    public function createPlatformRole(Request $request, int $platformId)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'user_id' => 'required|integer|exists:users,id',
            'created_by' => 'nullable|integer|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $role = $this->entityRoleService->createPlatformRole($platformId, $request->all());
            return response()->json(['status' => true, 'data' => $role, 'message' => 'Role created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Create partner role
     */
    public function createPartnerRole(Request $request, int $partnerId)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'user_id' => 'required|integer|exists:users,id',
            'created_by' => 'nullable|integer|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $role = $this->entityRoleService->createPartnerRole($partnerId, $request->all());
            return response()->json(['status' => true, 'data' => $role, 'message' => 'Role created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update role
     */
    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'user_id' => 'nullable|integer|exists:users,id',
            'updated_by' => 'nullable|integer|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $role = $this->entityRoleService->updateRole($id, $request->all());
            return response()->json(['status' => true, 'data' => $role, 'message' => 'Role updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 404);
        }
    }

    /**
     * Delete role
     */
    public function destroy(int $id)
    {
        try {
            $this->entityRoleService->deleteRole($id);
            return response()->json(['status' => true, 'message' => 'Role deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 404);
        }
    }

    /**
     * Search roles by name
     */
    public function searchByName(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $roles = $this->entityRoleService->searchRolesByName($request->input('name'));
        return response()->json(['status' => true, 'data' => $roles]);
    }
}

