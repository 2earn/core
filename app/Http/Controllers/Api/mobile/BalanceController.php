<?php

namespace App\Http\Controllers\Api\mobile;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Balances\Balances;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BalanceController extends Controller
{
    private const LOG_PREFIX = '[BalanceController] ';

    public function getBalances(Request $request): JsonResponse
    {
        try {
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
            $user = User::find($userId);
            $balances = Balances::getStoredUserBalances($user->idUser);

            return response()->json([
                'success' => true,
                'balance' =>  $balances ?? [],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch balances',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
