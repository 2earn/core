<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\News;
use App\Models\User;
use App\Services\CommentService;
use App\Services\News\NewsService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NewsShow extends Component
{
    public $id;
    public $news;
    public $comments;
    public $likesCount;
    public $like;
    public $comment;
    public $likeCount = 0;
    public $liked = false;
    public $unvalidatedComments = [];

    protected CommentService $commentService;
    protected NewsService $newsService;

    public function boot(CommentService $commentService, NewsService $newsService)
    {
        $this->commentService = $commentService;
        $this->newsService = $newsService;
    }

    public function mount($id)
    {
        $this->id = $id;
        $this->news = $this->newsService->getByIdOrFail($id, ['mainImage', 'comments.user', 'likes', 'hashtags']);
        $this->loadComments();
        $this->loadLikes();
        $this->like = $this->newsService->hasUserLiked($id, auth()->id());
        $this->comment = '';
    }


    public function toggleLike()
    {
        if (!Auth::check()) return;

        $isLiked = $this->newsService->hasUserLiked($this->id, Auth::id());

        if ($isLiked) {
            $this->newsService->removeLike($this->id, Auth::id());
        } else {
            $this->newsService->addLike($this->id, Auth::id());
        }

        $this->loadLikes();
    }

    public function loadLikes()
    {
        $this->likeCount = $this->news->likes()->count();
        $this->liked = Auth::check() && $this->newsService->hasUserLiked($this->id, Auth::id());
    }

    public function addComment()
    {
        if (!Auth::check()) {
            session()->flash('error', 'You must be logged in to comment.');
            return;
        }

        $this->validate([
            'comment' => 'required|string|min:2|max:500',
        ]);

        // Add comment using service
        $result = $this->commentService->createComment($this->id, Auth::id(), $this->comment);

        if ($result['success']) {
            $this->comment = '';
            $this->loadComments();
            session()->flash('success', 'Comment added successfully and is awaiting validation.');
        }
    }

    public function validateComment($commentId)
    {
        if (!auth()->check() || !User::isSuperAdmin()) return;
        $this->commentService->validateComment($commentId, auth()->id());
        $this->loadComments();
    }

    public function deleteComment($commentId)
    {
        if (!auth()->check() || !User::isSuperAdmin()) return;
        $this->commentService->deleteComment($commentId);
        $this->loadComments();
    }

    public function loadComments()
    {
        $this->comments = $this->news->comments()
            ->where('validated', true)
            ->orderByDesc('created_at')
            ->get();
        $this->unvalidatedComments = $this->news->comments()
            ->where('validated', false)
            ->orderByDesc('created_at')
            ->get();

    }


    public function render()
    {
        $this->news->load(['mainImage', 'comments.user', 'likes']);

        $params = [
            'news' => $this->news,
            'comments' => $this->comments,
            'likesCount' => $this->likesCount,
        ];
        return view('livewire.news-show', $params)->extends('layouts.master')->section('content');
    }
}
