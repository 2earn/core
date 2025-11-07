<?php

namespace App\Http\Controllers\Api\mobile;

use App\Http\Controllers\Controller;
use App\Models\CashBalances;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CashBalanceController extends Controller
{
    private const LOG_PREFIX = '[CashBalanceMobileController] ';


    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'value' => 'required|numeric',
            'description' => 'nullable|string|max:512',
            'reference' => 'nullable|string',
            'ref' => 'nullable|string',
            'beneficiary_id_auto' => 'required|integer|exists:users,idUser',
            'balance_operation_id' => 'nullable|exists:balance_operations,id',
            'operator_id' => 'nullable|integer|exists:users,id',
            'order_id' => 'nullable|integer|exists:orders,id',
            'item_id' => 'nullable|integer|exists:items,id',
            'deal_id' => 'nullable|integer|exists:deals,id',
            'platform_id' => 'nullable|integer|exists:platforms,id',
            'order_detail_id' => 'nullable|integer|exists:order_details,id',
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {

            $validatedData = $validator->validated();
            
            $cashBalance = CashBalances::create($validatedData);

            Log::info(self::LOG_PREFIX . 'Cash balance created successfully', ['id' => $cashBalance->id]);

            return response()->json([
                'success' => true,
                'message' => 'Cash balance record created successfully',
                'data' => $cashBalance
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Failed to create cash balance', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'Failed',
                'message' => 'Failed to create cash balance record',
                'error' => $e
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCashBalance(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id'
        ]);

        $userId = $request->input('user_id');

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $cashBalances = CashBalances::where('beneficiary_id_auto', $userId)->latest();

        return response()->json([
            'success' => true,
            'message' => 'Cash balance retrieved successfully',
            'data' => $cashBalances
        ]);
    }
}
