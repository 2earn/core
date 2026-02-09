<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Services\Comments\CommentsService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CommentsController extends Controller
{
    private CommentsService $commentsService;

    public function __construct(CommentsService $commentsService)
    {
        $this->commentsService = $commentsService;
    }

    /**
     * Get validated comments for a commentable entity
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getValidated(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'commentable_type' => 'required|string',
            'commentable_id' => 'required|integer',
            'order_by' => 'nullable|string|in:created_at,updated_at',
            'order_direction' => 'nullable|string|in:asc,desc'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $commentableType = $request->input('commentable_type');
        $commentableId = $request->input('commentable_id');

        // Get the commentable model instance
        $commentable = app($commentableType)::find($commentableId);

        if (!$commentable) {
            return response()->json([
                'status' => false,
                'message' => 'Commentable entity not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $comments = $this->commentsService->getValidatedComments(
            $commentable,
            $request->input('order_by', 'created_at'),
            $request->input('order_direction', 'desc')
        );

        return response()->json([
            'status' => true,
            'data' => $comments
        ]);
    }

    /**
     * Get unvalidated comments for a commentable entity
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnvalidated(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'commentable_type' => 'required|string',
            'commentable_id' => 'required|integer',
            'order_by' => 'nullable|string|in:created_at,updated_at',
            'order_direction' => 'nullable|string|in:asc,desc'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $commentableType = $request->input('commentable_type');
        $commentableId = $request->input('commentable_id');

        $commentable = app($commentableType)::find($commentableId);

        if (!$commentable) {
            return response()->json([
                'status' => false,
                'message' => 'Commentable entity not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $comments = $this->commentsService->getUnvalidatedComments(
            $commentable,
            $request->input('order_by', 'created_at'),
            $request->input('order_direction', 'desc')
        );

        return response()->json([
            'status' => true,
            'data' => $comments
        ]);
    }

    /**
     * Get all comments (validated and unvalidated)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'commentable_type' => 'required|string',
            'commentable_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $commentableType = $request->input('commentable_type');
        $commentableId = $request->input('commentable_id');

        $commentable = app($commentableType)::find($commentableId);

        if (!$commentable) {
            return response()->json([
                'status' => false,
                'message' => 'Commentable entity not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $comments = $this->commentsService->getAllComments($commentable);

        return response()->json([
            'status' => true,
            'data' => $comments
        ]);
    }

    /**
     * Add a new comment
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'commentable_type' => 'required|string',
            'commentable_id' => 'required|integer',
            'content' => 'required|string',
            'user_id' => 'required|integer|exists:users,id',
            'validated' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $commentableType = $request->input('commentable_type');
        $commentableId = $request->input('commentable_id');

        $commentable = app($commentableType)::find($commentableId);

        if (!$commentable) {
            return response()->json([
                'status' => false,
                'message' => 'Commentable entity not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $comment = $this->commentsService->addComment(
            $commentable,
            $request->input('content'),
            $request->input('user_id'),
            $request->input('validated', false)
        );

        return response()->json([
            'status' => true,
            'message' => 'Comment added successfully',
            'data' => $comment
        ], Response::HTTP_CREATED);
    }

    /**
     * Validate a comment
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function validate(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'validated_by_id' => 'required|integer|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $success = $this->commentsService->validateComment($id, $request->input('validated_by_id'));

            return response()->json([
                'status' => true,
                'message' => 'Comment validated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Comment not found or validation failed'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Delete a comment
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        try {
            $success = $this->commentsService->deleteComment($id);

            return response()->json([
                'status' => true,
                'message' => 'Comment deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Comment not found or deletion failed'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Get comment count
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'commentable_type' => 'required|string',
            'commentable_id' => 'required|integer',
            'validated' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $commentableType = $request->input('commentable_type');
        $commentableId = $request->input('commentable_id');

        $commentable = app($commentableType)::find($commentableId);

        if (!$commentable) {
            return response()->json([
                'status' => false,
                'message' => 'Commentable entity not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->has('validated') ? $request->boolean('validated') : null;
        $count = $this->commentsService->getCommentCount($commentable, $validated);

        return response()->json([
            'status' => true,
            'count' => $count
        ]);
    }

    /**
     * Check if user has commented
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function hasUserCommented(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'commentable_type' => 'required|string',
            'commentable_id' => 'required|integer',
            'user_id' => 'required|integer|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $commentableType = $request->input('commentable_type');
        $commentableId = $request->input('commentable_id');

        $commentable = app($commentableType)::find($commentableId);

        if (!$commentable) {
            return response()->json([
                'status' => false,
                'message' => 'Commentable entity not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $hasCommented = $this->commentsService->hasUserCommented(
            $commentable,
            $request->input('user_id')
        );

        return response()->json([
            'status' => true,
            'has_commented' => $hasCommented
        ]);
    }
}

