<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Services\News\NewsService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{
    private NewsService $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    /**
     * Get all news (paginated)
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');

            $news = $this->newsService->getPaginated($search, $perPage);

            return response()->json([
                'status' => true,
                'data' => $news->items(),
                'pagination' => [
                    'current_page' => $news->currentPage(),
                    'per_page' => $news->perPage(),
                    'total' => $news->total(),
                    'last_page' => $news->lastPage()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get all news (non-paginated)
     */
    public function all(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'with' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $with = $request->input('with', []);
            $news = $this->newsService->getAll($with);

            return response()->json(['status' => true, 'data' => $news]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get all enabled news
     */
    public function enabled()
    {
        try {
            $news = $this->newsService->getEnabledNews();
            return response()->json(['status' => true, 'data' => $news]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get a single news by ID
     */
    public function show(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'with' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $with = $request->input('with', []);
            $news = $this->newsService->getById($id, $with);

            if (!$news) {
                return response()->json(['status' => false, 'message' => 'News not found'], 404);
            }

            return response()->json(['status' => true, 'data' => $news]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get news with relationships
     */
    public function showWithRelations(int $id)
    {
        try {
            $news = $this->newsService->getNewsWithRelations($id);

            if (!$news) {
                return response()->json(['status' => false, 'message' => 'News not found'], 404);
            }

            return response()->json(['status' => true, 'data' => $news]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Create a new news
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'enabled' => 'nullable|boolean',
            'published_at' => 'nullable|date',
            'created_by' => 'nullable|integer|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $news = $this->newsService->create($request->all());
            return response()->json(['status' => true, 'data' => $news], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update a news
     */
    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'enabled' => 'nullable|boolean',
            'published_at' => 'nullable|date'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $result = $this->newsService->update($id, $request->all());

            if (!$result) {
                return response()->json(['status' => false, 'message' => 'Failed to update news'], 400);
            }

            return response()->json(['status' => true, 'message' => 'News updated successfully']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['status' => false, 'message' => 'News not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a news
     */
    public function destroy(int $id)
    {
        try {
            $result = $this->newsService->delete($id);

            if (!$result) {
                return response()->json(['status' => false, 'message' => 'Failed to delete news'], 400);
            }

            return response()->json(['status' => true, 'message' => 'News deleted successfully']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['status' => false, 'message' => 'News not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Duplicate a news
     */
    public function duplicate(int $id)
    {
        try {
            $news = $this->newsService->duplicate($id);
            return response()->json(['status' => true, 'data' => $news, 'message' => 'News duplicated successfully'], 201);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['status' => false, 'message' => 'News not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Check if user has liked a news
     */
    public function hasUserLiked(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $hasLiked = $this->newsService->hasUserLiked($id, $request->input('user_id'));
            return response()->json(['status' => true, 'has_liked' => $hasLiked]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Add a like to a news
     */
    public function addLike(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $result = $this->newsService->addLike($id, $request->input('user_id'));

            if (!$result) {
                return response()->json(['status' => false, 'message' => 'Failed to add like'], 400);
            }

            return response()->json(['status' => true, 'message' => 'Like added successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove a like from a news
     */
    public function removeLike(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $result = $this->newsService->removeLike($id, $request->input('user_id'));

            if (!$result) {
                return response()->json(['status' => false, 'message' => 'Failed to remove like'], 400);
            }

            return response()->json(['status' => true, 'message' => 'Like removed successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
}

