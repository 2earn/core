<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlatformChangeRequest;
use App\Services\Platform\PlatformChangeRequestService;
use App\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PlatformChangeRequestController extends Controller
{
    private const LOG_PREFIX = '[PlatformChangeRequestController] ';

    private PlatformChangeRequestService $changeRequestService;

    public function __construct(PlatformChangeRequestService $changeRequestService)
    {
        $this->changeRequestService = $changeRequestService;
    }

    public function pending(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 20);
        $platformId = $request->input('platform_id');

        $changeRequests = $this->changeRequestService->getPendingRequestsPaginated(
            $page,
            $perPage,
            $platformId
        );

        return response()->json([
            'status' => true,
            'data' => $changeRequests->items(),
            'pagination' => [
                'current_page' => $changeRequests->currentPage(),
                'per_page' => $changeRequests->perPage(),
                'total' => $changeRequests->total(),
                'last_page' => $changeRequests->lastPage()
            ]
        ]);
    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'nullable|in:pending,approved,rejected',
            'platform_id' => 'nullable|exists:platforms,id',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $status = $request->input('status');
        $platformId = $request->input('platform_id');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 20);

        $changeRequests = $this->changeRequestService->getChangeRequestsPaginated(
            $status,
            $platformId,
            $page,
            $perPage
        );

        return response()->json([
            'status' => true,
            'data' => $changeRequests->items(),
            'pagination' => [
                'current_page' => $changeRequests->currentPage(),
                'per_page' => $changeRequests->perPage(),
                'total' => $changeRequests->total(),
                'last_page' => $changeRequests->lastPage()
            ]
        ]);
    }

    public function show($id)
    {
        $changeRequest = $this->changeRequestService->getChangeRequestById($id);

        if (!$changeRequest) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Change request not found'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => true,
            'data' => $changeRequest
        ]);
    }

    public function statistics()
    {
        $stats = $this->changeRequestService->getStatistics();

        return response()->json([
            'status' => true,
            'data' => $stats
        ]);
    }
}

