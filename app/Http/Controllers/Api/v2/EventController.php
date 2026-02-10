<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Services\EventService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    private EventService $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    /**
     * Get all events (paginated)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');

            $events = $this->eventService->getPaginatedWithCounts($search, $perPage);

            return response()->json([
                'status' => true,
                'data' => $events->items(),
                'pagination' => [
                    'current_page' => $events->currentPage(),
                    'per_page' => $events->perPage(),
                    'total' => $events->total(),
                    'last_page' => $events->lastPage()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error fetching events: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all events (non-paginated)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        try {
            $events = $this->eventService->getAll();

            return response()->json([
                'status' => true,
                'data' => $events
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error fetching all events: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all enabled events
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function enabled()
    {
        try {
            $events = $this->eventService->getEnabledEvents();

            return response()->json([
                'status' => true,
                'data' => $events
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error fetching enabled events: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a single event by ID
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        try {
            $event = $this->eventService->getById($id);

            if (!$event) {
                return response()->json([
                    'status' => false,
                    'message' => 'Event not found'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data' => $event
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error fetching event: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get event with relationships
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showWithRelationships(int $id)
    {
        try {
            $event = $this->eventService->getWithRelationships($id);

            if (!$event) {
                return response()->json([
                    'status' => false,
                    'message' => 'Event not found'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data' => $event
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error fetching event with relationships: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get event with main image
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showWithMainImage(int $id)
    {
        try {
            $event = $this->eventService->getWithMainImage($id);

            if (!$event) {
                return response()->json([
                    'status' => false,
                    'message' => 'Event not found'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data' => $event
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error fetching event with main image: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new event
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'enabled' => 'nullable|boolean',
            'published_at' => 'nullable|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $event = $this->eventService->create($request->all());

            if (!$event) {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to create event'
                ], 500);
            }

            return response()->json([
                'status' => true,
                'data' => $event,
                'message' => 'Event created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error creating event: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an event
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
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
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $result = $this->eventService->update($id, $request->all());

            if (!$result) {
                return response()->json([
                    'status' => false,
                    'message' => 'Event not found or update failed'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Event updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error updating event: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an event
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        try {
            $result = $this->eventService->delete($id);

            if (!$result) {
                return response()->json([
                    'status' => false,
                    'message' => 'Event not found or delete failed'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Event deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error deleting event: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if user has liked an event
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function hasUserLiked(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $userId = $request->input('user_id');
            $hasLiked = $this->eventService->hasUserLiked($id, $userId);

            return response()->json([
                'status' => true,
                'data' => [
                    'has_liked' => $hasLiked
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error checking if user liked event: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add like to an event
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function addLike(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $userId = $request->input('user_id');
            $result = $this->eventService->addLike($id, $userId);

            if (!$result) {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to add like'
                ], 500);
            }

            return response()->json([
                'status' => true,
                'message' => 'Like added successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error adding like: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove like from an event
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeLike(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $userId = $request->input('user_id');
            $result = $this->eventService->removeLike($id, $userId);

            if (!$result) {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to remove like'
                ], 500);
            }

            return response()->json([
                'status' => true,
                'message' => 'Like removed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error removing like: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add comment to an event
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function addComment(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'content' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $userId = $request->input('user_id');
            $content = $request->input('content');
            $result = $this->eventService->addComment($id, $userId, $content);

            if (!$result) {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to add comment'
                ], 500);
            }

            return response()->json([
                'status' => true,
                'message' => 'Comment added successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error adding comment: ' . $e->getMessage()
            ], 500);
        }
    }
}

