<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Services\BusinessSector\BusinessSectorService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class BusinessSectorController extends Controller
{
    private BusinessSectorService $businessSectorService;

    public function __construct(BusinessSectorService $businessSectorService)
    {
        $this->businessSectorService = $businessSectorService;
    }

    /**
     * Get all business sectors
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string|max:255',
            'order_by' => 'nullable|string|in:name,id,created_at,updated_at',
            'order_direction' => 'nullable|string|in:asc,desc',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
            'with' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $params = [
            'search' => $request->input('search'),
            'order_by' => $request->input('order_by', 'name'),
            'order_direction' => $request->input('order_direction', 'asc'),
            'with' => $request->input('with', [])
        ];

        if ($request->has('per_page')) {
            $params['PAGE_SIZE'] = $request->input('per_page', 20);
            $params['page'] = $request->input('page', 1);
        }

        $sectors = $this->businessSectorService->getBusinessSectors($params);

        if ($request->has('per_page')) {
            return response()->json([
                'status' => true,
                'data' => $sectors->items(),
                'pagination' => [
                    'current_page' => $sectors->currentPage(),
                    'per_page' => $sectors->perPage(),
                    'total' => $sectors->total(),
                    'last_page' => $sectors->lastPage()
                ]
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $sectors
        ]);
    }

    /**
     * Get all business sectors (simple list)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        $sectors = $this->businessSectorService->getAll();

        return response()->json([
            'status' => true,
            'data' => $sectors
        ]);
    }

    /**
     * Get all business sectors ordered by name
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ordered(Request $request)
    {
        $direction = $request->input('direction', 'asc');

        $validator = Validator::make(['direction' => $direction], [
            'direction' => 'in:asc,desc'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid direction parameter',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $sectors = $this->businessSectorService->getAllOrderedByName($direction);

        return response()->json([
            'status' => true,
            'data' => $sectors
        ]);
    }

    /**
     * Get business sector by ID
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $sector = $this->businessSectorService->getById($id);

        if (!$sector) {
            return response()->json([
                'status' => false,
                'message' => 'Business sector not found'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => true,
            'data' => $sector
        ]);
    }

    /**
     * Get business sector with images
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showWithImages(int $id)
    {
        $sector = $this->businessSectorService->getBusinessSectorWithImages($id);

        if (!$sector) {
            return response()->json([
                'status' => false,
                'message' => 'Business sector not found'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => true,
            'data' => $sector
        ]);
    }

    /**
     * Get business sectors with user purchases
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userPurchases(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $sectors = $this->businessSectorService->getSectorsWithUserPurchases($request->input('user_id'));

        return response()->json([
            'status' => true,
            'data' => $sectors
        ]);
    }

    /**
     * Create a new business sector
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo_image_id' => 'nullable|integer|exists:images,id',
            'thumbnails_image_id' => 'nullable|integer|exists:images,id',
            'thumbnails_home_image_id' => 'nullable|integer|exists:images,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $sector = $this->businessSectorService->create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Business sector created successfully',
                'data' => $sector
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create business sector',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update a business sector
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'logo_image_id' => 'nullable|integer|exists:images,id',
            'thumbnails_image_id' => 'nullable|integer|exists:images,id',
            'thumbnails_home_image_id' => 'nullable|integer|exists:images,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $success = $this->businessSectorService->update($id, $request->all());

        if (!$success) {
            return response()->json([
                'status' => false,
                'message' => 'Business sector not found or update failed'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => true,
            'message' => 'Business sector updated successfully'
        ]);
    }

    /**
     * Delete a business sector
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        $success = $this->businessSectorService->delete($id);

        if (!$success) {
            return response()->json([
                'status' => false,
                'message' => 'Business sector not found or delete failed'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => true,
            'message' => 'Business sector deleted successfully'
        ]);
    }
}

