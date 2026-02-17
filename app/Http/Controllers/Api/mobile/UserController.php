<?php

namespace App\Http\Controllers\Api\mobile;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private const LOG_PREFIX = '[UserMobileController] ';

    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getUser(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id'
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $userId = $request->input('user_id');

        // Use UserService to get user with entity roles
        $user = $this->userService->getUserWithRoles($userId);

        if (!$user) {
            Log::error(self::LOG_PREFIX . 'User not found', ['user_id' => $userId]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'User not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $data = $user->toArray();
        unset($data['pass']);

        return response()->json([
            'success' => true,
            'message' => 'User retrieved successfully',
            'data' => $data
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'search_term' => 'required|string|min:3',
            'limit' => 'nullable|integer|min:1|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $searchTerm = $request->input('search_term');
        $limit = $request->input('limit', 10);

        try {
            $users = $this->userService->searchUsers($searchTerm, $limit);

            return response()->json([
                'success' => true,
                'data' => $users
            ]);
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Search failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while searching for users'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
