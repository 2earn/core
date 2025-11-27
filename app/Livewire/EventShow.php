<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Event;
use App\Models\User;
use App\Services\Comments\CommentsService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EventShow extends Component
{
    public $id;
    public $event;
    public $commentContent = '';
    public $comments;
    public $unvalidatedComments = [];
    public $likeCount = 0;
    public $liked = false;

    private CommentsService $commentsService;

    public function mount($id, CommentsService $commentsService)
    {
        $this->id = $id;
        $this->commentsService = $commentsService;
        $this->event = Event::with(['mainImage', 'hashtags'])->findOrFail($id);
        $this->loadComments();
        $this->loadLikes();
    }

    public function loadComments()
    {
        $this->comments = $this->commentsService->getValidatedComments($this->event);
        $this->unvalidatedComments = $this->commentsService->getUnvalidatedComments($this->event);
    }

    public function validateComment($commentId)
    {
        if (!auth()->check() || !User::isSuperAdmin()) return;

        $this->commentsService->validateComment($commentId, auth()->id());
        $this->loadComments();
    }

    public function deleteComment($commentId)
    {
        if (!auth()->check() || !User::isSuperAdmin()) return;

        $this->commentsService->deleteComment($commentId);
        $this->loadComments();
    }

    public function loadLikes()
    {
        $this->likeCount = $this->event->likes()->count();
        $this->liked = Auth::check() && $this->event->likes()->where('user_id', Auth::id())->exists();
    }


    public function addComment()
    {
        $this->validate([
            'commentContent' => 'required|string|max:500',
        ]);

        $this->commentsService->addComment(
            commentable: $this->event,
            content: $this->commentContent,
            userId: Auth::id(),
            validated: false
        );

        $this->commentContent = '';
        $this->loadComments();
    }

    public function toggleLike()
    {
        if (!Auth::check()) return;
        $like = $this->event->likes()->where('user_id', Auth::id())->first();
        if ($like) {
            $like->delete();
        } else {
            $this->event->likes()->create(['user_id' => Auth::id()]);
        }
        $this->loadLikes();
    }

    public function render()
    {
        $params = [
            'event' => $this->event,
            'comments' => $this->comments,
            'unvalidatedComments' => $this->unvalidatedComments,
            'likeCount' => $this->likeCount,
            'liked' => $this->liked,
        ];
        return view('livewire.event-show', $params)->extends('layouts.master')->section('content');
    }
}
