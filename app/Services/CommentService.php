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
}

