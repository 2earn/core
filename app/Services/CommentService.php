<?php

namespace App\Services;

use App\Models\Comment;
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
}

