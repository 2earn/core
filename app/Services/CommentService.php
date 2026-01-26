<?php

namespace App\Services;

use App\Models\Comment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class CommentService
{
    /**
     * Find comment by ID or fail
     *
     * @param int $id
     * @return Comment
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findByIdOrFail(int $id): Comment
    {
        return Comment::findOrFail($id);
    }

    /**
     * Delete a comment
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        try {
            $comment = Comment::findOrFail($id);
            return $comment->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting comment: ' . $e->getMessage(), ['id' => $id]);
            return false;
        }
    }

    /**
     * Validate a comment
     *
     * @param int $commentId
     * @param int $validatedById
     * @return bool
     */
    public function validateComment(int $commentId, int $validatedById): bool
    {
        try {
            $comment = Comment::findOrFail($commentId);
            $comment->update([
                'validated' => true,
                'validatedBy_id' => $validatedById,
                'validatedAt' => Carbon::now()
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error('Error validating comment: ' . $e->getMessage(), ['id' => $commentId]);
            return false;
        }
    }

    /**
     * Delete a comment (alias for delete method for backward compatibility)
     *
     * @param int $commentId
     * @return bool
     */
    public function deleteComment(int $commentId): bool
    {
        return $this->delete($commentId);
    }

    /**
     * Create a comment for a news item
     *
     * @param int $newsId
     * @param int $userId
     * @param string $content
     * @return array Result array with success status and comment
     */
    public function createComment(int $newsId, int $userId, string $content): array
    {
        try {
            $news = \App\Models\News::findOrFail($newsId);

            $comment = $news->comments()->create([
                'user_id' => $userId,
                'content' => $content
            ]);

            return [
                'success' => true,
                'message' => 'Comment created successfully',
                'comment' => $comment
            ];
        } catch (\Exception $e) {
            Log::error('Error creating comment: ' . $e->getMessage(), [
                'newsId' => $newsId,
                'userId' => $userId
            ]);
            return [
                'success' => false,
                'message' => 'Error creating comment'
            ];
        }
    }
}

