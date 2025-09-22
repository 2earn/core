<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\User;
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

    public function mount($id)
    {
        $this->id = $id;
        $this->event = Event::with(['mainImage', 'hashtags'])->findOrFail($id);
        $this->loadComments();
        $this->loadLikes();
    }

    public function loadComments()
    {
        $this->comments = $this->event->comments()
            ->where('validated', true)
            ->with('user')
            ->orderByDesc('created_at')
            ->get();
        $this->unvalidatedComments = $this->event->comments()
            ->where('validated', false)
            ->orderByDesc('created_at')
            ->get();
    }

    public function validateComment($commentId)
    {
        if (!auth()->check() || !User::isSuperAdmin()) return;
        \App\Models\Comment::validate($commentId);
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
        $this->event->comments()->create([
            'content' => $this->commentContent,
            'user_id' => Auth::id(),
            'validated' => false,
        ]);
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
