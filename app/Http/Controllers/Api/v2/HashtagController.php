<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Services\Hashtag\HashtagService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class HashtagController extends Controller
{
    private HashtagService $hashtagService;

    public function __construct(HashtagService $hashtagService)
    {
        $this->hashtagService = $hashtagService;
    }

    /**
     * Get all hashtags
     */
    public function index()
    {
        $hashtags = $this->hashtagService->getAll();
        return response()->json(['status' => true, 'data' => $hashtags]);
    }

    /**
     * Get hashtags with filters
     */
    public function getFiltered(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string',
            'with' => 'nullable|array',
            'order_by' => 'nullable|string',
            'order_direction' => 'nullable|in:asc,desc',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $filters = [
            'search' => $request->input('search'),
            'with' => $request->input('with', []),
            'order_by' => $request->input('order_by', 'id'),
            'order_direction' => $request->input('order_direction', 'desc')
        ];

        if ($request->has('per_page')) {
            $filters['PAGE_SIZE'] = $request->input('per_page');
        }

        $hashtags = $this->hashtagService->getHashtags($filters);

        if ($request->has('per_page') && method_exists($hashtags, 'items')) {
            return response()->json([
                'status' => true,
                'data' => $hashtags->items(),
                'pagination' => [
                    'current_page' => $hashtags->currentPage(),
                    'per_page' => $hashtags->perPage(),
                    'total' => $hashtags->total(),
                    'last_page' => $hashtags->lastPage()
                ]
            ]);
        }

        return response()->json(['status' => true, 'data' => $hashtags]);
    }

    /**
     * Get hashtag by ID
     */
    public function show(Request $request, int $id)
    {
        $hashtag = $this->hashtagService->getHashtagById($id, $request->input('with', []));

        if (!$hashtag) {
            return response()->json(['status' => false, 'message' => 'Hashtag not found'], 404);
        }

        return response()->json(['status' => true, 'data' => $hashtag]);
    }

    /**
     * Get hashtag by slug
     */
    public function getBySlug(string $slug)
    {
        $hashtag = $this->hashtagService->getHashtagBySlug($slug);

        if (!$hashtag) {
            return response()->json(['status' => false, 'message' => 'Hashtag not found'], 404);
        }

        return response()->json(['status' => true, 'data' => $hashtag]);
    }

    /**
     * Check if hashtag exists
     */
    public function checkExists(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'except_id' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $exists = $this->hashtagService->hashtagExists(
            $request->input('name'),
            $request->input('except_id')
        );

        return response()->json(['status' => true, 'exists' => $exists]);
    }

    /**
     * Create hashtag
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:hashtags,name',
            'slug' => 'nullable|string|max:255|unique:hashtags,slug'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $hashtag = $this->hashtagService->createHashtag($request->all());

        if (!$hashtag) {
            return response()->json(['status' => false, 'message' => 'Failed to create hashtag'], 500);
        }

        return response()->json([
            'status' => true,
            'data' => $hashtag,
            'message' => 'Hashtag created successfully'
        ], 201);
    }

    /**
     * Update hashtag
     */
    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255|unique:hashtags,name,' . $id,
            'slug' => 'nullable|string|max:255|unique:hashtags,slug,' . $id
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $success = $this->hashtagService->updateHashtag($id, $request->all());

        if (!$success) {
            return response()->json(['status' => false, 'message' => 'Hashtag not found or update failed'], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Hashtag updated successfully'
        ]);
    }

    /**
     * Delete hashtag
     */
    public function destroy(int $id)
    {
        $success = $this->hashtagService->deleteHashtag($id);

        if (!$success) {
            return response()->json(['status' => false, 'message' => 'Hashtag not found or deletion failed'], 404);
        }

        return response()->json(['status' => true, 'message' => 'Hashtag deleted successfully']);
    }
}

