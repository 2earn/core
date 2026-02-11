<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Services\Coupon\CouponService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    private CouponService $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    /**
     * Get paginated coupons
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string|max:255',
            'sort_field' => 'nullable|string|in:created_at,value,pin,sn',
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

        $coupons = $this->couponService->getCouponsPaginated(
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
     * Get all coupons ordered
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        $coupons = $this->couponService->getAllCouponsOrdered();

        return response()->json([
            'status' => true,
            'data' => $coupons
        ]);
    }

    /**
     * Get coupon by ID
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $coupon = $this->couponService->findCouponById($id);

        if (!$coupon) {
            return response()->json([
                'status' => false,
                'message' => 'Coupon not found'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => true,
            'data' => $coupon
        ]);
    }

    /**
     * Get coupon by serial number
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBySn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sn' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $coupon = $this->couponService->getBySn($request->input('sn'));

            return response()->json([
                'status' => true,
                'data' => $coupon
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Coupon not found'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Get user coupons paginated
     *
     * @param Request $request
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserCoupons(Request $request, int $userId)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string|max:255',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $coupons = $this->couponService->getUserCouponsPaginated(
            $userId,
            $request->input('search'),
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
     * Get purchased coupons for user
     *
     * @param Request $request
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPurchasedCoupons(Request $request, int $userId)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string|max:255',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $coupons = $this->couponService->getPurchasedCouponsPaginated(
            $userId,
            $request->input('search'),
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
     * Get purchased coupons by status
     *
     * @param int $userId
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPurchasedByStatus(int $userId, int $status)
    {
        $coupons = $this->couponService->getPurchasedCouponsByStatus($userId, $status);

        return response()->json([
            'status' => true,
            'data' => $coupons
        ]);
    }

    /**
     * Get available coupons for platform
     *
     * @param Request $request
     * @param int $platformId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvailableForPlatform(Request $request, int $platformId)
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

        $coupons = $this->couponService->getAvailableCouponsForPlatform(
            $platformId,
            $request->input('user_id')
        );

        return response()->json([
            'status' => true,
            'data' => $coupons
        ]);
    }

    /**
     * Get max available amount for platform
     *
     * @param int $platformId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMaxAvailableAmount(int $platformId)
    {
        $maxAmount = $this->couponService->getMaxAvailableAmount($platformId);

        return response()->json([
            'status' => true,
            'max_amount' => $maxAmount
        ]);
    }

    /**
     * Simulate coupon purchase
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function simulatePurchase(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'platform_id' => 'required|integer|exists:platforms,id',
            'user_id' => 'required|integer|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'reservation_minutes' => 'nullable|integer|min:1|max:1440'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $result = $this->couponService->simulateCouponPurchase(
            $request->input('platform_id'),
            $request->input('user_id'),
            $request->input('amount'),
            $request->input('reservation_minutes', 15)
        );

        if (!$result['success']) {
            return response()->json([
                'status' => false,
                'message' => $result['message']
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'status' => true,
            'data' => $result
        ]);
    }

    /**
     * Buy coupons
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function buyCoupon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coupons' => 'required|array',
            'coupons.*.value' => 'required|numeric',
            'coupons.*.sn' => 'required|string',
            'user_id' => 'required|integer|exists:users,id',
            'platform_id' => 'required|integer|exists:platforms,id',
            'platform_name' => 'required|string',
            'item_id' => 'required|integer|exists:items,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $result = $this->couponService->buyCoupon(
            $request->input('coupons'),
            $request->input('user_id'),
            $request->input('platform_id'),
            $request->input('platform_name'),
            $request->input('item_id')
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
            'data' => $result
        ]);
    }

    /**
     * Mark coupon as consumed
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsConsumed(int $id)
    {
        try {
            $success = $this->couponService->markAsConsumed($id);

            return response()->json([
                'status' => true,
                'message' => 'Coupon marked as consumed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Consume a coupon
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function consume(Request $request, int $id)
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

        $success = $this->couponService->consume($id, $request->input('user_id'));

        if (!$success) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to consume coupon'
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'status' => true,
            'message' => 'Coupon consumed successfully'
        ]);
    }

    /**
     * Delete a coupon
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, int $id)
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

        try {
            $success = $this->couponService->delete($id, $request->input('user_id'));

            return response()->json([
                'status' => true,
                'message' => 'Coupon deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Delete multiple coupons
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteMultiple(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:coupons,id',
            'user_id' => 'required|integer|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $deletedCount = $this->couponService->deleteMultiple(
            $request->input('ids'),
            $request->input('user_id')
        );

        return response()->json([
            'status' => true,
            'message' => 'Coupons deleted successfully',
            'deleted_count' => $deletedCount
        ]);
    }
}

