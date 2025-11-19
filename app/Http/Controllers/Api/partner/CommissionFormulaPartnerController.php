<?php

namespace App\Http\Controllers\Api\partner;

use App\Http\Controllers\Controller;
use App\Services\Commission\CommissionFormulaService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CommissionFormulaPartnerController extends Controller
{
    private const LOG_PREFIX = '[CommissionFormulaPartnerController] ';
    private const PAGINATION_LIMIT = 10;

    protected CommissionFormulaService $commissionFormulaService;

    public function __construct(CommissionFormulaService $commissionFormulaService)
    {
        $this->middleware('check.url');
        $this->commissionFormulaService = $commissionFormulaService;
    }

    /**
     * Expose a list of commission formulas (optionally paginated & searchable)
     *
     * Query Params:
     * - page: optional integer (>=1) for pagination
     * - search: optional string to search by name
     * - active: optional boolean (1|0) filter by is_active
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'nullable|integer|min:1',
            'search' => 'nullable|string|max:255',
            'active' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $search = $request->input('search');
        $page = $request->input('page');
        $filters = [];

        // Handle active filter - check if parameter exists
        if ($request->has('active')) {
            $filters['is_active'] = $request->boolean('active');
        }

        if ($search) {
            $filters['search'] = $search;
        }

        $result = $this->commissionFormulaService->getPaginatedFormulas(
            $filters,
            $page,
            self::PAGINATION_LIMIT
        );

        return response()->json([
            'status' => true,
            'data' => $result['formulas'],
            'total' => $result['total']
        ]);
    }
}

