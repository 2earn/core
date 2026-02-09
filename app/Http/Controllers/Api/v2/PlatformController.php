<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Services\Platform\PlatformService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlatformController extends Controller
{
    protected PlatformService $platformService;

    public function __construct(PlatformService $platformService)
    {
        $this->platformService = $platformService;
    }

    /**
     * Get paginated platforms with filters
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $search = $request->get('search', '');
            $perPage = (int) $request->get('per_page', 15);
            $businessSectors = $request->has('business_sectors') ? $request->get('business_sectors') : [];
            $types = $request->has('types') ? $request->get('types') : [];
            $enabled = $request->has('enabled') ? $request->get('enabled') : [];

            $perPage = min(max($perPage, 1), 100);

            $platforms = $this->platformService->getPaginatedPlatforms($search, $perPage, $businessSectors, $types, $enabled);

            return response()->json([
                'success' => true,
                'data' => $platforms
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching platforms: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all platforms (non-paginated)
     *
     * @return JsonResponse
     */
    public function all(): JsonResponse
    {
        try {
            $platforms = $this->platformService->getAll();

            return response()->json([
                'success' => true,
                'data' => $platforms
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching all platforms: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get enabled platforms only
     *
     * @return JsonResponse
     */
    public function enabled(): JsonResponse
    {
        try {
            $platforms = $this->platformService->getEnabledPlatforms();

            return response()->json([
                'success' => true,
                'data' => $platforms
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching enabled platforms: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific platform by ID
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $platform = $this->platformService->getById($id);

            if (!$platform) {
                return response()->json([
                    'success' => false,
                    'message' => 'Platform not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $platform
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching platform: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get platforms with user purchase history
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function withUserPurchases(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer|exists:users,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $platforms = $this->platformService->getPlatformsWithUserPurchases($request->user_id);

            return response()->json([
                'success' => true,
                'data' => $platforms
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching platforms: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get platforms with active deals for a business sector
     *
     * @param int $businessSectorId
     * @return JsonResponse
     */
    public function withActiveDeals(int $businessSectorId): JsonResponse
    {
        try {
            $platforms = $this->platformService->getPlatformsWithActiveDeals($businessSectorId);

            return response()->json([
                'success' => true,
                'data' => $platforms
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching platforms: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get items from enabled platforms for a business sector
     *
     * @param int $businessSectorId
     * @return JsonResponse
     */
    public function items(int $businessSectorId): JsonResponse
    {
        try {
            $items = $this->platformService->getItemsFromEnabledPlatforms($businessSectorId);

            return response()->json([
                'success' => true,
                'data' => $items
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching items: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get platforms for a partner (filtered by user roles)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function forPartner(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer|exists:users,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $page = $request->has('page') ? (int) $request->get('page') : 1;
            $search = $request->get('search');
            $limit = (int) $request->get('limit', 8);

            $result = $this->platformService->getPlatformsForPartner($request->user_id, $page, $search, $limit);

            return response()->json([
                'success' => true,
                'data' => $result
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching platforms: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single platform for a partner
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function partnerPlatform(Request $request, int $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer|exists:users,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $platform = $this->platformService->getPlatformForPartner($id, $request->user_id);

            if (!$platform) {
                return response()->json([
                    'success' => false,
                    'message' => 'Platform not found or access denied'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $platform
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching platform: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if user has role in platform
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function checkUserRole(Request $request, int $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer|exists:users,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $hasRole = $this->platformService->userHasRoleInPlatform($request->user_id, $id);

            return response()->json([
                'success' => true,
                'has_role' => $hasRole
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking user role: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get platforms with coupon deals
     *
     * @return JsonResponse
     */
    public function withCouponDeals(): JsonResponse
    {
        try {
            $platforms = $this->platformService->getPlatformsWithCouponDeals();

            return response()->json([
                'success' => true,
                'data' => $platforms
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching platforms: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get platforms with coupon deals for select dropdown
     *
     * @return JsonResponse
     */
    public function couponDealsSelect(): JsonResponse
    {
        try {
            $platforms = $this->platformService->getSelectPlatformsWithCouponDeals();

            return response()->json([
                'success' => true,
                'data' => $platforms
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching platforms: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new platform
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'type' => 'required|integer',
                'business_sector_id' => 'required|integer|exists:business_sectors,id',
                'enabled' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $platform = $this->platformService->create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Platform created successfully',
                'data' => $platform
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating platform: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a platform
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'type' => 'sometimes|integer',
                'business_sector_id' => 'sometimes|integer|exists:business_sectors,id',
                'enabled' => 'sometimes|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $success = $this->platformService->update($id, $request->all());

            if (!$success) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update platform'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Platform updated successfully'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Platform not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating platform: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a platform
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $success = $this->platformService->delete($id);

            if (!$success) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete platform'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Platform deleted successfully'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Platform not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting platform: ' . $e->getMessage()
            ], 500);
        }
    }
}

