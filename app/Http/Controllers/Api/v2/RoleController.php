<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Services\Role\RoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    private RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Get all roles (paginated)
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');

            $roles = $this->roleService->getPaginated($search, $perPage);

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
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get all roles (non-paginated)
     */
    public function all()
    {
        try {
            $roles = $this->roleService->getAll();
            return response()->json(['status' => true, 'data' => $roles]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get a single role by ID
     */
    public function show(int $id)
    {
        try {
            $role = $this->roleService->getById($id);

            if (!$role) {
                return response()->json(['status' => false, 'message' => 'Role not found'], 404);
            }

            return response()->json(['status' => true, 'data' => $role]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Create a new role
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name',
            'guard_name' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $data = [
                'name' => $request->input('name'),
                'guard_name' => $request->input('guard_name', 'web')
            ];

            $role = $this->roleService->create($data);
            return response()->json(['status' => true, 'data' => $role], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update a role
     */
    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255|unique:roles,name,' . $id,
            'guard_name' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $result = $this->roleService->update($id, $request->all());

            if (!$result) {
                return response()->json(['status' => false, 'message' => 'Failed to update role'], 400);
            }

            return response()->json(['status' => true, 'message' => 'Role updated successfully']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['status' => false, 'message' => 'Role not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a role
     */
    public function destroy(int $id)
    {
        try {
            $result = $this->roleService->delete($id);

            if (!$result) {
                return response()->json(['status' => false, 'message' => 'Failed to delete role'], 400);
            }

            return response()->json(['status' => true, 'message' => 'Role deleted successfully']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['status' => false, 'message' => 'Role not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Check if a role can be deleted
     */
    public function canDelete(int $id)
    {
        try {
            $canDelete = $this->roleService->canDelete($id);
            return response()->json(['status' => true, 'can_delete' => $canDelete]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get paginated user roles
     */
    public function getUserRoles(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $search = $request->input('search', '');
            $perPage = $request->input('per_page', 10);

            $userRoles = $this->roleService->getUserRoles($search, $perPage);

            return response()->json([
                'status' => true,
                'data' => $userRoles->items(),
                'pagination' => [
                    'current_page' => $userRoles->currentPage(),
                    'per_page' => $userRoles->perPage(),
                    'total' => $userRoles->total(),
                    'last_page' => $userRoles->lastPage()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
}

