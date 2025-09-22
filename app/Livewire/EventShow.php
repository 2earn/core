<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Event;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class EventShow extends Component
{
    public $id;
    public $event;
    public $commentContent = '';
    public $comments;
    public $likeCount = 0;
    public $liked = false;

    public function mount($id)
    {
        $this->id = $id;
        $this->event = Event::with('mainImage')->findOrFail($id);
        $this->loadComments();
        $this->loadLikes();
    }

    public function loadComments()
    {
        $this->comments = $this->event->comments()->with('user')->orderByDesc('created_at')->get();
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
        return view('livewire.event-show', [
            'event' => $this->event,
            'comments' => $this->comments,
            'likeCount' => $this->likeCount,
            'liked' => $this->liked,
        ])->extends('layouts.master')->section('content');
    }
}
