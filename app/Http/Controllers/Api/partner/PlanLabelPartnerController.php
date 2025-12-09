<?php

namespace App\Http\Controllers\Api\partner;

use App\Http\Controllers\Controller;
use App\Services\Commission\PlanLabelService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PlanLabelPartnerController extends Controller
{
    private const LOG_PREFIX = '[PlanLabelPartnerController] ';
    private const PAGINATION_LIMIT = 10;

    protected PlanLabelService $planLabelService;

    public function __construct(PlanLabelService $planLabelService)
    {
        $this->middleware('check.url');
        $this->planLabelService = $planLabelService;
    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'nullable|integer|min:1',
            'search' => 'nullable|string|max:255',
            'active' => 'nullable|boolean',
            'stars' => 'nullable|integer|min:1|max:5',
            'step' => 'nullable|integer'
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

        if ($request->has('active')) {
            $filters['is_active'] = $request->boolean('active');
        }

        if ($search) {
            $filters['search'] = $search;
        }

        if ($request->has('stars')) {
            $filters['stars'] = $request->input('stars');
        }

        if ($request->has('step')) {
            $filters['step'] = $request->input('step');
        }

        $result = $this->planLabelService->getPaginatedLabels(
            $filters,
            $page,
            self::PAGINATION_LIMIT
        );

        return response()->json([
            'status' => true,
            'data' => $result['labels'],
            'total' => $result['total']
        ]);
    }
}

