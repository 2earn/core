<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Services\UserGuide\UserGuideService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserGuideController extends Controller
{
    protected UserGuideService $userGuideService;

    public function __construct(UserGuideService $userGuideService)
    {
        $this->userGuideService = $userGuideService;
    }

    /**
     * Get paginated user guides with search
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $search = $request->get('search');
            $perPage = (int) $request->get('per_page', 10);
            $perPage = min(max($perPage, 1), 100);

            $guides = $this->userGuideService->getPaginated($search, $perPage);

            return response()->json([
                'success' => true,
                'data' => $guides
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching user guides: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all user guides
     *
     * @return JsonResponse
     */
    public function all(): JsonResponse
    {
        try {
            $guides = $this->userGuideService->getAll();

            return response()->json([
                'success' => true,
                'data' => $guides
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching all user guides: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific user guide by ID
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $guide = $this->userGuideService->getById($id);

            if (!$guide) {
                return response()->json([
                    'success' => false,
                    'message' => 'User guide not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $guide
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching user guide: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search user guides
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'search' => 'required|string|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $guides = $this->userGuideService->search($request->search);

            return response()->json([
                'success' => true,
                'data' => $guides
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error searching user guides: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user guides by route name
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function byRoute(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'route_name' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $guides = $this->userGuideService->getByRouteName($request->route_name);

            return response()->json([
                'success' => true,
                'data' => $guides
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching user guides: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user guides by user ID
     *
     * @param int $userId
     * @return JsonResponse
     */
    public function byUser(int $userId): JsonResponse
    {
        try {
            $guides = $this->userGuideService->getByUserId($userId);

            return response()->json([
                'success' => true,
                'data' => $guides
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching user guides: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recent user guides
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function recent(Request $request): JsonResponse
    {
        try {
            $limit = (int) $request->get('limit', 5);
            $limit = min(max($limit, 1), 50);

            $guides = $this->userGuideService->getRecent($limit);

            return response()->json([
                'success' => true,
                'data' => $guides
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching recent user guides: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user guide count
     *
     * @return JsonResponse
     */
    public function count(): JsonResponse
    {
        try {
            $count = $this->userGuideService->count();

            return response()->json([
                'success' => true,
                'count' => $count
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching count: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if user guide exists
     *
     * @param int $id
     * @return JsonResponse
     */
    public function exists(int $id): JsonResponse
    {
        try {
            $exists = $this->userGuideService->exists($id);

            return response()->json([
                'success' => true,
                'exists' => $exists
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking existence: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new user guide
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'content' => 'nullable|string',
                'routes' => 'nullable|array',
                'user_id' => 'required|integer|exists:users,id',
                'video_url' => 'nullable|url',
                'order' => 'nullable|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $guide = $this->userGuideService->create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'User guide created successfully',
                'data' => $guide
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating user guide: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a user guide
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'content' => 'nullable|string',
                'routes' => 'nullable|array',
                'user_id' => 'sometimes|integer|exists:users,id',
                'video_url' => 'nullable|url',
                'order' => 'nullable|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $success = $this->userGuideService->update($id, $request->all());

            if (!$success) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update user guide'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'User guide updated successfully'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User guide not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating user guide: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a user guide
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $success = $this->userGuideService->delete($id);

            if (!$success) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete user guide'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'User guide deleted successfully'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User guide not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting user guide: ' . $e->getMessage()
            ], 500);
        }
    }
}

