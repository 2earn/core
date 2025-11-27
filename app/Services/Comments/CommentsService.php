<?php

namespace App\Services\Comments;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class CommentsService
{
    /**
     * Get validated comments for a commentable entity
     *
     * @param mixed $commentable The commentable entity (Event, etc.)
     * @param string $orderBy Field to order by
     * @param string $orderDirection Order direction (asc or desc)
     * @return Collection
     */
    public function getValidatedComments(
        $commentable,
        string $orderBy = 'created_at',
        string $orderDirection = 'desc'
    ): Collection {
        return $commentable->comments()
            ->where('validated', true)
            ->with('user')
            ->orderBy($orderBy, $orderDirection)
            ->get();
    }

    /**
     * Get unvalidated comments for a commentable entity
     *
     * @param mixed $commentable The commentable entity (Event, etc.)
     * @param string $orderBy Field to order by
     * @param string $orderDirection Order direction (asc or desc)
     * @return Collection
     */
    public function getUnvalidatedComments(
        $commentable,
        string $orderBy = 'created_at',
        string $orderDirection = 'desc'
    ): Collection {
        return $commentable->comments()
            ->where('validated', false)
            ->orderBy($orderBy, $orderDirection)
            ->get();
    }

    /**
     * Get all comments (validated and unvalidated) for a commentable entity
     *
     * @param mixed $commentable The commentable entity
     * @return array Returns array with 'validated' and 'unvalidated' keys
     */
    public function getAllComments($commentable): array
    {
        return [
            'validated' => $this->getValidatedComments($commentable),
            'unvalidated' => $this->getUnvalidatedComments($commentable),
        ];
    }

    /**
     * Add a new comment to a commentable entity
     *
     * @param mixed $commentable The commentable entity
     * @param string $content Comment content
     * @param int $userId User ID
     * @param bool $validated Whether the comment is pre-validated
     * @return Comment
     */
    public function addComment(
        $commentable,
        string $content,
        int $userId,
        bool $validated = false
    ): Comment {
        return $commentable->comments()->create([
            'content' => $content,
            'user_id' => $userId,
            'validated' => $validated,
        ]);
    }

    /**
     * Validate a comment
     *
     * @param int $commentId Comment ID
     * @param int $validatedById ID of user validating the comment
     * @return bool
     */
    public function validateComment(int $commentId, int $validatedById): bool
    {
        $comment = Comment::findOrFail($commentId);

        return $comment->update([
            'validated' => true,
            'validatedBy_id' => $validatedById,
            'validatedAt' => Carbon::now(),
        ]);
    }

    /**
     * Delete a comment
     *
     * @param int $commentId Comment ID
     * @return bool
     */
    public function deleteComment(int $commentId): bool
    {
        $comment = Comment::findOrFail($commentId);
        return $comment->delete();
    }

    /**
     * Get comment count for a commentable entity
     *
     * @param mixed $commentable The commentable entity
     * @param bool|null $validated Filter by validated status (null = all)
     * @return int
     */
    public function getCommentCount($commentable, ?bool $validated = null): int
    {
        $query = $commentable->comments();

        if ($validated !== null) {
            $query->where('validated', $validated);
        }

        return $query->count();
    }

    /**
     * Check if a user has commented on a commentable entity
     *
     * @param mixed $commentable The commentable entity
     * @param int $userId User ID
     * @return bool
     */
    public function hasUserCommented($commentable, int $userId): bool
    {
        return $commentable->comments()
            ->where('user_id', $userId)
            ->exists();
    }
}

