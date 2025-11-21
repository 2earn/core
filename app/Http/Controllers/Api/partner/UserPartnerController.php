<?php

namespace App\Http\Controllers\Api\partner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Partner\AddRoleRequest;
use App\Models\User;
use Core\Models\Platform;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserPartnerController extends Controller
{
    private const LOG_PREFIX = '[UserPartnerController] ';


    public function __construct()
    {
        $this->middleware('check.url');
    }

    public function assignRole($user, $role, $platform)
    {
    if(!is_null($platform->{$role.'_id'})){

    }

    }

    /**
     * Add a role to a user on a specific platform
     *
     * Request body:
     * - platform_id: integer (required) - ID of the platform
     * - user_id: integer (required) - ID of the user
     * - role: string (required) - Name of the role to assign
     */
    public function addRole(AddRoleRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            $user = User::findOrFail($validated['user_id']);
            $platform = Platform::findOrFail($validated['platform_id']);
            switch ($validated['role']) {
                case 'owner':
                    $this->assignRole($user, 'owner', $platform);
                    break;
                case 'admin':
                    $this->assignRole($user, 'financial_manager', $platform);
                    break;
                case 'editor':
                    $this->assignRole($user, 'marketing_manager', $platform);
                default:
                    throw new \InvalidArgumentException('Invalid role specified');
            }

            Log::info(self::LOG_PREFIX . 'Role request sent successfully,waiting for approval', [
                'user_id' => $validated['user_id'],
                'platform_id' => $validated['platform_id'],
                'role' => $validated['role'],
                'assigned_by' => auth()->id()
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Role assigned successfully',
                'data' => [
                    'user_id' => $user->id,
                    'platform_id' => $platform->id,
                    'role' => $validated['role']
                ]
            ], Response::HTTP_OK);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error(self::LOG_PREFIX . 'Failed to assign role', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payload' => $validated
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to assign role: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

