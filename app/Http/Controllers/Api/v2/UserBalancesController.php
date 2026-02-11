<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Services\UserBalances\UserBalancesHelper;
use App\Services\UserBalances\UserCurrentBalanceVerticalService;
use App\Services\UserBalances\UserCurrentBalanceHorisontalService;
use App\Enums\BalanceEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserBalancesController extends Controller
{
    private UserBalancesHelper $userBalancesHelper;
    private UserCurrentBalanceVerticalService $verticalService;
    private UserCurrentBalanceHorisontalService $horisontalService;

    public function __construct(
        UserBalancesHelper $userBalancesHelper,
        UserCurrentBalanceVerticalService $verticalService,
        UserCurrentBalanceHorisontalService $horisontalService
    ) {
        $this->userBalancesHelper = $userBalancesHelper;
        $this->verticalService = $verticalService;
        $this->horisontalService = $horisontalService;
    }

    // ==================== HORIZONTAL BALANCE ENDPOINTS ====================

    /**
     * Get user's horizontal balance record
     * GET /api/v2/user-balances/horizontal/{userId}
     */
    public function getHorizontalBalance(int $userId)
    {
        $balance = $this->horisontalService->getStoredUserBalances($userId);

        if (!$balance) {
            return response()->json(['status' => false, 'message' => 'User balance not found'], 404);
        }

        return response()->json(['status' => true, 'data' => $balance]);
    }

    /**
     * Get specific horizontal balance field
     * GET /api/v2/user-balances/horizontal/{userId}/field/{field}
     */
    public function getHorizontalBalanceField(int $userId, string $field)
    {
        $balance = $this->horisontalService->getStoredUserBalances($userId, $field);

        if ($balance === null) {
            return response()->json(['status' => false, 'message' => 'Balance field not found'], 404);
        }

        return response()->json(['status' => true, 'data' => ['field' => $field, 'value' => $balance]]);
    }

    /**
     * Get user's cash balance
     * GET /api/v2/user-balances/horizontal/{userId}/cash
     */
    public function getCashBalance(int $userId)
    {
        $balance = $this->horisontalService->getStoredCash($userId);

        return response()->json(['status' => true, 'data' => ['cash_balance' => $balance]]);
    }

    /**
     * Get user's BFSS balance by type
     * GET /api/v2/user-balances/horizontal/{userId}/bfss/{type}
     */
    public function getBfssBalance(int $userId, string $type)
    {
        $balance = $this->horisontalService->getStoredBfss($userId, $type);

        return response()->json(['status' => true, 'data' => ['bfss_balance' => $balance, 'type' => $type]]);
    }

    /**
     * Get user's discount balance
     * GET /api/v2/user-balances/horizontal/{userId}/discount
     */
    public function getDiscountBalance(int $userId)
    {
        $balance = $this->horisontalService->getStoredDiscount($userId);

        return response()->json(['status' => true, 'data' => ['discount_balance' => $balance]]);
    }

    /**
     * Get user's tree balance
     * GET /api/v2/user-balances/horizontal/{userId}/tree
     */
    public function getTreeBalance(int $userId)
    {
        $balance = $this->horisontalService->getStoredTree($userId);

        return response()->json(['status' => true, 'data' => ['tree_balance' => $balance]]);
    }

    /**
     * Get user's SMS balance
     * GET /api/v2/user-balances/horizontal/{userId}/sms
     */
    public function getSmsBalance(int $userId)
    {
        $balance = $this->horisontalService->getStoredSms($userId);

        return response()->json(['status' => true, 'data' => ['sms_balance' => $balance]]);
    }

    /**
     * Update calculated horizontal balance
     * PUT /api/v2/user-balances/horizontal/{userId}/calculated
     */
    public function updateCalculatedHorizontal(Request $request, int $userId)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string',
            'value' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $updated = $this->horisontalService->updateCalculatedHorisental(
            $userId,
            $request->input('type'),
            $request->input('value')
        );

        return response()->json([
            'status' => true,
            'message' => 'Balance updated successfully',
            'data' => ['rows_updated' => $updated]
        ]);
    }

    /**
     * Update balance field
     * PUT /api/v2/user-balances/horizontal/{userId}/field
     */
    public function updateBalanceField(Request $request, int $userId)
    {
        $validator = Validator::make($request->all(), [
            'balance_field' => 'required|string',
            'new_balance' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $success = $this->horisontalService->updateBalanceField(
            $userId,
            $request->input('balance_field'),
            $request->input('new_balance')
        );

        if (!$success) {
            return response()->json(['status' => false, 'message' => 'Failed to update balance'], 400);
        }

        return response()->json(['status' => true, 'message' => 'Balance field updated successfully']);
    }

    /**
     * Calculate new balance after operation
     * POST /api/v2/user-balances/horizontal/{userId}/calculate
     */
    public function calculateNewBalance(Request $request, int $userId)
    {
        $validator = Validator::make($request->all(), [
            'balance_field' => 'required|string',
            'change_amount' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $result = $this->horisontalService->calculateNewBalance(
            $userId,
            $request->input('balance_field'),
            $request->input('change_amount')
        );

        if (!$result) {
            return response()->json(['status' => false, 'message' => 'User balance not found'], 404);
        }

        return response()->json(['status' => true, 'data' => $result]);
    }

    // ==================== VERTICAL BALANCE ENDPOINTS ====================

    /**
     * Get user's vertical balance by balance type
     * GET /api/v2/user-balances/vertical/{userId}/{balanceId}
     */
    public function getVerticalBalance(int $userId, $balanceId)
    {
        $balance = $this->verticalService->getUserBalanceVertical($userId, $balanceId);

        if (!$balance) {
            return response()->json(['status' => false, 'message' => 'Vertical balance not found'], 404);
        }

        return response()->json(['status' => true, 'data' => $balance]);
    }

    /**
     * Get all vertical balances for a user
     * GET /api/v2/user-balances/vertical/{userId}/all
     */
    public function getAllVerticalBalances(int $userId)
    {
        $balances = $this->verticalService->getAllUserBalances($userId);

        return response()->json(['status' => true, 'data' => $balances]);
    }

    /**
     * Update vertical balance after operation
     * PUT /api/v2/user-balances/vertical/{userId}/update-after-operation
     */
    public function updateVerticalBalanceAfterOperation(Request $request, int $userId)
    {
        $validator = Validator::make($request->all(), [
            'balance_id' => 'required',
            'balance_change' => 'required|numeric',
            'last_operation_id' => 'required|integer',
            'last_operation_value' => 'required|numeric',
            'last_operation_date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $success = $this->verticalService->updateBalanceAfterOperation(
            $userId,
            $request->input('balance_id'),
            $request->input('balance_change'),
            $request->input('last_operation_id'),
            $request->input('last_operation_value'),
            $request->input('last_operation_date')
        );

        if (!$success) {
            return response()->json(['status' => false, 'message' => 'Failed to update vertical balance'], 400);
        }

        return response()->json(['status' => true, 'message' => 'Vertical balance updated successfully']);
    }

    /**
     * Update calculated vertical balance
     * PUT /api/v2/user-balances/vertical/{userId}/calculated
     */
    public function updateCalculatedVertical(Request $request, int $userId)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'value' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $updated = $this->verticalService->updateCalculatedVertical(
            $userId,
            $request->input('type'),
            $request->input('value')
        );

        return response()->json([
            'status' => true,
            'message' => 'Vertical balance updated successfully',
            'data' => ['rows_updated' => $updated]
        ]);
    }
}

