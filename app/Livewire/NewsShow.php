<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\News;

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

    public function mount($id)
    {
        $this->id = $id;
        $this->news = News::with(['mainImage', 'comments.user', 'likes'])->findOrFail($id);
        $this->comments = $this->news->comments;
        $this->likesCount = $this->news->likes->count();
        $this->like = $this->news->likes()->where('user_id', auth()->id())->exists();
        $this->comment = '';
    }


    public function toggleLike()
    {
        if (!Auth::check()) return;
        $like = $this->news->likes()->where('user_id', Auth::id())->first();
        if ($like) {
            $like->delete();
        } else {
            $this->news->likes()->create(['user_id' => Auth::id()]);
        }
        $this->loadLikes();
    }
    public function loadLikes()
    {
        $this->likeCount = $this->news->likes()->count();
        $this->liked = Auth::check() && $this->news->likes()->where('user_id', Auth::id())->exists();
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
        $this->news->comments()->create([
            'user_id' => Auth::id(),
            'content' => $this->comment
        ]);
        $this->comment = '';
        $this->loadComments();
        session()->flash('success', 'Comment added successfully and is awaiting validation.');
    }

    public function validateComment($commentId)
    {
        if (!auth()->check() || !User::isSuperAdmin()) return;
        Comment::validate($commentId);
        $this->loadComments();
    }
    public function loadComments()
    {
        $this->comments = $this->news->comments()
            ->where('validated', true)
            ->with('user')
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
        $this->comments = $this->news->comments;
        $this->likesCount = $this->news->likes->count();
        $this->like = $this->news->likes()->where('user_id', auth()->id())->exists();
        return view('livewire.news-show', [
            'news' => $this->news,
            'comments' => $this->comments,
            'likesCount' => $this->likesCount,
            'like' => $this->like,
        ])->extends('layouts.master')->section('content');
    }
}
