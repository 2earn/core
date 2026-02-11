<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Services\Coupon\BalanceInjectorCouponService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class BalanceInjectorCouponController extends Controller
{
    private BalanceInjectorCouponService $balanceInjectorCouponService;

    public function __construct(BalanceInjectorCouponService $balanceInjectorCouponService)
    {
        $this->balanceInjectorCouponService = $balanceInjectorCouponService;
    }

    /**
     * Get paginated balance injector coupons
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string|max:255',
            'sort_field' => 'nullable|string|in:created_at,value,category,pin',
            'sort_direction' => 'nullable|string|in:asc,desc',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $coupons = $this->balanceInjectorCouponService->getPaginated(
            $request->input('search'),
            $request->input('sort_field', 'created_at'),
            $request->input('sort_direction', 'desc'),
            $request->input('per_page', 10)
        );

        return response()->json([
            'status' => true,
            'data' => $coupons->items(),
            'pagination' => [
                'current_page' => $coupons->currentPage(),
                'per_page' => $coupons->perPage(),
                'total' => $coupons->total(),
                'last_page' => $coupons->lastPage()
            ]
        ]);
    }

    /**
     * Get all balance injector coupons
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        $coupons = $this->balanceInjectorCouponService->getAll();

        return response()->json([
            'status' => true,
            'data' => $coupons
        ]);
    }

    /**
     * Get balance injector coupon by ID
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $coupon = $this->balanceInjectorCouponService->getById($id);

        if (!$coupon) {
            return response()->json([
                'status' => false,
                'message' => 'Balance injector coupon not found'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => true,
            'data' => $coupon
        ]);
    }

    /**
     * Get balance injector coupon by PIN
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByPin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pin' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $coupon = $this->balanceInjectorCouponService->getByPin($request->input('pin'));

        if (!$coupon) {
            return response()->json([
                'status' => false,
                'message' => 'Balance injector coupon not found'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => true,
            'data' => $coupon
        ]);
    }

    /**
     * Get balance injector coupons by user ID
     *
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByUserId(int $userId)
    {
        $coupons = $this->balanceInjectorCouponService->getByUserId($userId);

        return response()->json([
            'status' => true,
            'data' => $coupons
        ]);
    }

    /**
     * Create multiple balance injector coupons
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createMultiple(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'number_of_coupons' => 'required|integer|min:1|max:99',
            'attachment_date' => 'required|date',
            'value' => 'required|numeric|min:0',
            'category' => 'required|integer',
            'consumed' => 'required|boolean',
            'type' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $couponData = [
            'attachment_date' => $request->input('attachment_date'),
            'value' => $request->input('value'),
            'category' => $request->input('category'),
            'consumed' => $request->input('consumed')
        ];

        $result = $this->balanceInjectorCouponService->createMultipleCoupons(
            $request->input('number_of_coupons'),
            $couponData,
            $request->input('type')
        );

        if (!$result['success']) {
            return response()->json([
                'status' => false,
                'message' => $result['message']
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'status' => true,
            'message' => $result['message'],
            'created_count' => $result['createdCount']
        ], Response::HTTP_CREATED);
    }

    /**
     * Delete a balance injector coupon
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        try {
            $success = $this->balanceInjectorCouponService->delete($id);

            return response()->json([
                'status' => true,
                'message' => 'Balance injector coupon deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Coupon not found or deletion failed',
                'error' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Delete multiple balance injector coupons
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteMultiple(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:balance_injector_coupons,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $deletedCount = $this->balanceInjectorCouponService->deleteMultiple($request->input('ids'));

            return response()->json([
                'status' => true,
                'message' => 'Balance injector coupons deleted successfully',
                'deleted_count' => $deletedCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Deletion failed',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

