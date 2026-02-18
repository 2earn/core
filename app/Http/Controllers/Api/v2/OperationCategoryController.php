<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Services\Balances\OperationCategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OperationCategoryController extends Controller
{
      protected OperationCategoryService $operationCategoryService;

      public function __construct(OperationCategoryService $operationCategoryService)
      {
            $this->operationCategoryService = $operationCategoryService;
      }

      /**
       * Get filtered/paginated operation categories.
       *
       * Query params:
       *   - search (string, optional): filter by name, code, description or id
       *   - per_page (int, optional, default 10)
       */
      public function index(Request $request): JsonResponse
      {
            $validator = Validator::make($request->all(), [
                  'search' => 'nullable|string',
                  'per_page' => 'nullable|integer|min:1|max:100',
            ]);

            if ($validator->fails()) {
                  return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
            }

            $categories = $this->operationCategoryService->getFilteredCategories(
                  $request->input('search'),
                  (int) $request->input('per_page', 10)
            );

            return response()->json([
                  'status' => true,
                  'data' => $categories->items(),
                  'pagination' => [
                        'current_page' => $categories->currentPage(),
                        'per_page' => $categories->perPage(),
                        'total' => $categories->total(),
                        'last_page' => $categories->lastPage(),
                  ],
            ]);
      }

      /**
       * Get all operation categories (no pagination, ordered by id desc).
       */
      public function all(): JsonResponse
      {
            $categories = $this->operationCategoryService->getAllCategories();

            return response()->json(['status' => true, 'data' => $categories]);
      }

      /**
       * Get a single operation category by ID.
       */
      public function show(int $id): JsonResponse
      {
            $category = $this->operationCategoryService->getCategoryById($id);

            if (!$category) {
                  return response()->json(['status' => false, 'message' => 'Operation category not found'], 404);
            }

            return response()->json(['status' => true, 'data' => $category]);
      }

      /**
       * Create a new operation category.
       */
      public function store(Request $request): JsonResponse
      {
            $validator = Validator::make($request->all(), [
                  'name' => 'required|string|max:255',
                  'code' => 'nullable|string|max:100',
                  'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                  return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
            }

            $category = $this->operationCategoryService->createCategory($validator->validated());

            return response()->json(['status' => true, 'data' => $category], 201);
      }

      /**
       * Update an existing operation category.
       */
      public function update(Request $request, int $id): JsonResponse
      {
            $validator = Validator::make($request->all(), [
                  'name' => 'sometimes|required|string|max:255',
                  'code' => 'nullable|string|max:100',
                  'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                  return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
            }

            $success = $this->operationCategoryService->updateCategory($id, $validator->validated());

            if (!$success) {
                  return response()->json(['status' => false, 'message' => 'Operation category not found'], 404);
            }

            return response()->json(['status' => true, 'message' => 'Operation category updated successfully']);
      }

      /**
       * Delete an operation category.
       */
      public function destroy(int $id): JsonResponse
      {
            $success = $this->operationCategoryService->deleteCategory($id);

            if (!$success) {
                  return response()->json(['status' => false, 'message' => 'Operation category not found'], 404);
            }

            return response()->json(['status' => true, 'message' => 'Operation category deleted successfully']);
      }
}
