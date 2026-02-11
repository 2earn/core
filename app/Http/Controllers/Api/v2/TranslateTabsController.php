<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Services\Translation\TranslateTabsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TranslateTabsController extends Controller
{
    protected TranslateTabsService $translateTabsService;

    public function __construct(TranslateTabsService $translateTabsService)
    {
        $this->translateTabsService = $translateTabsService;
    }

    /**
     * Get paginated translations with search
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $search = $request->get('search');
            $perPage = (int) $request->get('per_page', 10);
            $perPage = min(max($perPage, 1), 100);

            $translations = $this->translateTabsService->getPaginated($search, $perPage);

            return response()->json([
                'success' => true,
                'data' => $translations
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching translations: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all translations
     *
     * @return JsonResponse
     */
    public function all(): JsonResponse
    {
        try {
            $translations = $this->translateTabsService->getAll();

            return response()->json([
                'success' => true,
                'data' => $translations
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching all translations: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific translation by ID
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $translation = $this->translateTabsService->getById($id);

            if (!$translation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Translation not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $translation
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching translation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search translations
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'search' => 'required|string|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $translations = $this->translateTabsService->search($request->search);

            return response()->json([
                'success' => true,
                'data' => $translations
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error searching translations: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all translations as key-value arrays
     *
     * @return JsonResponse
     */
    public function keyValueArrays(): JsonResponse
    {
        try {
            $arrays = $this->translateTabsService->getAllAsKeyValueArrays();

            return response()->json([
                'success' => true,
                'data' => $arrays
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching key-value arrays: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get translation count
     *
     * @return JsonResponse
     */
    public function count(): JsonResponse
    {
        try {
            $count = $this->translateTabsService->count();

            return response()->json([
                'success' => true,
                'count' => $count
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching count: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get translation statistics
     *
     * @return JsonResponse
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = $this->translateTabsService->getStatistics();

            return response()->json([
                'success' => true,
                'data' => $stats
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if translation exists
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function exists(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $exists = $this->translateTabsService->exists($request->name);

            return response()->json([
                'success' => true,
                'exists' => $exists
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking existence: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get translations by name pattern
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function byPattern(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'pattern' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $translations = $this->translateTabsService->getByNamePattern($request->pattern);

            return response()->json([
                'success' => true,
                'data' => $translations
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching translations: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new translation
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'value' => 'nullable|string',
                'valueFr' => 'nullable|string',
                'valueEn' => 'nullable|string',
                'valueEs' => 'nullable|string',
                'valueTr' => 'nullable|string',
                'valueRu' => 'nullable|string',
                'valueDe' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            if ($this->translateTabsService->exists($request->name)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Translation with this name already exists'
                ], 400);
            }

            $translation = $this->translateTabsService->create($request->name, $request->only([
                'value', 'valueFr', 'valueEn', 'valueEs', 'valueTr', 'valueRu', 'valueDe'
            ]));

            if (!$translation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create translation'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Translation created successfully',
                'data' => $translation
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating translation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk create translations
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkStore(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'translations' => 'required|array',
                'translations.*.name' => 'required|string|max:255',
                'translations.*.value' => 'nullable|string',
                'translations.*.valueFr' => 'nullable|string',
                'translations.*.valueEn' => 'nullable|string',
                'translations.*.valueEs' => 'nullable|string',
                'translations.*.valueTr' => 'nullable|string',
                'translations.*.valueRu' => 'nullable|string',
                'translations.*.valueDe' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $success = $this->translateTabsService->bulkCreate($request->translations);

            if (!$success) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to bulk create translations'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Translations created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error bulk creating translations: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a translation
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|string|max:255',
                'value' => 'nullable|string',
                'valueFr' => 'nullable|string',
                'valueEn' => 'nullable|string',
                'valueEs' => 'nullable|string',
                'valueTr' => 'nullable|string',
                'valueRu' => 'nullable|string',
                'valueDe' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $translation = $this->translateTabsService->getById($id);
            if (!$translation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Translation not found'
                ], 404);
            }

            $success = $this->translateTabsService->update($id, $request->all());

            if (!$success) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update translation'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Translation updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating translation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a translation
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $translation = $this->translateTabsService->getById($id);
            if (!$translation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Translation not found'
                ], 404);
            }

            $success = $this->translateTabsService->delete($id);

            if (!$success) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete translation'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Translation deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting translation: ' . $e->getMessage()
            ], 500);
        }
    }
}

