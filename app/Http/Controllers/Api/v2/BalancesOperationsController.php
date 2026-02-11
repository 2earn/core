<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Models\BalanceOperation;
use App\Models\OperationCategory;
use App\Services\Balances\BalanceOperationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BalancesOperationsController extends Controller
{
    protected BalanceOperationService $balanceOperationService;

    public function __construct(BalanceOperationService $balanceOperationService)
    {
        $this->balanceOperationService = $balanceOperationService;
    }

    public function getBalanceOperationsQuery()
    {
        return BalanceOperation::all();
    }

    public function index()
    {
        return datatables($this->getBalanceOperationsQuery())
            ->addColumn('details', function ($balance) {
                return view('parts.datatable.balances-details', ['balance' => $balance]);
            })
            ->addColumn('action', function ($balance) {
                return view('parts.datatable.balances-status', ['balance' => $balance]);
            })
            ->editColumn('modify_amount', function ($balance) {
                return view('parts.datatable.balances-modify', ['modify' => $balance->modify_amount]);
            })
            ->editColumn('parent_operation_id', function ($balance) {
                return view('parts.datatable.balances-parent', ['balance' => BalanceOperation::find($balance->parent_operation_id)]);
            })
            ->editColumn('operation_category_id', function ($balance) {
                return view('parts.datatable.balances-category', ['category' => OperationCategory::find($balance->operation_category_id)]);
            })
            ->addColumn('others', function ($balance) {
                return view('parts.datatable.balances-others', ['balance' => $balance]);
            })
            ->toJson();
    }

    public function getCategories()
    {
        return datatables(OperationCategory::all())
            ->addColumn('action', function ($operationCategory) {
                return view('parts.datatable.balances-categories-actions', ['operationCategory' => $operationCategory]);
            })
            ->escapeColumns([])
            ->toJson();
    }

    /**
     * Get filtered operations with search and pagination
     */
    public function getFilteredOperations(Request $request): JsonResponse
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $operations = $this->balanceOperationService->getFilteredOperations($search, $perPage);

        return response()->json($operations);
    }

    /**
     * Get operation by ID
     */
    public function show(int $id): JsonResponse
    {
        $operation = $this->balanceOperationService->getOperationById($id);

        if (!$operation) {
            return response()->json(['message' => 'Operation not found'], 404);
        }

        return response()->json($operation);
    }

    /**
     * Get all operations
     */
    public function getAllOperations(): JsonResponse
    {
        $operations = $this->balanceOperationService->getAllOperations();

        return response()->json($operations);
    }

    /**
     * Create a new operation
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'operation' => 'required|string|max:255',
            'io' => 'nullable|string',
            'source' => 'nullable|string',
            'mode' => 'nullable|string',
            'amounts_id' => 'nullable|integer',
            'note' => 'nullable|string',
            'modify_amount' => 'nullable|boolean',
            'operation_category_id' => 'nullable|integer|exists:operation_categories,id',
            'ref' => 'nullable|string',
            'direction' => 'nullable|string',
            'balance_id' => 'nullable|integer',
            'parent_operation_id' => 'nullable|integer|exists:balance_operations,id',
            'relateble' => 'nullable|integer',
            'relateble_model' => 'nullable|string',
            'relateble_types' => 'nullable|string',
        ]);

        $operation = $this->balanceOperationService->createOperation($validated);

        return response()->json($operation, 201);
    }

    /**
     * Update an existing operation
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'operation' => 'sometimes|string|max:255',
            'io' => 'nullable|string',
            'source' => 'nullable|string',
            'mode' => 'nullable|string',
            'amounts_id' => 'nullable|integer',
            'note' => 'nullable|string',
            'modify_amount' => 'nullable|boolean',
            'operation_category_id' => 'nullable|integer|exists:operation_categories,id',
            'ref' => 'nullable|string',
            'direction' => 'nullable|string',
            'balance_id' => 'nullable|integer',
            'parent_operation_id' => 'nullable|integer|exists:balance_operations,id',
            'relateble' => 'nullable|integer',
            'relateble_model' => 'nullable|string',
            'relateble_types' => 'nullable|string',
        ]);

        $success = $this->balanceOperationService->updateOperation($id, $validated);

        if (!$success) {
            return response()->json(['message' => 'Operation not found'], 404);
        }

        return response()->json(['message' => 'Operation updated successfully']);
    }

    /**
     * Delete an operation
     */
    public function destroy(int $id): JsonResponse
    {
        $success = $this->balanceOperationService->deleteOperation($id);

        if (!$success) {
            return response()->json(['message' => 'Operation not found'], 404);
        }

        return response()->json(['message' => 'Operation deleted successfully']);
    }

    /**
     * Get operation category name by ID
     */
    public function getCategoryName(int $categoryId): JsonResponse
    {
        $categoryName = $this->balanceOperationService->getOperationCategoryName($categoryId);

        return response()->json(['category_name' => $categoryName]);
    }
}
